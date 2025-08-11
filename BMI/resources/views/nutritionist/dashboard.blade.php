@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Welcome back, {{ auth()->user()->name }}</h1>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700">Total Children</h3>
            <p class="text-2xl font-bold text-blue-600">{{ $totalChildren ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700">At Risk</h3>
            <p class="text-2xl font-bold text-red-600">{{ $atRisk ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700">Recovered</h3>
            <p class="text-2xl font-bold text-green-600">{{ $recovered ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700">Inventory Items</h3>
            <p class="text-2xl font-bold text-purple-600">{{ $totalInventory ?? 0 }}</p>
        </div>
    </div>

    <!-- Data Analytics Visualization -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-lg font-bold mb-4">Malnutrition Status Distribution</h2>
            <canvas id="malnutritionPieChart" height="200"></canvas>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-lg font-bold mb-4">Malnutrition Trends (Last 6 Months)</h2>
            <canvas id="malnutritionLineChart" height="200"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-lg font-bold mb-4">Malnutrition Trends</h2>
            <ul>
                <li>Severe Malnutrition: <span class="font-bold">{{ $severe ?? 0 }}</span></li>
                <li>Moderate Malnutrition: <span class="font-bold">{{ $moderate ?? 0 }}</span></li>
                <li>At Risk: <span class="font-bold">{{ $atRisk ?? 0 }}</span></li>
            </ul>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-lg font-bold mb-4">Quick Links</h2>
            <ul class="space-y-2">
                <li><a href="{{ route('nutritionist.treatment-model') }}" class="text-green-600 hover:underline font-semibold">🤖 Treatment Model API</a></li>
                <li><a href="{{ route('nutritionist.nutrition') }}" class="text-blue-600 hover:underline">Patient Assessments</a></li>
                <li><a href="{{ route('nutritionist.inventory') }}" class="text-blue-600 hover:underline">Inventory</a></li>
                <li><a href="{{ route('nutritionist.transactions.log') }}" class="text-blue-600 hover:underline">Inventory Log</a></li>
                <li><a href="{{ route('nutritionist.reports') }}" class="text-blue-600 hover:underline">Reports</a></li>
                <li><a href="{{ route('profile.edit') }}" class="text-blue-600 hover:underline">Edit Profile</a></li>
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

    // Line Chart Data (Placeholder, replace with real trend data if available)
    const lineData = {
        labels: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
        datasets: [
            {
                label: 'Severe',
                data: [5, 7, 6, 4, 3, 2],
                borderColor: '#f87171',
                backgroundColor: 'rgba(248,113,113,0.2)',
                tension: 0.4
            },
            {
                label: 'Moderate',
                data: [8, 9, 7, 6, 5, 4],
                borderColor: '#fdba74',
                backgroundColor: 'rgba(253,186,116,0.2)',
                tension: 0.4
            },
            {
                label: 'Mild',
                data: [12, 10, 11, 13, 12, 11],
                borderColor: '#fde68a',
                backgroundColor: 'rgba(253,230,138,0.2)',
                tension: 0.4
            },
            {
                label: 'Normal',
                data: [20, 22, 23, 25, 27, 30],
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
