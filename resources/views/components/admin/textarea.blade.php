@props([
    'label' => null,
    'name' => null,
    'rows' => 3,
    'placeholder' => null,
])

<div class="space-y-1">
    @if($label)
        <label for="{{ $attributes->get('id') ?? $name }}" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
            {{ $label }}
        </label>
    @endif

    <textarea name="{{ $name }}"
              id="{{ $attributes->get('id') ?? $name }}"
              rows="{{ $rows }}"
              placeholder="{{ $placeholder }}"
              {{ $attributes->merge(['class' => 'w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs p-2.5 rounded-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none text-slate-800 dark:text-slate-200 placeholder:text-slate-400 transition-colors']) }}>{{ $slot }}</textarea>

    @if($name)
        @error($name)
            <p class="text-[11px] text-rose-500 mt-1 font-medium">{{ $message }}</p>
        @enderror
    @endif
</div>
