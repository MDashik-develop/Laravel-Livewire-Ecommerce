@props([
    'title' => null,
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-sm shadow-2xs overflow-hidden']) }}>
    @if($title || $description || isset($actions))
        <div class="p-3.5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/40">
            <div>
                @if($title)
                    <h2 class="font-bold text-xs uppercase tracking-wider text-slate-800 dark:text-slate-200">{{ $title }}</h2>
                @endif
                @if($description)
                    <p class="text-[11px] text-slate-400 mt-0.5">{{ $description }}</p>
                @endif
            </div>
            @if(isset($actions))
                <div class="flex items-center gap-2">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif

    {{ $slot }}
</div>
