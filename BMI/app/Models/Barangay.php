<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'municipality_id',
        'code',
        'population',
        'health_facility',
        'coordinates',
    ];

    protected $casts = [
        'coordinates' => 'array',
        'population' => 'integer',
    ];

    /**
     * Get the municipality that this barangay belongs to
     */
    // public function municipality()
    // {
    //     return $this->belongsTo(Municipality::class);
    // }

    /**
     * Get the facilities in this barangay
     */
    public function facilities()
    {
        return $this->hasMany(Facility::class);
    }

    /**
     * Get the users in this barangay
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
} 