@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Email Templates</h2>
                    <a href="{{ route('admin.email-templates.create-default') }}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                        Create Default Template
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if($templates->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Template Name
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Subject
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Colors
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($templates as $template)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ ucwords(str_replace('_', ' ', $template->name)) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ Str::limit($template->subject, 60) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($template->is_active)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{-- CSS linter disabled for dynamic color values --}}
                                            <div class="flex space-x-2">
                                                @php
                                                    $primaryColor = $template->styling['primary_color'] ?? '#10b981';
                                                    $secondaryColor = $template->styling['secondary_color'] ?? '#064e3b';
                                                    $buttonColor = $template->styling['button_color'] ?? '#10b981';
                                                @endphp
                                                <div class="w-4 h-4 rounded-full border border-gray-300" 
                                                     style="background-color: {{ $primaryColor }}"
                                                     title="Primary Color"></div>
                                                <div class="w-4 h-4 rounded-full border border-gray-300" 
                                                     style="background-color: {{ $secondaryColor }}"
                                                     title="Secondary Color"></div>
                                                <div class="w-4 h-4 rounded-full border border-gray-300" 
                                                     style="background-color: {{ $buttonColor }}"
                                                     title="Button Color"></div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <a href="{{ route('admin.email-templates.preview', $template) }}" 
                                               target="_blank"
                                               class="text-blue-600 hover:text-blue-900">Preview</a>
                                            <a href="{{ route('admin.email-templates.edit', $template) }}" 
                                               class="text-green-600 hover:text-green-900">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-gray-500 mb-4">
                            <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a4 4 0 005.66 0L24 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Email Templates</h3>
                        <p class="text-gray-500 mb-4">Get started by creating your first email template.</p>
                        <a href="{{ route('admin.email-templates.create-default') }}" 
                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                            Create Default Template
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
