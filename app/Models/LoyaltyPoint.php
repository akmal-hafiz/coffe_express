<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoyaltyPoint extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'order_id',
        'points',
        'type',
        'description',
        'balance_after',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'points' => 'integer',
            'balance_after' => 'integer',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Point types
     */
    const TYPE_EARNED = 'earned';
    const TYPE_REDEEMED = 'redeemed';
    const TYPE_BONUS = 'bonus';
    const TYPE_EXPIRED = 'expired';
    const TYPE_ADJUSTMENT = 'adjustment';

    /**
     * Get the user that owns the loyalty points.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order associated with the points.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scope a query to only include earned points.
     */
    public function scopeEarned($query)
    {
        return $query->where('type', self::TYPE_EARNED);
    }

    /**
     * Scope a query to only include redeemed points.
     */
    public function scopeRedeemed($query)
    {
        return $query->where('type', self::TYPE_REDEEMED);
    }

    /**
     * Scope a query to only include bonus points.
     */
    public function scopeBonus($query)
    {
        return $query->where('type', self::TYPE_BONUS);
    }

    /**
     * Scope a query to only include non-expired points.
     */
    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
                ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Scope a query to only include expired points.
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('expires_at')
            ->where('expires_at', '<=', now());
    }

    /**
     * Get the type badge color.
     */
    public function getTypeBadgeColorAttribute(): string
    {
        return match ($this->type) {
            self::TYPE_EARNED => 'green',
            self::TYPE_REDEEMED => 'blue',
            self::TYPE_BONUS => 'purple',
            self::TYPE_EXPIRED => 'red',
            self::TYPE_ADJUSTMENT => 'yellow',
            default => 'gray',
        };
    }

    /**
     * Get the formatted points with sign.
     */
    public function getFormattedPointsAttribute(): string
    {
        if ($this->points > 0) {
            return '+' . number_format($this->points);
        }
        return number_format($this->points);
    }

    /**
     * Check if points are positive (credit).
     */
    public function isCredit(): bool
    {
        return $this->points > 0;
    }

    /**
     * Check if points are negative (debit).
     */
    public function isDebit(): bool
    {
        return $this->points < 0;
    }

    /**
     * Award points to a user.
     */
    public static function award(
        int $userId,
        int $points,
        string $description,
        string $type = self::TYPE_EARNED,
        ?int $orderId = null,
        ?\DateTime $expiresAt = null
    ): self {
        $user = User::findOrFail($userId);
        $newBalance = $user->loyalty_points + $points;

        // Update user's total points
        $user->update(['loyalty_points' => $newBalance]);

        // Create transaction record
        return self::create([
            'user_id' => $userId,
            'order_id' => $orderId,
            'points' => $points,
            'type' => $type,
            'description' => $description,
            'balance_after' => $newBalance,
            'expires_at' => $expiresAt,
        ]);
    }

    /**
     * Deduct points from a user.
     */
    public static function deduct(
        int $userId,
        int $points,
        string $description,
        string $type = self::TYPE_REDEEMED,
        ?int $orderId = null
    ): self {
        $user = User::findOrFail($userId);

        if ($user->loyalty_points < $points) {
            throw new \Exception('Insufficient loyalty points.');
        }

        $newBalance = $user->loyalty_points - $points;

        // Update user's total points
        $user->update(['loyalty_points' => $newBalance]);

        // Create transaction record (negative points)
        return self::create([
            'user_id' => $userId,
            'order_id' => $orderId,
            'points' => -$points,
            'type' => $type,
            'description' => $description,
            'balance_after' => $newBalance,
        ]);
    }
}
