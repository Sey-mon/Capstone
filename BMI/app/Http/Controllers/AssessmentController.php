<?php

namespace App\Http\Controllers;

use App\Services\MalnutritionService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AssessmentController extends Controller
{
    protected $malnutritionService;

    public function __construct(MalnutritionService $malnutritionService)
    {
        $this->malnutritionService = $malnutritionService;
    }

    /**
     * Assess a patient
     */
    public function assess(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            // Required fields matching FastAPI requirements
            'age_months' => 'required|numeric|min:0|max:60',
            'weight' => 'required|numeric|min:0.1|max:50',
            'height' => 'required|numeric|min:30|max:150',
            'sex' => 'required|in:Male,Female',  // Exactly "Male" or "Female"
            
            // Optional fields
            'name' => 'nullable|string|max:255',
            'patient_id' => 'nullable|string|max:50',
            'municipality' => 'nullable|string|max:255',
            'total_household' => 'nullable|integer|min:1|max:20',
            'adults' => 'nullable|integer|min:1|max:10',
            'children' => 'nullable|integer|min:0|max:15',
            
            // Boolean fields
            'is_twin' => 'nullable|boolean',
            'edema' => 'nullable|boolean',
            
            // Yes/No fields
            'fourps_beneficiary' => 'nullable|in:Yes,No',
            'breastfeeding' => 'nullable|in:Yes,No',
            'tuberculosis' => 'nullable|in:Yes,No',
            'malaria' => 'nullable|in:Yes,No',
            'congenital_anomalies' => 'nullable|in:Yes,No',
            'other_medical_problems' => 'nullable|in:Yes,No',
        ]);

        // Map Laravel form data to FastAPI expected format
        $fastApiData = $this->mapToFastApiFormat($validatedData);

        $result = $this->malnutritionService->assessPatient($fastApiData);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Assessment completed successfully',
                'data' => $result['data']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Assessment failed',
            'error' => $result['error']
        ], 422);
    }

    /**
     * Map Laravel form data to FastAPI expected format
     * This ensures the data structure matches what your FastAPI model expects
     */
    private function mapToFastApiFormat(array $data): array
    {
        return [
            // Required fields with exact data types
            'age_months' => (float) $data['age_months'], // Must be number, not string
            'weight' => (float) $data['weight'],        // Must be number, not string
            'height' => (float) $data['height'],        // Must be number, not string  
            'sex' => $data['sex'],                      // Must be exactly "Male" or "Female"
            
            // Optional fields with proper formatting
            'name' => $data['name'] ?? '',
            'patient_id' => $data['patient_id'] ?? '',
            'municipality' => $data['municipality'] ?? '',
            'assessment_date' => date('Y-m-d'), // Current date in YYYY-MM-DD format
            
            // Household information as integers
            'total_household' => isset($data['total_household']) ? (int) $data['total_household'] : null,
            'adults' => isset($data['adults']) ? (int) $data['adults'] : null,
            'children' => isset($data['children']) ? (int) $data['children'] : null,
            
            // Boolean fields - must be true/false, not strings
            'is_twin' => (bool) ($data['is_twin'] ?? false),
            'edema' => (bool) ($data['edema'] ?? false),
            
            // Medical/Social fields - must be exactly "Yes" or "No" (not empty strings)
            'fourps_beneficiary' => $this->normalizeYesNo($data['fourps_beneficiary'] ?? ''),
            'breastfeeding' => $this->normalizeYesNo($data['breastfeeding'] ?? ''),
            'tuberculosis' => $this->normalizeYesNo($data['tuberculosis'] ?? ''),
            'malaria' => $this->normalizeYesNo($data['malaria'] ?? ''),
            'congenital_anomalies' => $this->normalizeYesNo($data['congenital_anomalies'] ?? ''),
            'other_medical_problems' => $this->normalizeYesNo($data['other_medical_problems'] ?? ''),
        ];
    }

    /**
     * Normalize Yes/No fields to ensure valid values
     */
    private function normalizeYesNo($value): string
    {
        // If empty or null, return "No" as default
        if (empty($value) || $value === '') {
            return 'No';
        }
        
        // Ensure exactly "Yes" or "No"
        return $value === 'Yes' ? 'Yes' : 'No';
    }

    /**
     * Check API health
     */
    public function healthCheck(): JsonResponse
    {
        $health = $this->malnutritionService->healthCheck();

        if ($health) {
            return response()->json([
                'success' => true,
                'message' => 'API is healthy',
                'data' => $health
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'API is not available'
        ], 503);
    }

    /**
     * Get treatment protocols
     */
    public function getProtocols(): JsonResponse
    {
        $result = $this->malnutritionService->getTreatmentProtocols();

        return response()->json($result);
    }
}