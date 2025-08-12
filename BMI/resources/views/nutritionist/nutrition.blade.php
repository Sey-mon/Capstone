<x-app-layout>
    <x-slot name="header">
        Nutrition Assessment
    </x-slot>

    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl">
                    <div class="flex items-center">
                        <div class="w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                        <span class="text-emerald-800 font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif
            
            <!-- Create New Assessment Card -->
            <div class="modern-card-lg mb-8">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Create New Assessment</h2>
                            <p class="text-gray-600 mt-1">Record nutrition data for patient evaluation</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-apple-alt text-green-600 text-lg"></i>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('nutritionist.nutrition.store') }}" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Patient Selection -->
                            <div>
                                <label for="patient_id" class="form-label">Patient</label>
                                <select name="patient_id" id="patient_id" class="form-input" required>
                                    <option value="">Select Patient</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}">{{ $patient->name }} ({{ $patient->age }} years)</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Assessment Date -->
                            <div>
                                <label for="assessment_date" class="form-label">Assessment Date</label>
                                <input type="date" name="assessment_date" id="assessment_date" class="form-input" value="{{ date('Y-m-d') }}" required>
                            </div>
                            
                            <!-- Weight -->
                            <div>
                                <label for="weight" class="form-label">Weight (kg)</label>
                                <div class="relative">
                                    <input type="number" step="0.1" name="weight" id="weight" class="form-input pl-10" placeholder="0.0" required>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-weight text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Height -->
                            <div>
                                <label for="height" class="form-label">Height (cm)</label>
                                <div class="relative">
                                    <input type="number" step="0.1" name="height" id="height" class="form-input pl-10" placeholder="0.0" required>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-ruler-vertical text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Clinical Signs -->
                        <div>
                            <label class="form-label">Clinical Signs</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-3">
                                <label class="flex items-center p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200 cursor-pointer">
                                    <input type="checkbox" name="edema" value="1" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                    <span class="ml-3 text-sm font-medium text-gray-700">Edema</span>
                                </label>
                                <label class="flex items-center p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200 cursor-pointer">
                                    <input type="checkbox" name="wasting" value="1" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                    <span class="ml-3 text-sm font-medium text-gray-700">Wasting</span>
                                </label>
                                <label class="flex items-center p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200 cursor-pointer">
                                    <input type="checkbox" name="stunting" value="1" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                    <span class="ml-3 text-sm font-medium text-gray-700">Stunting</span>
                                </label>
                                <label class="flex items-center p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200 cursor-pointer">
                                    <input type="checkbox" name="underweight" value="1" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                    <span class="ml-3 text-sm font-medium text-gray-700">Underweight</span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save text-sm"></i>
                                Create Assessment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
                                    </div>
                                    <!-- Recent Assessments -->
            <div class="modern-card-lg">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Recent Assessments</h2>
                            <p class="text-gray-600 mt-1">Latest nutrition assessments and evaluations</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-chart-line text-blue-600 text-lg"></i>
                        </div>
                    </div>
                    
                    @if($assessments->count() > 0)
                        <div class="overflow-hidden rounded-2xl border border-gray-200">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>Date</th>
                                        <th>BMI</th>
                                        <th>Status</th>
                                        <th>Method</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assessments as $assessment)
                                        <tr>
                                            <td>
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mr-3">
                                                        <i class="fas fa-user text-white text-sm"></i>
                                                    </div>
                                                    <div>
                                                        <div class="font-medium text-gray-900">{{ $assessment->patient->name ?? 'Unknown Patient' }}</div>
                                                        <div class="text-sm text-gray-500">ID: {{ $assessment->patient->id ?? 'N/A' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-sm">
                                                    <div class="font-medium text-gray-900">{{ date('M d, Y', strtotime($assessment->assessment_date)) }}</div>
                                                    <div class="text-gray-500">{{ date('g:i A', strtotime($assessment->created_at)) }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="font-medium text-gray-900">{{ number_format($assessment->bmi, 1) }}</div>
                                                @if($assessment->whz_score || $assessment->waz_score || $assessment->haz_score)
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        @if($assessment->whz_score)
                                                            WHZ: {{ number_format($assessment->whz_score, 1) }}
                                                        @endif
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $statusClass = 'status-normal';
                                                    $statusText = 'Normal';
                                                    if ($assessment->nutrition_status == 'severe_malnutrition') {
                                                        $statusClass = 'status-severe';
                                                        $statusText = 'Severe';
                                                    } elseif ($assessment->nutrition_status == 'moderate_malnutrition') {
                                                        $statusClass = 'status-moderate';
                                                        $statusText = 'Moderate';
                                                    } elseif ($assessment->nutrition_status == 'mild_malnutrition') {
                                                        $statusClass = 'status-mild';
                                                        $statusText = 'Mild';
                                                    }
                                                @endphp
                                                <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                                                @if($assessment->follow_up_required)
                                                    <div class="mt-2">
                                                        <span class="status-badge" style="background: #fef3c7; color: #92400e;">Follow-up</span>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                @if($assessment->assessment_method)
                                                    <div class="flex items-center">
                                                        @if($assessment->assessment_method == 'api')
                                                            <div class="w-2 h-2 bg-teal-500 rounded-full mr-2"></div>
                                                            <span class="text-sm font-medium text-gray-700">AI Model</span>
                                                        @else
                                                            <div class="w-2 h-2 bg-gray-400 rounded-full mr-2"></div>
                                                            <span class="text-sm font-medium text-gray-700">Manual</span>
                                                        @endif
                                                    </div>
                                                    @if($assessment->confidence_score)
                                                        <div class="text-xs text-gray-500 mt-1">
                                                            {{ number_format($assessment->confidence_score * 100, 0) }}% confidence
                                                        </div>
                                                    @endif
                                                @else
                                                    <span class="text-gray-400 text-sm">Legacy</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="flex items-center space-x-2">
                                                    <a href="{{ route('nutritionist.nutrition.show', $assessment->id) }}" class="inline-flex items-center px-3 py-1.5 bg-teal-100 text-teal-700 text-xs font-medium rounded-lg hover:bg-teal-200 transition-colors duration-200">
                                                        <i class="fas fa-eye mr-1"></i>
                                                        View
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-clipboard-list text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No assessments found</h3>
                            <p class="text-gray-600">Start by creating your first nutrition assessment above.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="clinical_signs" class="block text-sm font-medium text-gray-700">Additional Clinical Signs</label>
                                <textarea name="clinical_signs" id="clinical_signs" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50" placeholder="Describe any additional clinical observations..."></textarea>
                            </div>
                            <div>
                                <label for="dietary_intake" class="block text-sm font-medium text-gray-700">Dietary Intake</label>
                                <textarea name="dietary_intake" id="dietary_intake" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50" placeholder="Describe dietary patterns and intake..."></textarea>
                            </div>
                            <div>
                                <label for="feeding_practices" class="block text-sm font-medium text-gray-700">Feeding Practices</label>
                                <textarea name="feeding_practices" id="feeding_practices" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50" placeholder="Describe feeding practices and behaviors..."></textarea>
                            </div>
                            <div>
                                <label for="recommendations" class="block text-sm font-medium text-gray-700">Recommendations</label>
                                <textarea name="recommendations" id="recommendations" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50" placeholder="Treatment and dietary recommendations..."></textarea>
                            </div>
                        </div>
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Create Assessment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Assessment List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold text-lg mb-4 text-green-600">Recent Assessments</h3>
                    
                    @if($assessments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Patient</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">BMI</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Z-Scores</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Method</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assessments as $assessment)
                                        <tr>
                                            <td class="py-2 px-4 border-b border-gray-200">{{ $assessment->patient->name ?? 'Unknown Patient' }}</td>
                                            <td class="py-2 px-4 border-b border-gray-200">{{ date('M d, Y', strtotime($assessment->assessment_date)) }}</td>
                                            <td class="py-2 px-4 border-b border-gray-200">{{ $assessment->bmi }}</td>
                                            <td class="py-2 px-4 border-b border-gray-200">
                                                @if($assessment->whz_score || $assessment->waz_score || $assessment->haz_score)
                                                    <div class="text-xs space-y-1">
                                                        @if($assessment->whz_score)
                                                            <div class="flex justify-between">
                                                                <span class="text-gray-600">WHZ:</span>
                                                                <span class="{{ $assessment->whz_score < -2 ? 'text-red-600 font-semibold' : ($assessment->whz_score < -1 ? 'text-orange-600' : 'text-green-600') }}">
                                                                    {{ number_format($assessment->whz_score, 1) }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                        @if($assessment->waz_score)
                                                            <div class="flex justify-between">
                                                                <span class="text-gray-600">WAZ:</span>
                                                                <span class="{{ $assessment->waz_score < -2 ? 'text-red-600 font-semibold' : ($assessment->waz_score < -1 ? 'text-orange-600' : 'text-green-600') }}">
                                                                    {{ number_format($assessment->waz_score, 1) }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                        @if($assessment->haz_score)
                                                            <div class="flex justify-between">
                                                                <span class="text-gray-600">HAZ:</span>
                                                                <span class="{{ $assessment->haz_score < -2 ? 'text-red-600 font-semibold' : ($assessment->haz_score < -1 ? 'text-orange-600' : 'text-green-600') }}">
                                                                    {{ number_format($assessment->haz_score, 1) }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-gray-400 text-xs">Not available</span>
                                                @endif
                                            </td>
                                            <td class="py-2 px-4 border-b border-gray-200">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($assessment->nutrition_status == 'severe_malnutrition') bg-red-100 text-red-800
                                                    @elseif($assessment->nutrition_status == 'moderate_malnutrition') bg-orange-100 text-orange-800
                                                    @elseif($assessment->nutrition_status == 'mild_malnutrition') bg-yellow-100 text-yellow-800
                                                    @else bg-green-100 text-green-800 @endif">
                                                    {{ ucwords(str_replace('_', ' ', $assessment->nutrition_status)) }}
                                                </span>
                                                @if($assessment->follow_up_required)
                                                    <div class="mt-1">
                                                        <span class="px-1 inline-flex text-xs leading-4 font-medium rounded bg-yellow-100 text-yellow-800">
                                                            Follow-up
                                                        </span>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="py-2 px-4 border-b border-gray-200">
                                                @if($assessment->assessment_method)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-medium rounded-full 
                                                        @if($assessment->assessment_method == 'api') bg-blue-100 text-blue-800
                                                        @else bg-gray-100 text-gray-800 @endif">
                                                        {{ ucfirst($assessment->assessment_method) }}
                                                    </span>
                                                    @if($assessment->confidence_score)
                                                        <div class="text-xs text-gray-500 mt-1">
                                                            {{ number_format($assessment->confidence_score * 100, 0) }}% conf.
                                                        </div>
                                                    @endif
                                                @else
                                                    <span class="text-gray-400 text-xs">Legacy</span>
                                                @endif
                                            </td>
                                            <td class="py-2 px-4 border-b border-gray-200">
                                                <a href="{{ route('nutritionist.nutrition.show', $assessment->id) }}" class="text-green-600 hover:text-green-800 text-sm">View Details</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-600">No assessments found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
