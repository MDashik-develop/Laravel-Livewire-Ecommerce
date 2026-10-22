<div class="flex items-start max-md:flex-col gap-6">
    <div class="w-full md:w-56 shrink-0">
        <nav class="space-y-1">
            <a href="{{ route('settings.profile') }}" wire:navigate 
                class="flex items-center gap-2 px-3 py-2 text-xs font-semibold rounded-[3px] transition {{ request()->routeIs('settings.profile') ? 'bg-brand-50 text-brand-700 dark:bg-brand-950/50 dark:text-brand-300 border-l-2 border-brand-600' : 'text-slate-600 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-800' }}">
                <i data-lucide="user" class="w-3.5 h-3.5"></i>
                <span>{{ __('Profile') }}</span>
            </a>
            <a href="{{ route('settings.password') }}" wire:navigate 
                class="flex items-center gap-2 px-3 py-2 text-xs font-semibold rounded-[3px] transition {{ request()->routeIs('settings.password') ? 'bg-brand-50 text-brand-700 dark:bg-brand-950/50 dark:text-brand-300 border-l-2 border-brand-600' : 'text-slate-600 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-800' }}">
                <i data-lucide="key" class="w-3.5 h-3.5"></i>
                <span>{{ __('Password') }}</span>
            </a>
            <a href="{{ route('settings.appearance') }}" wire:navigate 
                class="flex items-center gap-2 px-3 py-2 text-xs font-semibold rounded-[3px] transition {{ request()->routeIs('settings.appearance') ? 'bg-brand-50 text-brand-700 dark:bg-brand-950/50 dark:text-brand-300 border-l-2 border-brand-600' : 'text-slate-600 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-800' }}">
                <i data-lucide="palette" class="w-3.5 h-3.5"></i>
                <span>{{ __('Appearance') }}</span>
            </a>
        </nav>
    </div>

    <div class="hidden md:block w-px bg-slate-200 dark:border-zinc-800 self-stretch"></div>

    <div class="flex-1 min-w-0 max-w-2xl">
        @if (!empty($heading))
            <h2 class="text-sm font-bold text-slate-900 dark:text-zinc-100 uppercase tracking-wider">{{ $heading }}</h2>
        @endif
        @if (!empty($subheading))
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5">{{ $subheading }}</p>
        @endif

        <div class="mt-4 w-full">
            {{ $slot }}
        </div>
    </div>
</div>
