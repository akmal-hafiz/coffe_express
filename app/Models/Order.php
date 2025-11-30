<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        "user_id",
        "customer_name",
        "phone",
        "address",
        "latitude",
        "longitude",
        "items",
        "total_price",
        "pickup_option",
        "payment_method",
        "status",
        "estimated_time",
        "completed_at",
    ];

    protected $casts = [
        "items" => "array",
        "total_price" => "decimal:2",
        "completed_at" => "datetime",
    ];

    /**
     * Get the user that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the review for the order.
     */
    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    /**
     * Check if the order has been reviewed.
     */
    public function hasReview(): bool
    {
        return $this->review()->exists();
    }

    /**
     * Get the status badge color for UI
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            "pending" => "gray",
            "preparing" => "yellow",
            "ready" => "green",
            "completed" => "blue",
            default => "gray",
        };
    }

    /**
     * Get formatted status text
     */
    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {
            "pending" => "Pending",
            "preparing" => "Preparing",
            "ready" => "Ready",
            "completed" => "Completed",
            default => "Unknown",
        };
    }
}
