@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div>
        <x-breadcrumbs :items="[
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Contact Settings']
        ]" />
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white mt-1">Contact Details & Map</h1>
        <p class="text-xs text-gray-500 dark:text-gray-400">Configure company public contact info displayed across the storefront.</p>
    </div>

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.contact-settings.update') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Official Support Email *</label>
                    <input type="email" name="email" value="{{ old('email', $setting->email ?? 'support@shopia.com') }}" required class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Hotline / Phone *</label>
                    <input type="text" name="phone" value="{{ old('phone', $setting->phone ?? '+880 1800-000000') }}" required class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Store / Office Address *</label>
                <input type="text" name="address" value="{{ old('address', $setting->address ?? 'Dhaka, Bangladesh') }}" required class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Working Hours</label>
                <input type="text" name="working_hours" value="{{ old('working_hours', $setting->working_hours ?? 'Sat - Thu: 9:00 AM - 9:00 PM') }}" placeholder="Sat - Thu: 9:00 AM - 9:00 PM" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Google Maps Embed URL</label>
                <textarea name="map_url" rows="3" placeholder="https://www.google.com/maps/embed?..." class="w-full px-3.5 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">{{ old('map_url', $setting->map_url) }}</textarea>
            </div>

            <div class="pt-4 flex items-center justify-end border-t border-gray-100 dark:border-gray-800">
                <button type="submit" class="px-5 py-2.5 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-md shadow-primary/20">Save Contact Info</button>
            </div>
        </form>
    </div>

</div>
@endsection
