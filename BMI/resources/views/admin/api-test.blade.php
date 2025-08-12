@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">🚀 API Test Dashboard</h1>
        <div class="flex items-center space-x-4">
            <div class="text-sm text-gray-600">
                {{ now()->format('l, F j, Y') }}
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                <span class="text-sm text-gray-600">Live Testing Environment</span>
            </div>
        </div>
    </div>

    <!-- API Health Status -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-green-600">
                <span class="mr-2">🏥</span>Treatment Model API Status
            </h3>
            <button id="checkHealthBtn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                <span class="mr-1">🔄</span>Check Health
            </button>
        </div>
        
        <!-- Demo Mode Notice -->
        @if(isset($apiStatus) && !$apiStatus['available'])
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
            <div class="flex items-center">
                <span class="mr-2">⚠️</span>
                <div>
                    <h4 class="font-semibold text-yellow-800">Python API Not Available</h4>
                    <p class="text-yellow-700 text-sm">The Treatment Model API is not running. Demo mode responses will be used.</p>
                    <p class="text-yellow-600 text-xs mt-1">To use real API: Start the Python server and set MALNUTRITION_API_URL in your .env file</p>
                </div>
            </div>
        </div>
        @endif
        
        <div id="demoNotice" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
            <div class="flex items-center">
                <span class="mr-2">ℹ️</span>
                <div>
                    <h4 class="font-semibold text-blue-800">Demo Mode Active</h4>
                    <p class="text-blue-700 text-sm">Using simulated responses for demonstration purposes.</p>
                </div>
            </div>
        </div>
        
        <div id="healthStatus" class="hidden">
            <!-- Status will be displayed here -->
        </div>
    </div>

    <!-- API Test Sections Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        
        <!-- Nutritionist Functions -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-4">
                <h3 class="text-lg font-semibold">
                    <span class="mr-2">👩‍⚕️</span>Nutritionist API Functions
                </h3>
                <p class="text-green-100 text-sm">Patient assessment and treatment planning</p>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Quick Assessment -->
                <div class="border-l-4 border-green-500 pl-4">
                    <h4 class="font-semibold text-gray-800 mb-3">Quick Patient Assessment</h4>
                    <form id="quickAssessmentForm" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Age (months)</label>
                                <input type="number" name="age_months" min="0" max="60" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500" 
                                       placeholder="12" value="12" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Weight (kg)</label>
                                <input type="number" name="weight" min="0.1" max="50" step="0.1" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500" 
                                       placeholder="8.5" value="8.5" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Height (cm)</label>
                                <input type="number" name="height" min="30" max="150" step="0.1" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500" 
                                       placeholder="75.0" value="75.0" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sex</label>
                                <select name="sex" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500" required>
                                    <option value="">Select</option>
                                    <option value="M" selected>Male</option>
                                    <option value="F">Female</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                            <span class="mr-1">🔍</span>Assess Patient
                        </button>
                    </form>
                    <div id="assessmentResult" class="mt-4 hidden"></div>
                </div>

                <!-- Risk Stratification -->
                <div class="border-l-4 border-yellow-500 pl-4">
                    <h4 class="font-semibold text-gray-800 mb-3">Risk Stratification</h4>
                    <button id="riskStratifyBtn" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                        <span class="mr-1">⚠️</span>Analyze Risk Factors
                    </button>
                    <div id="riskResult" class="mt-4 hidden"></div>
                </div>

                <!-- Treatment Protocols -->
                <div class="border-l-4 border-blue-500 pl-4">
                    <h4 class="font-semibold text-gray-800 mb-3">Treatment Protocols</h4>
                    <button id="getProtocolsBtn" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                        <span class="mr-1">📋</span>Load Protocols
                    </button>
                    <div id="protocolsResult" class="mt-4 hidden"></div>
                </div>
            </div>
        </div>

        <!-- Admin Functions -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-4">
                <h3 class="text-lg font-semibold">
                    <span class="mr-2">👨‍💼</span>Admin API Functions
                </h3>
                <p class="text-purple-100 text-sm">System management and analytics</p>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Model Information -->
                <div class="border-l-4 border-purple-500 pl-4">
                    <h4 class="font-semibold text-gray-800 mb-3">Model Information</h4>
                    <button id="getModelInfoBtn" class="w-full bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                        <span class="mr-1">🤖</span>Get Model Info
                    </button>
                    <div id="modelInfoResult" class="mt-4 hidden"></div>
                </div>

                <!-- Analytics Summary -->
                <div class="border-l-4 border-indigo-500 pl-4">
                    <h4 class="font-semibold text-gray-800 mb-3">Analytics Summary</h4>
                    <button id="getAnalyticsBtn" class="w-full bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                        <span class="mr-1">📊</span>View Analytics
                    </button>
                    <div id="analyticsResult" class="mt-4 hidden"></div>
                </div>

                <!-- Model Training -->
                <div class="border-l-4 border-red-500 pl-4">
                    <h4 class="font-semibold text-gray-800 mb-3">Model Training</h4>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-3">
                        <p class="text-red-700 text-sm">
                            <span class="mr-1">⚠️</span>Warning: Model training may take several minutes
                        </p>
                    </div>
                    <button id="trainModelBtn" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                        <span class="mr-1">🔧</span>Retrain Model
                    </button>
                    <div id="trainingResult" class="mt-4 hidden"></div>
                </div>

                <!-- Data Validation -->
                <div class="border-l-4 border-teal-500 pl-4">
                    <h4 class="font-semibold text-gray-800 mb-3">Data Validation</h4>
                    <textarea id="validationData" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500 mb-3" 
                              rows="3">{"age_months": 12, "weight": 8.5, "height": 75.0, "sex": "M"}</textarea>
                    <button id="validateDataBtn" class="w-full bg-teal-500 hover:bg-teal-600 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                        <span class="mr-1">✅</span>Validate Data
                    </button>
                    <div id="validationResult" class="mt-4 hidden"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Testing Section -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-6">
            <span class="mr-2">🧪</span>Advanced API Testing
        </h3>
        
        <!-- Quick Test All Button -->
        <div class="mb-6 p-4 bg-gradient-to-r from-purple-50 to-blue-50 border border-purple-200 rounded-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h4 class="font-semibold text-purple-800">Quick Test All Functions</h4>
                    <p class="text-purple-700 text-sm">Test all API endpoints with sample data</p>
                </div>
                <button id="testAllBtn" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                    <span class="mr-1">🚀</span>Test All APIs
                </button>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Custom API Request -->
            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="font-semibold text-gray-800 mb-3">Custom API Request</h4>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Endpoint</label>
                        <select id="customEndpoint" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Endpoint</option>
                            <option value="/api/treatment-model/health">Health Check</option>
                            <option value="/api/treatment-model/protocols">Get Protocols</option>
                            <option value="/api/treatment-model/assess/single">Assess Single Patient</option>
                            <option value="/api/treatment-model/model/info">Model Info (Admin)</option>
                            <option value="/api/treatment-model/analytics/summary">Analytics (Admin)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Method</label>
                        <select id="customMethod" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="GET">GET</option>
                            <option value="POST">POST</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Request Body (JSON)</label>
                        <textarea id="customBody" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" 
                                  rows="4" placeholder='{"key": "value"}'></textarea>
                    </div>
                    <button id="customRequestBtn" class="w-full bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                        <span class="mr-1">📡</span>Send Request
                    </button>
                </div>
            </div>

            <!-- Response Preview -->
            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="font-semibold text-gray-800 mb-3">Response Preview</h4>
                <div id="responsePreview" class="bg-gray-50 rounded-md p-3 min-h-32 font-mono text-sm">
                    <span class="text-gray-500">Response will appear here...</span>
                </div>
                <div class="mt-3 flex justify-between text-xs text-gray-500">
                    <span id="responseStatus">Status: -</span>
                    <span id="responseTime">Time: -</span>
                </div>
            </div>
        </div>
    </div>

    <!-- API Documentation Quick Reference -->
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-6 mb-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">
            <span class="mr-2">📚</span>API Quick Reference
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg p-4 border">
                <h4 class="font-semibold text-green-600 mb-2">Assessment Endpoints</h4>
                <ul class="text-sm space-y-1 text-gray-600">
                    <li><code class="text-xs bg-gray-100 px-1 rounded">POST /assess/single</code> - Single patient</li>
                    <li><code class="text-xs bg-gray-100 px-1 rounded">POST /assess/batch</code> - Multiple patients</li>
                    <li><code class="text-xs bg-gray-100 px-1 rounded">POST /assess/upload</code> - File upload</li>
                </ul>
            </div>
            
            <div class="bg-white rounded-lg p-4 border">
                <h4 class="font-semibold text-blue-600 mb-2">Analysis Endpoints</h4>
                <ul class="text-sm space-y-1 text-gray-600">
                    <li><code class="text-xs bg-gray-100 px-1 rounded">POST /risk/stratify</code> - Risk analysis</li>
                    <li><code class="text-xs bg-gray-100 px-1 rounded">POST /predict/uncertainty</code> - Uncertainty</li>
                    <li><code class="text-xs bg-gray-100 px-1 rounded">POST /recommendations/personalized</code> - Recommendations</li>
                </ul>
            </div>
            
            <div class="bg-white rounded-lg p-4 border">
                <h4 class="font-semibold text-purple-600 mb-2">Admin Endpoints</h4>
                <ul class="text-sm space-y-1 text-gray-600">
                    <li><code class="text-xs bg-gray-100 px-1 rounded">GET /model/info</code> - Model details</li>
                    <li><code class="text-xs bg-gray-100 px-1 rounded">POST /model/train</code> - Retrain model</li>
                    <li><code class="text-xs bg-gray-100 px-1 rounded">GET /analytics/summary</code> - Analytics</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Setup Instructions -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">
            <span class="mr-2">⚙️</span>API Setup Instructions
        </h3>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="border-l-4 border-green-500 pl-4">
                <h4 class="font-semibold text-gray-800 mb-3">🐍 Python API Setup</h4>
                <div class="bg-gray-50 rounded-lg p-4 font-mono text-sm mb-3">
                    <div class="text-gray-600 mb-2"># Navigate to Python API directory</div>
                    <div class="text-blue-600">cd Treatment_Model_Random_Forest</div>
                    <div class="text-gray-600 mt-2 mb-2"># Install dependencies</div>
                    <div class="text-blue-600">pip install -r requirements.txt</div>
                    <div class="text-gray-600 mt-2 mb-2"># Start the API server</div>
                    <div class="text-blue-600">python -m uvicorn main:app --host 0.0.0.0 --port 8081</div>
                </div>
            </div>
            
            <div class="border-l-4 border-blue-500 pl-4">
                <h4 class="font-semibold text-gray-800 mb-3">🔧 Laravel Configuration</h4>
                <div class="bg-gray-50 rounded-lg p-4 font-mono text-sm mb-3">
                    <div class="text-gray-600 mb-2"># Add to your .env file</div>
                    <div class="text-green-600">MALNUTRITION_API_URL=http://127.0.0.1:8081</div>
                    <div class="text-green-600">MALNUTRITION_API_TIMEOUT=30</div>
                    <div class="text-green-600">MALNUTRITION_API_KEY=your_api_key</div>
                    <div class="text-gray-600 mt-2 mb-2"># For demo mode (optional)</div>
                    <div class="text-orange-600">TREATMENT_MODEL_DEMO_MODE=true</div>
                </div>
            </div>
        </div>
        
        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <h5 class="font-semibold text-blue-800 mb-2">💡 Pro Tips</h5>
            <ul class="text-blue-700 text-sm space-y-1">
                <li>• Demo mode provides realistic sample data when the Python API is unavailable</li>
                <li>• The Python API includes automatic data validation and comprehensive error handling</li>
                <li>• All API requests are logged for audit purposes in the Laravel application</li>
                <li>• Use the Custom API Request section to test specific endpoints with your own data</li>
            </ul>
        </div>
    </div>
</div>

<!-- JavaScript for API Testing -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Utility function to display results
    function displayResult(elementId, data, isError = false) {
        const element = document.getElementById(elementId);
        element.classList.remove('hidden');
        
        const isDemo = data.demo_mode || (data.data && data.data.demo_mode);
        const className = isError ? 'bg-red-50 border border-red-200 text-red-700' : 'bg-green-50 border border-green-200 text-green-700';
        const icon = isError ? '❌' : '✅';
        const demoTag = isDemo ? '<span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full ml-2">DEMO MODE</span>' : '';
        
        // Show demo notice if in demo mode
        if (isDemo) {
            document.getElementById('demoNotice').classList.remove('hidden');
        }
        
        element.innerHTML = `
            <div class="${className} rounded-lg p-4">
                <div class="flex items-start">
                    <span class="mr-2">${icon}</span>
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            <span class="font-medium">Response</span>
                            ${demoTag}
                        </div>
                        <pre class="text-sm overflow-x-auto">${JSON.stringify(data, null, 2)}</pre>
                    </div>
                </div>
            </div>
        `;
    }

    // Health Check
    document.getElementById('checkHealthBtn').addEventListener('click', async function() {
        this.disabled = true;
        this.innerHTML = '<span class="mr-1">⏳</span>Checking...';
        
        try {
            const response = await fetch('/admin/treatment-model-api/health', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                }
            });
            
            const data = await response.json();
            displayResult('healthStatus', data, !response.ok);
        } catch (error) {
            displayResult('healthStatus', {error: error.message}, true);
        } finally {
            this.disabled = false;
            this.innerHTML = '<span class="mr-1">🔄</span>Check Health';
        }
    });

    // Quick Assessment
    document.getElementById('quickAssessmentForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="mr-1">⏳</span>Assessing...';
        
        try {
            const response = await fetch('/admin/treatment-model-api/assess/single', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            displayResult('assessmentResult', result, !response.ok);
        } catch (error) {
            displayResult('assessmentResult', {error: error.message}, true);
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span class="mr-1">🔍</span>Assess Patient';
        }
    });

    // Risk Stratification
    document.getElementById('riskStratifyBtn').addEventListener('click', async function() {
        this.disabled = true;
        this.innerHTML = '<span class="mr-1">⏳</span>Analyzing...';
        
        const sampleData = {
            age_months: 12,
            weight: 7.5,
            height: 75.0,
            sex: 'M',
            has_symptoms: true,
            family_history: false
        };
        
        try {
            const response = await fetch('/admin/treatment-model-api/risk/stratify', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(sampleData)
            });
            
            const result = await response.json();
            displayResult('riskResult', result, !response.ok);
        } catch (error) {
            displayResult('riskResult', {error: error.message}, true);
        } finally {
            this.disabled = false;
            this.innerHTML = '<span class="mr-1">⚠️</span>Analyze Risk Factors';
        }
    });

    // Get Protocols
    document.getElementById('getProtocolsBtn').addEventListener('click', async function() {
        this.disabled = true;
        this.innerHTML = '<span class="mr-1">⏳</span>Loading...';
        
        try {
            const response = await fetch('/admin/treatment-model-api/protocols', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                }
            });
            
            const result = await response.json();
            displayResult('protocolsResult', result, !response.ok);
        } catch (error) {
            displayResult('protocolsResult', {error: error.message}, true);
        } finally {
            this.disabled = false;
            this.innerHTML = '<span class="mr-1">📋</span>Load Protocols';
        }
    });

    // Model Info (Admin only)
    document.getElementById('getModelInfoBtn').addEventListener('click', async function() {
        this.disabled = true;
        this.innerHTML = '<span class="mr-1">⏳</span>Loading...';
        
        try {
            const response = await fetch('/admin/treatment-model-api/model/info', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                }
            });
            
            const result = await response.json();
            displayResult('modelInfoResult', result, !response.ok);
        } catch (error) {
            displayResult('modelInfoResult', {error: error.message}, true);
        } finally {
            this.disabled = false;
            this.innerHTML = '<span class="mr-1">🤖</span>Get Model Info';
        }
    });

    // Analytics (Admin only)
    document.getElementById('getAnalyticsBtn').addEventListener('click', async function() {
        this.disabled = true;
        this.innerHTML = '<span class="mr-1">⏳</span>Loading...';
        
        try {
            const response = await fetch('/admin/treatment-model-api/analytics/summary', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                }
            });
            
            const result = await response.json();
            displayResult('analyticsResult', result, !response.ok);
        } catch (error) {
            displayResult('analyticsResult', {error: error.message}, true);
        } finally {
            this.disabled = false;
            this.innerHTML = '<span class="mr-1">📊</span>View Analytics';
        }
    });

    // Model Training (Admin only)
    document.getElementById('trainModelBtn').addEventListener('click', async function() {
        if (!confirm('Are you sure you want to retrain the model? This may take several minutes.')) {
            return;
        }
        
        this.disabled = true;
        this.innerHTML = '<span class="mr-1">⏳</span>Training...';
        
        try {
            const response = await fetch('/admin/treatment-model-api/model/train', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                }
            });
            
            const result = await response.json();
            displayResult('trainingResult', result, !response.ok);
        } catch (error) {
            displayResult('trainingResult', {error: error.message}, true);
        } finally {
            this.disabled = false;
            this.innerHTML = '<span class="mr-1">🔧</span>Retrain Model';
        }
    });

    // Data Validation
    document.getElementById('validateDataBtn').addEventListener('click', async function() {
        const dataText = document.getElementById('validationData').value;
        
        if (!dataText.trim()) {
            alert('Please enter data to validate');
            return;
        }
        
        let data;
        try {
            data = JSON.parse(dataText);
        } catch (error) {
            displayResult('validationResult', {error: 'Invalid JSON format'}, true);
            return;
        }
        
        this.disabled = true;
        this.innerHTML = '<span class="mr-1">⏳</span>Validating...';
        
        try {
            const response = await fetch('/admin/treatment-model-api/data/validate', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            displayResult('validationResult', result, !response.ok);
        } catch (error) {
            displayResult('validationResult', {error: error.message}, true);
        } finally {
            this.disabled = false;
            this.innerHTML = '<span class="mr-1">✅</span>Validate Data';
        }
    });

    // Custom API Request
    document.getElementById('customRequestBtn').addEventListener('click', async function() {
        const endpoint = document.getElementById('customEndpoint').value;
        const method = document.getElementById('customMethod').value;
        const body = document.getElementById('customBody').value;
        
        if (!endpoint) {
            alert('Please select an endpoint');
            return;
        }
        
        this.disabled = true;
        this.innerHTML = '<span class="mr-1">⏳</span>Sending...';
        
        const startTime = Date.now();
        
        try {
            const options = {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                }
            };
            
            if (method === 'POST' && body.trim()) {
                options.headers['Content-Type'] = 'application/json';
                options.body = body;
            }
            
            const response = await fetch(endpoint, options);
            const result = await response.json();
            const endTime = Date.now();
            
            document.getElementById('responsePreview').innerHTML = `<pre>${JSON.stringify(result, null, 2)}</pre>`;
            document.getElementById('responseStatus').textContent = `Status: ${response.status} ${response.statusText}`;
            document.getElementById('responseTime').textContent = `Time: ${endTime - startTime}ms`;
            
        } catch (error) {
            document.getElementById('responsePreview').innerHTML = `<span class="text-red-600">Error: ${error.message}</span>`;
            document.getElementById('responseStatus').textContent = 'Status: Error';
            document.getElementById('responseTime').textContent = 'Time: -';
        } finally {
            this.disabled = false;
            this.innerHTML = '<span class="mr-1">📡</span>Send Request';
        }
    });

    // Test All Functions
    document.getElementById('testAllBtn').addEventListener('click', async function() {
        if (!confirm('This will test all API functions. Continue?')) {
            return;
        }
        
        this.disabled = true;
        this.innerHTML = '<span class="mr-1">⏳</span>Testing All...';
        
        const tests = [
            { name: 'Health Check', button: 'checkHealthBtn' },
            { name: 'Quick Assessment', form: 'quickAssessmentForm' },
            { name: 'Risk Stratification', button: 'riskStratifyBtn' },
            { name: 'Protocols', button: 'getProtocolsBtn' },
            { name: 'Model Info', button: 'getModelInfoBtn' },
            { name: 'Analytics', button: 'getAnalyticsBtn' },
            { name: 'Data Validation', button: 'validateDataBtn' }
        ];
        
        for (const test of tests) {
            console.log(`Testing: ${test.name}`);
            
            if (test.button) {
                const button = document.getElementById(test.button);
                if (button) {
                    button.click();
                    await new Promise(resolve => setTimeout(resolve, 1000)); // Wait 1 second between tests
                }
            } else if (test.form) {
                const form = document.getElementById(test.form);
                if (form) {
                    const event = new Event('submit');
                    form.dispatchEvent(event);
                    await new Promise(resolve => setTimeout(resolve, 1000)); // Wait 1 second between tests
                }
            }
        }
        
        this.disabled = false;
        this.innerHTML = '<span class="mr-1">🚀</span>Test All APIs';
        
        alert('All API tests completed! Check the results above.');
    });
});
</script>
@endsection
