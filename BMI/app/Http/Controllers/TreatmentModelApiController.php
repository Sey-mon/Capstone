<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\ApiLog;
use Exception;

class TreatmentModelApiController extends Controller
{
    protected $pythonApiUrl;
    
    public function __construct()
    {
        $this->pythonApiUrl = config('app.python_api_url', 'http://localhost:8000');
    }

    /**
     * Log API request for audit purposes
     */
    private function logApiRequest($endpoint, $method, $user_id, $request_data = null, $response_data = null, $status = 'success')
    {
        try {
            ApiLog::create([
                'endpoint' => $endpoint,
                'method' => $method,
                'user_id' => $user_id,
                'request_data' => $request_data ? json_encode($request_data) : null,
                'response_data' => $response_data ? json_encode($response_data) : null,
                'status' => $status,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (Exception $e) {
            Log::error('Failed to log API request: ' . $e->getMessage());
        }
    }

    /**
     * Make HTTP request to Python API
     */
    private function makePythonApiRequest($endpoint, $method = 'GET', $data = null)
    {
        try {
            $url = $this->pythonApiUrl . $endpoint;
            
            $response = Http::timeout(30)->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]);

            switch (strtoupper($method)) {
                case 'POST':
                    $response = $response->post($url, $data);
                    break;
                case 'GET':
                    $response = $response->get($url, $data);
                    break;
                case 'PUT':
                    $response = $response->put($url, $data);
                    break;
                default:
                    $response = $response->get($url, $data);
            }

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                    'status' => $response->status()
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $response->body(),
                    'status' => $response->status()
                ];
            }
        } catch (Exception $e) {
            Log::error('Python API request failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Connection to treatment model failed: ' . $e->getMessage(),
                'status' => 500
            ];
        }
    }

    // ========== NUTRITIONIST ROLE ENDPOINTS ==========

    /**
     * Assess single patient (Nutritionist & Admin)
     */
    public function assessSingle(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'age_months' => 'required|numeric|min:0|max:60',
            'weight' => 'required|numeric|min:0.1|max:50',
            'height' => 'required|numeric|min:30|max:150',
            'sex' => 'required|in:Male,Female',
            'name' => 'nullable|string|max:255',
            'patient_id' => 'nullable|string|max:50',
            'municipality' => 'nullable|string|max:255',
            'total_household' => 'nullable|integer|min:1|max:20',
            'adults' => 'nullable|integer|min:1|max:10',
            'children' => 'nullable|integer|min:0|max:15',
            'is_twin' => 'nullable|boolean',
            'edema' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        $result = $this->makePythonApiRequest('/assess', 'POST', $request->all());
        
        $this->logApiRequest('/assess/single', 'POST', Auth::id(), $request->all(), $result['data'] ?? null, $result['success'] ? 'success' : 'error');

        if ($result['success']) {
            return response()->json($result['data']);
        } else {
            return response()->json(['error' => $result['error']], $result['status']);
        }
    }

    /**
     * Assess multiple patients (Nutritionist & Admin)
     */
    public function assessBatch(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'patients' => 'required|array|max:100',
            'patients.*.age_months' => 'required|numeric|min:0|max:60',
            'patients.*.weight' => 'required|numeric|min:0.1|max:50',
            'patients.*.height' => 'required|numeric|min:30|max:150',
            'patients.*.sex' => 'required|in:Male,Female',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        $result = $this->makePythonApiRequest('/assess/batch', 'POST', $request->all());
        
        $this->logApiRequest('/assess/batch', 'POST', Auth::id(), ['patient_count' => count($request->patients)], $result['data'] ?? null, $result['success'] ? 'success' : 'error');

        if ($result['success']) {
            return response()->json($result['data']);
        } else {
            return response()->json(['error' => $result['error']], $result['status']);
        }
    }

    /**
     * Upload patient data files for assessment (Nutritionist & Admin)
     */
    public function uploadFileAssess(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv,xlsx,xls|max:10240', // 10MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        // TODO: Implement file upload to Python API
        // For now, return a placeholder response
        $this->logApiRequest('/assess/upload', 'POST', Auth::id(), ['filename' => $request->file('file')->getClientOriginalName()], null, 'pending');

        return response()->json([
            'message' => 'File upload functionality will be implemented',
            'filename' => $request->file('file')->getClientOriginalName()
        ]);
    }

    /**
     * View available treatment protocols (Nutritionist & Admin)
     */
    public function getProtocols(): JsonResponse
    {
        $result = $this->makePythonApiRequest('/protocols', 'GET');
        
        $this->logApiRequest('/protocols', 'GET', Auth::id(), null, $result['data'] ?? null, $result['success'] ? 'success' : 'error');

        if ($result['success']) {
            return response()->json($result['data']);
        } else {
            return response()->json(['error' => $result['error']], $result['status']);
        }
    }

    /**
     * Perform risk stratification analysis (Nutritionist & Admin)
     */
    public function riskStratification(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'age_months' => 'required|numeric|min:0|max:60',
            'weight' => 'required|numeric|min:0.1|max:50',
            'height' => 'required|numeric|min:30|max:150',
            'sex' => 'required|in:Male,Female',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        $result = $this->makePythonApiRequest('/risk/stratify', 'POST', $request->all());
        
        $this->logApiRequest('/risk/stratify', 'POST', Auth::id(), $request->all(), $result['data'] ?? null, $result['success'] ? 'success' : 'error');

        if ($result['success']) {
            return response()->json($result['data']);
        } else {
            return response()->json(['error' => $result['error']], $result['status']);
        }
    }

    /**
     * Get predictions with uncertainty measures (Nutritionist & Admin)
     */
    public function predictUncertainty(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'age_months' => 'required|numeric|min:0|max:60',
            'weight' => 'required|numeric|min:0.1|max:50',
            'height' => 'required|numeric|min:30|max:150',
            'sex' => 'required|in:Male,Female',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        $result = $this->makePythonApiRequest('/predict/uncertainty', 'POST', $request->all());
        
        $this->logApiRequest('/predict/uncertainty', 'POST', Auth::id(), $request->all(), $result['data'] ?? null, $result['success'] ? 'success' : 'error');

        if ($result['success']) {
            return response()->json($result['data']);
        } else {
            return response()->json(['error' => $result['error']], $result['status']);
        }
    }

    /**
     * Generate personalized treatment recommendations (Nutritionist & Admin)
     */
    public function personalizedRecommendations(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'age_months' => 'required|numeric|min:0|max:60',
            'weight' => 'required|numeric|min:0.1|max:50',
            'height' => 'required|numeric|min:30|max:150',
            'sex' => 'required|in:Male,Female',
            'medical_history' => 'nullable|array',
            'preferences' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        $result = $this->makePythonApiRequest('/recommendations/personalized', 'POST', $request->all());
        
        $this->logApiRequest('/recommendations/personalized', 'POST', Auth::id(), $request->all(), $result['data'] ?? null, $result['success'] ? 'success' : 'error');

        if ($result['success']) {
            return response()->json($result['data']);
        } else {
            return response()->json(['error' => $result['error']], $result['status']);
        }
    }

    /**
     * Get patient data entry templates (Nutritionist & Admin)
     */
    public function getDataTemplate(): JsonResponse
    {
        $template = [
            'required_fields' => [
                'age_months' => ['type' => 'number', 'min' => 0, 'max' => 60, 'description' => 'Age in months'],
                'weight' => ['type' => 'number', 'min' => 0.1, 'max' => 50, 'description' => 'Weight in kg'],
                'height' => ['type' => 'number', 'min' => 30, 'max' => 150, 'description' => 'Height in cm'],
                'sex' => ['type' => 'enum', 'values' => ['Male', 'Female'], 'description' => 'Sex of the child'],
            ],
            'optional_fields' => [
                'name' => ['type' => 'string', 'max_length' => 255, 'description' => 'Patient name'],
                'patient_id' => ['type' => 'string', 'max_length' => 50, 'description' => 'Unique patient identifier'],
                'municipality' => ['type' => 'string', 'max_length' => 255, 'description' => 'Municipality'],
                'total_household' => ['type' => 'number', 'min' => 1, 'max' => 20, 'description' => 'Total household members'],
                'adults' => ['type' => 'number', 'min' => 1, 'max' => 10, 'description' => 'Number of adults'],
                'children' => ['type' => 'number', 'min' => 0, 'max' => 15, 'description' => 'Number of children'],
                'is_twin' => ['type' => 'boolean', 'description' => 'Is the child a twin'],
                'edema' => ['type' => 'boolean', 'description' => 'Presence of edema'],
            ],
            'example' => [
                'age_months' => 24,
                'weight' => 10.5,
                'height' => 85,
                'sex' => 'Male',
                'name' => 'Sample Patient',
                'municipality' => 'Sample City'
            ]
        ];

        $this->logApiRequest('/data/template', 'GET', Auth::id(), null, $template, 'success');

        return response()->json($template);
    }

    /**
     * Validate patient data before assessment (Nutritionist & Admin)
     */
    public function validatePatientData(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'age_months' => 'required|numeric|min:0|max:60',
            'weight' => 'required|numeric|min:0.1|max:50',
            'height' => 'required|numeric|min:30|max:150',
            'sex' => 'required|in:Male,Female',
            'name' => 'nullable|string|max:255',
            'patient_id' => 'nullable|string|max:50',
            'municipality' => 'nullable|string|max:255',
        ]);

        $isValid = !$validator->fails();
        $errors = $validator->errors()->toArray();

        $response = [
            'valid' => $isValid,
            'errors' => $errors,
            'warnings' => []
        ];

        // Add custom warnings
        if ($request->age_months > 59) {
            $response['warnings'][] = 'Age is close to upper limit (60 months)';
        }
        if ($request->weight < 2) {
            $response['warnings'][] = 'Weight is very low - please double-check measurement';
        }
        if ($request->height < 45) {
            $response['warnings'][] = 'Height is very low - please double-check measurement';
        }

        $this->logApiRequest('/data/validate', 'POST', Auth::id(), $request->all(), $response, $isValid ? 'success' : 'validation_failed');

        return response()->json($response);
    }

    /**
     * Check system health (Nutritionist & Admin)
     */
    public function healthCheck(): JsonResponse
    {
        $result = $this->makePythonApiRequest('/health', 'GET');
        
        $health = [
            'status' => 'ok',
            'timestamp' => now()->toISOString(),
            'laravel_api' => 'operational',
            'python_model' => $result['success'] ? 'operational' : 'error',
            'database' => 'operational',
            'user' => [
                'id' => Auth::id(),
                'role' => Auth::user()->role,
                'name' => Auth::user()->first_name . ' ' . Auth::user()->last_name
            ]
        ];

        if (!$result['success']) {
            $health['python_model_error'] = $result['error'];
            $health['status'] = 'degraded';
        }

        $this->logApiRequest('/health', 'GET', Auth::id(), null, $health, 'success');

        return response()->json($health);
    }

    /**
     * View API information (Nutritionist & Admin)
     */
    public function apiInfo(): JsonResponse
    {
        $info = [
            'name' => 'Treatment Model API',
            'version' => '1.0.0',
            'description' => 'Laravel wrapper for Child Malnutrition Assessment System',
            'endpoints' => [
                'nutritionist' => [
                    'POST /treatment-model/assess/single',
                    'POST /treatment-model/assess/batch',
                    'POST /treatment-model/assess/upload',
                    'GET /treatment-model/protocols',
                    'POST /treatment-model/risk/stratify',
                    'POST /treatment-model/predict/uncertainty',
                    'POST /treatment-model/recommendations/personalized',
                    'GET /treatment-model/data/template',
                    'POST /treatment-model/data/validate',
                    'GET /treatment-model/health'
                ],
                'admin_only' => [
                    'GET /treatment-model/model/info',
                    'POST /treatment-model/model/train',
                    'POST /treatment-model/protocols/set',
                    'GET /treatment-model/analytics/summary'
                ]
            ],
            'authentication' => 'Laravel Sanctum (Bearer Token)',
            'rate_limits' => [
                'standard' => '60 requests per minute',
                'bulk_operations' => '10 requests per minute'
            ]
        ];

        $this->logApiRequest('/', 'GET', Auth::id(), null, $info, 'success');

        return response()->json($info);
    }

    // ========== ADMIN ROLE ENDPOINTS ==========

    /**
     * View model details and performance metrics (Admin only)
     */
    public function getModelInfo(): JsonResponse
    {
        $result = $this->makePythonApiRequest('/model-info', 'GET');
        
        $this->logApiRequest('/model/info', 'GET', Auth::id(), null, $result['data'] ?? null, $result['success'] ? 'success' : 'error');

        if ($result['success']) {
            return response()->json($result['data']);
        } else {
            return response()->json(['error' => $result['error']], $result['status']);
        }
    }

    /**
     * Retrain the ML model (Admin only - critical system function)
     */
    public function trainModel(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'retrain_percentage' => 'nullable|numeric|min:10|max:100',
            'validation_split' => 'nullable|numeric|min:0.1|max:0.5',
            'force_retrain' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        // Log this critical operation
        Log::warning('Model retraining initiated by admin', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email,
            'timestamp' => now(),
            'parameters' => $request->all()
        ]);

        $result = $this->makePythonApiRequest('/model/train', 'POST', $request->all());
        
        $this->logApiRequest('/model/train', 'POST', Auth::id(), $request->all(), $result['data'] ?? null, $result['success'] ? 'success' : 'error');

        if ($result['success']) {
            return response()->json($result['data']);
        } else {
            return response()->json(['error' => $result['error']], $result['status']);
        }
    }

    /**
     * Change active treatment protocols (Admin only - system configuration)
     */
    public function setProtocols(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'active_protocol' => 'required|string|in:who_standard,community_based,hospital_intensive',
            'custom_protocols' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        // Log this critical configuration change
        Log::warning('Treatment protocol configuration changed by admin', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email,
            'timestamp' => now(),
            'parameters' => $request->all()
        ]);

        $result = $this->makePythonApiRequest('/protocols/set', 'POST', $request->all());
        
        $this->logApiRequest('/protocols/set', 'POST', Auth::id(), $request->all(), $result['data'] ?? null, $result['success'] ? 'success' : 'error');

        if ($result['success']) {
            return response()->json($result['data']);
        } else {
            return response()->json(['error' => $result['error']], $result['status']);
        }
    }

    /**
     * View system usage statistics and performance (Admin only)
     */
    public function getAnalyticsSummary(): JsonResponse
    {
        $result = $this->makePythonApiRequest('/analytics/summary', 'GET');
        
        // Add Laravel-specific analytics
        $laravelAnalytics = [
            'total_api_requests_today' => ApiLog::whereDate('created_at', today())->count(),
            'total_api_requests_this_month' => ApiLog::whereMonth('created_at', now()->month)->count(),
            'active_users_today' => ApiLog::whereDate('created_at', today())->distinct('user_id')->count(),
            'top_endpoints' => ApiLog::selectRaw('endpoint, COUNT(*) as count')
                ->whereDate('created_at', '>=', now()->subDays(7))
                ->groupBy('endpoint')
                ->orderByDesc('count')
                ->limit(10)
                ->get(),
            'error_rate' => [
                'today' => ApiLog::whereDate('created_at', today())->where('status', '!=', 'success')->count(),
                'total_today' => ApiLog::whereDate('created_at', today())->count()
            ]
        ];

        $analytics = [
            'python_model_analytics' => $result['success'] ? $result['data'] : ['error' => $result['error']],
            'laravel_api_analytics' => $laravelAnalytics,
            'timestamp' => now()->toISOString()
        ];

        $this->logApiRequest('/analytics/summary', 'GET', Auth::id(), null, $analytics, 'success');

        return response()->json($analytics);
    }
}
