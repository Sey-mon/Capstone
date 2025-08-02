@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Inventory Item Details</h1>
        <a href="{{ route('nutritionist.inventory') }}" class="inline-block mt-4 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">Back to Inventory</a>
    </div>
    <div class="bg-white rounded-lg shadow-lg p-6">
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <dt class="font-semibold text-gray-700">Name</dt>
                <dd class="mb-2 text-gray-900">{{ $inventory->name }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-gray-700">Code</dt>
                <dd class="mb-2 text-gray-900">{{ $inventory->code }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-gray-700">Category</dt>
                <dd class="mb-2 text-gray-900">{{ ucfirst($inventory->category) }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-gray-700">Unit</dt>
                <dd class="mb-2 text-gray-900">{{ $inventory->unit }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-gray-700">Current Stock</dt>
                <dd class="mb-2 text-gray-900">{{ $inventory->current_stock }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-gray-700">Minimum Stock</dt>
                <dd class="mb-2 text-gray-900">{{ $inventory->minimum_stock }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-gray-700">Expiry Date</dt>
                <dd class="mb-2 text-gray-900">
                    @if($inventory->expiry_date)
                        {{ \Carbon\Carbon::parse($inventory->expiry_date)->format('M d, Y') }}
                    @else
                        <span class="text-gray-400">No expiry</span>
                    @endif
                </dd>
            </div>
            <div>
                <dt class="font-semibold text-gray-700">Description</dt>
                <dd class="mb-2 text-gray-900">{{ $inventory->description ?? '-' }}</dd>
            </div>
        </dl>
    </div>
</div>
@endsection 