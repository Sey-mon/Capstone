@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center mb-6">
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-800 mb-4 lg:mb-0">Patient Management</h1>
        <button onclick="openModal('addPatientModal')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 w-full lg:w-auto">
            Add New Patient
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-lg p-4">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-semibold text-gray-700">Total Patients</h3>
                    <p class="text-xl font-bold text-blue-600">{{ $totalPatients }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-4">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-red-100 text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-semibold text-gray-700">Malnourished</h3>
                    <p class="text-xl font-bold text-red-600">{{ $malnourishedPatients }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-4">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-semibold text-gray-700">At Risk</h3>
                    <p class="text-xl font-bold text-yellow-600">{{ $atRiskPatients }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-4">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-semibold text-gray-700">Normal</h3>
                    <p class="text-xl font-bold text-green-600">{{ $normalPatients }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow-lg p-4 mb-6">
        <div class="flex items-center mb-4">
            <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
            <h3 class="text-sm font-semibold text-gray-900">Filter Patients</h3>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-7 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Search</label>
                <input type="text" id="searchPatients" placeholder="Search..." 
                       class="w-full px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Barangay</label>
                <select id="barangayFilter" class="w-full px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">All</option>
                    @foreach($barangays ?? [] as $barangay)
                        <option value="{{ $barangay }}">{{ $barangay }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                <select id="nutritionFilter" class="w-full px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">All</option>
                    <option value="normal">Normal</option>
                    <option value="mild_malnutrition">Mild</option>
                    <option value="moderate_malnutrition">Moderate</option>
                    <option value="severe_malnutrition">Severe</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Age</label>
                <select id="ageGroupFilter" class="w-full px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">All</option>
                    <option value="0-6">0-6m</option>
                    <option value="6-12">6-12m</option>
                    <option value="12-24">12-24m</option>
                    <option value="24-60">24-60m</option>
                    <option value="60+">60+m</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Medical</label>
                <select id="medicalFilter" class="w-full px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">All</option>
                    <option value="tuberculosis">TB</option>
                    <option value="malaria">Malaria</option>
                    <option value="congenital">Congenital</option>
                    <option value="edema">Edema</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Gender</label>
                <select id="genderFilter" class="w-full px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">All</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Admission</label>
                <select id="statusFilter" class="w-full px-2 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">All</option>
                    <option value="admitted">Admitted</option>
                    <option value="discharged">Discharged</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
        </div>
        <div class="mt-4 flex justify-end">
            <button onclick="clearFilters()" class="px-3 py-1 text-xs font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500">
                Clear Filters
            </button>
        </div>
    </div>

    <!-- Patients Table -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Age</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admission</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Household</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="patientsTable">
                    @forelse($patients as $patient)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8">
                                    <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                        <span class="text-xs font-medium text-gray-700">{{ substr($patient->name ?? 'N/A', 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $patient->name ?? 'No Name' }}</div>
                                    <div class="text-xs text-gray-500">{{ ucfirst($patient->sex ?? 'Unknown') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                            @if($patient->municipality || $patient->barangay)
                                <div class="text-xs">{{ $patient->municipality ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $patient->barangay ?? ($patient->barangay->name ?? 'N/A') }}</div>
                            @else
                                <div class="text-xs text-gray-400">Location not set</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                            <div class="text-xs">{{ $patient->age_months }} months</div>
                            <div class="text-xs text-gray-500">({{ $patient->age_years }} years)</div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                            <div class="text-xs">{{ $patient->date_of_admission->format('M d, Y') }}</div>
                            <span class="px-1 inline-flex text-xs leading-4 font-semibold rounded-full 
                                {{ $patient->admission_status === 'admitted' ? 'bg-green-100 text-green-800' : 
                                   ($patient->admission_status === 'discharged' ? 'bg-gray-100 text-gray-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($patient->admission_status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                            <div class="text-xs">{{ $patient->total_household_members }} total</div>
                            <div class="text-xs text-gray-500">{{ $patient->household_adults }}A, {{ $patient->household_children }}C</div>
                            @if($patient->is_4ps_beneficiary)
                                <span class="text-xs bg-blue-100 text-blue-800 px-1 py-0.5 rounded">4P's</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if($patient->lastAssessment)
                                @php
                                    $status = $patient->lastAssessment->nutrition_status;
                                    $statusColors = [
                                        'normal' => 'bg-green-100 text-green-800',
                                        'mild_malnutrition' => 'bg-blue-100 text-blue-800',
                                        'moderate_malnutrition' => 'bg-yellow-100 text-yellow-800',
                                        'severe_malnutrition' => 'bg-red-100 text-red-800'
                                    ];
                                @endphp
                                <span class="px-1 inline-flex text-xs leading-4 font-semibold rounded-full {{ $statusColors[$status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </span>
                            @else
                                <span class="px-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Not assessed
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.patients.show', $patient) }}" class="text-green-600 hover:text-green-900 text-xs">View</a>
                                <button onclick="editPatient('{{ $patient->id }}')" class="text-blue-600 hover:text-blue-900 text-xs">Edit</button>
                                <button onclick="deletePatient('{{ $patient->id }}')" class="text-red-600 hover:text-red-900 text-xs">Delete</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-3 text-center text-gray-500 text-sm">No patients found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($patients->hasPages())
    <div class="mt-6 bg-white rounded-lg shadow-lg p-4">
        <div class="flex flex-col sm:flex-row justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="text-sm text-gray-700">
                    Showing {{ $patients->firstItem() ?? 0 }} to {{ $patients->lastItem() ?? 0 }} of {{ $patients->total() }} patients
                </div>
                <div class="flex items-center space-x-2">
                    <label class="text-sm text-gray-600">Per page:</label>
                    <select onchange="changePerPage(this.value)" class="text-sm border border-gray-300 rounded px-2 py-1">
                        <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                        <option value="25" {{ request('per_page', 15) == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page', 15) == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page', 15) == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                {{ $patients->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Add Patient Modal -->
<div id="addPatientModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Add New Patient</h3>
            </div>
            <form id="addPatientForm" action="{{ route('admin.patients.store') }}" method="POST">
                @csrf
                <div class="px-6 py-4 space-y-4">
                    <!-- Basic Information -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sex</label>
                            <select name="sex" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="">Select Sex</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Municipality</label>
                            <input type="text" name="municipality" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Barangay</label>
                            <input type="text" name="barangay" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Age (months)</label>
                            <input type="number" name="age_months" required min="0" max="1200" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date of Admission</label>
                            <input type="date" name="date_of_admission" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Admission Status</label>
                        <select name="admission_status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Select Status</option>
                            <option value="admitted">Admitted</option>
                            <option value="discharged">Discharged</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                    
                    <!-- Household Information -->
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Total Household</label>
                            <input type="number" name="total_household_members" required min="1" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Adults</label>
                            <input type="number" name="household_adults" required min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Children</label>
                            <input type="number" name="household_children" required min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                    </div>
                    
                    <div class="flex space-x-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_twin" value="1" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <span class="ml-2 text-sm text-gray-700">Twin</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_4ps_beneficiary" value="1" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <span class="ml-2 text-sm text-gray-700">4P's Beneficiary</span>
                        </label>
                    </div>
                    
                    <!-- Nutritional Measurements -->
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Weight (kg)</label>
                            <input type="number" name="weight" step="0.01" min="0" max="500" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Height (cm)</label>
                            <input type="number" name="height" step="0.01" min="0" max="300" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">WHZ Score</label>
                            <input type="number" name="whz_score" step="0.01" min="-5" max="5" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                    </div>
                    
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_breastfeeding" value="1" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <span class="ml-2 text-sm text-gray-700">Currently Breastfeeding</span>
                        </label>
                    </div>
                    
                    <!-- Medical Problems -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Medical Conditions</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="has_tuberculosis" value="1" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                <span class="ml-2 text-sm text-gray-700">Tuberculosis</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="has_malaria" value="1" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                <span class="ml-2 text-sm text-gray-700">Malaria</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="has_congenital_anomalies" value="1" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                <span class="ml-2 text-sm text-gray-700">Congenital Anomalies</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="has_edema" value="1" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                <span class="ml-2 text-sm text-gray-700">Edema</span>
                            </label>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Other Medical Problems</label>
                        <textarea name="other_medical_problems" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Describe any other medical problems..."></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Religion</label>
                            <input type="text" name="religion" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('addPatientModal')" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Add Patient</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function editPatient(patientId) {
    // Implement edit functionality
    console.log('Edit patient:', patientId);
    // You can redirect to edit page or open edit modal
    // window.location.href = `/admin/patients/${patientId}/edit`;
}

function deletePatient(patientId) {
    if (confirm('Are you sure you want to delete this patient?')) {
        // Implement delete functionality
        console.log('Delete patient:', patientId);
        // You can make an AJAX call to delete the patient
        // fetch(`/admin/patients/${patientId}`, { method: 'DELETE' })
        //     .then(() => location.reload());
    }
}

// Search and filter functionality
document.getElementById('searchPatients').addEventListener('input', filterPatients);
document.getElementById('barangayFilter').addEventListener('change', filterPatients);
document.getElementById('nutritionFilter').addEventListener('change', filterPatients);
document.getElementById('ageGroupFilter').addEventListener('change', filterPatients);
document.getElementById('medicalFilter').addEventListener('change', filterPatients);
document.getElementById('genderFilter').addEventListener('change', filterPatients);
document.getElementById('statusFilter').addEventListener('change', filterPatients);

function filterPatients() {
    const search = document.getElementById('searchPatients').value;
    const barangay = document.getElementById('barangayFilter').value;
    const nutrition = document.getElementById('nutritionFilter').value;
    const ageGroup = document.getElementById('ageGroupFilter').value;
    const medical = document.getElementById('medicalFilter').value;
    const gender = document.getElementById('genderFilter').value;
    const status = document.getElementById('statusFilter').value;
    
    // Build query parameters
    const params = new URLSearchParams();
    
    if (search) params.append('search', search);
    if (barangay) params.append('barangay', barangay);
    if (nutrition) params.append('nutrition', nutrition);
    if (ageGroup) params.append('age_group', ageGroup);
    if (medical) params.append('medical', medical);
    if (gender) params.append('gender', gender);
    if (status) params.append('status', status);
    
    // Redirect to filtered results
    const currentUrl = window.location.pathname;
    const queryString = params.toString();
    const redirectUrl = queryString ? `${currentUrl}?${queryString}` : currentUrl;
    
    window.location.href = redirectUrl;
}

function clearFilters() {
    const search = document.getElementById('searchPatients').value;
    const barangay = document.getElementById('barangayFilter').value;
    const nutrition = document.getElementById('nutritionFilter').value;
    const ageGroup = document.getElementById('ageGroupFilter').value;
    const medical = document.getElementById('medicalFilter').value;
    const gender = document.getElementById('genderFilter').value;
    const status = document.getElementById('statusFilter').value;

    if (search) document.getElementById('searchPatients').value = '';
    if (barangay) document.getElementById('barangayFilter').value = '';
    if (nutrition) document.getElementById('nutritionFilter').value = '';
    if (ageGroup) document.getElementById('ageGroupFilter').value = '';
    if (medical) document.getElementById('medicalFilter').value = '';
    if (gender) document.getElementById('genderFilter').value = '';
    if (status) document.getElementById('statusFilter').value = '';

    filterPatients(); // Apply filters after clearing
}

function changePerPage(perPage) {
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('per_page', perPage);
    currentUrl.searchParams.delete('page'); // Reset to first page when changing per page
    window.location.href = currentUrl.toString();
}
</script>
@endsection
