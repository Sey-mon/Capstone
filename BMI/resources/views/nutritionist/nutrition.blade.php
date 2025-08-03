<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nutrition Assessment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            
            <!-- Add Assessment Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold text-lg mb-4 text-green-600">Create New Assessment</h3>
                    <form method="POST" action="{{ route('nutritionist.nutrition.store') }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="patient_id" class="block text-sm font-medium text-gray-700">Patient</label>
                                <select name="patient_id" id="patient_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50" required>
                                    <option value="">Select Patient</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}">{{ $patient->name }} ({{ $patient->age }} years)</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="assessment_date" class="block text-sm font-medium text-gray-700">Assessment Date</label>
                                <input type="date" name="assessment_date" id="assessment_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div>
                                <label for="weight" class="block text-sm font-medium text-gray-700">Weight (kg)</label>
                                <input type="number" step="0.1" name="weight" id="weight" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50" required>
                            </div>
                            <div>
                                <label for="height" class="block text-sm font-medium text-gray-700">Height (cm)</label>
                                <input type="number" step="0.1" name="height" id="height" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Clinical Signs</label>
                                <div class="mt-2 space-y-2">
                                    <div class="flex items-center">
                                        <input id="edema" name="edema" type="checkbox" value="1" class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                        <label for="edema" class="ml-2 block text-sm text-gray-900">Edema</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="vomiting" name="vomiting" type="checkbox" value="1" class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                        <label for="vomiting" class="ml-2 block text-sm text-gray-900">Vomiting</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="diarrhea" name="diarrhea" type="checkbox" value="1" class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300 rounded">
                                        <label for="diarrhea" class="ml-2 block text-sm text-gray-900">Diarrhea</label>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label for="appetite" class="block text-sm font-medium text-gray-700">Appetite</label>
                                <select name="appetite" id="appetite" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                    <option value="">Select Appetite</option>
                                    <option value="poor">Poor</option>
                                    <option value="fair">Fair</option>
                                    <option value="good">Good</option>
                                </select>
                            </div>
                            <div>
                                <label for="next_assessment_date" class="block text-sm font-medium text-gray-700">Next Assessment Date</label>
                                <input type="date" name="next_assessment_date" id="next_assessment_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                            </div>
                        </div>
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
