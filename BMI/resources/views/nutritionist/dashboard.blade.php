@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="py-8">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}</h1>
                        <p class="text-lg text-gray-600 mt-1">Your nutrition assessment dashboard for today.</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">{{ now()->format('l') }}</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ now()->format('M j, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Patients -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Children</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalPatients ?? 0 }}</p>
                        <div class="flex items-center mt-2">
                            <div class="flex items-center text-green-600">
                                <i class="fas fa-child text-xs mr-1"></i>
                                <span class="text-sm font-medium">Under care</span>
                            </div>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-green-600 text-lg"></i>
                    </div>
                </div>
            </div>

            <!-- At Risk -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 mb-1">At Risk</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $criticalCases ?? 0 }}</p>
                        <div class="flex items-center mt-2">
                            <div class="flex items-center text-red-600">
                                <i class="fas fa-exclamation-triangle text-xs mr-1"></i>
                                <span class="text-sm font-medium">Needs attention</span>
                            </div>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-heartbeat text-red-600 text-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Total Assessments -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 mb-1">Assessments</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalAssessments ?? 0 }}</p>
                        <div class="flex items-center mt-2">
                            <div class="flex items-center text-green-600">
                                <i class="fas fa-clipboard-check text-xs mr-1"></i>
                                <span class="text-sm font-medium">Completed</span>
                            </div>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-apple-alt text-green-600 text-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Inventory Items -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 mb-1">Inventory Items</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalInventoryItems ?? 0 }}</p>
                        <div class="flex items-center mt-2">
                            <div class="flex items-center text-purple-600">
                                <i class="fas fa-box text-xs mr-1"></i>
                                <span class="text-sm font-medium">In stock</span>
                            </div>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-boxes text-purple-600 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Special Feature Card - AI Treatment Model -->
        <div class="mb-8">
            <a href="{{ route('nutritionist.treatment-model') }}" class="block group">
                <div class="bg-gradient-to-r from-green-500 to-blue-600 rounded-2xl p-8 shadow-sm hover:shadow-lg transition-all duration-300 transform group-hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-robot text-white text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-white mb-1">AI Treatment Model</h3>
                                <p class="text-green-100">Advanced nutrition assessment with machine learning</p>
                            </div>
                        </div>
                        <div class="flex items-center text-white">
                            <span class="text-sm font-medium mr-2">Try Now</span>
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform duration-300"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Analytics Visualization -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Nutrition Status Distribution</h3>
                <div class="relative h-64">
                    <canvas id="malnutritionPieChart"></canvas>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Trends (Last 6 Months)</h3>
                <div class="relative h-64">
                    <canvas id="malnutritionLineChart"></canvas>
                </div>
            </div>
        <!-- Summary Cards and Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Nutrition Summary -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Nutrition Summary</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-700">Severe Malnutrition</span>
                        </div>
                        <span class="text-lg font-bold text-red-600">{{ $nutritionStats['severe_malnutrition'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-orange-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-700">Moderate Malnutrition</span>
                        </div>
                        <span class="text-lg font-bold text-orange-600">{{ $nutritionStats['moderate_malnutrition'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-700">Mild Malnutrition</span>
                        </div>
                        <span class="text-lg font-bold text-yellow-600">{{ $nutritionStats['mild_malnutrition'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-700">Normal</span>
                        </div>
                        <span class="text-lg font-bold text-green-600">{{ $nutritionStats['normal'] ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Quick Actions</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <a href="{{ route('nutritionist.nutrition') }}" class="group flex flex-col items-center p-4 bg-green-50 hover:bg-green-100 rounded-xl transition-colors duration-200">
                        <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-apple-alt text-white"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 text-center">New Assessment</span>
                    </a>
                    
                    <a href="{{ route('nutritionist.patients') }}" class="group flex flex-col items-center p-4 bg-green-50 hover:bg-green-100 rounded-xl transition-colors duration-200">
                        <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 text-center">View Patients</span>
                    </a>
                    
                    <a href="{{ route('nutritionist.inventory') }}" class="group flex flex-col items-center p-4 bg-green-50 hover:bg-green-100 rounded-xl transition-colors duration-200">
                        <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-boxes text-white"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 text-center">Inventory</span>
                    </a>
                    
                    <a href="{{ route('nutritionist.transactions.log') }}" class="group flex flex-col items-center p-4 bg-green-50 hover:bg-green-100 rounded-xl transition-colors duration-200">
                        <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-exchange-alt text-white"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 text-center">Transactions</span>
                    </a>
                    
                    <a href="{{ route('nutritionist.reports') }}" class="group flex flex-col items-center p-4 bg-green-50 hover:bg-green-100 rounded-xl transition-colors duration-200">
                        <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-chart-bar text-white"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 text-center">Reports</span>
                    </a>
                    
                    <a href="{{ route('profile.edit') }}" class="group flex flex-col items-center p-4 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors duration-200">
                        <div class="w-12 h-12 bg-gray-600 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-user-edit text-white"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 text-center">Edit Profile</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Pie Chart Data
    const pieData = {
        labels: [
            'Normal',
            'Mild Malnutrition',
            'Moderate Malnutrition',
            'Severe Malnutrition'
        ],
        datasets: [{
            data: [
                {{ $nutritionStats['normal'] ?? 0 }},
                {{ $nutritionStats['mild_malnutrition'] ?? 0 }},
                {{ $nutritionStats['moderate_malnutrition'] ?? 0 }},
                {{ $nutritionStats['severe_malnutrition'] ?? 0 }}
            ],
            backgroundColor: [
                '#10b981', // emerald-500
                '#f59e0b', // amber-500
                '#f97316', // orange-500
                '#ef4444'  // red-500
            ],
            borderWidth: 2,
            borderColor: '#ffffff'
        }]
    };
    
    new Chart(document.getElementById('malnutritionPieChart'), {
        type: 'doughnut',
        data: pieData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            },
            cutout: '60%'
        }
    });

    // Line Chart Data (Placeholder, replace with real trend data if available)
    const lineData = {
        labels: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
        datasets: [
            {
                label: 'Severe',
                data: [5, 7, 6, 4, 3, 2],
                borderColor: '#ef4444',
                backgroundColor: 'rgba(239,68,68,0.1)',
                tension: 0.4,
                fill: true
            },
            {
                label: 'Moderate',
                data: [8, 9, 7, 6, 5, 4],
                borderColor: '#f97316',
                backgroundColor: 'rgba(249,115,22,0.1)',
                tension: 0.4,
                fill: true
            },
            {
                label: 'Mild',
                data: [12, 10, 11, 13, 12, 11],
                borderColor: '#f59e0b',
                backgroundColor: 'rgba(245,158,11,0.1)',
                tension: 0.4,
                fill: true
            },
            {
                label: 'Normal',
                data: [20, 22, 23, 25, 27, 30],
                borderColor: '#10b981',
                backgroundColor: 'rgba(16,185,129,0.1)',
                tension: 0.4,
                fill: true
            }
        ]
    };
    
    new Chart(document.getElementById('malnutritionLineChart'), {
        type: 'line',
        data: lineData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
</script>
@endsection
