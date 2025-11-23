<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'image',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Scope for active menus
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for coffee category
     */
    public function scopeCoffee($query)
    {
        return $query->where('category', 'coffee');
    }

    /**
     * Scope for non-coffee category
     */
    public function scopeNonCoffee($query)
    {
        return $query->where('category', 'non-coffee');
    }
}
