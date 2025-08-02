<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NutritionAssessment extends Model
{
    protected $fillable = [
        'patient_id',
        'assessed_by',
        'assessment_date',
        'weight',
        'height',
        'bmi',
        'muac',
        'nutrition_status',
        'risk_level',
        'symptoms',
        'dietary_intake',
        'clinical_signs',
        'recommendations',
        'next_assessment_date'
    ];

    protected $casts = [
        'assessment_date' => 'date',
        'next_assessment_date' => 'date',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'bmi' => 'decimal:2',
        'muac' => 'decimal:1',
    ];

    /**
     * Get the patient that owns the assessment
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the user who performed the assessment
     */
    public function assessor()
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }

    /**
     * Calculate BMI automatically
     */
    public static function calculateBMI($weight, $height)
    {
        $heightInMeters = $height / 100;
        return round($weight / ($heightInMeters * $heightInMeters), 2);
    }

    /**
     * Get nutrition status badge color
     */
    public function getStatusColorAttribute()
    {
        return match($this->nutrition_status) {
            'normal' => 'green',
            'mild_malnutrition' => 'yellow',
            'moderate_malnutrition' => 'orange',
            'severe_malnutrition' => 'red',
            default => 'gray'
        };
    }
}
