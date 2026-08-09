<!DOCTYPE html>
<html lang="en" class="h-full" x-data="{
    darkMode: localStorage.getItem('theme') === 'dark',
    sidebarCollapsed: false,
    mobileOpen: false,
    theme: localStorage.getItem('theme') || 'light',
    setTheme(val) {
        this.theme = val;
        window.toggleDarkMode(val);
    }
}" :class="{ 'dark': theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches) }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Panel' }} — Shoukhin Fashion</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght,wdth,slnt,grad,ROND@8..144,100..1000,75..125,-12..0,0,0..1&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-white antialiased transition-colors duration-200 flex overflow-x-hidden">

    @php
        $pendingOrdersCount = \App\Models\Order::where('status', 'pending')->count();
        $recentPendingOrders = \App\Models\Order::where('status', 'pending')->latest()->take(5)->get();
        $unreadMessagesCount = \App\Models\ContactMessage::where('status', 'Unread')->count();
        $recentMessages = \App\Models\ContactMessage::latest()->take(5)->get();
        $pendingUsersCount = \App\Models\User::where('status', 'pending')->count();
        $recentPendingUsers = \App\Models\User::where('status', 'pending')->latest()->take(5)->get();
        $currentUser = Auth::user();
    @endphp

    <!-- DESKTOP SIDEBAR -->
    <aside 
        class="hidden md:flex flex-col fixed inset-y-0 left-0 z-30 border-r border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 transition-all duration-300"
        :class="sidebarCollapsed ? 'w-16' : 'w-64'"
    >
        <!-- Sidebar Header -->
        <div class="flex items-center h-16 px-4 border-b border-gray-200 dark:border-gray-800" :class="sidebarCollapsed ? 'justify-center' : 'justify-between px-6'">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 font-bold text-lg">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-primary text-white shadow-md shadow-primary/20 shrink-0">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z" />
                    </svg>
                </div>
                <span x-show="!sidebarCollapsed" class="tracking-tight text-gray-900 dark:text-white font-bold truncate">Shoukhin</span>
            </a>
        </div>

        <!-- Sidebar Navigation Menu -->
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto overflow-x-hidden">
            <!-- Dashboard -->
            <a 
                href="{{ route('admin.dashboard') }}" 
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white shadow-md shadow-primary/15' : 'text-gray-600 dark:text-gray-300 hover:bg-primary/10 hover:text-primary' }}"
                :class="sidebarCollapsed && 'justify-center px-2'"
                title="Dashboard"
            >
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                <span x-show="!sidebarCollapsed">Dashboard</span>
            </a>

            <!-- Product Menu (Collapsible) -->
            <div x-data="{ open: {{ request()->is('admin/products*') ? 'true' : 'false' }} }" class="space-y-1">
                <button 
                    @click="if(sidebarCollapsed) { sidebarCollapsed = false; open = true; } else { open = !open; }" 
                    type="button" 
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->is('admin/products*') ? 'bg-gray-100 dark:bg-gray-800/60 text-primary font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-primary/10 hover:text-primary' }}"
                    :class="sidebarCollapsed && 'justify-center px-2'"
                    title="Products"
                >
                    <div class="flex items-center gap-3">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                        </svg>
                        <span x-show="!sidebarCollapsed">Products</span>
                    </div>
                    <svg x-show="!sidebarCollapsed" class="h-4 w-4 text-gray-400 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open && !sidebarCollapsed" x-collapse class="pl-4 pr-1 py-1 space-y-1 text-xs">
                    <a href="{{ route('admin.products.index') }}" class="flex items-center px-3 py-2 rounded-xl border-l-4 transition-all {{ request()->routeIs('admin.products.index') ? 'bg-green-50 dark:bg-green-950/20 text-primary font-semibold border-primary' : 'text-gray-500 dark:text-gray-400 hover:text-primary border-transparent pl-4' }}">
                        <span class="mr-1.5 font-bold">○</span> All Products
                    </a>
                    <a href="{{ route('admin.products.create') }}" class="flex items-center px-3 py-2 rounded-xl border-l-4 transition-all {{ request()->routeIs('admin.products.create') ? 'bg-green-50 dark:bg-green-950/20 text-primary font-semibold border-primary' : 'text-gray-500 dark:text-gray-400 hover:text-primary border-transparent pl-4' }}">
                        <span class="mr-1.5 font-bold">○</span> Add Product
                    </a>
                </div>
            </div>

            <!-- Categories Menu (Collapsible) -->
            <div x-data="{ open: {{ (request()->is('admin/categories*') || request()->is('admin/sub-categories*')) ? 'true' : 'false' }} }" class="space-y-1">
                <button 
                    @click="if(sidebarCollapsed) { sidebarCollapsed = false; open = true; } else { open = !open; }" 
                    type="button" 
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ (request()->is('admin/categories*') || request()->is('admin/sub-categories*')) ? 'bg-gray-100 dark:bg-gray-800/60 text-primary font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-primary/10 hover:text-primary' }}"
                    :class="sidebarCollapsed && 'justify-center px-2'"
                    title="Categories"
                >
                    <div class="flex items-center gap-3">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75 2.25 12l4.179 2.25m0-4.5 5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0 4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0-5.571 3-5.571-3" />
                        </svg>
                        <span x-show="!sidebarCollapsed">Categories</span>
                    </div>
                    <svg x-show="!sidebarCollapsed" class="h-4 w-4 text-gray-400 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open && !sidebarCollapsed" x-collapse class="pl-4 pr-1 py-1 space-y-1 text-xs">
                    <a href="{{ route('admin.categories.index') }}" class="flex items-center px-3 py-2 rounded-xl border-l-4 transition-all {{ request()->routeIs('admin.categories.index') ? 'bg-green-50 dark:bg-green-950/20 text-primary font-semibold border-primary' : 'text-gray-500 dark:text-gray-400 hover:text-primary border-transparent pl-4' }}">
                        <span class="mr-1.5 font-bold">○</span> Main Categories
                    </a>
                    <a href="{{ route('admin.categories.create') }}" class="flex items-center px-3 py-2 rounded-xl border-l-4 transition-all {{ request()->routeIs('admin.categories.create') ? 'bg-green-50 dark:bg-green-950/20 text-primary font-semibold border-primary' : 'text-gray-500 dark:text-gray-400 hover:text-primary border-transparent pl-4' }}">
                        <span class="mr-1.5 font-bold">○</span> Add Category
                    </a>
                    <a href="{{ route('admin.sub-categories.index') }}" class="flex items-center px-3 py-2 rounded-xl border-l-4 transition-all {{ request()->routeIs('admin.sub-categories.index') ? 'bg-green-50 dark:bg-green-950/20 text-primary font-semibold border-primary' : 'text-gray-500 dark:text-gray-400 hover:text-primary border-transparent pl-4' }}">
                        <span class="mr-1.5 font-bold">○</span> Sub Categories
                    </a>
                    <a href="{{ route('admin.sub-categories.create') }}" class="flex items-center px-3 py-2 rounded-xl border-l-4 transition-all {{ request()->routeIs('admin.sub-categories.create') ? 'bg-green-50 dark:bg-green-950/20 text-primary font-semibold border-primary' : 'text-gray-500 dark:text-gray-400 hover:text-primary border-transparent pl-4' }}">
                        <span class="mr-1.5 font-bold">○</span> Add Sub Category
                    </a>
                </div>
            </div>

            <!-- Attributes Menu (Collapsible) -->
            <div x-data="{ open: {{ request()->is('admin/attributes*') ? 'true' : 'false' }} }" class="space-y-1">
                <button 
                    @click="if(sidebarCollapsed) { sidebarCollapsed = false; open = true; } else { open = !open; }" 
                    type="button" 
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->is('admin/attributes*') ? 'bg-gray-100 dark:bg-gray-800/60 text-primary font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-primary/10 hover:text-primary' }}"
                    :class="sidebarCollapsed && 'justify-center px-2'"
                    title="Attributes"
                >
                    <div class="flex items-center gap-3">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                        </svg>
                        <span x-show="!sidebarCollapsed">Attributes</span>
                    </div>
                    <svg x-show="!sidebarCollapsed" class="h-4 w-4 text-gray-400 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open && !sidebarCollapsed" x-collapse class="pl-4 pr-1 py-1 space-y-1 text-xs">
                    <a href="{{ route('admin.attributes.index') }}" class="flex items-center px-3 py-2 rounded-xl border-l-4 transition-all {{ request()->routeIs('admin.attributes.index') ? 'bg-green-50 dark:bg-green-950/20 text-primary font-semibold border-primary' : 'text-gray-500 dark:text-gray-400 hover:text-primary border-transparent pl-4' }}">
                        <span class="mr-1.5 font-bold">○</span> Attributes List
                    </a>
                    <a href="{{ route('admin.attributes.create') }}" class="flex items-center px-3 py-2 rounded-xl border-l-4 transition-all {{ request()->routeIs('admin.attributes.create') ? 'bg-green-50 dark:bg-green-950/20 text-primary font-semibold border-primary' : 'text-gray-500 dark:text-gray-400 hover:text-primary border-transparent pl-4' }}">
                        <span class="mr-1.5 font-bold">○</span> Add Attribute
                    </a>
                </div>
            </div>

            <!-- Orders Menu (Collapsible) -->
            <div x-data="{ open: {{ request()->is('admin/orders*') ? 'true' : 'false' }} }" class="space-y-1">
                <button 
                    @click="if(sidebarCollapsed) { sidebarCollapsed = false; open = true; } else { open = !open; }" 
                    type="button" 
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->is('admin/orders*') ? 'bg-gray-100 dark:bg-gray-800/60 text-primary font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-primary/10 hover:text-primary' }}"
                    :class="sidebarCollapsed && 'justify-center px-2'"
                    title="Orders"
                >
                    <div class="flex items-center gap-3">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>
                        <span x-show="!sidebarCollapsed">Orders</span>
                    </div>
                    <div class="flex items-center gap-1.5" x-show="!sidebarCollapsed">
                        @if($pendingOrdersCount > 0)
                            <span class="px-1.5 py-0.5 text-[10px] font-bold bg-amber-500 text-white rounded-full">{{ $pendingOrdersCount }}</span>
                        @endif
                        <svg class="h-4 w-4 text-gray-400 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </button>
                <div x-show="open && !sidebarCollapsed" x-collapse class="pl-4 pr-1 py-1 space-y-1 text-xs">
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center px-3 py-2 rounded-xl border-l-4 transition-all {{ request()->routeIs('admin.orders.index') ? 'bg-green-50 dark:bg-green-950/20 text-primary font-semibold border-primary' : 'text-gray-500 dark:text-gray-400 hover:text-primary border-transparent pl-4' }}">
                        <span class="mr-1.5 font-bold">○</span> Order List
                    </a>
                    <a href="{{ route('admin.orders.payment-status') }}" class="flex items-center px-3 py-2 rounded-xl border-l-4 transition-all {{ request()->routeIs('admin.orders.payment-status') ? 'bg-green-50 dark:bg-green-950/20 text-primary font-semibold border-primary' : 'text-gray-500 dark:text-gray-400 hover:text-primary border-transparent pl-4' }}">
                        <span class="mr-1.5 font-bold">○</span> Payment Status
                    </a>
                    <a href="{{ route('admin.orders.order-status') }}" class="flex items-center px-3 py-2 rounded-xl border-l-4 transition-all {{ request()->routeIs('admin.orders.order-status') ? 'bg-green-50 dark:bg-green-950/20 text-primary font-semibold border-primary' : 'text-gray-500 dark:text-gray-400 hover:text-primary border-transparent pl-4' }}">
                        <span class="mr-1.5 font-bold">○</span> Order Status
                    </a>
                </div>
            </div>

            <!-- Customers -->
            <a 
                href="{{ route('admin.customers.index') }}" 
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->is('admin/customers*') ? 'bg-primary text-white shadow-md shadow-primary/15' : 'text-gray-600 dark:text-gray-300 hover:bg-primary/10 hover:text-primary' }}"
                :class="sidebarCollapsed && 'justify-center px-2'"
                title="Customers"
            >
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                </svg>
                <span x-show="!sidebarCollapsed">Customers</span>
            </a>

            <!-- Messages -->
            <a 
                href="{{ route('admin.messages.index') }}" 
                class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->is('admin/messages*') ? 'bg-primary text-white shadow-md shadow-primary/15' : 'text-gray-600 dark:text-gray-300 hover:bg-primary/10 hover:text-primary' }}"
                :class="sidebarCollapsed && 'justify-center px-2'"
                title="Messages"
            >
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>
                    <span x-show="!sidebarCollapsed">Messages</span>
                </div>
                @if($unreadMessagesCount > 0)
                    <span x-show="!sidebarCollapsed" class="px-1.5 py-0.5 text-[10px] font-bold bg-red-500 text-white rounded-full">{{ $unreadMessagesCount }}</span>
                @endif
            </a>

            <!-- Live Chat -->
            <a 
                href="{{ route('admin.chats.index') }}" 
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->is('admin/chats*') ? 'bg-primary text-white shadow-md shadow-primary/15' : 'text-gray-600 dark:text-gray-300 hover:bg-primary/10 hover:text-primary' }}"
                :class="sidebarCollapsed && 'justify-center px-2'"
                title="Live Chat"
            >
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a.75.75 0 0 1-.974-.94 4.093 4.093 0 0 0 .546-1.517A7.95 7.95 0 0 1 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                </svg>
                <span x-show="!sidebarCollapsed">Live Chat</span>
            </a>

            <!-- Reviews Menu (Collapsible) -->
            <div x-data="{ open: {{ request()->is('admin/reviews*') ? 'true' : 'false' }} }" class="space-y-1">
                <button 
                    @click="if(sidebarCollapsed) { sidebarCollapsed = false; open = true; } else { open = !open; }" 
                    type="button" 
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->is('admin/reviews*') ? 'bg-gray-100 dark:bg-gray-800/60 text-primary font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-primary/10 hover:text-primary' }}"
                    :class="sidebarCollapsed && 'justify-center px-2'"
                    title="Reviews"
                >
                    <div class="flex items-center gap-3">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                        </svg>
                        <span x-show="!sidebarCollapsed">Reviews</span>
                    </div>
                    <svg x-show="!sidebarCollapsed" class="h-4 w-4 text-gray-400 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open && !sidebarCollapsed" x-collapse class="pl-4 pr-1 py-1 space-y-1 text-xs">
                    <a href="{{ route('admin.reviews.index') }}" class="flex items-center px-3 py-2 rounded-xl border-l-4 transition-all {{ request()->routeIs('admin.reviews.index') ? 'bg-green-50 dark:bg-green-950/20 text-primary font-semibold border-primary' : 'text-gray-500 dark:text-gray-400 hover:text-primary border-transparent pl-4' }}">
                        <span class="mr-1.5 font-bold">○</span> Reviews List
                    </a>
                    <a href="{{ route('admin.reviews.create') }}" class="flex items-center px-3 py-2 rounded-xl border-l-4 transition-all {{ request()->routeIs('admin.reviews.create') ? 'bg-green-50 dark:bg-green-950/20 text-primary font-semibold border-primary' : 'text-gray-500 dark:text-gray-400 hover:text-primary border-transparent pl-4' }}">
                        <span class="mr-1.5 font-bold">○</span> Add Review
                    </a>
                </div>
            </div>

            <!-- Wishlist -->
            <a 
                href="{{ route('admin.wishlist.index') }}" 
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->is('admin/wishlist*') ? 'bg-primary text-white shadow-md shadow-primary/15' : 'text-gray-600 dark:text-gray-300 hover:bg-primary/10 hover:text-primary' }}"
                :class="sidebarCollapsed && 'justify-center px-2'"
                title="Wishlist"
            >
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                </svg>
                <span x-show="!sidebarCollapsed">Wishlist</span>
            </a>

            <!-- Partners Menu (Collapsible) -->
            <div x-data="{ open: {{ request()->is('admin/partners*') ? 'true' : 'false' }} }" class="space-y-1">
                <button 
                    @click="if(sidebarCollapsed) { sidebarCollapsed = false; open = true; } else { open = !open; }" 
                    type="button" 
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->is('admin/partners*') ? 'bg-gray-100 dark:bg-gray-800/60 text-primary font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-primary/10 hover:text-primary' }}"
                    :class="sidebarCollapsed && 'justify-center px-2'"
                    title="Partners"
                >
                    <div class="flex items-center gap-3">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                        </svg>
                        <span x-show="!sidebarCollapsed">Partners</span>
                    </div>
                    <svg x-show="!sidebarCollapsed" class="h-4 w-4 text-gray-400 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open && !sidebarCollapsed" x-collapse class="pl-4 pr-1 py-1 space-y-1 text-xs">
                    <a href="{{ route('admin.partners.index') }}" class="flex items-center px-3 py-2 rounded-xl border-l-4 transition-all {{ request()->routeIs('admin.partners.index') ? 'bg-green-50 dark:bg-green-950/20 text-primary font-semibold border-primary' : 'text-gray-500 dark:text-gray-400 hover:text-primary border-transparent pl-4' }}">
                        <span class="mr-1.5 font-bold">○</span> Partner List
                    </a>
                    <a href="{{ route('admin.partners.create') }}" class="flex items-center px-3 py-2 rounded-xl border-l-4 transition-all {{ request()->routeIs('admin.partners.create') ? 'bg-green-50 dark:bg-green-950/20 text-primary font-semibold border-primary' : 'text-gray-500 dark:text-gray-400 hover:text-primary border-transparent pl-4' }}">
                        <span class="mr-1.5 font-bold">○</span> Add Partner
                    </a>
                </div>
            </div>

            <!-- FAQs Menu (Collapsible) -->
            <div x-data="{ open: {{ (request()->is('admin/faqs*') || request()->is('admin/faq-categories*')) ? 'true' : 'false' }} }" class="space-y-1">
                <button 
                    @click="if(sidebarCollapsed) { sidebarCollapsed = false; open = true; } else { open = !open; }" 
                    type="button" 
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ (request()->is('admin/faqs*') || request()->is('admin/faq-categories*')) ? 'bg-gray-100 dark:bg-gray-800/60 text-primary font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-primary/10 hover:text-primary' }}"
                    :class="sidebarCollapsed && 'justify-center px-2'"
                    title="FAQs"
                >
                    <div class="flex items-center gap-3">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
                        </svg>
                        <span x-show="!sidebarCollapsed">FAQs</span>
                    </div>
                    <svg x-show="!sidebarCollapsed" class="h-4 w-4 text-gray-400 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open && !sidebarCollapsed" x-collapse class="pl-4 pr-1 py-1 space-y-1 text-xs">
                    <a href="{{ route('admin.faq-categories.index') }}" class="flex items-center px-3 py-2 rounded-xl border-l-4 transition-all {{ request()->routeIs('admin.faq-categories.index') ? 'bg-green-50 dark:bg-green-950/20 text-primary font-semibold border-primary' : 'text-gray-500 dark:text-gray-400 hover:text-primary border-transparent pl-4' }}">
                        <span class="mr-1.5 font-bold">○</span> FAQ Categories
                    </a>
                    <a href="{{ route('admin.faqs.index') }}" class="flex items-center px-3 py-2 rounded-xl border-l-4 transition-all {{ request()->routeIs('admin.faqs.index') ? 'bg-green-50 dark:bg-green-950/20 text-primary font-semibold border-primary' : 'text-gray-500 dark:text-gray-400 hover:text-primary border-transparent pl-4' }}">
                        <span class="mr-1.5 font-bold">○</span> FAQ List
                    </a>
                    <a href="{{ route('admin.faqs.create') }}" class="flex items-center px-3 py-2 rounded-xl border-l-4 transition-all {{ request()->routeIs('admin.faqs.create') ? 'bg-green-50 dark:bg-green-950/20 text-primary font-semibold border-primary' : 'text-gray-500 dark:text-gray-400 hover:text-primary border-transparent pl-4' }}">
                        <span class="mr-1.5 font-bold">○</span> Add FAQ
                    </a>
                </div>
            </div>

            <!-- Subscriptions -->
            <a 
                href="{{ route('admin.subscriptions.index') }}" 
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->is('admin/subscriptions*') ? 'bg-primary text-white shadow-md shadow-primary/15' : 'text-gray-600 dark:text-gray-300 hover:bg-primary/10 hover:text-primary' }}"
                :class="sidebarCollapsed && 'justify-center px-2'"
                title="Subscriptions"
            >
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                </svg>
                <span x-show="!sidebarCollapsed">Subscriptions</span>
            </a>

            <!-- Banners -->
            <a 
                href="{{ route('admin.banners.index') }}" 
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->is('admin/banners*') ? 'bg-primary text-white shadow-md shadow-primary/15' : 'text-gray-600 dark:text-gray-300 hover:bg-primary/10 hover:text-primary' }}"
                :class="sidebarCollapsed && 'justify-center px-2'"
                title="Banners"
            >
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
                <span x-show="!sidebarCollapsed">Banners</span>
            </a>

            <!-- Locations Menu (Collapsible) -->
            <div x-data="{ open: {{ request()->is('admin/locations*') ? 'true' : 'false' }} }" class="space-y-1">
                <button 
                    @click="if(sidebarCollapsed) { sidebarCollapsed = false; open = true; } else { open = !open; }" 
                    type="button" 
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->is('admin/locations*') ? 'bg-gray-100 dark:bg-gray-800/60 text-primary font-semibold' : 'text-gray-600 dark:text-gray-300 hover:bg-primary/10 hover:text-primary' }}"
                    :class="sidebarCollapsed && 'justify-center px-2'"
                    title="Locations"
                >
                    <div class="flex items-center gap-3">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        <span x-show="!sidebarCollapsed">Locations</span>
                    </div>
                    <svg x-show="!sidebarCollapsed" class="h-4 w-4 text-gray-400 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open && !sidebarCollapsed" x-collapse class="pl-4 pr-1 py-1 space-y-1 text-xs">
                    <a href="{{ route('admin.locations.divisions') }}" class="flex items-center px-3 py-2 rounded-xl border-l-4 transition-all {{ request()->routeIs('admin.locations.divisions') ? 'bg-green-50 dark:bg-green-950/20 text-primary font-semibold border-primary' : 'text-gray-500 dark:text-gray-400 hover:text-primary border-transparent pl-4' }}">
                        <span class="mr-1.5 font-bold">○</span> Divisions
                    </a>
                    <a href="{{ route('admin.locations.districts') }}" class="flex items-center px-3 py-2 rounded-xl border-l-4 transition-all {{ request()->routeIs('admin.locations.districts') ? 'bg-green-50 dark:bg-green-950/20 text-primary font-semibold border-primary' : 'text-gray-500 dark:text-gray-400 hover:text-primary border-transparent pl-4' }}">
                        <span class="mr-1.5 font-bold">○</span> Districts
                    </a>
                    <a href="{{ route('admin.locations.thanas') }}" class="flex items-center px-3 py-2 rounded-xl border-l-4 transition-all {{ request()->routeIs('admin.locations.thanas') ? 'bg-green-50 dark:bg-green-950/20 text-primary font-semibold border-primary' : 'text-gray-500 dark:text-gray-400 hover:text-primary border-transparent pl-4' }}">
                        <span class="mr-1.5 font-bold">○</span> Thanas
                    </a>
                </div>
            </div>

            <!-- About Page Settings -->
            <a 
                href="{{ route('admin.about.index') }}" 
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->is('admin/about*') ? 'bg-primary text-white shadow-md shadow-primary/15' : 'text-gray-600 dark:text-gray-300 hover:bg-primary/10 hover:text-primary' }}"
                :class="sidebarCollapsed && 'justify-center px-2'"
                title="About Page"
            >
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                </svg>
                <span x-show="!sidebarCollapsed">About Page</span>
            </a>

            <!-- Contact Settings -->
            <a 
                href="{{ route('admin.contact-settings.index') }}" 
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->is('admin/contact-settings*') ? 'bg-primary text-white shadow-md shadow-primary/15' : 'text-gray-600 dark:text-gray-300 hover:bg-primary/10 hover:text-primary' }}"
                :class="sidebarCollapsed && 'justify-center px-2'"
                title="Contact Page"
            >
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                </svg>
                <span x-show="!sidebarCollapsed">Contact Page</span>
            </a>

            <!-- Footer Settings -->
            <a 
                href="{{ route('admin.footer-settings.index') }}" 
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->is('admin/footer-settings*') ? 'bg-primary text-white shadow-md shadow-primary/15' : 'text-gray-600 dark:text-gray-300 hover:bg-primary/10 hover:text-primary' }}"
                :class="sidebarCollapsed && 'justify-center px-2'"
                title="Footer Settings"
            >
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
                </svg>
                <span x-show="!sidebarCollapsed">Footer Settings</span>
            </a>

            <!-- Users / Staff -->
            <a 
                href="{{ route('admin.users.index') }}" 
                class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->is('admin/users*') ? 'bg-primary text-white shadow-md shadow-primary/15' : 'text-gray-600 dark:text-gray-300 hover:bg-primary/10 hover:text-primary' }}"
                :class="sidebarCollapsed && 'justify-center px-2'"
                title="Users"
            >
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    <span x-show="!sidebarCollapsed">Users</span>
                </div>
                @if($pendingUsersCount > 0)
                    <span x-show="!sidebarCollapsed" class="px-1.5 py-0.5 text-[10px] font-bold bg-primary text-white rounded-full">{{ $pendingUsersCount }}</span>
                @endif
            </a>

            <!-- Settings -->
            <a 
                href="{{ route('admin.settings.index') }}" 
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->is('admin/settings*') ? 'bg-primary text-white shadow-md shadow-primary/15' : 'text-gray-600 dark:text-gray-300 hover:bg-primary/10 hover:text-primary' }}"
                :class="sidebarCollapsed && 'justify-center px-2'"
                title="Settings"
            >
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 0 1 0 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 0 1 0-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
                <span x-show="!sidebarCollapsed">Settings</span>
            </a>
        </nav>

        <!-- Sidebar Collapse Toggle Button -->
        <button 
            @click="sidebarCollapsed = !sidebarCollapsed" 
            type="button" 
            class="absolute -right-3 top-10 h-6 w-6 rounded-full border border-gray-200 dark:border-gray-800 shadow-md bg-white dark:bg-gray-900 text-gray-600 dark:text-gray-400 flex items-center justify-center cursor-pointer hover:bg-gray-50 z-40"
        >
            <svg x-show="!sidebarCollapsed" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            <svg x-show="sidebarCollapsed" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-gray-200 dark:border-gray-800">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button 
                    type="submit" 
                    class="w-full flex items-center gap-3 text-gray-500 dark:text-gray-400 hover:bg-red-50 dark:hover:bg-red-950/20 hover:text-red-600 text-sm font-medium justify-start px-3 py-2.5 rounded-xl transition-colors cursor-pointer"
                    :class="sidebarCollapsed && 'justify-center px-2'"
                    title="Logout"
                >
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                    </svg>
                    <span x-show="!sidebarCollapsed">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- MOBILE DRAWER BACKDROP & MENU -->
    <div 
        x-show="mobileOpen" 
        x-cloak 
        @click="mobileOpen = false" 
        class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm md:hidden transition-opacity"
    ></div>
    
    <div 
        x-show="mobileOpen" 
        x-cloak 
        class="fixed inset-y-0 left-0 z-50 w-72 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 flex flex-col md:hidden transition-transform"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
    >
        <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200 dark:border-gray-800">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 font-bold text-lg">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-primary text-white">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z" />
                    </svg>
                </div>
                <span>Shoukhin Fashion</span>
            </a>
            <button @click="mobileOpen = false" class="p-1 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="flex-1 px-4 py-6 overflow-y-auto space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-primary/10 hover:text-primary">Dashboard</a>
            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-primary/10 hover:text-primary">Products</a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-primary/10 hover:text-primary">Categories</a>
            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-primary/10 hover:text-primary">Orders</a>
            <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-primary/10 hover:text-primary">Customers</a>
            <a href="{{ route('admin.messages.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-primary/10 hover:text-primary">Messages</a>
            <a href="{{ route('admin.chats.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-primary/10 hover:text-primary">Live Chat</a>
            <a href="{{ route('admin.reviews.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-primary/10 hover:text-primary">Reviews</a>
            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-primary/10 hover:text-primary">Settings</a>
        </div>
    </div>

    <!-- MAIN WRAPPER -->
    <div 
        class="flex-1 flex flex-col min-h-screen transition-all duration-300"
        :class="sidebarCollapsed ? 'md:pl-16' : 'md:pl-64'"
    >
        <!-- STICKY TOPBAR -->
        <header class="sticky top-0 z-20 flex h-16 w-full items-center justify-between border-b border-gray-200 dark:border-gray-800 bg-white/95 dark:bg-gray-900/95 backdrop-blur-md px-4 md:px-6 shadow-sm">
            
            <!-- Left Area: Mobile Menu Toggle + Global Search -->
            <div class="flex items-center gap-4 flex-1 max-w-lg">
                <button @click="mobileOpen = true" type="button" class="md:hidden p-2 rounded-xl text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="relative w-full max-w-sm hidden sm:block">
                    <svg class="absolute top-2.5 left-3 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input 
                        type="text" 
                        placeholder="Search products, orders, customers..." 
                        class="w-full pl-9 pr-4 h-9 bg-gray-50 dark:bg-gray-950/50 border border-gray-200 dark:border-gray-800 rounded-xl text-xs focus:outline-none focus:border-primary text-gray-900 dark:text-white"
                    />
                </div>
            </div>

            <!-- Right Area: Theme Toggle, Notifications, User Menu -->
            <div class="flex items-center gap-2.5 sm:gap-3">
                
                <!-- Theme Switcher -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" type="button" class="h-9 w-9 flex items-center justify-center rounded-full text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <template x-if="theme === 'light'">
                            <svg class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        </template>
                        <template x-if="theme === 'dark'">
                            <svg class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                        </template>
                        <template x-if="theme === 'system'">
                            <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        </template>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-cloak class="absolute right-0 mt-2 w-36 bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-200 dark:border-gray-800 py-1 z-50 text-xs">
                        <button @click="setTheme('light'); open = false;" class="w-full flex items-center gap-2 px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-800 text-left">Light</button>
                        <button @click="setTheme('dark'); open = false;" class="w-full flex items-center gap-2 px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-800 text-left">Dark</button>
                        <button @click="setTheme('system'); open = false;" class="w-full flex items-center gap-2 px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-800 text-left">System</button>
                    </div>
                </div>

                <!-- Messages Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" type="button" class="relative h-9 w-9 flex items-center justify-center rounded-full text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        @if($unreadMessagesCount > 0)
                            <span class="absolute -top-1 -right-1 h-5 w-5 flex items-center justify-center p-0 bg-red-500 text-[10px] text-white border-2 border-white dark:border-gray-900 rounded-full font-bold">
                                {{ $unreadMessagesCount }}
                            </span>
                        @endif
                    </button>
                    <div x-show="open" @click.outside="open = false" x-cloak class="absolute right-0 mt-2 w-80 sm:w-96 bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-800 overflow-hidden z-50">
                        <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Messages</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $unreadMessagesCount }} unread messages</p>
                            </div>
                            <a href="{{ route('admin.messages.index') }}" class="text-xs font-semibold text-primary hover:underline">View All</a>
                        </div>
                        <div class="max-h-72 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-800 text-xs">
                            @forelse($recentMessages as $msg)
                                <a href="{{ route('admin.messages.index', ['id' => $msg->id]) }}" class="block p-3.5 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors {{ $msg->status === 'Unread' ? 'bg-blue-50/30 dark:bg-blue-950/15 border-l-4 border-l-blue-500' : '' }}">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-gray-900 dark:text-white">{{ $msg->name }}</span>
                                        <span class="text-[10px] text-gray-400">{{ $msg->created_at ? $msg->created_at->diffForHumans() : '' }}</span>
                                    </div>
                                    <p class="font-semibold text-gray-700 dark:text-gray-300 truncate mt-0.5">{{ $msg->subject }}</p>
                                    <p class="text-gray-500 dark:text-gray-400 line-clamp-1 mt-0.5">{{ $msg->message }}</p>
                                </a>
                            @empty
                                <div class="p-6 text-center text-gray-400">No new messages</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Admin Approvals Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" type="button" class="relative h-9 w-9 flex items-center justify-center rounded-full text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        @if($pendingUsersCount > 0)
                            <span class="absolute -top-1 -right-1 h-5 w-5 flex items-center justify-center p-0 bg-primary text-[10px] text-white border-2 border-white dark:border-gray-900 rounded-full font-bold">
                                {{ $pendingUsersCount }}
                            </span>
                        @endif
                    </button>
                    <div x-show="open" @click.outside="open = false" x-cloak class="absolute right-0 mt-2 w-80 sm:w-96 bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-800 overflow-hidden z-50">
                        <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Admin Approvals</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $pendingUsersCount }} pending requests</p>
                            </div>
                            <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-primary hover:underline">View All</a>
                        </div>
                        <div class="max-h-72 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-800 text-xs">
                            @forelse($recentPendingUsers as $u)
                                <div class="p-3.5 hover:bg-gray-50 dark:hover:bg-gray-800/50 flex items-center justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="font-bold text-gray-900 dark:text-white truncate">{{ $u->name }}</p>
                                        <p class="text-gray-500 text-[11px] truncate">{{ $u->email }}</p>
                                    </div>
                                    <form method="POST" action="{{ route('admin.users.update-status', $u->id) }}">
                                        @csrf
                                        <input type="hidden" name="status" value="active">
                                        <button type="submit" class="px-2.5 py-1 bg-green-50 text-green-700 dark:bg-green-950 dark:text-green-400 border border-green-200 dark:border-green-800 rounded-lg text-xs font-bold hover:bg-green-100 transition-colors">
                                            Approve
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <div class="p-6 text-center text-gray-400">No pending approvals</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Notifications (Pending Orders) -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" type="button" class="relative h-9 w-9 flex items-center justify-center rounded-full text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if($pendingOrdersCount > 0)
                            <span class="absolute -top-1 -right-1 h-5 w-5 flex items-center justify-center p-0 bg-primary text-[10px] text-white border-2 border-white dark:border-gray-900 rounded-full font-bold">
                                {{ $pendingOrdersCount }}
                            </span>
                        @endif
                    </button>
                    <div x-show="open" @click.outside="open = false" x-cloak class="absolute right-0 mt-2 w-80 sm:w-96 bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-800 overflow-hidden z-50">
                        <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Notifications</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $pendingOrdersCount }} pending orders</p>
                            </div>
                            <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-primary hover:underline">View All</a>
                        </div>
                        <div class="max-h-72 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-800 text-xs">
                            @forelse($recentPendingOrders as $order)
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="block p-3.5 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-gray-900 dark:text-white">Order #{{ $order->order_number }}</span>
                                        <span class="font-bold text-primary">৳{{ number_format($order->total, 2) }}</span>
                                    </div>
                                    <p class="text-gray-500 mt-0.5">{{ $order->customer_name ?? 'Customer' }} ({{ $order->customer_phone ?? 'N/A' }})</p>
                                    <span class="inline-block mt-1 px-2 py-0.5 bg-amber-50 text-amber-700 dark:bg-amber-950 dark:text-amber-400 rounded-full text-[10px] font-bold">Pending</span>
                                </a>
                            @empty
                                <div class="p-6 text-center text-gray-400">No new order notifications</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Admin Profile Menu -->
                <div x-data="{ open: false }" class="relative ml-1">
                    <button @click="open = !open" type="button" class="flex items-center gap-2 pl-2 pr-3 h-9 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <div class="flex h-7 w-7 items-center justify-center rounded-full bg-primary/15 text-primary font-bold text-xs">
                            {{ strtoupper(substr($currentUser->name ?? 'A', 0, 1)) }}
                        </div>
                        <span class="text-xs font-semibold text-gray-800 dark:text-gray-200 hidden sm:inline-block">
                            {{ $currentUser->name ?? 'Administrator' }}
                        </span>
                        <svg class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-cloak class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-800 py-2 z-50">
                        <div class="px-4 py-2.5 border-b border-gray-100 dark:border-gray-800">
                            <p class="text-xs font-bold text-gray-900 dark:text-white truncate">{{ $currentUser->name ?? 'Admin' }}</p>
                            <p class="text-[11px] text-gray-500 truncate">{{ $currentUser->email ?? 'admin@shoukhin.com' }}</p>
                        </div>
                        <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-2 px-4 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            Profile & Settings
                        </a>
                        <div class="border-t border-gray-100 dark:border-gray-800 my-1"></div>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-xs text-red-600 hover:bg-red-50 dark:hover:bg-red-950/20 text-left">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </header>

        <!-- FLASH NOTIFICATIONS -->
        @if(session('success'))
            <div class="mx-4 md:mx-8 mt-4 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 flex items-center justify-between text-emerald-800 dark:text-emerald-300 text-sm">
                <div class="flex items-center gap-2.5 font-medium">
                    <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('success') }}
                </div>
                <button type="button" @click="$el.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="mx-4 md:mx-8 mt-4 p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 flex items-center justify-between text-rose-800 dark:text-rose-300 text-sm">
                <div class="flex items-center gap-2.5 font-medium">
                    <svg class="h-5 w-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('error') }}
                </div>
                <button type="button" @click="$el.parentElement.remove()" class="text-rose-500 hover:text-rose-800">&times;</button>
            </div>
        @endif

        <!-- MAIN PAGE CONTENT -->
        <main class="flex-1 p-4 md:p-8 overflow-y-auto">
            @yield('content')
        </main>
    </div>

</body>
</html>
