<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nutrition Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Nutrition Status Overview -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold text-lg mb-4 text-green-600">Nutrition Status Distribution</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                        <div class="p-4 border rounded-md bg-green-100">
                            <h4 class="font-semibold">Normal</h4>
                            <p class="text-2xl font-bold">{{ $nutritionStats['normal'] ?? 0 }}</p>
                            <p class="text-sm text-gray-600">{{ $totalAssessments > 0 ? round(($nutritionStats['normal'] ?? 0) / $totalAssessments * 100) : 0 }}% of assessments</p>
                        </div>
                        <div class="p-4 border rounded-md bg-yellow-100">
                            <h4 class="font-semibold">Mild Malnutrition</h4>
                            <p class="text-2xl font-bold">{{ $nutritionStats['mild_malnutrition'] ?? 0 }}</p>
                            <p class="text-sm text-gray-600">{{ $totalAssessments > 0 ? round(($nutritionStats['mild_malnutrition'] ?? 0) / $totalAssessments * 100) : 0 }}% of assessments</p>
                        </div>
                        <div class="p-4 border rounded-md bg-orange-100">
                            <h4 class="font-semibold">Moderate Malnutrition</h4>
                            <p class="text-2xl font-bold">{{ $nutritionStats['moderate_malnutrition'] ?? 0 }}</p>
                            <p class="text-sm text-gray-600">{{ $totalAssessments > 0 ? round(($nutritionStats['moderate_malnutrition'] ?? 0) / $totalAssessments * 100) : 0 }}% of assessments</p>
                        </div>
                        <div class="p-4 border rounded-md bg-red-100">
                            <h4 class="font-semibold">Severe Malnutrition</h4>
                            <p class="text-2xl font-bold">{{ $nutritionStats['severe_malnutrition'] ?? 0 }}</p>
                            <p class="text-sm text-gray-600">{{ $totalAssessments > 0 ? round(($nutritionStats['severe_malnutrition'] ?? 0) / $totalAssessments * 100) : 0 }}% of assessments</p>
                        </div>
                    </div>
                    
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-blue-800">
                            <strong>Total Assessments:</strong> {{ $totalAssessments }}
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Critical Cases List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold text-lg mb-4 text-green-600">Critical Cases Requiring Immediate Attention</h3>
                    
                    @if($criticalCases->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Patient</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Age</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Assessment Date</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">BMI</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($criticalCases as $case)
                                        <tr>
                                            <td class="py-2 px-4 border-b border-gray-200">{{ $case->patient->name ?? 'Unknown Patient' }}</td>
                                            <td class="py-2 px-4 border-b border-gray-200">{{ $case->patient->age ?? 'Unknown' }} years</td>
                                            <td class="py-2 px-4 border-b border-gray-200">{{ $case->assessment_date ? date('M d, Y', strtotime($case->assessment_date)) : 'Not set' }}</td>
                                            <td class="py-2 px-4 border-b border-gray-200">{{ $case->bmi }}</td>
                                            <td class="py-2 px-4 border-b border-gray-200">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Severe Malnutrition
                                                </span>
                                            </td>
                                            <td class="py-2 px-4 border-b border-gray-200">
                                                <div class="flex space-x-2">
                                                    @if($case->patient)
                                                        <a href="{{ route('nutritionist.patients.show', $case->patient->id) }}" class="text-green-600 hover:text-green-800">View Patient</a>
                                                    @endif
                                                    <a href="{{ route('nutritionist.nutrition.show', $case->id) }}" class="text-blue-600 hover:text-blue-800">View Assessment</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-600">No critical cases found.</p>
                    @endif
                </div>
            </div>
            
            <!-- Report Notes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold text-lg mb-4 text-green-600">Assessment Guidelines</h3>
                    
                    <div class="space-y-4">
                        <div class="p-4 bg-yellow-50 rounded-lg">
                            <h4 class="font-semibold mb-2">Standard BMI Classification</h4>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Severe Thinness: BMI &lt; 16.0</li>
                                <li>Moderate Thinness: BMI 16.0 - 16.9</li>
                                <li>Mild Thinness: BMI 17.0 - 18.4</li>
                                <li>Normal: BMI 18.5 - 24.9</li>
                                <li>Overweight: BMI 25.0 - 29.9</li>
                                <li>Obese: BMI ≥ 30.0</li>
                            </ul>
                        </div>
                        
                        <div class="p-4 bg-green-50 rounded-lg">
                            <h4 class="font-semibold mb-2">MUAC Guidelines</h4>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Severe Acute Malnutrition (SAM): MUAC &lt; 11.5 cm</li>
                                <li>Moderate Acute Malnutrition (MAM): MUAC 11.5 - 12.5 cm</li>
                                <li>At Risk: MUAC 12.6 - 13.5 cm</li>
                                <li>Normal: MUAC &gt; 13.5 cm</li>
                            </ul>
                            <p class="mt-2 text-sm italic">Note: MUAC cutoffs vary by age group. These values are for children 6-59 months.</p>
                        </div>
                        
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <h4 class="font-semibold mb-2">Recommended Follow-up Schedule</h4>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Severe Malnutrition: Weekly follow-up</li>
                                <li>Moderate Malnutrition: Bi-weekly follow-up</li>
                                <li>Mild Malnutrition: Monthly follow-up</li>
                                <li>Normal: Quarterly follow-up</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
