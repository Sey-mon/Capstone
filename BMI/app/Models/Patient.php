<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Patient extends Model
{
    protected $fillable = [
        'name',
        'municipality',
        'barangay',
        'age_months',
        'sex',
        'date_of_admission',
        'admission_status',
        'total_household_members',
        'household_adults',
        'household_children',
        'is_twin',
        'is_4ps_beneficiary',
        'weight',
        'height',
        'whz_score',
        'is_breastfeeding',
        'has_tuberculosis',
        'has_malaria',
        'has_congenital_anomalies',
        'other_medical_problems',
        'has_edema',
        'contact_number',
        'address',
        'guardian_name',
        'religion',
        'guardian_contact',
        'status',
        'medical_history'
    ];

    protected $casts = [
        'date_of_admission' => 'date',
        'is_twin' => 'boolean',
        'is_4ps_beneficiary' => 'boolean',
        'is_breastfeeding' => 'boolean',
        'has_tuberculosis' => 'boolean',
        'has_malaria' => 'boolean',
        'has_congenital_anomalies' => 'boolean',
        'has_edema' => 'boolean',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'whz_score' => 'decimal:2',
    ];

    /**
     * Get the patient's full name
     */
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    /**
     * Calculate patient's age in years
     */
    public function getAgeYearsAttribute()
    {
        return floor($this->age_months / 12);
    }

    /**
     * Get nutrition assessments for this patient
     */
    public function nutritionAssessments()
    {
        return $this->hasMany(NutritionAssessment::class);
    }

    /**
     * Get the latest nutrition assessment
     */
    public function latestAssessment()
    {
        return $this->hasOne(NutritionAssessment::class)->latest('assessment_date');
    }
    
    /**
     * Alias for latestAssessment to maintain compatibility
     */
    public function lastAssessment()
    {
        return $this->latestAssessment();
    }

    /**
     * Check if patient is at risk
     */
    public function isAtRisk()
    {
        $latest = $this->latestAssessment;
        return $latest && in_array($latest->risk_level, ['high', 'critical']);
    }

    /**
     * Calculate BMI if weight and height are available
     */
    public function getBmiAttribute()
    {
        if ($this->weight && $this->height && $this->height > 0) {
            $heightInMeters = $this->height / 100;
            return round($this->weight / ($heightInMeters * $heightInMeters), 2);
        }
        return null;
    }

    /**
     * Get medical problems as array
     */
    public function getMedicalProblemsArrayAttribute()
    {
        $problems = [];
        if ($this->has_tuberculosis) $problems[] = 'Tuberculosis';
        if ($this->has_malaria) $problems[] = 'Malaria';
        if ($this->has_congenital_anomalies) $problems[] = 'Congenital Anomalies';
        if ($this->has_edema) $problems[] = 'Edema';
        if ($this->other_medical_problem) $problems[] = $this->other_medical_problem;
        
        return $problems;
    }
}
