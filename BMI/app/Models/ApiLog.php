<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'endpoint',
        'method',
        'user_id',
        'request_data',
        'response_data',
        'status',
        'ip_address',
        'user_agent',
        'execution_time',
    ];

    protected $casts = [
        'request_data' => 'json',
        'response_data' => 'json',
        'execution_time' => 'float',
    ];

    /**
     * Get the user that made the request
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by endpoint
     */
    public function scopeByEndpoint($query, $endpoint)
    {
        return $query->where('endpoint', 'like', '%' . $endpoint . '%');
    }

    /**
     * Scope to filter by date range
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}
