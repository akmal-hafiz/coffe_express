<?php

namespace App\Services;

use App\Models\LoyaltyPoint;
use App\Models\LoyaltyTier;
use App\Models\Order;
use App\Models\User;

class LoyaltyService
{
    /**
     * Points earned per currency unit (e.g., 1 point per Rp1,000)
     */
    const POINTS_PER_CURRENCY = 1000;

    /**
     * Default points expiration in days
     */
    const POINTS_EXPIRATION_DAYS = 365;

    /**
     * Award points for a completed order.
     */
    public function awardPointsForOrder(Order $order): ?LoyaltyPoint
    {
        // Only award points for completed orders
        if ($order->status !== 'completed') {
            return null;
        }

        // Check if points already awarded for this order
        $existingPoints = LoyaltyPoint::where('order_id', $order->id)
            ->where('type', LoyaltyPoint::TYPE_EARNED)
            ->first();

        if ($existingPoints) {
            return null; // Points already awarded
        }

        $user = $order->user;
        if (!$user) {
            return null;
        }

        // Calculate base points
        $basePoints = $this->calculatePointsForAmount($order->total_price);

        // Apply tier multiplier
        $currentTier = LoyaltyTier::getTierForPoints($user->loyalty_points);
        if ($currentTier) {
            $multiplier = $currentTier->multiplier_decimal;
            $basePoints = (int) round($basePoints * $multiplier);
        }

        // Award the points
        $loyaltyPoint = LoyaltyPoint::award(
            $user->id,
            $basePoints,
            "Points earned from Order #{$order->id}",
            LoyaltyPoint::TYPE_EARNED,
            $order->id,
            now()->addDays(self::POINTS_EXPIRATION_DAYS)
        );

        // Check and update user tier
        $this->updateUserTier($user);

        return $loyaltyPoint;
    }

    /**
     * Calculate points for a given amount.
     */
    public function calculatePointsForAmount(float $amount): int
    {
        return (int) floor($amount / self::POINTS_PER_CURRENCY);
    }

    /**
     * Calculate points preview for an order (before completion).
     */
    public function calculatePointsPreview(float $amount, User $user): array
    {
        $basePoints = $this->calculatePointsForAmount($amount);

        $currentTier = LoyaltyTier::getTierForPoints($user->loyalty_points);
        $multiplier = $currentTier ? $currentTier->multiplier_decimal : 1;
        $finalPoints = (int) round($basePoints * $multiplier);

        return [
            'base_points' => $basePoints,
            'multiplier' => $multiplier,
            'final_points' => $finalPoints,
            'tier_name' => $currentTier ? $currentTier->name : 'bronze',
        ];
    }

    /**
     * Award welcome bonus points to new user.
     */
    public function awardWelcomeBonus(User $user, int $points = 50): LoyaltyPoint
    {
        return LoyaltyPoint::award(
            $user->id,
            $points,
            'Welcome bonus for joining Coffee Express!',
            LoyaltyPoint::TYPE_BONUS,
            null,
            now()->addDays(self::POINTS_EXPIRATION_DAYS)
        );
    }

    /**
     * Award birthday bonus points.
     */
    public function awardBirthdayBonus(User $user, int $points = 100): LoyaltyPoint
    {
        // Check if birthday bonus already awarded this year
        $existingBonus = LoyaltyPoint::where('user_id', $user->id)
            ->where('type', LoyaltyPoint::TYPE_BONUS)
            ->where('description', 'like', '%birthday%')
            ->whereYear('created_at', now()->year)
            ->first();

        if ($existingBonus) {
            throw new \Exception('Birthday bonus already awarded this year.');
        }

        return LoyaltyPoint::award(
            $user->id,
            $points,
            'Happy Birthday! Here\'s your birthday bonus!',
            LoyaltyPoint::TYPE_BONUS,
            null,
            now()->addDays(30) // Birthday bonus expires in 30 days
        );
    }

    /**
     * Award promotional bonus points.
     */
    public function awardPromoBonus(User $user, int $points, string $promoName): LoyaltyPoint
    {
        return LoyaltyPoint::award(
            $user->id,
            $points,
            "Promotional bonus: {$promoName}",
            LoyaltyPoint::TYPE_BONUS,
            null,
            now()->addDays(90) // Promo bonus expires in 90 days
        );
    }

    /**
     * Award referral bonus points.
     */
    public function awardReferralBonus(User $referrer, User $referred, int $points = 100): array
    {
        $referrerPoints = LoyaltyPoint::award(
            $referrer->id,
            $points,
            "Referral bonus for inviting {$referred->name}",
            LoyaltyPoint::TYPE_BONUS
        );

        $referredPoints = LoyaltyPoint::award(
            $referred->id,
            $points,
            "Welcome bonus from referral by {$referrer->name}",
            LoyaltyPoint::TYPE_BONUS
        );

        return [
            'referrer' => $referrerPoints,
            'referred' => $referredPoints,
        ];
    }

    /**
     * Update user's tier based on total points earned.
     */
    public function updateUserTier(User $user): void
    {
        $totalPointsEarned = LoyaltyPoint::where('user_id', $user->id)
            ->where('points', '>', 0)
            ->sum('points');

        $newTier = LoyaltyTier::getTierForPoints($totalPointsEarned);

        if ($newTier && $user->loyalty_tier !== $newTier->name) {
            $oldTier = $user->loyalty_tier;
            $user->update(['loyalty_tier' => $newTier->name]);

            // Log tier change
            \Log::info("User {$user->id} tier updated from {$oldTier} to {$newTier->name}");
        }
    }

    /**
     * Process expired points.
     */
    public function processExpiredPoints(): int
    {
        $expiredPoints = LoyaltyPoint::where('type', LoyaltyPoint::TYPE_EARNED)
            ->where('points', '>', 0)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->get();

        $processedCount = 0;

        foreach ($expiredPoints as $point) {
            // Create expiration record
            LoyaltyPoint::create([
                'user_id' => $point->user_id,
                'order_id' => null,
                'points' => -$point->points,
                'type' => LoyaltyPoint::TYPE_EXPIRED,
                'description' => "Points expired from " . $point->created_at->format('M d, Y'),
                'balance_after' => $point->user->loyalty_points - $point->points,
            ]);

            // Update user balance
            $point->user->decrement('loyalty_points', $point->points);

            // Mark original points as processed (update expires_at to null)
            $point->update(['expires_at' => null]);

            $processedCount++;
        }

        return $processedCount;
    }

    /**
     * Get user's points summary.
     */
    public function getUserPointsSummary(User $user): array
    {
        $totalEarned = LoyaltyPoint::where('user_id', $user->id)
            ->where('points', '>', 0)
            ->sum('points');

        $totalRedeemed = abs(LoyaltyPoint::where('user_id', $user->id)
            ->where('type', LoyaltyPoint::TYPE_REDEEMED)
            ->sum('points'));

        $totalExpired = abs(LoyaltyPoint::where('user_id', $user->id)
            ->where('type', LoyaltyPoint::TYPE_EXPIRED)
            ->sum('points'));

        $expiringPoints = LoyaltyPoint::where('user_id', $user->id)
            ->where('points', '>', 0)
            ->whereNotNull('expires_at')
            ->where('expires_at', '>', now())
            ->where('expires_at', '<=', now()->addDays(30))
            ->sum('points');

        $currentTier = LoyaltyTier::getTierForPoints($user->loyalty_points);
        $nextTier = $currentTier ? $currentTier->getNextTier() : LoyaltyTier::ordered()->first();

        return [
            'current_balance' => $user->loyalty_points,
            'total_earned' => $totalEarned,
            'total_redeemed' => $totalRedeemed,
            'total_expired' => $totalExpired,
            'expiring_soon' => $expiringPoints,
            'current_tier' => $currentTier,
            'next_tier' => $nextTier,
            'points_to_next_tier' => $nextTier ? max(0, $nextTier->min_points - $user->loyalty_points) : null,
        ];
    }

    /**
     * Check if user can afford a certain amount of points.
     */
    public function canAfford(User $user, int $points): bool
    {
        return $user->loyalty_points >= $points;
    }

    /**
     * Get discount amount based on user's tier.
     */
    public function getTierDiscount(User $user): int
    {
        $currentTier = LoyaltyTier::getTierForPoints($user->loyalty_points);
        return $currentTier ? $currentTier->discount_percentage : 0;
    }

    /**
     * Apply tier discount to an order total.
     */
    public function applyTierDiscount(User $user, float $orderTotal): array
    {
        $discountPercentage = $this->getTierDiscount($user);
        $discountAmount = ($orderTotal * $discountPercentage) / 100;
        $finalTotal = $orderTotal - $discountAmount;

        return [
            'original_total' => $orderTotal,
            'discount_percentage' => $discountPercentage,
            'discount_amount' => $discountAmount,
            'final_total' => $finalTotal,
        ];
    }

    /**
     * Get leaderboard of top users by points.
     */
    public function getLeaderboard(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return User::where('loyalty_points', '>', 0)
            ->where('role', '!=', 'admin')
            ->orderBy('loyalty_points', 'desc')
            ->take($limit)
            ->get(['id', 'name', 'loyalty_points', 'loyalty_tier']);
    }
}
