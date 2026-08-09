@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <!-- Top Bar & Breadcrumbs -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <x-breadcrumbs :items="[
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Messages']
            ]" />
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white mt-1">Customer Inquiries & Messages</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400">Manage contact inquiries submitted through your website.</p>
        </div>
        @if($unreadCount > 0)
            <form method="POST" action="{{ route('admin.messages.mark-all-read') }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-bold rounded-xl transition-colors">
                    Mark All As Read
                </button>
            </form>
        @endif
    </div>

    <!-- Messages Container -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Messages Table -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <div class="flex items-center gap-2 text-xs">
                    <a href="{{ route('admin.messages.index') }}" class="px-3 py-1 rounded-lg font-bold {{ !request('status') ? 'bg-primary text-white' : 'text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        All ({{ $totalCount }})
                    </a>
                    <a href="{{ route('admin.messages.index', ['status' => 'Unread']) }}" class="px-3 py-1 rounded-lg font-bold {{ request('status') === 'Unread' ? 'bg-primary text-white' : 'text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        Unread ({{ $unreadCount }})
                    </a>
                </div>
            </div>

            <div class="divide-y divide-gray-100 dark:divide-gray-800 text-xs">
                @forelse($messages as $msg)
                    <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800/40 transition-colors flex items-start justify-between gap-4 {{ $msg->status === 'Unread' ? 'bg-blue-50/20 dark:bg-blue-950/10 border-l-4 border-l-primary' : '' }}">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.messages.index', ['id' => $msg->id]) }}" class="font-bold text-sm text-gray-900 dark:text-white hover:text-primary transition-colors">
                                    {{ $msg->name }}
                                </a>
                                @if($msg->status === 'Unread')
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-primary/10 text-primary">New</span>
                                @endif
                            </div>
                            <p class="text-gray-500 text-[11px] font-mono">{{ $msg->email }} {{ $msg->phone ? '• ' . $msg->phone : '' }}</p>
                            <h4 class="font-semibold text-gray-800 dark:text-gray-200 mt-1 truncate">{{ $msg->subject }}</h4>
                            <p class="text-gray-500 dark:text-gray-400 line-clamp-2 mt-0.5">{{ $msg->message }}</p>
                            <span class="text-[10px] text-gray-400 font-mono mt-1.5 block">{{ $msg->created_at ? $msg->created_at->diffForHumans() : '' }}</span>
                        </div>

                        <div class="flex items-center gap-1 shrink-0">
                            <a href="{{ route('admin.messages.index', ['id' => $msg->id]) }}" class="p-1.5 text-gray-500 hover:text-primary rounded-lg" title="Open Message">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.messages.toggle-status', $msg->id) }}">
                                @csrf
                                <button type="submit" class="p-1.5 text-gray-500 hover:text-blue-600 rounded-lg" title="Toggle Read/Unread">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.messages.destroy', $msg->id) }}" onsubmit="return confirm('Delete message?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-500 hover:text-rose-600 rounded-lg" title="Delete">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-gray-400">No contact inquiries found.</div>
                @endforelse
            </div>

            @if($messages->hasPages())
                <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                    {{ $messages->links() }}
                </div>
            @endif
        </div>

        <!-- Right: Message Reader Drawer -->
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
            @if($activeMessage)
                <div class="space-y-4 text-xs">
                    <div class="border-b border-gray-100 dark:border-gray-800 pb-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-bold text-gray-900 dark:text-white">{{ $activeMessage->name }}</h3>
                            <span class="text-gray-400 text-[11px] font-mono">{{ $activeMessage->created_at ? $activeMessage->created_at->format('M d, Y H:i') : '' }}</span>
                        </div>
                        <a href="mailto:{{ $activeMessage->email }}" class="text-primary hover:underline font-mono block mt-0.5">{{ $activeMessage->email }}</a>
                        @if($activeMessage->phone)
                            <a href="tel:{{ $activeMessage->phone }}" class="text-gray-500 hover:underline font-mono block mt-0.5">{{ $activeMessage->phone }}</a>
                        @endif
                    </div>

                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Subject</span>
                        <h4 class="font-bold text-sm text-gray-900 dark:text-white">{{ $activeMessage->subject }}</h4>
                    </div>

                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Message Content</span>
                        <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-800/50 text-gray-800 dark:text-gray-200 leading-relaxed whitespace-pre-line text-xs border border-gray-100 dark:border-gray-800">
                            {{ $activeMessage->message }}
                        </div>
                    </div>

                    <div class="pt-4 flex items-center gap-3 border-t border-gray-100 dark:border-gray-800">
                        <a href="mailto:{{ $activeMessage->email }}?subject=Re: {{ urlencode($activeMessage->subject) }}" class="flex-1 py-2.5 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl text-center shadow-md shadow-primary/20">
                            Reply via Email
                        </a>
                    </div>
                </div>
            @else
                <div class="py-20 text-center text-gray-400 space-y-2">
                    <svg class="h-10 w-10 mx-auto text-gray-300 dark:text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    <p class="text-xs font-semibold">Select a message on the left to read full details.</p>
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
