@props([
    'items' => [],
])

<nav {{ $attributes->merge(['class' => 'flex items-center space-x-1.5 text-xs text-slate-500 font-medium flex-wrap']) }}>
    <a href="{{ route('dashboard') }}" wire:navigate class="hover:text-slate-900 dark:hover:text-white transition-colors">
        Admin Hub
    </a>
    @foreach($items as $key => $item)
        @php
            if (is_array($item)) {
                $itemLabel = $item['label'] ?? $item['title'] ?? $item['name'] ?? '';
                $itemUrl = $item['url'] ?? $item['href'] ?? null;
            } elseif (is_numeric($key)) {
                $itemLabel = (string) $item;
                $itemUrl = null;
            } else {
                $itemLabel = (string) $key;
                $itemUrl = !empty($item) ? (string) $item : null;
            }
        @endphp
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400 shrink-0"></i>
        @if($itemUrl)
            <a href="{{ $itemUrl }}" wire:navigate class="hover:text-slate-900 dark:hover:text-white transition-colors">
                {{ $itemLabel }}
            </a>
        @else
            <span class="text-slate-900 dark:text-slate-100 font-semibold bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded-xs border border-slate-200 dark:border-slate-700">
                {{ $itemLabel }}
            </span>
        @endif
    @endforeach
</nav>
