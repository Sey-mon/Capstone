<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class MalnutritionService
{
    protected $apiUrl;
    protected $timeout;

    public function __construct()
    {
        $this->apiUrl = config('services.malnutrition.api_url', 'http://127.0.0.1:8080');
        $this->timeout = config('services.malnutrition.timeout', 10); // Reduced timeout
    }

    /**
     * Check if the API is healthy
     */
    public function healthCheck()
    {
        try {
            $response = Http::timeout($this->timeout)
                ->connectTimeout(5) // 5 second connection timeout
                ->retry(2, 1000) // Retry 2 times with 1 second delay
                ->get("{$this->apiUrl}/health");

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
            $response = Http::timeout($this->timeout)
                ->connectTimeout(5)
                ->retry(2, 1000)
                ->post("{$this->apiUrl}/assess", $patientData);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()['data']
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
            $response = Http::timeout($this->timeout * 2) // Longer timeout for batch
                ->post("{$this->apiUrl}/batch-assess", [
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
            $response = Http::timeout($this->timeout)
                ->get("{$this->apiUrl}/protocols");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()['data']
                ];
            }

            return [
                'success' => false,
                'error' => 'Failed to load protocols'
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
            $response = Http::timeout($this->timeout)
                ->get("{$this->apiUrl}/model-info");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()['data']
                ];
            }

            return [
                'success' => false,
                'error' => 'Failed to get model info'
            ];

        } catch (Exception $e) {
            Log::error('Failed to get model info: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => 'Model info connection failed: ' . $e->getMessage()
            ];
        }
    }
}