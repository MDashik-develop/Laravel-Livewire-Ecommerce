@props([
    'name',
    'title' => null,
    'description' => null,
    'maxWidth' => 'md',
])

@php
$size = strtolower(trim(str_replace(['sm:max-w-', 'max-w-'], '', (string)$maxWidth)));

$maxWidthClasses = [
    'xs'   => 'sm:max-w-sm',
    'sm'   => 'sm:max-w-md',
    'md'   => 'sm:max-w-xl',
    'lg'   => 'sm:max-w-2xl',
    'xl'   => 'sm:max-w-3xl',
    '2xl'  => 'sm:max-w-4xl',
    '3xl'  => 'sm:max-w-5xl',
    '4xl'  => 'sm:max-w-6xl',
    '5xl'  => 'w-[94vw] max-w-6xl xl:max-w-7xl',
    '6xl'  => 'w-[96vw] max-w-7xl',
    '7xl'  => 'w-[96vw] max-w-[1360px]',
    'full' => 'w-[98vw] max-w-[1440px]',
][$size] ?? (str_starts_with($maxWidth, 'max-w-') ? "sm:$maxWidth" : 'sm:max-w-3xl');
@endphp

<div x-data="{ open: false }"
     x-cloak
     x-show="open"
     @modal-show.window="let n = $event.detail?.name ?? ($event.detail?.[0]?.name ?? $event.detail); if (n === '{{ $name }}') { open = true; $nextTick(() => { if (window.lucide) window.lucide.createIcons(); }); }"
     @modal-close.window="let n = $event.detail?.name ?? ($event.detail?.[0]?.name ?? $event.detail); if (!n || n === '{{ $name }}') { open = false; }"
     @open-modal.window="if ($event.detail === '{{ $name }}' || $event.detail?.name === '{{ $name }}') open = true"
     @close-modal.window="if (!$event.detail || $event.detail === '{{ $name }}' || $event.detail?.name === '{{ $name }}') open = false"
     @keydown.escape.window="open = false"
     class="fixed inset-0 z-50 overflow-y-auto p-3 sm:p-6 flex items-center justify-center">

    <!-- Backdrop with blur -->
    <div x-show="open"
         x-transition:enter="ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="open = false"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

    <!-- Modal Dialog Box (Spacious, responsive, Max 3-4px border radius) -->
    <div x-show="open"
         x-transition:enter="ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         class="w-full {{ $maxWidthClasses }} max-h-[92vh] flex flex-col bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-sm shadow-2xl overflow-hidden relative z-10 transition-all text-slate-800 dark:text-slate-100">

        @if($title || $description)
            <div class="px-5 py-3.5 border-b border-slate-100 dark:border-slate-800 flex items-start justify-between bg-slate-50/60 dark:bg-slate-800/40 shrink-0">
                <div>
                    @if($title)
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white">{{ $title }}</h3>
                    @endif
                    @if($description)
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">{{ $description }}</p>
                    @endif
                </div>
                <button type="button" @click="open = false" class="text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 p-1 rounded-xs hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        @endif

        <div class="p-5 overflow-y-auto flex-1">
            {{ $slot }}
        </div>
    </div>
</div>
