<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'barangay_id',
        'address',
        'contact_number',
        'email',
        'license_number',
        'capacity',
        'status',
        'coordinates',
    ];

    protected $casts = [
        'coordinates' => 'array',
    ];

    /**
     * Get the barangay that the facility belongs to
     */
    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }

    /**
     * Get the users that belong to this facility
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the inventory items that belong to this facility
     */
    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class);
    }

    /**
     * Get the facility's full address
     */
    public function getFullAddressAttribute(): string
    {
        $address = $this->address ?? '';
        if ($this->barangay) {
            $address .= ($address ? ', ' : '') . $this->barangay->name;
        }
        return $address;
    }
} 