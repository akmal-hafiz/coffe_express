<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyTier extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'min_points',
        'points_multiplier',
        'discount_percentage',
        'benefits',
        'badge_color',
        'badge_icon',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'min_points' => 'integer',
            'points_multiplier' => 'integer',
            'discount_percentage' => 'integer',
            'benefits' => 'array',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Tier name constants
     */
    const TIER_BRONZE = 'bronze';
    const TIER_SILVER = 'silver';
    const TIER_GOLD = 'gold';
    const TIER_PLATINUM = 'platinum';

    /**
     * Scope a query to order by sort_order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    /**
     * Get the actual points multiplier as a decimal.
     * (100 = 1x, 150 = 1.5x, 200 = 2x)
     */
    public function getMultiplierDecimalAttribute(): float
    {
        return $this->points_multiplier / 100;
    }

    /**
     * Get formatted multiplier text.
     */
    public function getMultiplierTextAttribute(): string
    {
        $decimal = $this->multiplier_decimal;
        return $decimal . 'x points';
    }

    /**
     * Get formatted discount text.
     */
    public function getDiscountTextAttribute(): string
    {
        if ($this->discount_percentage <= 0) {
            return 'No discount';
        }

        return $this->discount_percentage . '% off all orders';
    }

    /**
     * Get the tier for a given points amount.
     */
    public static function getTierForPoints(int $points): ?self
    {
        return self::where('min_points', '<=', $points)
            ->orderBy('min_points', 'desc')
            ->first();
    }

    /**
     * Get the next tier after this one.
     */
    public function getNextTier(): ?self
    {
        return self::where('min_points', '>', $this->min_points)
            ->orderBy('min_points', 'asc')
            ->first();
    }

    /**
     * Get the previous tier before this one.
     */
    public function getPreviousTier(): ?self
    {
        return self::where('min_points', '<', $this->min_points)
            ->orderBy('min_points', 'desc')
            ->first();
    }

    /**
     * Get points needed to reach next tier.
     */
    public function getPointsToNextTierAttribute(): ?int
    {
        $nextTier = $this->getNextTier();

        if (!$nextTier) {
            return null;
        }

        return $nextTier->min_points - $this->min_points;
    }

    /**
     * Calculate points needed for a user to reach next tier.
     */
    public static function pointsNeededForNextTier(int $currentPoints): ?int
    {
        $currentTier = self::getTierForPoints($currentPoints);

        if (!$currentTier) {
            $lowestTier = self::ordered()->first();
            return $lowestTier ? $lowestTier->min_points - $currentPoints : null;
        }

        $nextTier = $currentTier->getNextTier();

        if (!$nextTier) {
            return null; // Already at highest tier
        }

        return $nextTier->min_points - $currentPoints;
    }

    /**
     * Get all benefits as an array.
     */
    public function getBenefitsList(): array
    {
        $benefits = [];

        // Add multiplier benefit
        if ($this->points_multiplier > 100) {
            $benefits[] = $this->multiplier_text;
        }

        // Add discount benefit
        if ($this->discount_percentage > 0) {
            $benefits[] = $this->discount_text;
        }

        // Add custom benefits
        if (is_array($this->benefits)) {
            $benefits = array_merge($benefits, $this->benefits);
        }

        return $benefits;
    }

    /**
     * Get default tiers configuration.
     */
    public static function getDefaultTiers(): array
    {
        return [
            [
                'name' => self::TIER_BRONZE,
                'min_points' => 0,
                'points_multiplier' => 100,
                'discount_percentage' => 0,
                'benefits' => ['Earn 1 point per Rp1,000 spent', 'Birthday bonus points'],
                'badge_color' => '#CD7F32',
                'badge_icon' => 'award',
                'sort_order' => 1,
            ],
            [
                'name' => self::TIER_SILVER,
                'min_points' => 500,
                'points_multiplier' => 125,
                'discount_percentage' => 5,
                'benefits' => ['Earn 1.25x points', '5% off all orders', 'Early access to promos'],
                'badge_color' => '#C0C0C0',
                'badge_icon' => 'award',
                'sort_order' => 2,
            ],
            [
                'name' => self::TIER_GOLD,
                'min_points' => 1500,
                'points_multiplier' => 150,
                'discount_percentage' => 10,
                'benefits' => ['Earn 1.5x points', '10% off all orders', 'Free delivery', 'Priority support'],
                'badge_color' => '#FFD700',
                'badge_icon' => 'star',
                'sort_order' => 3,
            ],
            [
                'name' => self::TIER_PLATINUM,
                'min_points' => 5000,
                'points_multiplier' => 200,
                'discount_percentage' => 15,
                'benefits' => ['Earn 2x points', '15% off all orders', 'Free delivery', 'VIP support', 'Exclusive rewards'],
                'badge_color' => '#E5E4E2',
                'badge_icon' => 'crown',
                'sort_order' => 4,
            ],
        ];
    }

    /**
     * Seed default tiers if none exist.
     */
    public static function seedDefaultTiers(): void
    {
        if (self::count() > 0) {
            return;
        }

        foreach (self::getDefaultTiers() as $tier) {
            self::create($tier);
        }
    }
}
