@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Admin Dashboard</h1>
        <div class="text-sm text-gray-600">
            {{ now()->format('l, F j, Y') }}
        </div>
    </div>

    <!-- Key Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Patients -->
        <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Total Patients</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['total_patients'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Malnourished Patients -->
        <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Malnourished</h3>
                    <p class="text-3xl font-bold text-red-600">{{ $stats['malnourished_patients'] ?? 0 }}</p>
                    <p class="text-sm text-gray-600">Critical cases</p>
                </div>
                <div class="p-3 bg-red-100 rounded-full">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Assessments -->
        <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Assessments</h3>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['total_assessments'] ?? 0 }}</p>
                    <p class="text-sm text-gray-600">{{ $stats['recent_assessments'] ?? 0 }} this week</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Inventory Alerts -->
        <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Low Stock</h3>
                    <p class="text-3xl font-bold text-yellow-600">{{ $stats['low_stock_items'] ?? 0 }}</p>
                    <p class="text-sm text-gray-600">Items need restocking</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <a href="{{ route('admin.patients') }}" class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg p-6 text-center transition-colors duration-200">
            <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <h3 class="text-lg font-semibold mb-2">Manage Patients</h3>
            <p class="text-sm opacity-80">View and manage patient records</p>
        </a>

        <a href="{{ route('admin.nutrition') }}" class="bg-green-600 hover:bg-green-700 text-white rounded-lg p-6 text-center transition-colors duration-200">
            <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <h3 class="text-lg font-semibold mb-2">Nutrition Assessments</h3>
            <p class="text-sm opacity-80">Conduct and view assessments</p>
        </a>

        <a href="{{ route('admin.inventory') }}" class="bg-purple-600 hover:bg-purple-700 text-white rounded-lg p-6 text-center transition-colors duration-200">
            <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            <h3 class="text-lg font-semibold mb-2">Inventory</h3>
            <p class="text-sm opacity-80">Manage supplies and stock</p>
        </a>

        <a href="{{ route('admin.reports') }}" class="bg-orange-600 hover:bg-orange-700 text-white rounded-lg p-6 text-center transition-colors duration-200">
            <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <h3 class="text-lg font-semibold mb-2">Reports</h3>
            <p class="text-sm opacity-80">Analytics and insights</p>
        </a>
    </div>

    <!-- Recent Activity & Alerts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Patients -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Recent Patients</h3>
                <a href="{{ route('admin.patients') }}" class="text-blue-600 hover:text-blue-800 text-sm">View All</a>
            </div>
            <div class="space-y-3">
                @forelse($recent_patients as $patient)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <div class="font-medium text-gray-900">{{ $patient->first_name }} {{ $patient->last_name }}</div>
                        <div class="text-sm text-gray-500">{{ $patient->age }} years old</div>
                    </div>
                    <div class="text-sm text-gray-600">
                        {{ $patient->created_at->diffForHumans() }}
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No recent patients</p>
                @endforelse
            </div>
        </div>

        <!-- Critical Cases -->
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
