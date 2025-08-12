@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="py-8">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Good {{ now()->format('A') === 'AM' ? 'morning' : (now()->format('H') < 18 ? 'afternoon' : 'evening') }}, {{ auth()->user()->name }}</h1>
                        <p class="text-lg text-gray-600 mt-1">Here's what's happening with your nutrition program today.</p>
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
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Patients</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_patients'] ?? 0 }}</p>
                        <div class="flex items-center mt-2">
                            <div class="flex items-center text-green-600">
                                <i class="fas fa-arrow-up text-xs mr-1"></i>
                                <span class="text-sm font-medium">12% vs last month</span>
                            </div>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-green-600 text-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Critical Cases -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 mb-1">Critical Cases</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['malnourished_patients'] ?? 0 }}</p>
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

            <!-- Assessments -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Assessments</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_assessments'] ?? 0 }}</p>
                        <div class="flex items-center mt-2">
                            <div class="flex items-center text-green-600">
                                <i class="fas fa-plus text-xs mr-1"></i>
                                <span class="text-sm font-medium">{{ $stats['recent_assessments'] ?? 0 }} this week</span>
                            </div>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clipboard-check text-green-600 text-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Inventory Alerts -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 mb-1">Low Stock Items</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['low_stock_items'] ?? 0 }}</p>
                        <div class="flex items-center mt-2">
                            <div class="flex items-center text-amber-600">
                                <i class="fas fa-exclamation-circle text-xs mr-1"></i>
                                <span class="text-sm font-medium">Restock needed</span>
                            </div>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-box text-amber-600 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <a href="{{ route('admin.patients') }}" class="group bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-6 hover:from-green-100 hover:to-green-200 transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-green-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Manage Patients</h3>
                    <p class="text-sm text-gray-600">View and manage patient records</p>
                </div>
            </a>

            <a href="{{ route('admin.nutrition') }}" class="group bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-6 hover:from-green-100 hover:to-green-200 transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-green-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-apple-alt text-white text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Nutrition Assessments</h3>
                    <p class="text-sm text-gray-600">Conduct and view assessments</p>
                </div>
            </a>

            <a href="{{ route('admin.inventory') }}" class="group bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-6 hover:from-green-100 hover:to-green-200 transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-green-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-boxes text-white text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Inventory Management</h3>
                    <p class="text-sm text-gray-600">Track supplies and stock</p>
                </div>
            </a>

            <a href="{{ route('admin.reports') }}" class="group bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-6 hover:from-green-100 hover:to-green-200 transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-green-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-chart-bar text-white text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Analytics & Reports</h3>
                    <p class="text-sm text-gray-600">View comprehensive reports</p>
                </div>
            </a>
        <!-- Recent Activity & Critical Cases -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Patients -->
            <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Patients</h3>
                    <a href="{{ route('admin.patients') }}" class="text-green-600 hover:text-green-700 text-sm font-medium flex items-center">
                        View All <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>
                <div class="space-y-3">
                    @forelse($recent_patients as $patient)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">{{ $patient->first_name }} {{ $patient->last_name }}</div>
                                <div class="text-sm text-gray-500">{{ $patient->age }} years old • {{ $patient->gender ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600">
                            {{ $patient->created_at->diffForHumans() }}
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-users text-gray-300 text-3xl mb-3"></i>
                        <p class="text-gray-500">No recent patients</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Critical Cases Alert -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Critical Cases</h3>
                    <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                </div>
                <div class="space-y-3">
                    @forelse($critical_cases as $case)
                    <div class="p-4 bg-red-50 rounded-xl border border-red-100">
                        <div class="flex items-center justify-between mb-2">
                            <div class="font-medium text-gray-900">{{ $case->patient->first_name ?? 'Unknown' }} {{ $case->patient->last_name ?? '' }}</div>
                            <span class="text-xs bg-red-500 text-white px-2 py-1 rounded-full">Critical</span>
                        </div>
                        <div class="text-sm text-gray-600">
                            <div>BMI: {{ number_format($case->bmi, 1) }}</div>
                            <div class="text-xs text-gray-500 mt-1">{{ $case->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-shield-alt text-green-300 text-3xl mb-3"></i>
                        <p class="text-gray-500 text-sm">No critical cases</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Low Stock Alerts -->
        @if($low_stock_items && count($low_stock_items) > 0)
        <div class="mt-6 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Low Stock Alerts</h3>
                <a href="{{ route('admin.inventory') }}" class="text-green-600 hover:text-green-700 text-sm font-medium flex items-center">
                    Manage Inventory <i class="fas fa-arrow-right ml-1 text-xs"></i>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($low_stock_items as $item)
                <div class="p-4 bg-amber-50 rounded-xl border border-amber-100">
                    <div class="flex items-center justify-between mb-2">
                        <div class="font-medium text-gray-900">{{ $item->name }}</div>
                        <span class="text-xs bg-amber-500 text-white px-2 py-1 rounded-full">Low Stock</span>
                    </div>
                    <div class="text-sm text-gray-600">
                        <div>Current: {{ $item->current_stock ?? 0 }}</div>
                        <div>Minimum: {{ $item->minimum_stock ?? 0 }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Critical Cases Section -->
    <div class="mt-6">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Critical Cases</h3>
                <a href="{{ route('admin.nutrition') }}" class="text-red-600 hover:text-red-800 text-sm">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($critical_cases as $case)
                <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                    <div>
                        <div class="font-medium text-gray-900">{{ $case->patient->name }}</div>
                        <div class="text-sm text-red-600">{{ ucfirst(str_replace('_', ' ', $case->nutrition_status)) }}</div>
                    </div>
                    <div class="text-sm text-gray-600">
                        {{ $case->created_at->diffForHumans() }}
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No critical cases</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Low Stock Alert -->
    @if($low_stock_items->count() > 0)
    <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">Low Stock Alert</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p>{{ $low_stock_items->count() }} items are running low. <a href="{{ route('admin.inventory') }}" class="underline">Restock now</a></p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
