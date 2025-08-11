<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class MalnutritionService
{
    protected $apiUrl;
    protected $apiKey;
    protected $timeout;
    protected $retryAttempts;
    protected $retryDelay;

    public function __construct()
    {
        $this->apiUrl = config('services.malnutrition.api_url', 'http://127.0.0.1:8081');
        
        // Use API key from config
        $this->apiKey = config('services.malnutrition.api_key', '0mI4mQA975wCrFiDTIoj8UDOrFT0OtEqOKi4DhpRfOBdzch8HyKk58zieQ9I5F3j');
        
        $this->timeout = config('services.malnutrition.timeout', 30);
        $this->retryAttempts = config('services.malnutrition.retry_attempts', 3);
        $this->retryDelay = config('services.malnutrition.retry_delay', 1000);
        
        // Debug log
        Log::info("MalnutritionService API Key: " . substr($this->apiKey, 0, 10) . "...");
    }

    /**
     * Get HTTP client with authentication headers
     */
    private function getHttpClient()
    {
        return Http::timeout($this->timeout)
            ->connectTimeout(5)
            ->retry($this->retryAttempts, $this->retryDelay)
            ->withHeaders([
                'X-API-Key' => $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]);
    }

    /**
     * Check if the API is healthy
     */
    public function healthCheck()
    {
        try {
            $response = $this->getHttpClient()->get("{$this->apiUrl}/health");

            return $response->successful() ? $response->json() : false;
        } catch (Exception $e) {
            Log::error('Malnutrition API health check failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Test connection to the API
     */
    public function testConnection()
    {
        try {
            $response = Http::timeout(5)
                ->connectTimeout(2)
                ->get($this->apiUrl);

            return [
                'success' => true,
                'status' => $response->status(),
                'url' => $this->apiUrl,
                'response' => $response->body()
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'url' => $this->apiUrl
            ];
        }
    }

    /**
     * Assess a single patient
     */
    public function assessPatient(array $patientData)
    {
        try {
            $response = $this->getHttpClient()
                ->post("{$this->apiUrl}/assess/single", $patientData);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json() // FastAPI returns the data directly, not wrapped in 'data' key
                ];
            }

            Log::error('Malnutrition API Error: ' . $response->body());
            
            return [
                'success' => false,
                'error' => 'Assessment failed: ' . ($response->json()['detail'] ?? 'Unknown error'),
                'status_code' => $response->status()
            ];

        } catch (Exception $e) {
            Log::error('Malnutrition API Exception: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => 'API connection failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Batch assess multiple patients
     */
    public function batchAssess(array $patientsData)
    {
        try {
            $response = $this->getHttpClient()
                ->timeout($this->timeout * 2) // Longer timeout for batch
                ->post("{$this->apiUrl}/assess/batch", [
                    'patients' => $patientsData
                ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'error' => 'Batch assessment failed',
                'details' => $response->json()
            ];

        } catch (Exception $e) {
            Log::error('Batch assessment failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => 'Batch assessment connection failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get treatment protocols
     */
    public function getTreatmentProtocols()
    {
        try {
            $response = $this->getHttpClient()
                ->get("{$this->apiUrl}/protocols");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json() // FastAPI returns the protocols object directly
                ];
            }

            return [
                'success' => false,
                'error' => 'Failed to load protocols: HTTP ' . $response->status()
            ];

        } catch (Exception $e) {
            Log::error('Failed to get protocols: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => 'Protocol connection failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get model information
     */
    public function getModelInfo()
    {
        try {
            $response = $this->getHttpClient()
                ->get("{$this->apiUrl}/model/info");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json() // FastAPI returns the ModelInfo object directly
                ];
            }

            return [
                'success' => false,
                'error' => 'Failed to get model info: HTTP ' . $response->status()
            ];

        } catch (Exception $e) {
            Log::error('Failed to get model info: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => 'Model info connection failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get uncertainty quantification for a patient assessment
     */
    public function getUncertaintyQuantification(array $patientData)
    {
        try {
            $response = $this->getHttpClient()
                ->post("{$this->apiUrl}/uncertainty/quantify", $patientData);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'error' => 'Failed to get uncertainty quantification'
            ];

        } catch (Exception $e) {
            Log::error('Failed to get uncertainty quantification: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => 'Uncertainty quantification failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get personalized recommendations for a patient
     */
    public function getPersonalizedRecommendations(array $patientData)
    {
        try {
            $response = $this->getHttpClient()
                ->post("{$this->apiUrl}/recommendations/personalized", $patientData);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'error' => 'Failed to get personalized recommendations'
            ];

        } catch (Exception $e) {
            Log::error('Failed to get personalized recommendations: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => 'Personalized recommendations failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Upload file for batch assessment
     */
    public function uploadFileForAssessment($file, string $fileType = 'csv')
    {
        try {
            $response = $this->getHttpClient()
                ->attach('file', file_get_contents($file->getRealPath()), $file->getClientOriginalName())
                ->post("{$this->apiUrl}/assess/upload", [
                    'file_type' => $fileType
                ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'error' => 'File upload failed'
            ];

        } catch (Exception $e) {
            Log::error('File upload failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => 'File upload connection failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get analytics summary
     */
    public function getAnalyticsSummary()
    {
        try {
            $response = $this->getHttpClient()
                ->get("{$this->apiUrl}/analytics/summary");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'error' => 'Failed to get analytics summary'
            ];

        } catch (Exception $e) {
            Log::error('Failed to get analytics summary: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => 'Analytics summary failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Validate patient data format
     */
    public function validatePatientData(array $patientData)
    {
        try {
            $response = $this->getHttpClient()
                ->post("{$this->apiUrl}/data/validate", $patientData);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'error' => 'Data validation failed'
            ];

        } catch (Exception $e) {
            Log::error('Data validation failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => 'Data validation connection failed: ' . $e->getMessage()
            ];
        }
    }
}