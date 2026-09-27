@php
    $store = null;
    try {
        $store = \App\Models\Store::with('media')->first();
    } catch (\Throwable $e) {}
    $storeName = $store?->name ?: config('app.name', 'Laravel');
    $storeLogo = $store?->media?->url;
@endphp

@if ($storeLogo)
    <div class="flex aspect-square size-8 shrink-0 items-center justify-center rounded-lg bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 overflow-hidden shadow-2xs p-0.5">
        <img src="{{ $storeLogo }}" alt="{{ $storeName }}" class="h-full w-full object-contain rounded-md">
    </div>
@else
    <div class="flex aspect-square size-8 shrink-0 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
        <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
    </div>
@endif

<div class="ms-1.5 grid flex-1 text-start text-sm min-w-0">
    <span class="truncate leading-tight font-semibold text-gray-900 dark:text-white" title="{{ $storeName }}">
        {{ $storeName }}
    </span>
</div>
