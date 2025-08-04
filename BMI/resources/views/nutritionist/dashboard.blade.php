@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Welcome back, {{ auth()->user()->name }}</h1>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-semibold text-gray-700">Total Children</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $totalChildren ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-red-100 text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-semibold text-gray-700">At Risk</h3>
                    <p class="text-2xl font-bold text-red-600">{{ $atRisk ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-semibold text-gray-700">Recovered</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $recovered ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-purple-100 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-semibold text-gray-700">Inventory Items</h3>
                    <p class="text-2xl font-bold text-purple-600">{{ $totalInventory ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Analytics Visualization -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
        <div class="bg-white rounded-lg shadow-lg p-2 max-w-sm">
            <h2 class="text-xs font-bold mb-1">Malnutrition Status Distribution</h2>
            <canvas id="malnutritionPieChart" height="120"></canvas>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-2 max-w-sm">
            <h2 class="text-xs font-bold mb-1">Malnutrition Trends (Last 6 Months)</h2>
            <canvas id="malnutritionLineChart" height="120"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-lg font-bold mb-4">Nutrition Status Breakdown</h2>
            <ul class="space-y-2">
                <li class="flex justify-between">
                    <span>Severe Malnutrition:</span>
                    <span class="font-bold text-red-600">{{ $nutritionStats['severe_malnutrition'] ?? 0 }}</span>
                </li>
                <li class="flex justify-between">
                    <span>Moderate Malnutrition:</span>
                    <span class="font-bold text-yellow-600">{{ $nutritionStats['moderate_malnutrition'] ?? 0 }}</span>
                </li>
                <li class="flex justify-between">
                    <span>Mild Malnutrition:</span>
                    <span class="font-bold text-blue-600">{{ $nutritionStats['mild_malnutrition'] ?? 0 }}</span>
                </li>
                <li class="flex justify-between">
                    <span>Normal:</span>
                    <span class="font-bold text-green-600">{{ $nutritionStats['normal'] ?? 0 }}</span>
                </li>
            </ul>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-lg font-bold mb-4">Quick Actions</h2>
            <ul class="space-y-2">
                <li><a href="{{ route('nutritionist.patients') }}" class="text-blue-600 hover:underline">View All Children</a></li>
                <li><a href="{{ route('nutritionist.nutrition') }}" class="text-blue-600 hover:underline">Nutrition Assessments</a></li>
                <li><a href="{{ route('nutritionist.inventory') }}" class="text-blue-600 hover:underline">Inventory</a></li>
                <li><a href="{{ route('nutritionist.reports') }}" class="text-blue-600 hover:underline">Reports</a></li>
            </ul>
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
                '#34d399', // green-400
                '#fde68a', // yellow-300
                '#fdba74', // orange-300
                '#f87171'  // red-400
            ],
            borderWidth: 1
        }]
    };
    new Chart(document.getElementById('malnutritionPieChart'), {
        type: 'pie',
        data: pieData,
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // Line Chart Data from Database
    const monthlyTrends = @json($monthlyTrends ?? []);
    const months = Object.keys(monthlyTrends);
    
    const lineData = {
        labels: months,
        datasets: [
            {
                label: 'Severe',
                data: months.map(month => monthlyTrends[month]?.severe_malnutrition || 0),
                borderColor: '#f87171',
                backgroundColor: 'rgba(248,113,113,0.2)',
                tension: 0.4
            },
            {
                label: 'Moderate',
                data: months.map(month => monthlyTrends[month]?.moderate_malnutrition || 0),
                borderColor: '#fdba74',
                backgroundColor: 'rgba(253,186,116,0.2)',
                tension: 0.4
            },
            {
                label: 'Mild',
                data: months.map(month => monthlyTrends[month]?.mild_malnutrition || 0),
                borderColor: '#fde68a',
                backgroundColor: 'rgba(253,230,138,0.2)',
                tension: 0.4
            },
            {
                label: 'Normal',
                data: months.map(month => monthlyTrends[month]?.normal || 0),
                borderColor: '#34d399',
                backgroundColor: 'rgba(52,211,153,0.2)',
                tension: 0.4
            }
        ]
    };
    new Chart(document.getElementById('malnutritionLineChart'), {
        type: 'line',
        data: lineData,
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
