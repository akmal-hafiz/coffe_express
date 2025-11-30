<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyPoint;
use App\Models\LoyaltyTier;
use App\Models\Reward;
use App\Models\RewardRedemption;
use App\Models\User;
use Illuminate\Http\Request;

class LoyaltyController extends Controller
{
    /**
     * Display the loyalty program dashboard.
     */
    public function index()
    {
        // Overall stats
        $stats = [
            'total_points_issued' => LoyaltyPoint::where('points', '>', 0)->sum('points'),
            'total_points_redeemed' => abs(LoyaltyPoint::where('points', '<', 0)->sum('points')),
            'total_active_rewards' => Reward::active()->count(),
            'total_redemptions' => RewardRedemption::count(),
            'pending_redemptions' => RewardRedemption::pending()->count(),
        ];

        // Recent transactions
        $recentTransactions = LoyaltyPoint::with(['user', 'order'])
            ->latest()
            ->take(10)
            ->get();

        // Recent redemptions
        $recentRedemptions = RewardRedemption::with(['user', 'reward'])
            ->latest()
            ->take(10)
            ->get();

        // Top users by points
        $topUsers = User::where('loyalty_points', '>', 0)
            ->orderBy('loyalty_points', 'desc')
            ->take(10)
            ->get();

        // Tier distribution
        $tiers = LoyaltyTier::ordered()->get();
        $tierDistribution = [];
        foreach ($tiers as $tier) {
            $nextTier = $tier->getNextTier();
            $query = User::where('loyalty_points', '>=', $tier->min_points);
            if ($nextTier) {
                $query->where('loyalty_points', '<', $nextTier->min_points);
            }
            $tierDistribution[$tier->name] = $query->count();
        }

        return view('admin.loyalty.index', compact(
            'stats',
            'recentTransactions',
            'recentRedemptions',
            'topUsers',
            'tierDistribution',
            'tiers'
        ));
    }

    /**
     * Manage rewards catalog.
     */
    public function rewards(Request $request)
    {
        $query = Reward::query();

        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $rewards = $query->latest()->paginate(15)->withQueryString();

        return view('admin.loyalty.rewards', compact('rewards'));
    }

    /**
     * Show form to create a new reward.
     */
    public function createReward()
    {
        $menus = \App\Models\Menu::active()->get();
        return view('admin.loyalty.reward-create', compact('menus'));
    }

    /**
     * Store a new reward.
     */
    public function storeReward(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:2048',
            'points_required' => 'required|integer|min:1',
            'type' => 'required|in:discount,free_item,voucher,merchandise',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_percentage' => 'nullable|integer|min:0|max:100',
            'free_item_id' => 'nullable|exists:menus,id',
            'stock' => 'required|integer|min:-1',
            'max_redemption_per_user' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('rewards', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        Reward::create($validated);

        return redirect()->route('admin.loyalty.rewards')
            ->with('success', 'Reward created successfully.');
    }

    /**
     * Show form to edit a reward.
     */
    public function editReward(Reward $reward)
    {
        $menus = \App\Models\Menu::active()->get();
        return view('admin.loyalty.reward-edit', compact('reward', 'menus'));
    }

    /**
     * Update a reward.
     */
    public function updateReward(Request $request, Reward $reward)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:2048',
            'points_required' => 'required|integer|min:1',
            'type' => 'required|in:discount,free_item,voucher,merchandise',
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_percentage' => 'nullable|integer|min:0|max:100',
            'free_item_id' => 'nullable|exists:menus,id',
            'stock' => 'required|integer|min:-1',
            'max_redemption_per_user' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($reward->image) {
                \Storage::disk('public')->delete($reward->image);
            }
            $validated['image'] = $request->file('image')->store('rewards', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        $reward->update($validated);

        return redirect()->route('admin.loyalty.rewards')
            ->with('success', 'Reward updated successfully.');
    }

    /**
     * Delete a reward.
     */
    public function destroyReward(Reward $reward)
    {
        // Delete image
        if ($reward->image) {
            \Storage::disk('public')->delete($reward->image);
        }

        $reward->delete();

        return redirect()->route('admin.loyalty.rewards')
            ->with('success', 'Reward deleted successfully.');
    }

    /**
     * Manage redemptions.
     */
    public function redemptions(Request $request)
    {
        $query = RewardRedemption::with(['user', 'reward']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Search by code or user
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $redemptions = $query->latest()->paginate(20)->withQueryString();

        // Stats
        $stats = [
            'total' => RewardRedemption::count(),
            'pending' => RewardRedemption::pending()->count(),
            'used' => RewardRedemption::used()->count(),
            'expired' => RewardRedemption::expired()->count(),
        ];

        return view('admin.loyalty.redemptions', compact('redemptions', 'stats'));
    }

    /**
     * Mark a redemption as used.
     */
    public function markRedemptionUsed(RewardRedemption $redemption)
    {
        if ($redemption->markAsUsed()) {
            return back()->with('success', 'Redemption marked as used.');
        }

        return back()->with('error', 'Cannot mark this redemption as used.');
    }

    /**
     * Cancel a redemption.
     */
    public function cancelRedemption(RewardRedemption $redemption)
    {
        if ($redemption->cancel()) {
            return back()->with('success', 'Redemption cancelled and points refunded.');
        }

        return back()->with('error', 'Cannot cancel this redemption.');
    }

    /**
     * Manage loyalty tiers.
     */
    public function tiers()
    {
        $tiers = LoyaltyTier::ordered()->get();

        // Seed default tiers if none exist
        if ($tiers->isEmpty()) {
            LoyaltyTier::seedDefaultTiers();
            $tiers = LoyaltyTier::ordered()->get();
        }

        return view('admin.loyalty.tiers', compact('tiers'));
    }

    /**
     * Update a tier.
     */
    public function updateTier(Request $request, LoyaltyTier $tier)
    {
        $validated = $request->validate([
            'min_points' => 'required|integer|min:0',
            'points_multiplier' => 'required|integer|min:100|max:500',
            'discount_percentage' => 'required|integer|min:0|max:50',
            'benefits' => 'nullable|array',
            'badge_color' => 'required|string|max:20',
        ]);

        $tier->update($validated);

        return back()->with('success', 'Tier updated successfully.');
    }

    /**
     * Award points to a user manually.
     */
    public function awardPoints(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'points' => 'required|integer|min:1',
            'description' => 'required|string|max:255',
        ]);

        LoyaltyPoint::award(
            $validated['user_id'],
            $validated['points'],
            $validated['description'],
            LoyaltyPoint::TYPE_BONUS
        );

        return back()->with('success', 'Points awarded successfully.');
    }

    /**
     * Deduct points from a user manually.
     */
    public function deductPoints(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'points' => 'required|integer|min:1',
            'description' => 'required|string|max:255',
        ]);

        try {
            LoyaltyPoint::deduct(
                $validated['user_id'],
                $validated['points'],
                $validated['description'],
                LoyaltyPoint::TYPE_ADJUSTMENT
            );

            return back()->with('success', 'Points deducted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * View user's loyalty details.
     */
    public function userDetails(User $user)
    {
        $currentTier = LoyaltyTier::getTierForPoints($user->loyalty_points);

        $transactions = LoyaltyPoint::where('user_id', $user->id)
            ->with('order')
            ->latest()
            ->paginate(20);

        $redemptions = RewardRedemption::where('user_id', $user->id)
            ->with('reward')
            ->latest()
            ->get();

        $stats = [
            'total_earned' => LoyaltyPoint::where('user_id', $user->id)
                ->where('points', '>', 0)
                ->sum('points'),
            'total_redeemed' => abs(LoyaltyPoint::where('user_id', $user->id)
                ->where('points', '<', 0)
                ->sum('points')),
            'current_balance' => $user->loyalty_points,
        ];

        return view('admin.loyalty.user-details', compact('user', 'currentTier', 'transactions', 'redemptions', 'stats'));
    }

    /**
     * Points transactions list.
     */
    public function transactions(Request $request)
    {
        $query = LoyaltyPoint::with(['user', 'order']);

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Filter by user
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $transactions = $query->latest()->paginate(30)->withQueryString();

        return view('admin.loyalty.transactions', compact('transactions'));
    }
}
