<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        "user_id",
        "order_id",
        "rating",
        "comment",
        "is_approved",
        "approved_at",
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            "rating" => "integer",
            "is_approved" => "boolean",
            "approved_at" => "datetime",
        ];
    }

    /**
     * Get the user that owns the review.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order that the review belongs to.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scope a query to only include approved reviews.
     */
    public function scopeApproved($query)
    {
        return $query->where("is_approved", true);
    }

    /**
     * Scope a query to only include pending reviews.
     */
    public function scopePending($query)
    {
        return $query->where("is_approved", false);
    }

    /**
     * Scope a query to filter by rating.
     */
    public function scopeWithRating($query, int $rating)
    {
        return $query->where("rating", $rating);
    }

    /**
     * Scope a query to filter by minimum rating.
     */
    public function scopeMinRating($query, int $minRating)
    {
        return $query->where("rating", ">=", $minRating);
    }

    /**
     * Approve the review.
     */
    public function approve(): bool
    {
        return $this->update([
            "is_approved" => true,
            "approved_at" => now(),
        ]);
    }

    /**
     * Reject/unapprove the review.
     */
    public function reject(): bool
    {
        return $this->update([
            "is_approved" => false,
            "approved_at" => null,
        ]);
    }

    /**
     * Get the star rating as HTML.
     */
    public function getStarsHtmlAttribute(): string
    {
        $stars = "";
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .=
                    '<i data-feather="star" class="w-4 h-4 fill-yellow-400 text-yellow-400"></i>';
            } else {
                $stars .=
                    '<i data-feather="star" class="w-4 h-4 text-gray-300"></i>';
            }
        }
        return $stars;
    }

    /**
     * Get formatted rating text.
     */
    public function getRatingTextAttribute(): string
    {
        return match ($this->rating) {
            5 => "Excellent",
            4 => "Very Good",
            3 => "Good",
            2 => "Fair",
            1 => "Poor",
            default => "Unknown",
        };
    }
}
