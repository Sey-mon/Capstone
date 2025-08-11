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
            'age_months' => (int) round($data['age_months']), // Must be integer for FastAPI
            'weight' => (float) $data['weight'],        // Must be number, not string
            'height' => (float) $data['height'],        // Must be number, not string  
            'sex' => $data['sex'],                      // Must be exactly "Male" or "Female"
            
            // Optional fields with proper formatting
            'name' => $data['name'] ?? '',
            'patient_id' => $data['patient_id'] ?? '',
            'municipality' => $data['municipality'] ?? '',
            'assessment_date' => date('Y-m-d'), // Current date in YYYY-MM-DD format
            
            // Household information as integers (FastAPI requires these to be provided)
            'total_household' => isset($data['total_household']) ? (int) $data['total_household'] : 1,
            'adults' => isset($data['adults']) ? (int) $data['adults'] : 1,
            'children' => isset($data['children']) ? (int) $data['children'] : 1,
            
            // Boolean fields - must be true/false, not strings
            'twins' => (int) ($data['is_twin'] ?? false), // Convert boolean to 0/1 for FastAPI
            'edema' => (bool) ($data['edema'] ?? false),
            
            // Medical/Social fields - must be exactly "Yes" or "No" (not empty strings)
            'four_ps_beneficiary' => $this->normalizeYesNo($data['fourps_beneficiary'] ?? ''),
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

    /**
     * Get model information
     */
    public function getModelInfo(): JsonResponse
    {
        $result = $this->malnutritionService->getModelInfo();

        return response()->json($result);
    }

    /**
     * Batch assess multiple patients
     */
    public function batchAssess(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'patients' => 'required|array|min:1|max:100',
            'patients.*.name' => 'required|string|max:255',
            'patients.*.age_months' => 'required|numeric|min:0|max:60',
            'patients.*.weight' => 'required|numeric|min:0.1|max:50',
            'patients.*.height' => 'required|numeric|min:30|max:150',
            'patients.*.sex' => 'required|in:Male,Female',
        ]);

        // Map each patient to FastAPI format
        $fastApiPatients = array_map(function($patient) {
            return $this->mapToFastApiFormat($patient);
        }, $validatedData['patients']);

        $result = $this->malnutritionService->batchAssess($fastApiPatients);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Batch assessment completed successfully',
                'data' => $result['data']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Batch assessment failed',
            'error' => $result['error']
        ], 422);
    }

    /**
     * Upload file for batch assessment
     */
    public function uploadFileAssess(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,json|max:10240', // 10MB max
            'file_type' => 'nullable|in:csv,xlsx,json'
        ]);

        $file = $request->file('file');
        $fileType = $request->input('file_type', 'csv');

        $result = $this->malnutritionService->uploadFileForAssessment($file, $fileType);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'File assessment completed successfully',
                'data' => $result['data']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'File assessment failed',
            'error' => $result['error']
        ], 422);
    }

    /**
     * Get uncertainty quantification for assessment
     */
    public function getUncertaintyQuantification(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'age_months' => 'required|numeric|min:0|max:60',
            'weight' => 'required|numeric|min:0.1|max:50',
            'height' => 'required|numeric|min:30|max:150',
            'sex' => 'required|in:Male,Female',
        ]);

        $fastApiData = $this->mapToFastApiFormat($validatedData);
        $result = $this->malnutritionService->getUncertaintyQuantification($fastApiData);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Uncertainty quantification completed successfully',
                'data' => $result['data']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Uncertainty quantification failed',
            'error' => $result['error']
        ], 422);
    }

    /**
     * Get personalized recommendations
     */
    public function getPersonalizedRecommendations(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'age_months' => 'required|numeric|min:0|max:60',
            'weight' => 'required|numeric|min:0.1|max:50',
            'height' => 'required|numeric|min:30|max:150',
            'sex' => 'required|in:Male,Female',
            'name' => 'nullable|string|max:255',
            'municipality' => 'nullable|string|max:255',
        ]);

        $fastApiData = $this->mapToFastApiFormat($validatedData);
        $result = $this->malnutritionService->getPersonalizedRecommendations($fastApiData);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Personalized recommendations generated successfully',
                'data' => $result['data']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Personalized recommendations failed',
            'error' => $result['error']
        ], 422);
    }

    /**
     * Validate patient data format
     */
    public function validatePatientData(Request $request): JsonResponse
    {
        $patientData = $request->all();
        $result = $this->malnutritionService->validatePatientData($patientData);

        return response()->json($result);
    }

    /**
     * Get analytics summary
     */
    public function getAnalyticsSummary(): JsonResponse
    {
        $result = $this->malnutritionService->getAnalyticsSummary();

        return response()->json($result);
    }
}