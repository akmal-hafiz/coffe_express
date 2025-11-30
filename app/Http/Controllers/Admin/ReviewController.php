<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of all reviews.
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'order'])->latest();

        // Filter by approval status
        if ($request->has('status')) {
            if ($request->status === 'approved') {
                $query->approved();
            } elseif ($request->status === 'pending') {
                $query->pending();
            }
        }

        // Filter by rating
        if ($request->has('rating') && $request->rating) {
            $query->where('rating', $request->rating);
        }

        // Search by user name or comment
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('comment', 'like', "%{$search}%");
            });
        }

        $reviews = $query->paginate(15)->withQueryString();

        // Stats
        $stats = [
            'total' => Review::count(),
            'approved' => Review::approved()->count(),
            'pending' => Review::pending()->count(),
            'average_rating' => round(Review::approved()->avg('rating'), 1) ?: 0,
        ];

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }

    /**
     * Display the specified review.
     */
    public function show(Review $review)
    {
        $review->load(['user', 'order']);

        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Approve the specified review.
     */
    public function approve(Review $review)
    {
        $review->approve();

        return back()->with('success', 'Review approved successfully.');
    }

    /**
     * Reject/unapprove the specified review.
     */
    public function reject(Review $review)
    {
        $review->reject();

        return back()->with('success', 'Review rejected successfully.');
    }

    /**
     * Bulk approve reviews.
     */
    public function bulkApprove(Request $request)
    {
        $validated = $request->validate([
            'review_ids' => 'required|array',
            'review_ids.*' => 'exists:reviews,id',
        ]);

        Review::whereIn('id', $validated['review_ids'])->update([
            'is_approved' => true,
            'approved_at' => now(),
        ]);

        return back()->with('success', count($validated['review_ids']) . ' reviews approved successfully.');
    }

    /**
     * Bulk reject reviews.
     */
    public function bulkReject(Request $request)
    {
        $validated = $request->validate([
            'review_ids' => 'required|array',
            'review_ids.*' => 'exists:reviews,id',
        ]);

        Review::whereIn('id', $validated['review_ids'])->update([
            'is_approved' => false,
            'approved_at' => null,
        ]);

        return back()->with('success', count($validated['review_ids']) . ' reviews rejected successfully.');
    }

    /**
     * Remove the specified review.
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review deleted successfully.');
    }

    /**
     * Bulk delete reviews.
     */
    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'review_ids' => 'required|array',
            'review_ids.*' => 'exists:reviews,id',
        ]);

        Review::whereIn('id', $validated['review_ids'])->delete();

        return back()->with('success', count($validated['review_ids']) . ' reviews deleted successfully.');
    }
}
