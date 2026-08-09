@props([
    'title',
    'value',
    'icon' => null,
    'description' => null,
    'trend' => null,
    'isPositive' => true,
])

<div class="overflow-hidden border-l-4 border-l-primary border-y border-r border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-sm">
    <div class="flex items-center justify-between pb-2">
        <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
            {{ $title }}
        </span>
        @if($icon)
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-primary/10 text-primary">
                {{ $icon }}
            </div>
        @endif
    </div>
    <div class="mt-2">
        <div class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $value }}</div>
        @if($description || $trend)
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 flex items-center gap-1.5">
                @if($trend)
                    <span class="font-bold {{ $isPositive ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                        {{ $trend }}
                    </span>
                @endif
                @if($description)
                    <span>{{ $description }}</span>
                @endif
            </p>
        @endif
    </div>
</div>
