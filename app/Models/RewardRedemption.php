<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class RewardRedemption extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'reward_id',
        'order_id',
        'points_spent',
        'code',
        'status',
        'used_at',
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
            'points_spent' => 'integer',
            'used_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_USED = 'used';
    const STATUS_EXPIRED = 'expired';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($redemption) {
            if (empty($redemption->code)) {
                $redemption->code = self::generateUniqueCode();
            }
        });
    }

    /**
     * Generate a unique redemption code.
     */
    public static function generateUniqueCode(): string
    {
        do {
            $code = 'RWD-' . strtoupper(Str::random(8));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    /**
     * Get the user that owns the redemption.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the reward associated with the redemption.
     */
    public function reward(): BelongsTo
    {
        return $this->belongsTo(Reward::class);
    }

    /**
     * Get the order associated with the redemption.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scope a query to only include pending redemptions.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope a query to only include used redemptions.
     */
    public function scopeUsed($query)
    {
        return $query->where('status', self::STATUS_USED);
    }

    /**
     * Scope a query to only include expired redemptions.
     */
    public function scopeExpired($query)
    {
        return $query->where('status', self::STATUS_EXPIRED);
    }

    /**
     * Scope a query to only include valid (usable) redemptions.
     */
    public function scopeValid($query)
    {
        return $query->where('status', self::STATUS_PENDING)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Check if the redemption is valid (can be used).
     */
    public function isValid(): bool
    {
        if ($this->status !== self::STATUS_PENDING) {
            return false;
        }

        if ($this->expires_at && $this->expires_at <= now()) {
            return false;
        }

        return true;
    }

    /**
     * Mark the redemption as used.
     */
    public function markAsUsed(?int $orderId = null): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_USED,
            'used_at' => now(),
            'order_id' => $orderId ?? $this->order_id,
        ]);
    }

    /**
     * Mark the redemption as expired.
     */
    public function markAsExpired(): bool
    {
        if ($this->status !== self::STATUS_PENDING) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_EXPIRED,
        ]);
    }

    /**
     * Cancel the redemption and refund points.
     */
    public function cancel(): bool
    {
        if ($this->status !== self::STATUS_PENDING) {
            return false;
        }

        // Refund points to user
        LoyaltyPoint::award(
            $this->user_id,
            $this->points_spent,
            'Refund for cancelled reward: ' . $this->reward->name,
            LoyaltyPoint::TYPE_ADJUSTMENT
        );

        return $this->update([
            'status' => self::STATUS_CANCELLED,
        ]);
    }

    /**
     * Get the status badge color.
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_USED => 'green',
            self::STATUS_EXPIRED => 'red',
            self::STATUS_CANCELLED => 'gray',
            default => 'gray',
        };
    }

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Pending',
            self::STATUS_USED => 'Used',
            self::STATUS_EXPIRED => 'Expired',
            self::STATUS_CANCELLED => 'Cancelled',
            default => 'Unknown',
        };
    }

    /**
     * Get remaining time until expiration.
     */
    public function getRemainingTimeAttribute(): ?string
    {
        if (!$this->expires_at || $this->status !== self::STATUS_PENDING) {
            return null;
        }

        if ($this->expires_at <= now()) {
            return 'Expired';
        }

        return $this->expires_at->diffForHumans();
    }

    /**
     * Create a new redemption for a user.
     */
    public static function redeem(User $user, Reward $reward): self
    {
        if (!$reward->canBeRedeemedBy($user)) {
            throw new \Exception('Cannot redeem this reward.');
        }

        // Deduct points from user
        LoyaltyPoint::deduct(
            $user->id,
            $reward->points_required,
            'Redeemed reward: ' . $reward->name,
            LoyaltyPoint::TYPE_REDEEMED
        );

        // Decrease reward stock
        $reward->decreaseStock();

        // Create redemption record
        return self::create([
            'user_id' => $user->id,
            'reward_id' => $reward->id,
            'points_spent' => $reward->points_required,
            'status' => self::STATUS_PENDING,
            'expires_at' => now()->addDays(30), // Default 30 days validity
        ]);
    }
}
