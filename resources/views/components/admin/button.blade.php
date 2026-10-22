@props([
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'type' => 'button',
])

@php
$baseClasses = 'inline-flex items-center justify-center gap-1.5 font-semibold transition-colors rounded-sm cursor-pointer border shadow-2xs whitespace-nowrap align-middle';

$sizeClasses = [
    'xs' => 'text-[10px] px-2 py-1',
    'sm' => 'text-[11px] px-2.5 py-1.5',
    'md' => 'text-xs px-3.5 py-2',
    'lg' => 'text-sm px-4 py-2.5',
][$size] ?? 'text-xs px-3.5 py-2';

$variantClasses = [
    'primary'   => 'bg-brand-600 hover:bg-brand-700 text-white border-brand-600 focus:ring-1 focus:ring-brand-500',
    'secondary' => 'bg-emerald-600 hover:bg-emerald-700 text-white border-emerald-600',
    'danger'    => 'bg-rose-600 hover:bg-rose-700 text-white border-rose-600',
    'warning'   => 'bg-amber-600 hover:bg-amber-700 text-white border-amber-600',
    'outline'   => 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/60',
    'ghost'     => 'bg-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 border-transparent shadow-none',
][$variant] ?? 'bg-brand-600 hover:bg-brand-700 text-white border-brand-600';
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "$baseClasses $sizeClasses $variantClasses"]) }}>
    @if($icon)
        <i data-lucide="{{ $icon }}" class="w-3.5 h-3.5 shrink-0"></i>
    @endif
    @if(!$slot->isEmpty())
        <span>{{ $slot }}</span>
    @endif
</button>
