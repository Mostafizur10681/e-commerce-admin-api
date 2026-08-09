@extends('layouts.auth')

@section('content')
<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-3xl p-6 sm:p-8 shadow-xl relative overflow-hidden backdrop-blur-md">
    
    <!-- Header Logo & Title -->
    <div class="text-center mb-6">
        <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-primary text-white shadow-lg shadow-primary/25 mb-3">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z" />
            </svg>
        </div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Create Admin Account</h1>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Submit registration for administrator review</p>
    </div>

    <!-- Mode Switcher Tabs -->
    <div class="grid grid-cols-2 p-1 bg-gray-100 dark:bg-gray-800/60 rounded-2xl mb-6">
        <a href="{{ route('admin.login') }}" class="py-2 text-xs font-semibold rounded-xl text-center text-gray-500 hover:text-gray-900 dark:hover:text-white transition-all">
            Sign In
        </a>
        <a href="{{ route('admin.register') }}" class="py-2 text-xs font-semibold rounded-xl text-center bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm transition-all">
            Sign Up
        </a>
    </div>

    @if($errors->any())
        <div class="mb-4 p-3 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs font-medium">
            <ul class="list-disc pl-4 space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Register Form -->
    <form method="POST" action="{{ route('admin.register.post') }}" class="space-y-3.5">
        @csrf

        <div>
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Full Name</label>
            <input 
                type="text" 
                name="name" 
                value="{{ old('name') }}" 
                required 
                placeholder="John Doe"
                class="w-full px-3.5 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary"
            >
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
            <input 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required 
                placeholder="admin@example.com"
                class="w-full px-3.5 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary"
            >
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Phone Number</label>
            <input 
                type="text" 
                name="phone" 
                value="{{ old('phone') }}" 
                required 
                placeholder="+880 1XXXXXXXXX"
                class="w-full px-3.5 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary"
            >
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Password</label>
            <input 
                type="password" 
                name="password" 
                required 
                placeholder="Minimum 6 characters"
                class="w-full px-3.5 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary"
            >
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Confirm Password</label>
            <input 
                type="password" 
                name="password_confirmation" 
                required 
                placeholder="Re-enter password"
                class="w-full px-3.5 py-2 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary"
            >
        </div>

        <button 
            type="submit" 
            class="w-full py-3 px-4 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-lg shadow-primary/20 transition-all duration-200 mt-2 cursor-pointer"
        >
            Submit for Approval
        </button>
    </form>
</div>
@endsection
