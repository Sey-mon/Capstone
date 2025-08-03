@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Edit Email Template</h2>
                    <div class="space-x-2">
                        <a href="{{ route('admin.email-templates.preview', $emailTemplate) }}" 
                           target="_blank"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                            Preview
                        </a>
                        <a href="{{ route('admin.email-templates.index') }}" 
                           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                            Back
                        </a>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.email-templates.update', $emailTemplate) }}">
                    @csrf
                    @method('PUT')

                    <!-- Subject -->
                    <div class="mb-6">
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Subject
                        </label>
                        <input type="text" 
                               name="subject" 
                               id="subject" 
                               value="{{ old('subject', $emailTemplate->subject) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               required>
                        @error('subject')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-sm text-gray-600 mt-1">
                            Use @{{app_name}} and @{{user_name}} as placeholders
                        </p>
                    </div>

                    <!-- Color Settings -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <div>
                            <label for="primary_color" class="block text-sm font-medium text-gray-700 mb-2">
                                Primary Color
                            </label>
                            <input type="color" 
                                   name="primary_color" 
                                   id="primary_color" 
                                   value="{{ old('primary_color', $emailTemplate->styling['primary_color'] ?? '#10b981') }}"
                                   class="w-full h-10 border border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label for="secondary_color" class="block text-sm font-medium text-gray-700 mb-2">
                                Secondary Color
                            </label>
                            <input type="color" 
                                   name="secondary_color" 
                                   id="secondary_color" 
                                   value="{{ old('secondary_color', $emailTemplate->styling['secondary_color'] ?? '#064e3b') }}"
                                   class="w-full h-10 border border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label for="background_color" class="block text-sm font-medium text-gray-700 mb-2">
                                Background Color
                            </label>
                            <input type="color" 
                                   name="background_color" 
                                   id="background_color" 
                                   value="{{ old('background_color', $emailTemplate->styling['background_color'] ?? '#f9fafb') }}"
                                   class="w-full h-10 border border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label for="text_color" class="block text-sm font-medium text-gray-700 mb-2">
                                Text Color
                            </label>
                            <input type="color" 
                                   name="text_color" 
                                   id="text_color" 
                                   value="{{ old('text_color', $emailTemplate->styling['text_color'] ?? '#374151') }}"
                                   class="w-full h-10 border border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label for="button_color" class="block text-sm font-medium text-gray-700 mb-2">
                                Button Color
                            </label>
                            <input type="color" 
                                   name="button_color" 
                                   id="button_color" 
                                   value="{{ old('button_color', $emailTemplate->styling['button_color'] ?? '#10b981') }}"
                                   class="w-full h-10 border border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label for="button_text_color" class="block text-sm font-medium text-gray-700 mb-2">
                                Button Text Color
                            </label>
                            <input type="color" 
                                   name="button_text_color" 
                                   id="button_text_color" 
                                   value="{{ old('button_text_color', $emailTemplate->styling['button_text_color'] ?? '#ffffff') }}"
                                   class="w-full h-10 border border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label for="font_family" class="block text-sm font-medium text-gray-700 mb-2">
                                Font Family
                            </label>
                            <select name="font_family" 
                                    id="font_family" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="Arial, sans-serif" {{ old('font_family', $emailTemplate->styling['font_family']) == 'Arial, sans-serif' ? 'selected' : '' }}>Arial</option>
                                <option value="Georgia, serif" {{ old('font_family', $emailTemplate->styling['font_family']) == 'Georgia, serif' ? 'selected' : '' }}>Georgia</option>
                                <option value="Helvetica, sans-serif" {{ old('font_family', $emailTemplate->styling['font_family']) == 'Helvetica, sans-serif' ? 'selected' : '' }}>Helvetica</option>
                                <option value="Times New Roman, serif" {{ old('font_family', $emailTemplate->styling['font_family']) == 'Times New Roman, serif' ? 'selected' : '' }}>Times New Roman</option>
                            </select>
                        </div>

                        <div>
                            <label for="container_width" class="block text-sm font-medium text-gray-700 mb-2">
                                Container Width
                            </label>
                            <select name="container_width" 
                                    id="container_width" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="500px" {{ old('container_width', $emailTemplate->styling['container_width']) == '500px' ? 'selected' : '' }}>500px</option>
                                <option value="600px" {{ old('container_width', $emailTemplate->styling['container_width']) == '600px' ? 'selected' : '' }}>600px</option>
                                <option value="700px" {{ old('container_width', $emailTemplate->styling['container_width']) == '700px' ? 'selected' : '' }}>700px</option>
                                <option value="800px" {{ old('container_width', $emailTemplate->styling['container_width']) == '800px' ? 'selected' : '' }}>800px</option>
                            </select>
                        </div>
                    </div>

                    <!-- Email Content -->
                    <div class="mb-6">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Content (HTML)
                        </label>
                        <textarea name="content" 
                                  id="content" 
                                  rows="20"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent font-mono text-sm"
                                  required>{{ old('content', $emailTemplate->content) }}</textarea>
                        @error('content')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <div class="text-sm text-gray-600 mt-2">
                            <p class="font-medium">Available Placeholders:</p>
                            <ul class="list-disc ml-5 mt-1">
                                <li>@{{app_name}} - Application name</li>
                                <li>@{{user_name}} - User's name</li>
                                <li>@{{verification_url}} - Email verification link</li>
                                <li>@{{primary_color}}, @{{secondary_color}}, @{{background_color}}, @{{text_color}} - Color variables</li>
                                <li>@{{button_color}}, @{{button_text_color}} - Button color variables</li>
                                <li>@{{font_family}}, @{{container_width}} - Layout variables</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4">
                        <button type="submit" 
                                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors">
                            Update Template
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Live preview of colors
    const colorInputs = ['primary_color', 'secondary_color', 'background_color', 'text_color', 'button_color', 'button_text_color'];
    
    colorInputs.forEach(function(inputId) {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('input', function() {
                // You could add live preview functionality here
                console.log(inputId + ' changed to: ' + this.value);
            });
        }
    });
});
</script>
@endsection
