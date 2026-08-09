@props(['items' => []])

<nav aria-label="Breadcrumb" class="flex items-center space-x-1.5 text-sm text-gray-500 dark:text-gray-400">
    @foreach($items as $index => $item)
        @if(!empty($item['url']) && !$loop->last)
            <a href="{{ $item['url'] }}" class="hover:text-primary transition-colors font-medium">
                {{ $item['label'] }}
            </a>
        @else
            <span class="{{ $loop->last ? 'text-gray-900 dark:text-white font-semibold' : '' }}">
                {{ $item['label'] }}
            </span>
        @endif

        @if(!$loop->last)
            <svg class="h-3.5 w-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
        @endif
    @endforeach
</nav>
