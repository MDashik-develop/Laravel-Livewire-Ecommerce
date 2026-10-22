@props([
    'title',
    'description' => null,
])

<div class="flex w-full flex-col text-center">
    <h2 class="text-lg font-bold text-slate-900 dark:text-zinc-100">{{ $title }}</h2>
    @if ($description)
        <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">{{ $description }}</p>
    @endif
</div>
