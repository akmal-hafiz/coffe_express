<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of approved reviews.
     */
    public function index()
    {
        $reviews = Review::approved()
            ->with(['user', 'order'])
            ->latest()
            ->paginate(10);

        $averageRating = Review::approved()->avg('rating');
        $totalReviews = Review::approved()->count();

        // Rating distribution
        $ratingDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = Review::approved()->where('rating', $i)->count();
            $ratingDistribution[$i] = [
                'count' => $count,
                'percentage' => $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0,
            ];
        }

        return view('reviews.index', compact('reviews', 'averageRating', 'totalReviews', 'ratingDistribution'));
    }

    /**
     * Show the form for creating a new review.
     */
    public function create(Order $order)
    {
        // Check if user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if order is completed
        if ($order->status !== 'completed') {
            return redirect()->route('order.history')
                ->with('error', 'You can only review completed orders.');
        }

        // Check if already reviewed
        if ($order->hasReview()) {
            return redirect()->route('order.history')
                ->with('error', 'You have already reviewed this order.');
        }

        return view('reviews.create', compact('order'));
    }

    /**
     * Store a newly created review.
     */
    public function store(Request $request, Order $order)
    {
        // Check if user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if order is completed
        if ($order->status !== 'completed') {
            return redirect()->route('order.history')
                ->with('error', 'You can only review completed orders.');
        }

        // Check if already reviewed
        if ($order->hasReview()) {
            return redirect()->route('order.history')
                ->with('error', 'You have already reviewed this order.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'order_id' => $order->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_approved' => false, // Needs admin approval
        ]);

        return redirect()->route('order.history')
            ->with('success', 'Thank you for your review! It will be visible after approval.');
    }

    /**
     * Display the specified review.
     */
    public function show(Review $review)
    {
        // Only show approved reviews to public
        if (!$review->is_approved && $review->user_id !== Auth::id()) {
            abort(404);
        }

        $review->load(['user', 'order']);

        return view('reviews.show', compact('review'));
    }

    /**
     * Show user's own reviews.
     */
    public function myReviews()
    {
        $reviews = Review::where('user_id', Auth::id())
            ->with('order')
            ->latest()
            ->paginate(10);

        return view('reviews.my-reviews', compact('reviews'));
    }

    /**
     * Update the specified review (only if not yet approved).
     */
    public function update(Request $request, Review $review)
    {
        // Check if user owns this review
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Can only edit if not yet approved
        if ($review->is_approved) {
            return back()->with('error', 'You cannot edit an approved review.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update($validated);

        return back()->with('success', 'Review updated successfully.');
    }

    /**
     * Remove the specified review.
     */
    public function destroy(Review $review)
    {
        // Check if user owns this review
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Can only delete if not yet approved
        if ($review->is_approved) {
            return back()->with('error', 'You cannot delete an approved review.');
        }

        $review->delete();

        return redirect()->route('reviews.my-reviews')
            ->with('success', 'Review deleted successfully.');
    }
}
