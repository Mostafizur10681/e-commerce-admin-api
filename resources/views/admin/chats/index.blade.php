@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <!-- Top Bar & Breadcrumbs -->
    <div>
        <x-breadcrumbs :items="[
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Live Support Chat']
        ]" />
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white mt-1">Live Support Conversations</h1>
        <p class="text-xs text-gray-500 dark:text-gray-400">Real-time messaging with online store visitors and customers.</p>
    </div>

    <!-- Chat App Container -->
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-3xl shadow-sm overflow-hidden h-[650px] grid grid-cols-1 md:grid-cols-3">
        
        <!-- Left: Conversations List -->
        <div class="border-r border-gray-200 dark:border-gray-800 flex flex-col h-full bg-gray-50/40 dark:bg-gray-950/20">
            <div class="p-4 border-b border-gray-200 dark:border-gray-800">
                <h3 class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">Conversations ({{ $sessions->count() }})</h3>
            </div>

            <div class="overflow-y-auto flex-1 divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($sessions as $sess)
                    @php
                        $name = $sess->user ? $sess->user->name : ($sess->session_id ? 'Guest (' . substr($sess->session_id, 0, 8) . ')' : 'Website Visitor');
                        $email = $sess->user ? $sess->user->email : 'Anonymous session';
                    @endphp
                    <a href="{{ route('admin.chats.index', ['session_id' => $sess->session_id]) }}" class="p-3.5 block hover:bg-white dark:hover:bg-gray-800 transition-colors {{ $activeSessionId === $sess->session_id ? 'bg-white dark:bg-gray-800 border-l-4 border-l-primary' : '' }}">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-primary/10 text-primary font-bold flex items-center justify-center text-sm shrink-0">
                                {{ strtoupper(substr($name, 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-bold text-xs text-gray-900 dark:text-white truncate">{{ $name }}</h4>
                                    <span class="text-[10px] text-gray-400 font-mono">{{ $sess->last_message_at ? \Carbon\Carbon::parse($sess->last_message_at)->format('H:i') : '' }}</span>
                                </div>
                                <p class="text-[11px] text-gray-500 truncate">{{ $email }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-8 text-center text-xs text-gray-400">No active chat sessions.</div>
                @endforelse
            </div>
        </div>

        <!-- Right: Active Chat Thread -->
        <div class="md:col-span-2 flex flex-col h-full bg-white dark:bg-gray-900">
            @if($activeSessionId)
                <!-- Chat Header -->
                <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-sm text-gray-900 dark:text-white">Session: {{ Str::limit($activeSessionId, 20) }}</h3>
                        <span class="inline-flex items-center gap-1 text-[10px] text-primary font-bold">
                            <span class="h-1.5 w-1.5 rounded-full bg-primary animate-pulse"></span> Active Conversation
                        </span>
                    </div>
                </div>

                <!-- Messages Thread -->
                <div class="flex-1 overflow-y-auto p-4 space-y-3">
                    @forelse($messages as $msg)
                        @php
                            $isAdmin = ($msg->sender === 'admin' || $msg->sender_type === 'admin');
                        @endphp
                        <div class="flex {{ $isAdmin ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[75%] rounded-2xl p-3 text-xs {{ $isAdmin ? 'bg-primary text-white rounded-br-none' : 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white rounded-bl-none' }}">
                                <p class="leading-relaxed">{{ $msg->message }}</p>
                                <span class="text-[9px] block mt-1 {{ $isAdmin ? 'text-white/70 text-right' : 'text-gray-400' }}">
                                    {{ $msg->created_at ? $msg->created_at->format('h:i A') : '' }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-xs text-gray-400">No messages exchanged yet.</div>
                    @endforelse
                </div>

                <!-- Reply Box -->
                <div class="p-3.5 border-t border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-950/20">
                    <form method="POST" action="{{ route('admin.chats.reply') }}" class="flex items-center gap-2">
                        @csrf
                        <input type="hidden" name="session_id" value="{{ $activeSessionId }}">
                        <input 
                            type="text" 
                            name="message" 
                            required 
                            placeholder="Type your response to the customer..." 
                            class="flex-1 px-4 py-2.5 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl text-xs text-gray-900 dark:text-white focus:outline-none focus:border-primary"
                        >
                        <button type="submit" class="px-5 py-2.5 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-md shadow-primary/20 transition-all cursor-pointer">
                            Send
                        </button>
                    </form>
                </div>
            @else
                <div class="flex-1 flex flex-col items-center justify-center text-gray-400 space-y-2 p-6">
                    <svg class="h-12 w-12 text-gray-300 dark:text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                    <p class="text-xs font-semibold">Select a conversation from the left to start replying.</p>
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
