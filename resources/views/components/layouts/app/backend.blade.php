<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">

        
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

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

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>
        
        

        
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