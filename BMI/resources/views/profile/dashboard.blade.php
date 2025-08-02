@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Welcome, {{ auth()->user()->name }}</h1>
    <div class="bg-white rounded-lg shadow-lg max-w-lg mx-auto p-6">
        <p class="mb-4">This is your dashboard. You can update your profile information below.</p>
        <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Edit Profile</a>
    </div>
</div>
@endsection 