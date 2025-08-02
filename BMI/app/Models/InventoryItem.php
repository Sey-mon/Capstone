<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class InventoryItem extends Model
{
    protected $fillable = [
        'name',
        'barangay',
        'sku',
        'description',
        'category',
        'unit',
        'current_stock',
        'minimum_stock',
        'maximum_stock',
        'unit_cost',
        'expiry_date',
        'supplier',
        'status'
    ];

    protected $casts = [
        'expiry_date' => 'datetime',
        'unit_cost' => 'decimal:2',
    ];

    /**
     * Get all transactions for this item
     */
    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    /**
     * Check if item is low stock
     */
    public function isLowStock()
    {
        return $this->getAttribute('current_stock') <= $this->getAttribute('minimum_stock');
    }

    /**
     * Check if item is out of stock
     */
    public function isOutOfStock()
    {
        return $this->current_stock <= 0;
    }

    /**
     * Check if item is near expiry (within 30 days)
     */
    public function isNearExpiry()
    {
        if (!$this->expiry_date) return false;
        return Carbon::parse($this->expiry_date)->diffInDays(now()) <= 30;
    }

    /**
     * Check if item is expired
     */
    public function isExpired()
    {
        if (!$this->expiry_date) return false;
        return Carbon::parse($this->expiry_date)->isPast();
    }

    /**
     * Get stock status color
     */
    public function getStockStatusColorAttribute()
    {
        if ($this->isOutOfStock()) return 'red';
        if ($this->isLowStock()) return 'orange';
        return 'green';
    }

    /**
     * Get stock status text
     */
    public function getStockStatusTextAttribute()
    {
        if ($this->isOutOfStock()) return 'Out of Stock';
        if ($this->isLowStock()) return 'Low Stock';
        return 'In Stock';
    }
}
