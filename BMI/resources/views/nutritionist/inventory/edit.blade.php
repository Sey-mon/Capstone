@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Edit Inventory Item</h1>
        <a href="{{ route('nutritionist.inventory.show', $inventory) }}" class="inline-block mt-4 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">Back to Details</a>
    </div>
    <div class="bg-white rounded-lg shadow-lg p-6">
        <form action="{{ route('nutritionist.inventory.update', $inventory) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Item Name</label>
                    <input type="text" name="name" value="{{ old('name', $inventory->name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Item Code</label>
                    <input type="text" name="code" value="{{ old('code', $inventory->code) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="category" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">Select Category</option>
                        <option value="supplements" @if(old('category', $inventory->category) == 'supplements') selected @endif>Supplements</option>
                        <option value="food" @if(old('category', $inventory->category) == 'food') selected @endif>Food</option>
                        <option value="medicine" @if(old('category', $inventory->category) == 'medicine') selected @endif>Medicine</option>
                        <option value="equipment" @if(old('category', $inventory->category) == 'equipment') selected @endif>Equipment</option>
                        <option value="other" @if(old('category', $inventory->category) == 'other') selected @endif>Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                    <input type="text" name="unit" value="{{ old('unit', $inventory->unit) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Stock</label>
                    <input type="number" name="current_stock" value="{{ old('current_stock', $inventory->current_stock) }}" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Stock</label>
                    <input type="number" name="minimum_stock" value="{{ old('minimum_stock', $inventory->minimum_stock) }}" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date (Optional)</label>
                    <input type="date" name="expiry_date" value="{{ old('expiry_date', $inventory->expiry_date ? \Carbon\Carbon::parse($inventory->expiry_date)->format('Y-m-d') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('description', $inventory->description) }}</textarea>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update Item</button>
            </div>
        </form>
    </div>
</div>
@endsection 