@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">FastAPI Model Test Interface</h1>
        <div class="flex space-x-2">
            <button onclick="checkAPIHealth()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                Check API Health
            </button>
            <button onclick="getModelInfo()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200">
                Model Info
            </button>
        </div>
    </div>

    <!-- API Status Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">API Status</h3>
                    <p class="text-2xl font-bold" id="apiStatus">Unknown</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">Model Status</h3>
                    <p class="text-2xl font-bold" id="modelStatus">Unknown</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">Protocols</h3>
                    <p class="text-2xl font-bold" id="protocolStatus">Unknown</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Test Results Panel -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">API Response</h2>
        <div class="bg-gray-50 rounded-lg p-4 min-h-32">
            <pre id="apiResponse" class="text-sm text-gray-700 whitespace-pre-wrap">Click a button above to test the API...</pre>
        </div>
    </div>

    <!-- Debug Panel for Data Sent -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Debug: Data Sent to FastAPI</h2>
        <div class="bg-blue-50 rounded-lg p-4 min-h-32">
            <pre id="debugData" class="text-sm text-blue-700 whitespace-pre-wrap">Assessment data will appear here...</pre>
        </div>
    </div>

    <!-- Patient Assessment Test Form -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Test Malnutrition Assessment</h2>
        
        <form id="assessmentForm" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-700">Basic Information</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Patient Name</label>
                        <input type="text" name="name" value="Test Patient" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Age (months)</label>
                        <input type="number" name="age_months" value="24" min="0" max="60" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sex</label>
                        <select name="sex" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Municipality</label>
                        <input type="text" name="municipality" value="Test Municipality" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                </div>

                <!-- Measurements -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-700">Measurements</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Weight (kg)</label>
                        <input type="number" name="weight" value="10.5" step="0.1" min="0.1" max="50" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Height (cm)</label>
                        <input type="number" name="height" value="85" step="0.1" min="30" max="150" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Patient ID (optional)</label>
                        <input type="text" name="patient_id" value="TEST001" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                </div>
            </div>

            <!-- Household Information -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-700">Household Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Household Members</label>
                        <input type="number" name="total_household" value="5" min="1" max="20" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Adults</label>
                        <input type="number" name="adults" value="2" min="1" max="10" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Children</label>
                        <input type="number" name="children" value="3" min="0" max="15" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                </div>
            </div>

            <!-- Medical History -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-700">Medical History</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_twin" class="mr-2">
                            <span class="text-sm">Is Twin</span>
                        </label>
                    </div>
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="edema" class="mr-2">
                            <span class="text-sm">Has Edema</span>
                        </label>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">4P's Beneficiary</label>
                        <select name="fourps_beneficiary" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="">Select</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Breastfeeding</label>
                        <select name="breastfeeding" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="">Select</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tuberculosis</label>
                        <select name="tuberculosis" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="">Select</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Malaria</label>
                        <select name="malaria" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="">Select</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Congenital Anomalies</label>
                        <select name="congenital_anomalies" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="">Select</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Other Medical Problems</label>
                        <select name="other_medical_problems" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="">Select</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex space-x-4">
                <button type="button" onclick="testAssessment()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition duration-200">
                    Test Assessment
                </button>
                <button type="button" onclick="loadSampleData()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                    Load Sample Data
                </button>
                <button type="button" onclick="testMinimumData()" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg transition duration-200">
                    Test Minimum Data
                </button>
                <button type="button" onclick="clearForm()" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition duration-200">
                    Clear Form
                </button>
            </div>
        </form>
    </div>

    <!-- Assessment Results -->
    <div id="assessmentResults" class="bg-white rounded-lg shadow-lg p-6 hidden">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Assessment Results</h2>
        <div id="resultContent" class="space-y-4"></div>
    </div>
</div>

<script>
// Global variables
const LARAVEL_API_BASE = '{{ url("/api") }}';
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Utility function to display results
function displayResult(data, containerId = 'apiResponse') {
    const container = document.getElementById(containerId);
    container.textContent = JSON.stringify(data, null, 2);
}

// Utility function to show loading state
function showLoading(elementId, loadingText = 'Loading...') {
    document.getElementById(elementId).textContent = loadingText;
}

// Check API Health
async function checkAPIHealth() {
    showLoading('apiResponse', 'Checking API health...');
    
    try {
        const response = await fetch(`${LARAVEL_API_BASE}/health`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
        });
        
        const data = await response.json();
        displayResult(data);
        
        // Update status indicators
        if (data.success) {
            document.getElementById('apiStatus').textContent = 'Healthy';
            document.getElementById('apiStatus').className = 'text-2xl font-bold text-green-600';
            
            if (data.data && data.data.model_loaded) {
                document.getElementById('modelStatus').textContent = 'Loaded';
                document.getElementById('modelStatus').className = 'text-2xl font-bold text-green-600';
            }
            
            if (data.data && data.data.protocols_loaded) {
                document.getElementById('protocolStatus').textContent = 'Loaded';
                document.getElementById('protocolStatus').className = 'text-2xl font-bold text-green-600';
            }
        } else {
            document.getElementById('apiStatus').textContent = 'Error';
            document.getElementById('apiStatus').className = 'text-2xl font-bold text-red-600';
        }
        
    } catch (error) {
        console.error('Health check failed:', error);
        displayResult({
            error: 'Failed to connect to API',
            details: error.message
        });
        
        document.getElementById('apiStatus').textContent = 'Offline';
        document.getElementById('apiStatus').className = 'text-2xl font-bold text-red-600';
    }
}

// Get Model Information
async function getModelInfo() {
    showLoading('apiResponse', 'Getting model information...');
    
    try {
        const response = await fetch(`${LARAVEL_API_BASE}/model-info`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
        });
        
        const data = await response.json();
        displayResult(data);
        
    } catch (error) {
        console.error('Model info failed:', error);
        displayResult({
            error: 'Failed to get model info',
            details: error.message
        });
    }
}

// Test Assessment
async function testAssessment() {
    showLoading('apiResponse', 'Running assessment...');
    
    const form = document.getElementById('assessmentForm');
    const formData = new FormData(form);
    
    // Convert FormData to JSON
    const data = {};
    for (let [key, value] of formData.entries()) {
        if (key === 'is_twin' || key === 'edema') {
            data[key] = form.querySelector(`[name="${key}"]`).checked;
        } else if (key === 'age_months' || key === 'weight' || key === 'height' || key === 'total_household' || key === 'adults' || key === 'children') {
            data[key] = parseFloat(value) || 0;
        } else {
            data[key] = value || '';
        }
    }
    
    // Show debug data
    document.getElementById('debugData').textContent = JSON.stringify(data, null, 2);
    
    try {
        const response = await fetch(`${LARAVEL_API_BASE}/assess`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        displayResult(result);
        
        // Show detailed results if successful
        if (result.success && result.data) {
            showAssessmentResults(result.data);
        }
        
    } catch (error) {
        console.error('Assessment failed:', error);
        displayResult({
            error: 'Assessment failed',
            details: error.message
        });
    }
}

// Show detailed assessment results
function showAssessmentResults(data) {
    const resultsDiv = document.getElementById('assessmentResults');
    const contentDiv = document.getElementById('resultContent');
    
    // Create result cards based on your actual FastAPI response structure
    let html = '';
    
    // Patient Information
    if (data.patient_info) {
        html += `
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-blue-800 mb-2">Patient Information</h3>
                <div class="grid grid-cols-2 gap-4">
                    <p class="text-blue-700"><strong>Name:</strong> ${data.patient_info.name || 'N/A'}</p>
                    <p class="text-blue-700"><strong>Patient ID:</strong> ${data.patient_info.patient_id || 'N/A'}</p>
                    <p class="text-blue-700"><strong>Age:</strong> ${data.patient_info.age_months || 'N/A'} months</p>
                    <p class="text-blue-700"><strong>Sex:</strong> ${data.patient_info.sex || 'N/A'}</p>
                    <p class="text-blue-700"><strong>Municipality:</strong> ${data.patient_info.municipality || 'N/A'}</p>
                </div>
            </div>
        `;
    }
    
    // Measurements
    if (data.measurements) {
        html += `
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-green-800 mb-2">Physical Measurements</h3>
                <div class="grid grid-cols-3 gap-4">
                    <p class="text-green-700"><strong>Weight:</strong> ${data.measurements.weight_kg || 'N/A'} kg</p>
                    <p class="text-green-700"><strong>Height:</strong> ${data.measurements.height_cm || 'N/A'} cm</p>
                    <p class="text-green-700"><strong>BMI:</strong> ${data.measurements.bmi || 'N/A'}</p>
                </div>
            </div>
        `;
    }
    
    // Assessment Results
    if (data.assessment_results) {
        const status = data.assessment_results.nutritional_status;
        const statusColor = status && status.includes('SAM') ? 'red' : 
                           status && status.includes('MAM') ? 'yellow' : 'green';
        
        html += `
            <div class="bg-${statusColor}-50 border border-${statusColor}-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-${statusColor}-800 mb-2">Assessment Results</h3>
                <div class="grid grid-cols-2 gap-4">
                    <p class="text-${statusColor}-700"><strong>Nutritional Status:</strong> ${data.assessment_results.nutritional_status || 'N/A'}</p>
                    <p class="text-${statusColor}-700"><strong>Risk Level:</strong> ${data.assessment_results.risk_level || 'N/A'}</p>
                    <p class="text-${statusColor}-700"><strong>WHZ Score:</strong> ${data.assessment_results.whz_score || 'N/A'}</p>
                    <p class="text-${statusColor}-700"><strong>Confidence:</strong> ${data.assessment_results.confidence_score || 'N/A'}</p>
                </div>
            </div>
        `;
    }
    
    // Recommendations
    if (data.recommendations) {
        html += `
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-purple-800 mb-2">Recommendations</h3>
                <p class="text-purple-700">${data.recommendations.note || 'No specific recommendations available'}</p>
            </div>
        `;
    }
    
    // Medical History
    if (data.medical_history) {
        html += `
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Medical History</h3>
                <div class="grid grid-cols-3 gap-4">
                    <p class="text-gray-700"><strong>4P's Beneficiary:</strong> ${data.medical_history.fourps_beneficiary || 'N/A'}</p>
                    <p class="text-gray-700"><strong>Breastfeeding:</strong> ${data.medical_history.breastfeeding || 'N/A'}</p>
                    <p class="text-gray-700"><strong>Tuberculosis:</strong> ${data.medical_history.tuberculosis || 'N/A'}</p>
                    <p class="text-gray-700"><strong>Malaria:</strong> ${data.medical_history.malaria || 'N/A'}</p>
                    <p class="text-gray-700"><strong>Edema:</strong> ${data.medical_history.edema ? 'Yes' : 'No'}</p>
                </div>
            </div>
        `;
    }
    
    // Assessment Metadata
    if (data.assessment_metadata) {
        html += `
            <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-indigo-800 mb-2">Assessment Metadata</h3>
                <div class="grid grid-cols-3 gap-4">
                    <p class="text-indigo-700"><strong>Assessment Date:</strong> ${data.assessment_metadata.assessment_date || 'N/A'}</p>
                    <p class="text-indigo-700"><strong>Model Version:</strong> ${data.assessment_metadata.model_version || 'N/A'}</p>
                    <p class="text-indigo-700"><strong>API Version:</strong> ${data.assessment_metadata.api_version || 'N/A'}</p>
                </div>
            </div>
        `;
    }
    
    contentDiv.innerHTML = html;
    resultsDiv.classList.remove('hidden');
}

// Clear form
function clearForm() {
    document.getElementById('assessmentForm').reset();
    document.getElementById('assessmentResults').classList.add('hidden');
    document.getElementById('debugData').textContent = 'Assessment data will appear here...';
}

// Load sample data from your FastAPI documentation
function loadSampleData() {
    const form = document.getElementById('assessmentForm');
    
    // Sample data matching your FastAPI example
    form.querySelector('[name="name"]').value = "Juan Dela Cruz";
    form.querySelector('[name="age_months"]').value = "15.5";
    form.querySelector('[name="weight"]').value = "8.7";
    form.querySelector('[name="height"]').value = "78.3";
    form.querySelector('[name="sex"]').value = "Male";
    form.querySelector('[name="municipality"]').value = "Manila";
    form.querySelector('[name="patient_id"]').value = "P12345";
    form.querySelector('[name="total_household"]').value = "6";
    form.querySelector('[name="adults"]').value = "2";
    form.querySelector('[name="children"]').value = "4";
    form.querySelector('[name="is_twin"]').checked = false;
    form.querySelector('[name="fourps_beneficiary"]').value = "Yes";
    form.querySelector('[name="breastfeeding"]').value = "No";
    form.querySelector('[name="tuberculosis"]').value = "No";
    form.querySelector('[name="malaria"]').value = "No";
    form.querySelector('[name="congenital_anomalies"]').value = "No";
    form.querySelector('[name="other_medical_problems"]').value = "No";
    form.querySelector('[name="edema"]').checked = false;
}

// Test with minimum required data only
function testMinimumData() {
    const form = document.getElementById('assessmentForm');
    
    // Clear form first
    clearForm();
    
    // Set only required fields
    form.querySelector('[name="age_months"]').value = "15.5";
    form.querySelector('[name="weight"]').value = "8.7";
    form.querySelector('[name="height"]').value = "78.3";
    form.querySelector('[name="sex"]').value = "Male";
    
    // Run assessment with minimum data
    testAssessment();
}

// Auto-check API health on page load
document.addEventListener('DOMContentLoaded', function() {
    checkAPIHealth();
});
</script>
@endsection
