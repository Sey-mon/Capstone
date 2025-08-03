<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_person',
        'email',
        'phone_number',
        'address',
        'license_number',
        'tax_identification_number',
        'payment_terms',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the inventory items supplied by this supplier
     */
    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class);
    }
} 