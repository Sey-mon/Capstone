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
        'edema',
        'whz_score',
        'waz_score',
        'haz_score',
        'nutrition_status',
        'risk_level',
        'confidence_score',
        'api_response',
        'model_version',
        'assessment_method',
        'symptoms',
        'dietary_intake',
        'feeding_practices',
        'appetite',
        'vomiting',
        'diarrhea',
        'clinical_signs',
        'recommendations',
        'next_assessment_date',
        'follow_up_required',
        'notes'
    ];

    protected $casts = [
        'assessment_date' => 'date',
        'next_assessment_date' => 'date',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'bmi' => 'decimal:2',
        'whz_score' => 'decimal:2',
        'waz_score' => 'decimal:2',
        'haz_score' => 'decimal:2',
        'confidence_score' => 'decimal:4',
        'api_response' => 'array',
        'edema' => 'boolean',
        'vomiting' => 'boolean',
        'diarrhea' => 'boolean',
        'follow_up_required' => 'boolean',
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
    public function setBmiAttribute($value)
    {
        if ($this->weight && $this->height) {
            $heightInMeters = $this->height / 100;
            $this->attributes['bmi'] = round($this->weight / ($heightInMeters * $heightInMeters), 2);
        } else {
            $this->attributes['bmi'] = $value;
        }
    }

    /**
     * Get the Z-score category color for display
     */
    public function getCategoryColor($category)
    {
        switch ($category) {
            case 'severe_wasting':
            case 'severe_underweight':
            case 'severe_stunting':
            case 'severe_malnutrition':
                return 'text-red-600 bg-red-50';
            case 'moderate_wasting':
            case 'moderate_underweight':
            case 'moderate_stunting':
            case 'moderate_malnutrition':
                return 'text-orange-600 bg-orange-50';
            case 'mild_wasting':
            case 'mild_underweight':
            case 'mild_stunting':
            case 'mild_malnutrition':
                return 'text-yellow-600 bg-yellow-50';
            case 'normal':
                return 'text-green-600 bg-green-50';
            case 'overweight':
            case 'obese':
                return 'text-blue-600 bg-blue-50';
            default:
                return 'text-gray-600 bg-gray-50';
        }
    }

    /**
     * Get formatted Z-score display
     */
    public function getFormattedZScores()
    {
        return [
            'whz' => $this->whz_score ? number_format((float)$this->whz_score, 2) : 'N/A',
            'waz' => $this->waz_score ? number_format((float)$this->waz_score, 2) : 'N/A',
            'haz' => $this->haz_score ? number_format((float)$this->haz_score, 2) : 'N/A',
        ];
    }

    /**
     * Calculate BMI static method
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
