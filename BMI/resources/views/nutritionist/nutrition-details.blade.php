<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assessment Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Assessment Details Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-lg text-green-600">Nutrition Assessment Details</h3>
                        <div>
                            <a href="{{ route('nutritionist.nutrition') }}" class="text-green-600 hover:text-green-800 mr-4">Back to Assessments</a>
                            @if($assessment->patient)
                                <a href="{{ route('nutritionist.patients.show', $assessment->patient->id) }}" class="text-green-600 hover:text-green-800">View Patient</a>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Patient Information -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-semibold text-md mb-2">Patient Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Name</p>
                                <p class="mt-1">{{ $assessment->patient->name ?? 'Unknown Patient' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Age</p>
                                <p class="mt-1">{{ $assessment->patient->age ?? 'Unknown' }} years</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Gender</p>
                                <p class="mt-1">{{ $assessment->patient ? ucfirst($assessment->patient->gender) : 'Unknown' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Assessment Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Assessment Date</p>
                            <p class="mt-1">{{ date('M d, Y', strtotime($assessment->assessment_date)) }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Weight</p>
                            <p class="mt-1">{{ $assessment->weight }} kg</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Height</p>
                            <p class="mt-1">{{ $assessment->height }} cm</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">BMI</p>
                            <p class="mt-1 font-semibold">{{ $assessment->bmi }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Nutritional Status</p>
                            <p class="mt-1">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($assessment->nutrition_status == 'severe_malnutrition') bg-red-100 text-red-800
                                    @elseif($assessment->nutrition_status == 'moderate_malnutrition') bg-orange-100 text-orange-800
                                    @elseif($assessment->nutrition_status == 'mild_malnutrition') bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ ucwords(str_replace('_', ' ', $assessment->nutrition_status)) }}
                                </span>
                            </p>
                        </div>
                        @if($assessment->assessment_method)
                        <div>
                            <p class="text-sm font-medium text-gray-500">Assessment Method</p>
                            <p class="mt-1">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($assessment->assessment_method == 'api') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($assessment->assessment_method) }}
                                    @if($assessment->model_version) ({{ $assessment->model_version }}) @endif
                                </span>
                            </p>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Z-Score Analysis (if available) -->
                    @if($assessment->whz_score || $assessment->waz_score || $assessment->haz_score)
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <h4 class="font-semibold text-md mb-3 text-blue-800">Z-Score Analysis</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @if($assessment->whz_score)
                            <div class="text-center p-3 bg-white rounded border">
                                <p class="text-xs font-medium text-gray-500 uppercase">Weight-for-Height</p>
                                <p class="mt-1 text-lg font-bold {{ $assessment->whz_score < -2 ? 'text-red-600' : ($assessment->whz_score < -1 ? 'text-orange-600' : 'text-green-600') }}">
                                    {{ number_format($assessment->whz_score, 2) }}
                                </p>
                                <p class="text-xs text-gray-600">
                                    @if($assessment->whz_score >= -1) Normal
                                    @elseif($assessment->whz_score >= -2) Mild Wasting
                                    @elseif($assessment->whz_score >= -3) Moderate Wasting
                                    @else Severe Wasting
                                    @endif
                                </p>
                            </div>
                            @endif
                            
                            @if($assessment->waz_score)
                            <div class="text-center p-3 bg-white rounded border">
                                <p class="text-xs font-medium text-gray-500 uppercase">Weight-for-Age</p>
                                <p class="mt-1 text-lg font-bold {{ $assessment->waz_score < -2 ? 'text-red-600' : ($assessment->waz_score < -1 ? 'text-orange-600' : 'text-green-600') }}">
                                    {{ number_format($assessment->waz_score, 2) }}
                                </p>
                                <p class="text-xs text-gray-600">
                                    @if($assessment->waz_score >= -1) Normal
                                    @elseif($assessment->waz_score >= -2) Mild Underweight
                                    @elseif($assessment->waz_score >= -3) Moderate Underweight
                                    @else Severe Underweight
                                    @endif
                                </p>
                            </div>
                            @endif
                            
                            @if($assessment->haz_score)
                            <div class="text-center p-3 bg-white rounded border">
                                <p class="text-xs font-medium text-gray-500 uppercase">Height-for-Age</p>
                                <p class="mt-1 text-lg font-bold {{ $assessment->haz_score < -2 ? 'text-red-600' : ($assessment->haz_score < -1 ? 'text-orange-600' : 'text-green-600') }}">
                                    {{ number_format($assessment->haz_score, 2) }}
                                </p>
                                <p class="text-xs text-gray-600">
                                    @if($assessment->haz_score >= -1) Normal
                                    @elseif($assessment->haz_score >= -2) Mild Stunting
                                    @elseif($assessment->haz_score >= -3) Moderate Stunting
                                    @else Severe Stunting
                                    @endif
                                </p>
                            </div>
                            @endif
                        </div>
                        
                        @if($assessment->confidence_score)
                        <div class="mt-3 text-center">
                            <p class="text-sm text-blue-700">
                                <strong>Confidence Score:</strong> {{ number_format($assessment->confidence_score * 100, 1) }}%
                            </p>
                        </div>
                        @endif
                    </div>
                    @endif
                    
                    <!-- Additional Clinical Information -->
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($assessment->edema !== null || $assessment->vomiting !== null || $assessment->diarrhea !== null)
                        <div>
                            <h4 class="font-semibold text-md mb-2">Clinical Signs</h4>
                            <div class="space-y-1">
                                @if($assessment->edema !== null)
                                <p class="text-sm">
                                    <span class="font-medium">Edema:</span> 
                                    <span class="{{ $assessment->edema ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $assessment->edema ? 'Present' : 'Absent' }}
                                    </span>
                                </p>
                                @endif
                                @if($assessment->vomiting !== null)
                                <p class="text-sm">
                                    <span class="font-medium">Vomiting:</span> 
                                    <span class="{{ $assessment->vomiting ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $assessment->vomiting ? 'Present' : 'Absent' }}
                                    </span>
                                </p>
                                @endif
                                @if($assessment->diarrhea !== null)
                                <p class="text-sm">
                                    <span class="font-medium">Diarrhea:</span> 
                                    <span class="{{ $assessment->diarrhea ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $assessment->diarrhea ? 'Present' : 'Absent' }}
                                    </span>
                                </p>
                                @endif
                                @if($assessment->appetite)
                                <p class="text-sm">
                                    <span class="font-medium">Appetite:</span> 
                                    <span class="{{ $assessment->appetite == 'poor' ? 'text-red-600' : ($assessment->appetite == 'fair' ? 'text-yellow-600' : 'text-green-600') }}">
                                        {{ ucfirst($assessment->appetite) }}
                                    </span>
                                </p>
                                @endif
                            </div>
                        </div>
                        @endif
                        
                        <div>
                            <h4 class="font-semibold text-md mb-2">Assessment Details</h4>
                            <div class="space-y-1">
                                <p class="text-sm">
                                    <span class="font-medium">Clinical Signs:</span> {{ $assessment->clinical_signs ?? 'None reported' }}
                                </p>
                                @if($assessment->dietary_intake)
                                <p class="text-sm">
                                    <span class="font-medium">Dietary Intake:</span> {{ $assessment->dietary_intake }}
                                </p>
                                @endif
                                @if($assessment->feeding_practices)
                                <p class="text-sm">
                                    <span class="font-medium">Feeding Practices:</span> {{ $assessment->feeding_practices }}
                                </p>
                                @endif
                                <p class="text-sm">
                                    <span class="font-medium">Next Assessment:</span> {{ $assessment->next_assessment_date ? date('M d, Y', strtotime($assessment->next_assessment_date)) : 'Not scheduled' }}
                                </p>
                                @if($assessment->follow_up_required !== null)
                                <p class="text-sm">
                                    <span class="font-medium">Follow-up Required:</span> 
                                    <span class="{{ $assessment->follow_up_required ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $assessment->follow_up_required ? 'Yes' : 'No' }}
                                    </span>
                                </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recommendations -->
                    @if($assessment->recommendations)
                    <div class="mt-6">
                        <h4 class="font-semibold text-md mb-2">Recommendations</h4>
                        <div class="p-4 bg-green-50 rounded-lg">
                            <p class="text-sm">{{ $assessment->recommendations }}</p>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Notes -->
                    <div class="mt-6">
                        <p class="text-sm font-medium text-gray-500">Notes</p>
                        <div class="mt-1 p-4 bg-gray-50 rounded-lg">
                            <p>{{ $assessment->notes ?? 'No notes provided' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- BMI Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold text-lg mb-4 text-green-600">BMI Classification</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Classification</th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">BMI Range (kg/m²)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="{{ $assessment->bmi < 16 ? 'bg-red-50' : '' }}">
                                    <td class="py-2 px-4 border-b border-gray-200">Severe Thinness</td>
                                    <td class="py-2 px-4 border-b border-gray-200">&lt; 16.0</td>
                                </tr>
                                <tr class="{{ $assessment->bmi >= 16 && $assessment->bmi < 17 ? 'bg-red-50' : '' }}">
                                    <td class="py-2 px-4 border-b border-gray-200">Moderate Thinness</td>
                                    <td class="py-2 px-4 border-b border-gray-200">16.0 - 16.9</td>
                                </tr>
                                <tr class="{{ $assessment->bmi >= 17 && $assessment->bmi < 18.5 ? 'bg-yellow-50' : '' }}">
                                    <td class="py-2 px-4 border-b border-gray-200">Mild Thinness</td>
                                    <td class="py-2 px-4 border-b border-gray-200">17.0 - 18.4</td>
                                </tr>
                                <tr class="{{ $assessment->bmi >= 18.5 && $assessment->bmi < 25 ? 'bg-green-50' : '' }}">
                                    <td class="py-2 px-4 border-b border-gray-200">Normal</td>
                                    <td class="py-2 px-4 border-b border-gray-200">18.5 - 24.9</td>
                                </tr>
                                <tr class="{{ $assessment->bmi >= 25 && $assessment->bmi < 30 ? 'bg-yellow-50' : '' }}">
                                    <td class="py-2 px-4 border-b border-gray-200">Overweight</td>
                                    <td class="py-2 px-4 border-b border-gray-200">25.0 - 29.9</td>
                                </tr>
                                <tr class="{{ $assessment->bmi >= 30 ? 'bg-red-50' : '' }}">
                                    <td class="py-2 px-4 border-b border-gray-200">Obese</td>
                                    <td class="py-2 px-4 border-b border-gray-200">&gt;= 30.0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-blue-800">
                            <strong>Note:</strong> BMI classifications may vary for children and adolescents. Additional assessments like growth charts and MUAC measurements should be considered for a comprehensive nutritional evaluation.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
