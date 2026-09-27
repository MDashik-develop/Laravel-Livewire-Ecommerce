<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">

        
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between gap-2 mb-1">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 rtl:space-x-reverse min-w-0 flex-1" wire:navigate>
                    <x-app-logo />
                </a>
                <flux:sidebar.toggle class="lg:!hidden cursor-pointer shrink-0" icon="x-mark" />
            </div>

            <livewire:layouts.backend.sidebar-nav-items />

            <flux:spacer />

            <flux:navlist variant="outline">
                <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                {{ __('Repository') }}
                </flux:navlist.item>

                <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                {{ __('Documentation') }}
                </flux:navlist.item>
            </flux:navlist>

            <!-- Desktop & Mobile Permanent User Menu in Sidebar -->
            @if (auth()->check())
                <div class="pt-2 border-t border-gray-200 dark:border-zinc-700/80">
                    <flux:dropdown position="top" align="start" class="w-full">
                        <button type="button" class="flex items-center gap-3 p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-zinc-800 transition cursor-pointer group w-full text-start">
                            <span class="relative flex h-9 w-9 shrink-0 overflow-hidden rounded-xl bg-indigo-600 text-white font-bold items-center justify-center text-xs shadow-xs">
                                @if (auth()->user()->avatar_url)
                                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                                @else
                                    {{ auth()->user()->initials() }}
                                @endif
                            </span>
                            <div class="flex-1 min-w-0">
                                <div class="truncate text-xs font-bold text-gray-800 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400">
                                    {{ auth()->user()->name }}
                                </div>
                                <div class="truncate text-[11px] text-gray-400">
                                    {{ auth()->user()->email }}
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 shrink-0 group-hover:text-gray-600 dark:group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                            </svg>
                        </button>

                        <flux:menu class="w-60">
                            <div class="px-3 py-2 border-b border-gray-100 dark:border-zinc-700">
                                <p class="text-xs font-bold text-gray-800 dark:text-gray-100">{{ auth()->user()->name }}</p>
                                <p class="text-[11px] text-gray-400 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <flux:menu.item :href="route('settings.profile')" icon="user" wire:navigate>{{ __('My Profile') }}</flux:menu.item>
                            <flux:menu.item :href="route('settings.password')" icon="key" wire:navigate>{{ __('Password') }}</flux:menu.item>
                            <flux:menu.item :href="route('settings.appearance')" icon="paint-brush" wire:navigate>{{ __('Appearance') }}</flux:menu.item>
                            <flux:menu.separator />
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full text-rose-600 dark:text-rose-400">
                                    {{ __('Log Out') }}
                                </flux:menu.item>
                            </form>
                        </flux:menu>
                    </flux:dropdown>
                </div>
            @endif
        </flux:sidebar>

        <!-- Global Header (Live Clock, Search Pages, Notifications, Messages, Profile, Refresh) -->
        <livewire:layouts.backend.global-header />

        {{ $slot }}

        <livewire:media />

        @fluxScripts

        <!-- Livewire lifecycle re-initialization system -->
        <script data-navigate-once>
            function initializePageContent() {
                if (!window.jQuery || !window.jQuery.fn.summernote) return;

                // Defer to next frame so clicks and DOM updates complete smoothly
                requestAnimationFrame(function () {
                    $('.summernote-init:visible').each(function () {
                        let $el = $(this);
                        if (!$el.next('.note-editor').length) {
                            let height = $el.data('height') || 220;
                            $el.summernote({
                                height: height,
                                placeholder: $el.attr('placeholder') || 'Write content...',
                                toolbar: [
                                    ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                                    ['font', ['strikethrough']],
                                    ['para', ['ul', 'ol', 'paragraph']],
                                    ['insert', ['link', 'table']],
                                    ['view', ['fullscreen', 'codeview']]
                                ]
                            });
                        }
                    });
                });
            }

            function syncSummernoteBeforeSave() {
                if (window.jQuery && window.jQuery.fn.summernote) {
                    $('.summernote-init').each(function () {
                        let $el = $(this);
                        let field = $el.data('field');
                        if ($el.next('.note-editor').length && field) {
                            let code = $el.summernote('code');
                            let comp = $el.closest('[wire\\:id]');
                            if (comp.length && window.Livewire) {
                                let livewireComp = Livewire.find(comp.attr('wire:id'));
                                if (livewireComp) {
                                    livewireComp.set(field, code, false);
                                }
                            }
                        }
                    });
                }
            }

            // Register lifecycle events exactly as requested, protected against duplicate stacking
            if (!window.__app_lifecycle_initialized) {
                window.__app_lifecycle_initialized = true;

                document.addEventListener('DOMContentLoaded', initializePageContent);
                document.addEventListener('livewire:load', initializePageContent);
                document.addEventListener('livewire:init', initializePageContent);
                document.addEventListener('livewire:updated', initializePageContent);
                document.addEventListener('livewire:navigated', initializePageContent);

                // Clean up before navigating to prevent memory traps and detached DOM
                document.addEventListener('livewire:navigating', function () {
                    if (window.jQuery && window.jQuery.fn.summernote) {
                        $('.summernote-init').each(function () {
                            if ($(this).next('.note-editor').length) {
                                try {
                                    $(this).summernote('destroy');
                                } catch(e) {}
                            }
                        });
                    }
                });
            }
        </script>
    </body>
</html>