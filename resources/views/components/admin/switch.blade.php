@props([
    'label' => null,
    'description' => null,
    'id' => null,
])

@php
    $id = $id ?? 'switch-' . md5($attributes->wire('model')->value() ?: Str::random(8));
@endphp

<label for="{{ $id }}" class="inline-flex items-center gap-3 cursor-pointer select-none">
    <div class="relative inline-flex items-center">
        <input 
            type="checkbox" 
            id="{{ $id }}"
            {{ $attributes->merge(['class' => 'sr-only peer']) }}
        >
        <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-brand-500/30 dark:peer-focus:ring-brand-600/30 rounded-[4px] peer dark:bg-zinc-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-[3px] after:h-4 after:w-4 after:transition-all dark:border-zinc-600 peer-checked:bg-brand-600"></div>
    </div>
    @if ($label || $description)
        <div class="flex flex-col">
            @if ($label)
                <span class="text-xs font-semibold text-slate-700 dark:text-zinc-200">{{ $label }}</span>
            @endif
            @if ($description)
                <span class="text-[11px] text-slate-500 dark:text-zinc-400">{{ $description }}</span>
            @endif
        </div>
    @endif
</label>
