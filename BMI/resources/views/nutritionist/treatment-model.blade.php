<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Treatment Model API') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- API Status Check -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-lg text-green-600">Treatment Model API Status</h3>
                        <button id="checkHealthBtn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Check Status
                        </button>
                    </div>
                    <div id="healthStatus" class="hidden">
                        <!-- Status will be displayed here -->
                    </div>
                </div>
            </div>

            <!-- Quick Assessment Tool -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold text-lg text-green-600 mb-4">Quick Patient Assessment</h3>
                    
                    <form id="assessmentForm">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                            <div>
                                <label for="age_months" class="block text-sm font-medium text-gray-700">Age (months)</label>
                                <input type="number" name="age_months" id="age_months" min="0" max="60" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                            </div>
                            <div>
                                <label for="weight" class="block text-sm font-medium text-gray-700">Weight (kg)</label>
                                <input type="number" name="weight" id="weight" min="0.1" max="50" step="0.1" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                            </div>
                            <div>
                                <label for="height" class="block text-sm font-medium text-gray-700">Height (cm)</label>
                                <input type="number" name="height" id="height" min="30" max="150" step="0.1" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                            </div>
                            <div>
                                <label for="sex" class="block text-sm font-medium text-gray-700">Sex</label>
                                <select name="sex" id="sex" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                                    <option value="">Select Sex</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Patient Name (Optional)</label>
                                <input type="text" name="name" id="name" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                            </div>
                            <div>
                                <label for="patient_id" class="block text-sm font-medium text-gray-700">Patient ID (Optional)</label>
                                <input type="text" name="patient_id" id="patient_id" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                            </div>
                            <div>
                                <label for="municipality" class="block text-sm font-medium text-gray-700">Municipality (Optional)</label>
                                <input type="text" name="municipality" id="municipality" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                            </div>
                        </div>

                        <div class="flex space-x-4 mb-4">
                            <button type="button" id="validateDataBtn" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                Validate Data
                            </button>
                            <button type="submit" id="assessBtn" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Assess Patient
                            </button>
                            <button type="button" id="riskStratifyBtn" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
                                Risk Stratification
                            </button>
                            <button type="button" id="uncertaintyBtn" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                                Uncertainty Analysis
                            </button>
                        </div>
                    </form>

                    <!-- Results Display -->
                    <div id="resultsContainer" class="hidden mt-6">
                        <h4 class="font-semibold text-md mb-3 text-gray-800">Results</h4>
                        <div id="resultContent" class="p-4 bg-gray-50 rounded-lg">
                            <!-- Results will be displayed here -->
                        </div>
                    </div>

                    <!-- Validation Results -->
                    <div id="validationContainer" class="hidden mt-6">
                        <h4 class="font-semibold text-md mb-3 text-gray-800">Data Validation</h4>
                        <div id="validationContent" class="p-4 bg-gray-50 rounded-lg">
                            <!-- Validation results will be displayed here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Treatment Protocols -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-lg text-green-600">Treatment Protocols</h3>
                        <button id="loadProtocolsBtn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Load Protocols
                        </button>
                    </div>
                    <div id="protocolsContainer" class="hidden">
                        <div id="protocolsContent">
                            <!-- Protocols will be displayed here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Personalized Recommendations -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold text-lg text-green-600 mb-4">Personalized Recommendations</h3>
                    
                    <form id="recommendationsForm">
                        @csrf
                        <input type="hidden" id="rec_age_months" name="age_months">
                        <input type="hidden" id="rec_weight" name="weight">
                        <input type="hidden" id="rec_height" name="height">
                        <input type="hidden" id="rec_sex" name="sex">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Medical History</label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="medical_history[]" value="tuberculosis" class="mr-2">
                                        <span class="text-sm">Tuberculosis</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="medical_history[]" value="malaria" class="mr-2">
                                        <span class="text-sm">Malaria</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="medical_history[]" value="congenital_anomalies" class="mr-2">
                                        <span class="text-sm">Congenital Anomalies</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="medical_history[]" value="other_medical_problems" class="mr-2">
                                        <span class="text-sm">Other Medical Problems</span>
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Preferences</label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="preferences[]" value="breastfeeding" class="mr-2">
                                        <span class="text-sm">Currently Breastfeeding</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="preferences[]" value="fourps_beneficiary" class="mr-2">
                                        <span class="text-sm">4Ps Beneficiary</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="preferences[]" value="is_twin" class="mr-2">
                                        <span class="text-sm">Twin Birth</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Generate Personalized Recommendations
                        </button>
                    </form>

                    <div id="recommendationsContainer" class="hidden mt-6">
                        <h4 class="font-semibold text-md mb-3 text-gray-800">Recommendations</h4>
                        <div id="recommendationsContent" class="p-4 bg-gray-50 rounded-lg">
                            <!-- Recommendations will be displayed here -->
                        </div>
                    </div>
                </div>
            </div>

            @if(auth()->user()->isAdmin())
            <!-- Admin Controls -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold text-lg text-red-600 mb-4">Admin Controls</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <button id="modelInfoBtn" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Model Information
                        </button>
                        <button id="analyticsBtn" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            Analytics Summary
                        </button>
                        <button id="retrainModelBtn" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" 
                                onclick="return confirm('Are you sure you want to retrain the model? This is a critical operation.')">
                            Retrain Model
                        </button>
                    </div>

                    <div id="adminResultsContainer" class="hidden mt-6">
                        <h4 class="font-semibold text-md mb-3 text-gray-800">Admin Results</h4>
                        <div id="adminResultContent" class="p-4 bg-gray-50 rounded-lg">
                            <!-- Admin results will be displayed here -->
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Data Template -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-lg text-green-600">Data Template & Guidelines</h3>
                        <button id="getTemplateBtn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Get Template
                        </button>
                    </div>
                    <div id="templateContainer" class="hidden">
                        <div id="templateContent">
                            <!-- Template will be displayed here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                    <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Processing...</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">Please wait while we process your request.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/treatment-model.js') }}"></script>
</x-app-layout>
