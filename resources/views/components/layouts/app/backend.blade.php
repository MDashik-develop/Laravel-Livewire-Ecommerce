<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <title>{{ $title ?? config('app.name', 'ShopNest Enterprise Admin') }}</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <!-- Fonts: Geist & JetBrains Mono (from demo.html) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Lucide Icons (from demo.html) -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- jQuery & Summernote Lite (Rich Text Editor) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        dialog:not([open]) {
            display: none !important;
            pointer-events: none !important;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>
<body class="h-full bg-slate-100/70 dark:bg-slate-950 text-slate-800 dark:text-slate-100 font-sans text-sm selection:bg-brand-600 selection:text-white antialiased overflow-hidden">

    <!-- Mobile Off-Canvas Backdrop -->
    <div id="mobileBackdrop" onclick="toggleMobileSidebar()" class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-xs hidden opacity-0 transition-opacity duration-200 md:hidden"></div>

    <div class="flex h-screen overflow-hidden">

        <!-- ENTERPRISE SIDEBAR (Sharp / low border radius matching demo.html) -->
        <aside id="mainSidebar" class="fixed md:static inset-y-0 left-0 z-50 flex flex-col bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800/80 shadow-lg md:shadow-none w-64 md:w-64 sidebar-transition -translate-x-full md:translate-x-0 shrink-0">

            <!-- Top Brand Header with integrated collapse button -->
            <div id="sidebarHeader" class="h-14 px-3.5 flex items-center justify-between border-b border-slate-200 dark:border-slate-800/80 shrink-0 bg-slate-50/50 dark:bg-slate-900/50 transition-all duration-200">

                <!-- Logo Section (Logo mark + brand title) - will be completely hidden in rail mode -->
                <a href="{{ route('dashboard') }}" wire:navigate id="sidebarBrandBlock" class="flex items-center gap-2.5 overflow-hidden transition-all duration-200">
                    <!-- Logo symbol (Low radius rounded-sm) -->
                    <div class="h-8 w-8 shrink-0 rounded-sm bg-gradient-to-br from-brand-600 to-indigo-700 flex items-center justify-center text-white shadow-xs font-bold text-base tracking-tighter">
                        <span>AX</span>
                    </div>
                    <!-- Brand title -->
                    <div class="sidebar-text truncate leading-tight">
                        <div class="flex items-center gap-1.5">
                            <span class="font-bold text-xs tracking-wider uppercase text-slate-900 dark:text-white">APEX CORE</span>
                            <span class="text-[9px] px-1 py-0.5 font-semibold bg-brand-50 text-brand-700 dark:bg-brand-950 dark:text-brand-400 border border-brand-200 dark:border-brand-800/80 rounded-xs">v3.2</span>
                        </div>
                        <p class="text-[10px] text-slate-500 font-mono tracking-tight">CMS & Config Hub</p>
                    </div>
                </a>

                <!-- Top Sidebar Collapse Toggle Button (beside logo when expanded, centered when collapsed) -->
                <div id="sidebarCollapseWrapper" class="flex items-center justify-center">
                    <button type="button" id="sidebarCollapseBtn" onclick="toggleSidebarRail()" title="Collapse / Expand Sidebar" class="hidden md:inline-flex p-1.5 rounded-sm border border-slate-200 dark:border-slate-700/80 text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                        <i id="collapseIcon" data-lucide="panel-left-close" class="w-4 h-4"></i>
                    </button>
                    {{-- <button type="button" onclick="toggleMobileSidebar()" class="md:hidden p-1.5 rounded-sm text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button> --}}
                </div>
            </div>

            <!-- Quick Command Bar / Search (Hidden in rail mode) -->
            <div class="p-2.5 sidebar-text border-b border-slate-100 dark:border-slate-800/60">
                <div class="relative">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2"></i>
                    <input type="text" placeholder="Jump to setting..."
                           class="w-full bg-slate-50 dark:bg-slate-800/70 text-xs text-slate-800 dark:text-slate-200 pl-8 pr-12 py-1.5 rounded-sm border border-slate-200 dark:border-slate-700/60 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 focus:outline-none transition-all placeholder:text-slate-400">
                    <span class="absolute right-2 top-1/2 -translate-y-1/2 text-[9px] font-mono text-slate-400 bg-white dark:bg-slate-700/80 px-1 py-0.5 rounded-xs border border-slate-200 dark:border-slate-600">⌘K</span>
                </div>
            </div>

            <!-- Navigation Items Container -->
            <nav class="flex-1 overflow-y-auto no-scrollbar p-2 space-y-1">
                <livewire:layouts.backend.sidebar-nav-items />
            </nav>

            <!-- Sidebar Status Card (low radius rounded-xs, hidden in rail mode) -->
            <div class="p-3 m-2 border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40 rounded-sm sidebar-text">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Cache Layer</span>
                    <span class="flex items-center gap-1 text-[10px] font-semibold text-emerald-600 dark:text-emerald-400">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Active
                    </span>
                </div>
                <p class="text-[11px] text-slate-600 dark:text-slate-400 mt-1 truncate">Setting::rememberForever()</p>
            </div>

            <!-- User mini status bottom bar -->
            @if (auth()->check())
                <div class="p-2 border-t border-slate-200 dark:border-slate-800/80 bg-white dark:bg-slate-900 flex items-center justify-between">
                    <div class="flex items-center gap-2 overflow-hidden px-1">
                        @if (auth()->user()->avatar_url)
                            <img class="h-7 w-7 rounded-sm object-cover shrink-0" src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}">
                        @else
                            <div class="h-7 w-7 rounded-sm bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-xs font-bold text-slate-700 dark:text-slate-200 shrink-0">
                                {{ auth()->user()->initials() }}
                            </div>
                        @endif
                        <div class="sidebar-text truncate leading-tight">
                            <p class="text-xs font-semibold truncate text-slate-800 dark:text-slate-200">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-slate-400 truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="sidebar-text">
                        @csrf
                        <button type="submit" title="Log Out" class="p-1.5 text-slate-400 hover:text-rose-500 dark:hover:text-rose-400 rounded-sm hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                            <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                        </button>
                    </form>
                </div>
            @endif

        </aside>

        <!-- MAIN APPLICATION WRAPPER -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-100/60 dark:bg-slate-950">

            <!-- Top Enterprise Header -->
            <livewire:layouts.backend.global-header />

            <!-- Dynamic Body Slot -->
            {{ $slot }}

        </div>
    </div>

    <!-- Media Library Modal Component -->
    <livewire:media />

    <!-- Notification Toast message container matching demo.html -->
    <div id="toastMessage" class="fixed bottom-4 right-4 z-50 transform translate-y-12 opacity-0 transition-all duration-200 bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-xs px-3.5 py-2.5 rounded-sm shadow-xl flex items-center gap-2 border border-slate-700 dark:border-slate-300 pointer-events-none">
        <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-400 dark:text-emerald-600"></i>
        <span id="toastText">Changes saved successfully!</span>
    </div>

    @livewireScripts

    <!-- CORE INTERACTION SCRIPTS (from demo.html) -->
    <script>
        // 1. Initialize Lucide Icons with Livewire 3 & DOM Morph Support
        let isIconRefreshing = false;
        function refreshIcons() {
            if (!window.lucide || typeof window.lucide.createIcons !== 'function') return;
            if (isIconRefreshing) return;
            isIconRefreshing = true;

            try {
                window.lucide.createIcons();
            } catch (err) {
                console.warn('Lucide icon refresh error:', err);
            } finally {
                isIconRefreshing = false;
            }
        }

        let iconDebounceTimer = null;
        function debouncedRefreshIcons() {
            if (iconDebounceTimer) cancelAnimationFrame(iconDebounceTimer);
            iconDebounceTimer = requestAnimationFrame(() => {
                refreshIcons();
            });
        }

        // Attach Livewire lifecycle hooks
        function attachLivewireIconHooks() {
            if (!window.Livewire || window._livewireLucideHooked) return;
            window._livewireLucideHooked = true;

            // Livewire 3 commit hook: fires when AJAX response succeeds and finishes processing
            Livewire.hook('commit', ({ succeed }) => {
                succeed(() => {
                    queueMicrotask(refreshIcons);
                    setTimeout(refreshIcons, 20);
                });
            });

            // Livewire 3 morph hooks: fires when DOM nodes are updated, added, or swapped
            Livewire.hook('morph.updated', () => debouncedRefreshIcons());
            Livewire.hook('morph.added', () => debouncedRefreshIcons());
            Livewire.hook('element.init', () => debouncedRefreshIcons());
        }

        // Standard Page Events
        document.addEventListener('DOMContentLoaded', refreshIcons);
        document.addEventListener('livewire:navigated', () => {
            refreshIcons();
            attachLivewireIconHooks();
        });
        document.addEventListener('livewire:init', () => {
            refreshIcons();
            attachLivewireIconHooks();
        });
        window.addEventListener('refresh-icons', refreshIcons);

        // Immediate check if Livewire already exists
        if (window.Livewire) {
            attachLivewireIconHooks();
        }

        // MutationObserver: Auto-detect any newly inserted or modified [data-lucide] icons
        const iconObserver = new MutationObserver((mutations) => {
            let hasUnrenderedIcon = false;
            for (let i = 0; i < mutations.length; i++) {
                const m = mutations[i];
                if (m.type === 'childList') {
                    for (let j = 0; j < m.addedNodes.length; j++) {
                        const node = m.addedNodes[j];
                        if (node.nodeType === 1) { // Element node
                            if (node.matches && node.matches('i[data-lucide]')) {
                                hasUnrenderedIcon = true;
                                break;
                            }
                            if (node.querySelector && node.querySelector('i[data-lucide]')) {
                                hasUnrenderedIcon = true;
                                break;
                            }
                        }
                    }
                } else if (m.type === 'attributes' && m.attributeName === 'data-lucide') {
                    hasUnrenderedIcon = true;
                    break;
                }
                if (hasUnrenderedIcon) break;
            }
            if (hasUnrenderedIcon) {
                debouncedRefreshIcons();
            }
        });

        if (document.body) {
            iconObserver.observe(document.body, { childList: true, subtree: true, attributes: true, attributeFilter: ['data-lucide'] });
        } else {
            document.addEventListener('DOMContentLoaded', () => {
                iconObserver.observe(document.body, { childList: true, subtree: true, attributes: true, attributeFilter: ['data-lucide'] });
            });
        }

        // 2. Real-time Live Date & Time Clock Ticker (UTC+6)
        function updateLiveClock() {
            const now = new Date();
            const dateOptions = { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' };
            const formattedDate = now.toLocaleDateString('en-US', dateOptions);

            let hours = now.getHours();
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12;
            const formattedHours = String(hours).padStart(2, '0');
            const timeString = `${formattedHours}:${minutes}:${seconds} ${ampm}`;

            const clockEl = document.getElementById('liveClock');
            const dateEl = document.getElementById('liveDate');
            if (clockEl) clockEl.textContent = timeString;
            if (dateEl) dateEl.textContent = formattedDate;
        }

        updateLiveClock();
        setInterval(updateLiveClock, 1000);

        // 3. Sidebar Rail Mode Toggle (Desktop)
        let isRailCollapsed = false;

        function toggleSidebarRail() {
            const sidebar = document.getElementById('mainSidebar');
            const sidebarHeader = document.getElementById('sidebarHeader');
            const sidebarBrandBlock = document.getElementById('sidebarBrandBlock');
            const collapseWrapper = document.getElementById('sidebarCollapseWrapper');
            const textElements = document.querySelectorAll('.sidebar-text');
            const railTooltips = document.querySelectorAll('.rail-tooltip');
            const collapseIcon = document.getElementById('collapseIcon');

            isRailCollapsed = !isRailCollapsed;

            if (isRailCollapsed) {
                // Shrink to crisp mini-rail (~68px)
                sidebar.classList.remove('md:w-64');
                sidebar.classList.add('md:w-[68px]');

                // HIDE brand logo block in rail mode
                if (sidebarBrandBlock) {
                    sidebarBrandBlock.classList.add('hidden');
                }

                // Center collapse toggle button
                if (sidebarHeader) {
                    sidebarHeader.classList.remove('justify-between', 'px-3.5');
                    sidebarHeader.classList.add('justify-center', 'px-0');
                }
                if (collapseWrapper) {
                    collapseWrapper.classList.add('w-full');
                }

                // Hide all text elements
                textElements.forEach(el => el.classList.add('hidden'));

                // Reveal tooltips on hover
                railTooltips.forEach(t => t.classList.remove('hidden'));

                if (collapseIcon) {
                    collapseIcon.setAttribute('data-lucide', 'panel-left-open');
                }
            } else {
                // Expand to full 64 width
                sidebar.classList.remove('md:w-[68px]');
                sidebar.classList.add('md:w-64');

                // RESTORE brand block
                if (sidebarBrandBlock) {
                    sidebarBrandBlock.classList.remove('hidden');
                }

                // Restore header layout
                if (sidebarHeader) {
                    sidebarHeader.classList.remove('justify-center', 'px-0');
                    sidebarHeader.classList.add('justify-between', 'px-3.5');
                }
                if (collapseWrapper) {
                    collapseWrapper.classList.remove('w-full');
                }

                // Restore text elements
                textElements.forEach(el => el.classList.remove('hidden'));

                // Hide tooltips
                railTooltips.forEach(t => t.classList.add('hidden'));

                if (collapseIcon) {
                    collapseIcon.setAttribute('data-lucide', 'panel-left-close');
                }
            }

            refreshIcons();
        }

        // 4. Mobile Off-Canvas Drawer Toggle
        let isMobileSidebarOpen = false;

        function toggleMobileSidebar() {
            const sidebar = document.getElementById('mainSidebar');
            const backdrop = document.getElementById('mobileBackdrop');

            isMobileSidebarOpen = !isMobileSidebarOpen;

            if (isMobileSidebarOpen) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                setTimeout(() => backdrop.classList.add('opacity-100'), 10);
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.remove('opacity-100');
                setTimeout(() => backdrop.classList.add('hidden'), 200);
            }
        }

        // 5. Dark / Light Theme Toggle (Default: Light Mode)
        function syncThemeState() {
            const html = document.documentElement;
            const themeIcon = document.getElementById('themeToggleIcon');
            const savedTheme = localStorage.getItem('theme');

            if (savedTheme === 'dark') {
                html.classList.add('dark');
                if (themeIcon) themeIcon.setAttribute('data-lucide', 'sun');
            } else {
                html.classList.remove('dark');
                if (themeIcon) themeIcon.setAttribute('data-lucide', 'moon');
            }
            refreshIcons();
        }

        function toggleDarkMode() {
            const html = document.documentElement;
            const isDark = html.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            syncThemeState();
        }

        // Initialize theme state immediately on script load
        syncThemeState();

        // 6. Toast Notification Helper
        function showToast(text) {
            const toast = document.getElementById('toastMessage');
            const toastText = document.getElementById('toastText');
            if (toast && toastText) {
                toastText.textContent = text;
                toast.classList.remove('translate-y-12', 'opacity-0');
                setTimeout(() => {
                    toast.classList.add('translate-y-12', 'opacity-0');
                }, 2500);
            }
        }

        // Listen for Livewire show-toast events
        window.addEventListener('show-toast', event => {
            const data = event.detail[0] || event.detail;
            const msg = data.message || data.title || 'Action completed successfully!';
            showToast(msg);
        });

        // 7. Summernote Rich Editor Re-initialization
        function initializePageContent() {
            if (!window.jQuery || !window.jQuery.fn.summernote) return;

            requestAnimationFrame(function () {
                $('.summernote-init').each(function () {
                    let $el = $(this);
                    let isVisible = $el.is(':visible') || $el.closest('dialog[open]').length > 0 || $el.closest('[open]').length > 0;
                    if (isVisible && !$el.next('.note-editor').length) {
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
                            ],
                            callbacks: {
                                onChange: function(contents) {
                                    let field = $el.data('field');
                                    let comp = $el.closest('[wire\\:id]');
                                    if (comp.length && window.Livewire && field) {
                                        let livewireComp = Livewire.find(comp.attr('wire:id'));
                                        if (livewireComp) {
                                            livewireComp.set(field, contents, false);
                                        }
                                    }
                                }
                            }
                        });
                    }
                });
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            syncThemeState();
            initializePageContent();
        });
        document.addEventListener('livewire:load', initializePageContent);
        document.addEventListener('livewire:init', initializePageContent);
        document.addEventListener('livewire:navigated', () => {
            syncThemeState();
            initializePageContent();
        });
    </script>
</body>
</html>