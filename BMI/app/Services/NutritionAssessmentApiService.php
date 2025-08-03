<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class NutritionAssessmentApiService
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.nutrition_api.url', 'https://your-api-endpoint.com/api');
        $this->apiKey = config('services.nutrition_api.key', '');
    }

    /**
     * Calculate Z-scores and categories for a child
     */
    public function assessChild($weight, $height, $ageMonths, $gender, $hasEdema = false)
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->apiUrl . '/assess', [
                    'weight' => (float) $weight,
                    'height' => (float) $height,
                    'age_months' => (int) $ageMonths,
                    'gender' => strtolower($gender),
                    'edema' => (bool) $hasEdema
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'whz_score' => $data['weight_for_height_zscore'] ?? null,
                    'waz_score' => $data['weight_for_age_zscore'] ?? null,
                    'haz_score' => $data['height_for_age_zscore'] ?? null,
                    'wfh_category' => $data['wfh_category'] ?? 'normal',
                    'wfa_category' => $data['wfa_category'] ?? 'normal',
                    'hfa_category' => $data['hfa_category'] ?? 'normal',
                    'overall_assessment' => $data['overall_assessment'] ?? 'normal',
                    'confidence_score' => $data['confidence_score'] ?? null,
                    'model_version' => $data['model_version'] ?? null,
                    'api_response' => $data,
                    'assessment_method' => 'api',
                    'api_available' => true
                ];
            }

            Log::error('Nutrition API Error: ' . $response->body());
            return $this->getFallbackAssessment($weight, $height, $ageMonths, $gender, $hasEdema);

        } catch (\Exception $e) {
            Log::error('Nutrition API Exception: ' . $e->getMessage());
            return $this->getFallbackAssessment($weight, $height, $ageMonths, $gender, $hasEdema);
        }
    }

    /**
     * Fallback assessment calculation when API is unavailable
     */
    private function getFallbackAssessment($weight, $height, $ageMonths, $gender, $hasEdema = false)
    {
        // Basic BMI calculation as fallback
        $heightInMeters = $height / 100;
        $bmi = $weight / ($heightInMeters * $heightInMeters);
        
        // Simple Z-score approximation based on WHO standards (simplified)
        $whzScore = $this->approximateWHZScore($bmi, $ageMonths, $gender);
        $wazScore = $this->approximateWAZScore($weight, $ageMonths, $gender);
        $hazScore = $this->approximateHAZScore($height, $ageMonths, $gender);
        
        // Determine categories based on Z-scores
        $wfhCategory = $this->getZScoreCategory($whzScore, 'wfh');
        $wfaCategory = $this->getZScoreCategory($wazScore, 'wfa');
        $hfaCategory = $this->getZScoreCategory($hazScore, 'hfa');
        
        // Overall assessment
        $overallAssessment = $this->determineOverallAssessment($wfhCategory, $wfaCategory, $hfaCategory, $hasEdema);

        return [
            'whz_score' => round($whzScore, 2),
            'waz_score' => round($wazScore, 2),
            'haz_score' => round($hazScore, 2),
            'wfh_category' => $wfhCategory,
            'wfa_category' => $wfaCategory,
            'hfa_category' => $hfaCategory,
            'overall_assessment' => $overallAssessment,
            'confidence_score' => 0.6, // Lower confidence for fallback
            'model_version' => 'fallback_v1.0',
            'api_response' => ['fallback' => true],
            'assessment_method' => 'manual',
            'api_available' => false
        ];
    }

    /**
     * Approximate WHZ score calculation (simplified)
     */
    private function approximateWHZScore($bmi, $ageMonths, $gender)
    {
        // Simplified approximation - in reality, this would use WHO growth standards
        $expectedBMI = ($gender === 'male') ? 16.5 : 16.2; // Simplified baseline
        
        if ($ageMonths < 24) {
            $expectedBMI += 0.1 * ($ageMonths / 12);
        } else {
            $expectedBMI += 0.2 + (0.05 * (($ageMonths - 24) / 12));
        }
        
        $zScore = ($bmi - $expectedBMI) / 1.5; // Simplified SD
        return max(-5, min(5, $zScore)); // Clamp between -5 and 5
    }

    /**
     * Approximate WAZ score calculation (simplified)
     */
    private function approximateWAZScore($weight, $ageMonths, $gender)
    {
        // Simplified weight-for-age calculation
        $expectedWeight = ($gender === 'male') ? 3.5 : 3.3; // Birth weight baseline
        $expectedWeight += ($ageMonths * 0.5); // Simplified growth rate
        
        $zScore = ($weight - $expectedWeight) / ($expectedWeight * 0.15); // Simplified SD
        return max(-5, min(5, $zScore));
    }

    /**
     * Approximate HAZ score calculation (simplified)
     */
    private function approximateHAZScore($height, $ageMonths, $gender)
    {
        // Simplified height-for-age calculation
        $expectedHeight = ($gender === 'male') ? 50 : 49.5; // Birth height baseline
        $expectedHeight += ($ageMonths * 2.2); // Simplified growth rate
        
        $zScore = ($height - $expectedHeight) / ($expectedHeight * 0.08); // Simplified SD
        return max(-5, min(5, $zScore));
    }

    /**
     * Get category based on Z-score
     */
    private function getZScoreCategory($zScore, $type)
    {
        if ($zScore >= -1) {
            return 'normal';
        } elseif ($zScore >= -2) {
            return ($type === 'wfh') ? 'mild_wasting' : (($type === 'wfa') ? 'mild_underweight' : 'mild_stunting');
        } elseif ($zScore >= -3) {
            return ($type === 'wfh') ? 'moderate_wasting' : (($type === 'wfa') ? 'moderate_underweight' : 'moderate_stunting');
        } else {
            return ($type === 'wfh') ? 'severe_wasting' : (($type === 'wfa') ? 'severe_underweight' : 'severe_stunting');
        }
    }

    /**
     * Determine overall nutritional assessment
     */
    private function determineOverallAssessment($wfhCategory, $wfaCategory, $hfaCategory, $hasEdema = false)
    {
        // If edema is present, it's severe malnutrition regardless of other indicators
        if ($hasEdema) {
            return 'severe_malnutrition';
        }

        // Check for severe conditions first
        if (in_array('severe_wasting', [$wfhCategory]) || 
            in_array('severe_underweight', [$wfaCategory])) {
            return 'severe_malnutrition';
        }

        // Check for moderate conditions
        if (in_array('moderate_wasting', [$wfhCategory]) || 
            in_array('moderate_underweight', [$wfaCategory])) {
            return 'moderate_malnutrition';
        }

        // Check for mild conditions
        if (in_array('mild_wasting', [$wfhCategory]) || 
            in_array('mild_underweight', [$wfaCategory]) ||
            in_array('moderate_stunting', [$hfaCategory]) ||
            in_array('severe_stunting', [$hfaCategory])) {
            return 'mild_malnutrition';
        }

        return 'normal';
    }

    /**
     * Calculate age in months from date of birth
     */
    public function calculateAgeInMonths($dateOfBirth)
    {
        return Carbon::parse($dateOfBirth)->diffInMonths(Carbon::now());
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
}
