@extends('layouts.admin')

@section('title', 'Theme Customizer')

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Theme Customizer</h1>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Settings Form -->
            <div class="lg:col-span-2">
                <form action="{{ route('admin.theme.save') }}" method="POST" class="bg-white rounded-lg shadow p-6">
                    @csrf

                    <div class="mb-6">
                        <label for="primary_color" class="block text-sm font-medium text-gray-700 mb-2">Primary Color</label>
                        <div class="flex items-center space-x-3">
                            <input type="color" name="primary_color" id="primary_color"
                                   value="{{ old('primary_color', $settings['primary_color'] ?? '#3B82F6') }}"
                                   class="h-10 w-16 border border-gray-300 rounded cursor-pointer">
                            <input type="text" name="primary_color_text"
                                   value="{{ old('primary_color', $settings['primary_color'] ?? '#3B82F6') }}"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg" readonly>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="secondary_color" class="block text-sm font-medium text-gray-700 mb-2">Secondary Color</label>
                        <div class="flex items-center space-x-3">
                            <input type="color" name="secondary_color" id="secondary_color"
                                   value="{{ old('secondary_color', $settings['secondary_color'] ?? '#10B981') }}"
                                   class="h-10 w-16 border border-gray-300 rounded cursor-pointer">
                            <input type="text" name="secondary_color_text"
                                   value="{{ old('secondary_color', $settings['secondary_color'] ?? '#10B981') }}"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg" readonly>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="font_family" class="block text-sm font-medium text-gray-700 mb-2">Font Family</label>
                        <select name="font_family" id="font_family"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="Inter, sans-serif" {{ (old('font_family', $settings['font_family'] ?? '') == 'Inter, sans-serif') ? 'selected' : '' }}>Inter</option>
                            <option value="Poppins, sans-serif" {{ (old('font_family', $settings['font_family'] ?? '') == 'Poppins, sans-serif') ? 'selected' : '' }}>Poppins</option>
                            <option value="Plus Jakarta Sans, sans-serif" {{ (old('font_family', $settings['font_family'] ?? '') == 'Plus Jakarta Sans, sans-serif') ? 'selected' : '' }}>Plus Jakarta Sans</option>
                            <option value="Roboto, sans-serif" {{ (old('font_family', $settings['font_family'] ?? '') == 'Roboto, sans-serif') ? 'selected' : '' }}>Roboto</option>
                            <option value="Open Sans, sans-serif" {{ (old('font_family', $settings['font_family'] ?? '') == 'Open Sans, sans-serif') ? 'selected' : '' }}>Open Sans</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label for="layout_width" class="block text-sm font-medium text-gray-700 mb-2">Layout Width</label>
                        <div class="flex space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="layout_width" value="boxed"
                                       {{ old('layout_width', $settings['layout_width'] ?? 'full') == 'boxed' ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">Boxed</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="layout_width" value="full"
                                       {{ old('layout_width', $settings['layout_width'] ?? 'full') == 'full' ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">Full Width</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="border_radius" class="block text-sm font-medium text-gray-700 mb-2">Border Radius</label>
                        <div class="flex space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="border_radius" value="sharp"
                                       {{ old('border_radius', $settings['border_radius'] ?? 'rounded') == 'sharp' ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">Sharp (0px)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="border_radius" value="rounded"
                                       {{ old('border_radius', $settings['border_radius'] ?? 'rounded') == 'rounded' ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">Rounded (8px)</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="dark_mode" value="1"
                                   {{ old('dark_mode', $settings['dark_mode'] ?? false) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">Enable Dark Mode</span>
                        </label>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Save Theme Settings
                        </button>
                    </div>
                </form>
            </div>

            <!-- Live Preview -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Live Preview</h3>
                    <div id="preview-box" class="space-y-4">
                        <div class="p-4 rounded-lg" :style="{ backgroundColor: primaryColor + '20' }">
                            <p class="text-sm font-medium" :style="{ color: primaryColor }">Primary Color</p>
                        </div>
                        <div class="p-4 rounded-lg" :style="{ backgroundColor: secondaryColor + '20' }">
                            <p class="text-sm font-medium" :style="{ color: secondaryColor }">Secondary Color</p>
                        </div>
                        <div class="p-4 border rounded-lg" :style="{ borderRadius: borderRadius === 'rounded' ? '8px' : '0px' }">
                            <p class="text-sm text-gray-600">Sample Button</p>
                        </div>
                        <div class="p-4" :style="{ fontFamily: fontFamily }">
                            <p class="text-sm">Font Preview: The quick brown fox jumps over the lazy dog.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('themePreview', () => ({
        primaryColor: '{{ old("primary_color", $settings["primary_color"] ?? "#3B82F6") }}',
        secondaryColor: '{{ old("secondary_color", $settings["secondary_color"] ?? "#10B981") }}',
        fontFamily: '{{ old("font_family", $settings["font_family"] ?? "Inter, sans-serif") }}',
        borderRadius: '{{ old("border_radius", $settings["border_radius"] ?? "rounded") }}',

        init() {
            this.$watch('primaryColor', value => {
                document.querySelector('[name="primary_color"]').value = value;
                document.querySelector('[name="primary_color_text"]').value = value;
            });

            this.$watch('secondaryColor', value => {
                document.querySelector('[name="secondary_color"]').value = value;
                document.querySelector('[name="secondary_color_text"]').value = value;
            });
        }
    }));
});
</script>
@endpush
@endsection
