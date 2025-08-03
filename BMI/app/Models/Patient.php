<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Patient extends Model
{
    protected $fillable = [
        'facility_id',
        'patient_number',
        'first_name',
        'middle_name',
        'last_name',
        'nickname',
        'sex',
        'date_of_birth',
        'age_months',
        'place_of_birth',
        'barangay_id',
        'address',
        'coordinates',
        'date_of_admission',
        'admission_status',
        'admission_weight',
        'admission_height',
        'mother_name',
        'mother_age',
        'mother_education',
        'father_name',
        'father_age',
        'father_education',
        'guardian_name',
        'guardian_relationship',
        'guardian_contact',
        'total_household_members',
        'household_adults',
        'household_children',
        'is_twin',
        'is_4ps_beneficiary',
        'birth_weight',
        'birth_length',
        'gestational_age_weeks',
        'delivery_type',
        'birth_complications',
        'current_weight',
        'current_height',
        'whz_score',
        'waz_score',
        'haz_score',
        'is_breastfeeding',
        'breastfeeding_duration_months',
        'immunization_status',
        'allergies',
        'has_tuberculosis',
        'has_malaria',
        'has_congenital_anomalies',
        'congenital_anomalies_details',
        'other_medical_problems',
        'has_edema',
        'medical_history',
        'contact_number',
        'alternate_contact',
        'emergency_contact',
        'status',
        'photo',
        'created_by',
        'parent_id'
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
        $name = $this->first_name;
        if ($this->middle_name) {
            $name .= ' ' . $this->middle_name;
        }
        $name .= ' ' . $this->last_name;
        return $name;
    }

    /**
     * Get the patient's name (alias for full name)
     */
    public function getNameAttribute()
    {
        return $this->getFullNameAttribute();
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

    /**
     * Get the parent (user) of the patient
     */
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Get the barangay relationship
     */
    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }

    /**
     * Calculate date of birth from age in months
     */
    public function getDateOfBirthAttribute()
    {
        if ($this->age_months) {
            return Carbon::now()->subMonths($this->age_months);
        }
        return null;
    }

    /**
     * Get age in years for display
     */
    public function getAgeInYearsAttribute()
    {
        if ($this->age_months) {
            return floor($this->age_months / 12);
        }
        return 0;
    }

    /**
     * Get remaining months after years
     */
    public function getRemainingMonthsAttribute()
    {
        if ($this->age_months) {
            return $this->age_months % 12;
        }
        return 0;
    }

    /**
     * Get formatted age display
     */
    public function getFormattedAgeAttribute()
    {
        $years = $this->age_in_years;
        $months = $this->remaining_months;
        
        if ($years > 0) {
            return $months > 0 ? "{$years} years, {$months} months" : "{$years} years";
        }
        return "{$months} months";
    }
}
