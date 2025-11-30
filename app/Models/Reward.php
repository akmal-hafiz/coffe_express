<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reward extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'description',
        'image',
        'points_required',
        'type',
        'discount_amount',
        'discount_percentage',
        'free_item_id',
        'stock',
        'max_redemption_per_user',
        'is_active',
        'valid_from',
        'valid_until',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'points_required' => 'integer',
            'discount_amount' => 'decimal:2',
            'discount_percentage' => 'integer',
            'stock' => 'integer',
            'max_redemption_per_user' => 'integer',
            'is_active' => 'boolean',
            'valid_from' => 'datetime',
            'valid_until' => 'datetime',
        ];
    }

    /**
     * Reward types
     */
    const TYPE_DISCOUNT = 'discount';
    const TYPE_FREE_ITEM = 'free_item';
    const TYPE_VOUCHER = 'voucher';
    const TYPE_MERCHANDISE = 'merchandise';

    /**
     * Get all redemptions for this reward.
     */
    public function redemptions(): HasMany
    {
        return $this->hasMany(RewardRedemption::class);
    }

    /**
     * Scope a query to only include active rewards.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include available rewards (in stock and within date range).
     */
    public function scopeAvailable($query)
    {
        return $query->active()
            ->where(function ($q) {
                $q->where('stock', -1) // Unlimited
                    ->orWhere('stock', '>', 0);
            })
            ->where(function ($q) {
                $q->whereNull('valid_from')
                    ->orWhere('valid_from', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('valid_until')
                    ->orWhere('valid_until', '>=', now());
            });
    }

    /**
     * Check if the reward is available.
     */
    public function isAvailable(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->stock !== -1 && $this->stock <= 0) {
            return false;
        }

        if ($this->valid_from && $this->valid_from > now()) {
            return false;
        }

        if ($this->valid_until && $this->valid_until < now()) {
            return false;
        }

        return true;
    }

    /**
     * Check if a user can redeem this reward.
     */
    public function canBeRedeemedBy(User $user): bool
    {
        if (!$this->isAvailable()) {
            return false;
        }

        if ($user->loyalty_points < $this->points_required) {
            return false;
        }

        // Check max redemption per user
        if ($this->max_redemption_per_user > 0) {
            $userRedemptions = $this->redemptions()
                ->where('user_id', $user->id)
                ->whereIn('status', ['pending', 'used'])
                ->count();

            if ($userRedemptions >= $this->max_redemption_per_user) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            self::TYPE_DISCOUNT => 'Discount',
            self::TYPE_FREE_ITEM => 'Free Item',
            self::TYPE_VOUCHER => 'Voucher',
            self::TYPE_MERCHANDISE => 'Merchandise',
            default => 'Unknown',
        };
    }

    /**
     * Get the type badge color.
     */
    public function getTypeBadgeColorAttribute(): string
    {
        return match ($this->type) {
            self::TYPE_DISCOUNT => 'green',
            self::TYPE_FREE_ITEM => 'blue',
            self::TYPE_VOUCHER => 'purple',
            self::TYPE_MERCHANDISE => 'orange',
            default => 'gray',
        };
    }

    /**
     * Get the discount value formatted.
     */
    public function getDiscountValueAttribute(): string
    {
        if ($this->discount_percentage) {
            return $this->discount_percentage . '%';
        }

        if ($this->discount_amount) {
            return 'Rp' . number_format($this->discount_amount, 0, ',', '.');
        }

        return '-';
    }

    /**
     * Get stock status label.
     */
    public function getStockStatusAttribute(): string
    {
        if ($this->stock === -1) {
            return 'Unlimited';
        }

        if ($this->stock <= 0) {
            return 'Out of Stock';
        }

        if ($this->stock <= 10) {
            return 'Low Stock (' . $this->stock . ' left)';
        }

        return 'In Stock (' . $this->stock . ')';
    }

    /**
     * Decrease stock by one.
     */
    public function decreaseStock(): bool
    {
        if ($this->stock === -1) {
            return true; // Unlimited stock
        }

        if ($this->stock <= 0) {
            return false;
        }

        return $this->decrement('stock');
    }

    /**
     * Get the free item menu if applicable.
     */
    public function getFreeItemMenu()
    {
        if ($this->type !== self::TYPE_FREE_ITEM || !$this->free_item_id) {
            return null;
        }

        return Menu::find($this->free_item_id);
    }
}
