@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div>
        <x-breadcrumbs :items="[
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Footer Settings']
        ]" />
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white mt-1">Footer & Social Links</h1>
        <p class="text-xs text-gray-500 dark:text-gray-400">Configure copyright notice and social media profile handles.</p>
    </div>

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.footer-settings.update') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Footer Short About Text</label>
                <textarea name="about_text" rows="3" placeholder="Brief company summary appearing in the footer..." class="w-full px-3.5 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">{{ old('about_text', $footer->about_text) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Copyright Notice</label>
                <input type="text" name="copyright_text" value="{{ old('copyright_text', $footer->copyright_text ?? '© ' . date('Y') . ' Shopia. All rights reserved.') }}" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
            </div>

            <div class="space-y-3 pt-2">
                <h3 class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Social Channels</h3>
                
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Facebook URL</label>
                    <input type="url" name="facebook_url" value="{{ old('facebook_url', $footer->facebook_url) }}" placeholder="https://facebook.com/shopia" class="w-full px-3.5 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Instagram URL</label>
                    <input type="url" name="instagram_url" value="{{ old('instagram_url', $footer->instagram_url) }}" placeholder="https://instagram.com/shopia" class="w-full px-3.5 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">Twitter (X) URL</label>
                    <input type="url" name="twitter_url" value="{{ old('twitter_url', $footer->twitter_url) }}" placeholder="https://twitter.com/shopia" class="w-full px-3.5 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 dark:text-gray-300 mb-1">YouTube URL</label>
                    <input type="url" name="youtube_url" value="{{ old('youtube_url', $footer->youtube_url) }}" placeholder="https://youtube.com/shopia" class="w-full px-3.5 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary">
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end border-t border-gray-100 dark:border-gray-800">
                <button type="submit" class="px-5 py-2.5 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-md shadow-primary/20">Save Footer Settings</button>
            </div>
        </form>
    </div>

</div>
@endsection
