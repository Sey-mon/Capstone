<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Patient Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Patient Info Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-lg text-green-600">Patient Information</h3>
                        <a href="{{ route('nutritionist.patients') }}" class="text-green-600 hover:text-green-800">Back to Patients</a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Name</p>
                            <p class="mt-1">{{ $patient->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Date of Birth</p>
                            <p class="mt-1">{{ date('M d, Y', strtotime($patient->date_of_birth)) }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Age</p>
                            <p class="mt-1">{{ $patient->age }} years</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Gender</p>
                            <p class="mt-1">{{ ucfirst($patient->gender) }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Address</p>
                            <p class="mt-1">{{ $patient->address ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Guardian</p>
                            <p class="mt-1">{{ $patient->guardian_name ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Guardian Contact</p>
                            <p class="mt-1">{{ $patient->guardian_contact ?? 'Not provided' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm font-medium text-gray-500">Medical History</p>
                            <p class="mt-1">{{ $patient->medical_history ?? 'None' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Nutrition Assessments List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold text-lg mb-4 text-green-600">Nutrition Assessment History</h3>
                    
                    @if($assessments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Weight (kg)</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Height (cm)</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">BMI</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">MUAC</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Follow-up</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assessments as $assessment)
                                        <tr>
                                            <td class="py-2 px-4 border-b border-gray-200">{{ date('M d, Y', strtotime($assessment->assessment_date)) }}</td>
                                            <td class="py-2 px-4 border-b border-gray-200">{{ $assessment->weight }}</td>
                                            <td class="py-2 px-4 border-b border-gray-200">{{ $assessment->height }}</td>
                                            <td class="py-2 px-4 border-b border-gray-200">{{ $assessment->bmi }}</td>
                                            <td class="py-2 px-4 border-b border-gray-200">{{ $assessment->muac ?? 'N/A' }}</td>
                                            <td class="py-2 px-4 border-b border-gray-200">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($assessment->nutrition_status == 'severe_malnutrition') bg-red-100 text-red-800
                                                    @elseif($assessment->nutrition_status == 'moderate_malnutrition') bg-orange-100 text-orange-800
                                                    @elseif($assessment->nutrition_status == 'mild_malnutrition') bg-yellow-100 text-yellow-800
                                                    @else bg-green-100 text-green-800 @endif">
                                                    {{ ucwords(str_replace('_', ' ', $assessment->nutrition_status)) }}
                                                </span>
                                            </td>
                                            <td class="py-2 px-4 border-b border-gray-200">
                                                {{ $assessment->next_assessment_date ? date('M d, Y', strtotime($assessment->next_assessment_date)) : 'Not scheduled' }}
                                            </td>
                                            <td class="py-2 px-4 border-b border-gray-200">
                                                <a href="{{ route('nutritionist.nutrition.show', $assessment->id) }}" class="text-green-600 hover:text-green-800">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-600">No assessments found for this patient.</p>
                    @endif
                    
                    <div class="mt-6">
                        <a href="{{ route('nutritionist.nutrition') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Add New Assessment
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
