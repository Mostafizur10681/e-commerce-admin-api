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
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Welcome Back</h1>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Sign in to manage your e-commerce platform</p>
    </div>

    <!-- Mode Switcher Tabs -->
    <div class="grid grid-cols-2 p-1 bg-gray-100 dark:bg-gray-800/60 rounded-2xl mb-6">
        <a href="{{ route('admin.login') }}" class="py-2 text-xs font-semibold rounded-xl text-center bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm transition-all">
            Sign In
        </a>
        <a href="{{ route('admin.register') }}" class="py-2 text-xs font-semibold rounded-xl text-center text-gray-500 hover:text-gray-900 dark:hover:text-white transition-all">
            Sign Up
        </a>
    </div>

    <!-- Error Alerts -->
    @if(session('error'))
        <div class="mb-4 p-3 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs font-medium">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-3 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs font-medium">
            <ul class="list-disc pl-4 space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Login Form -->
    <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Email Address</label>
            <div class="relative">
                <svg class="absolute left-3.5 top-3 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <input 
                    type="email" 
                    name="email" 
                    value="{{ old('email', 'admin@dataflow.com') }}" 
                    required 
                    placeholder="admin@example.com"
                    class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary"
                >
            </div>
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label class="text-xs font-semibold text-gray-700 dark:text-gray-300">Password</label>
            </div>
            <div class="relative" x-data="{ showPass: false }">
                <svg class="absolute left-3.5 top-3 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <input 
                    :type="showPass ? 'text' : 'password'" 
                    name="password" 
                    required 
                    placeholder="••••••••"
                    class="w-full pl-10 pr-10 py-2.5 bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary"
                >
                <button 
                    type="button" 
                    @click="showPass = !showPass"
                    class="absolute right-3 top-3 text-gray-400 hover:text-gray-600"
                >
                    <svg x-show="!showPass" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    <svg x-show="showPass" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" /></svg>
                </button>
            </div>
        </div>

        <div class="flex items-center justify-between text-xs pt-1">
            <label class="flex items-center gap-2 cursor-pointer text-gray-600 dark:text-gray-400">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary focus:ring-primary h-4 w-4">
                <span>Remember me</span>
            </label>
        </div>

        <button 
            type="submit" 
            class="w-full py-3 px-4 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-lg shadow-primary/20 transition-all duration-200 mt-2 cursor-pointer flex items-center justify-center gap-2"
        >
            <span>Sign In to Dashboard</span>
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
        </button>
    </form>
</div>
@endsection
