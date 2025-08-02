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
                <li><a href="{{ route('nutritionist.inventory') }}" class="text-blue-600 hover:underline">Inventory</a></li>
                <li><a href="{{ route('nutritionist.transactions.log') }}" class="text-blue-600 hover:underline">Inventory Log</a></li>
                <li><a href="{{ route('nutritionist.reports') }}" class="text-blue-600 hover:underline">Reports</a></li>
                <li><a href="{{ route('profile.edit') }}" class="text-blue-600 hover:underline">Edit Profile</a></li>
            </ul>
        </div>
    </div>
</div>
@endsection
