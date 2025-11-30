<?php

namespace App\Http\Controllers;

use App\Models\LoyaltyPoint;
use App\Models\LoyaltyTier;
use App\Models\Reward;
use App\Models\RewardRedemption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoyaltyController extends Controller
{
    /**
     * Display the loyalty program dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Get user's current tier
        $currentTier = LoyaltyTier::getTierForPoints($user->loyalty_points);
        $nextTier = $currentTier ? $currentTier->getNextTier() : LoyaltyTier::ordered()->first();
        $pointsToNextTier = LoyaltyTier::pointsNeededForNextTier($user->loyalty_points);

        // Get recent transactions
        $recentTransactions = LoyaltyPoint::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        // Get available rewards
        $availableRewards = Reward::available()
            ->orderBy('points_required', 'asc')
            ->get();

        // Get user's active redemptions
        $activeRedemptions = RewardRedemption::where('user_id', $user->id)
            ->valid()
            ->with('reward')
            ->latest()
            ->get();

        // Get all tiers for display
        $allTiers = LoyaltyTier::ordered()->get();

        // Calculate progress to next tier
        $progressPercentage = 0;
        if ($currentTier && $nextTier) {
            $pointsInCurrentTier = $user->loyalty_points - $currentTier->min_points;
            $pointsNeededForNext = $nextTier->min_points - $currentTier->min_points;
            $progressPercentage = min(100, ($pointsInCurrentTier / $pointsNeededForNext) * 100);
        }

        return view('loyalty.index', compact(
            'user',
            'currentTier',
            'nextTier',
            'pointsToNextTier',
            'recentTransactions',
            'availableRewards',
            'activeRedemptions',
            'allTiers',
            'progressPercentage'
        ));
    }

    /**
     * Display all available rewards.
     */
    public function rewards()
    {
        $user = Auth::user();

        $rewards = Reward::available()
            ->orderBy('points_required', 'asc')
            ->paginate(12);

        // Add canRedeem flag to each reward
        foreach ($rewards as $reward) {
            $reward->can_redeem = $reward->canBeRedeemedBy($user);
        }

        return view('loyalty.rewards', compact('rewards', 'user'));
    }

    /**
     * Show a specific reward.
     */
    public function showReward(Reward $reward)
    {
        $user = Auth::user();

        if (!$reward->is_active) {
            abort(404);
        }

        $canRedeem = $reward->canBeRedeemedBy($user);

        // Get user's previous redemptions of this reward
        $userRedemptions = RewardRedemption::where('user_id', $user->id)
            ->where('reward_id', $reward->id)
            ->latest()
            ->get();

        return view('loyalty.reward-detail', compact('reward', 'user', 'canRedeem', 'userRedemptions'));
    }

    /**
     * Redeem a reward.
     */
    public function redeem(Request $request, Reward $reward)
    {
        $user = Auth::user();

        try {
            $redemption = RewardRedemption::redeem($user, $reward);

            return redirect()->route('loyalty.redemption', $redemption)
                ->with('success', 'Reward redeemed successfully! Your redemption code is: ' . $redemption->code);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show a redemption.
     */
    public function showRedemption(RewardRedemption $redemption)
    {
        // Check if user owns this redemption
        if ($redemption->user_id !== Auth::id()) {
            abort(403);
        }

        $redemption->load('reward');

        return view('loyalty.redemption', compact('redemption'));
    }

    /**
     * Display user's redemption history.
     */
    public function redemptionHistory()
    {
        $user = Auth::user();

        $redemptions = RewardRedemption::where('user_id', $user->id)
            ->with('reward')
            ->latest()
            ->paginate(15);

        return view('loyalty.redemption-history', compact('redemptions'));
    }

    /**
     * Display user's points history.
     */
    public function pointsHistory()
    {
        $user = Auth::user();

        $transactions = LoyaltyPoint::where('user_id', $user->id)
            ->with('order')
            ->latest()
            ->paginate(20);

        // Summary stats
        $stats = [
            'total_earned' => LoyaltyPoint::where('user_id', $user->id)
                ->where('points', '>', 0)
                ->sum('points'),
            'total_redeemed' => abs(LoyaltyPoint::where('user_id', $user->id)
                ->where('points', '<', 0)
                ->sum('points')),
            'current_balance' => $user->loyalty_points,
        ];

        return view('loyalty.points-history', compact('transactions', 'stats', 'user'));
    }

    /**
     * Cancel a redemption.
     */
    public function cancelRedemption(RewardRedemption $redemption)
    {
        // Check if user owns this redemption
        if ($redemption->user_id !== Auth::id()) {
            abort(403);
        }

        if ($redemption->cancel()) {
            return redirect()->route('loyalty.redemption-history')
                ->with('success', 'Redemption cancelled and points refunded.');
        }

        return back()->with('error', 'Cannot cancel this redemption.');
    }

    /**
     * Display all tiers information.
     */
    public function tiers()
    {
        $user = Auth::user();
        $allTiers = LoyaltyTier::ordered()->get();
        $currentTier = LoyaltyTier::getTierForPoints($user->loyalty_points);

        return view('loyalty.tiers', compact('allTiers', 'currentTier', 'user'));
    }
}
