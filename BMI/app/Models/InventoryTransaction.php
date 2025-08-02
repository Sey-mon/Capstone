<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    protected $fillable = [
        'inventory_item_id',
        'user_id',
        'type',
        'quantity',
        'previous_stock',
        'new_stock',
        'reason',
        'notes',
        'transaction_date'
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    /**
     * Get the inventory item that owns the transaction
     */
    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class);
    }

    /**
     * Get the user who performed the transaction
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get transaction type color
     */
    public function getTypeColorAttribute()
    {
        return match($this->type) {
            'stock_in' => 'green',
            'stock_out' => 'blue',
            'adjustment' => 'yellow',
            'expired' => 'red',
            'damaged' => 'red',
            default => 'gray'
        };
    }
}
