<header x-data="{
        notifOpen: false,
        userOpen: false,
        searchOpen: false,
        searchQuery: '',
        activeLang: '{{ app()->getLocale() == 'bn' ? 'bn' : 'en' }}',
        pages: [
            { name: 'Dashboard', url: '{{ route('dashboard') }}', icon: 'layout-grid', category: 'Platform' },
            { name: 'Media Library', action: 'media', icon: 'images', category: 'Assets' },
            { name: 'Categories', url: '{{ route('backend.categories.index') }}', icon: 'folder-tree', category: 'Catalog' },
            { name: 'Sub-Categories', url: '{{ route('backend.subcategories.index') }}', icon: 'git-fork', category: 'Catalog' },
            { name: 'Brands', url: '{{ route('backend.brands.index') }}', icon: 'tag', category: 'Catalog' },
            { name: 'Attributes', url: '{{ route('backend.attributes.index') }}', icon: 'sliders', category: 'Catalog' },
            { name: 'Products', url: '{{ route('backend.products.index') }}', icon: 'package', category: 'Catalog' },
            { name: 'Banners & Sliders', url: '{{ route('backend.banners.index') }}', icon: 'presentation', category: 'Marketing' },
            { name: 'Store Configuration', url: '{{ route('backend.stores.index') }}', icon: 'store', category: 'Admin' },
            { name: 'Shipping Methods', url: '{{ route('backend.shipping-methods.index') }}', icon: 'truck', category: 'Logistics' },
            { name: 'Payment Methods', url: '{{ route('backend.payment-methods.index') }}', icon: 'credit-card', category: 'Logistics' },
            { name: 'Permissions & Roles', url: '{{ route('backend.permissions.index') }}', icon: 'shield-check', category: 'Security' },
            { name: 'My Profile', url: '{{ route('settings.profile') }}', icon: 'user', category: 'Account' },
            { name: 'Security & Password', url: '{{ route('settings.password') }}', icon: 'key', category: 'Account' },
            { name: 'Appearance Settings', url: '{{ route('settings.appearance') }}', icon: 'paint-brush', category: 'Account' }
        ],
        get filteredPages() {
            if (!this.searchQuery) return this.pages;
            return this.pages.filter(p => p.name.toLowerCase().includes(this.searchQuery.toLowerCase()) || p.category.toLowerCase().includes(this.searchQuery.toLowerCase()));
        }
    }"
    class="h-14 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800/80 sticky top-0 z-30 flex items-center justify-between px-3 sm:px-5 shrink-0">

    <!-- Left Section: Mobile trigger + Breadcrumb trail + Live Site -->
    <div class="flex items-center gap-2.5 sm:gap-3">
        <!-- Mobile Hamburger -->
        <button type="button" onclick="toggleMobileSidebar()" class="md:hidden p-1.5 rounded-sm border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
            <i data-lucide="menu" class="w-4 h-4"></i>
        </button>

        <!-- Live Website Button -->
        <a href="{{ route('home') }}" target="_blank" title="View Live Website"
           class="p-1.5 rounded-sm border border-slate-200 dark:border-slate-700 text-slate-500 hover:text-emerald-600 dark:text-slate-400 dark:hover:text-emerald-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors flex items-center gap-1.5 text-xs font-semibold">
            <i data-lucide="external-link" class="w-3.5 h-3.5 text-emerald-500"></i>
            <span class="hidden sm:inline">Live Store</span>
        </a>

        <!-- Breadcrumb indicator -->
        <nav class="hidden md:flex items-center space-x-1.5 text-xs text-slate-500 font-medium">
            <span class="text-slate-400">Admin Hub</span>
            <i data-lucide="chevron-right" class="w-3 h-3 text-slate-300 dark:text-slate-600"></i>
            <span class="text-slate-900 dark:text-slate-100 font-semibold bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded-xs border border-slate-200 dark:border-slate-700">
                @if (request()->routeIs('dashboard'))
                    Dashboard Overview
                @elseif (request()->routeIs('backend.categories.*'))
                    Categories
                @elseif (request()->routeIs('backend.products.*'))
                    Products
                @elseif (request()->routeIs('backend.banners.*'))
                    Banners
                @elseif (request()->routeIs('backend.stores.*'))
                    Store Settings
                @elseif (request()->routeIs('backend.permissions.*'))
                    Permissions & Roles
                @elseif (request()->routeIs('settings.*'))
                    Account Settings
                @else
                    Management
                @endif
            </span>
        </nav>
    </div>

    <!-- Center: Real-time Live Clock and Date Ticker -->
    <div class="hidden lg:flex items-center gap-2 px-3 py-1 bg-slate-50 dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700/80 rounded-sm font-mono text-xs">
        <i data-lucide="clock" class="w-3.5 h-3.5 text-brand-600 dark:text-brand-400"></i>
        <span id="liveDate" class="text-slate-600 dark:text-slate-400 font-sans text-[11px] font-medium">Loading date...</span>
        <span class="text-slate-300 dark:text-slate-700 font-normal">|</span>
        <span id="liveClock" class="font-semibold text-slate-800 dark:text-slate-200 text-xs tracking-wider">--:--:-- --</span>
        <span class="text-[9px] font-sans font-semibold text-brand-600 dark:text-brand-400 bg-brand-50 dark:bg-brand-950 px-1 py-0.2 rounded-xs">UTC+6</span>
    </div>

    <!-- Right Controls: Quick Search, Language Switcher, Theme Toggle, Notification Bell, User Profile -->
    <div class="flex items-center gap-1.5 sm:gap-2">

        <!-- Search Input with Dropdown (⌘K) -->
        {{-- <div class="relative hidden sm:block w-44 md:w-56" @click.outside="searchOpen = false">
            <div class="relative">
                <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                <input type="text"
                    x-model="searchQuery"
                    @focus="searchOpen = true"
                    @keydown.escape="searchOpen = false"
                    placeholder="Jump to page..."
                    class="w-full bg-slate-50 dark:bg-slate-800/70 text-xs text-slate-800 dark:text-slate-200 pl-8 pr-10 py-1.5 rounded-sm border border-slate-200 dark:border-slate-700/60 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none transition-all placeholder:text-slate-400">
                <span class="absolute right-2 top-1/2 -translate-y-1/2 text-[9px] font-mono text-slate-400 bg-white dark:bg-slate-700/80 px-1 py-0.2 rounded-xs border border-slate-200 dark:border-slate-600">⌘K</span>
            </div>

            <!-- Search Dropdown Box -->
            <div x-show="searchOpen" x-cloak
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="absolute right-0 mt-1.5 w-72 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xl rounded-sm z-50 overflow-hidden py-1.5 max-h-72 overflow-y-auto text-xs">
                <div class="px-3 py-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Quick Navigation</div>
                <template x-for="p in filteredPages" :key="p.name">
                    <div class="px-1.5">
                        <template x-if="p.url">
                            <a :href="p.url" wire:navigate @click="searchOpen = false"
                               class="flex items-center justify-between px-2.5 py-1.5 rounded-sm hover:bg-brand-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 hover:text-brand-600 dark:hover:text-brand-400 transition cursor-pointer">
                                <span class="font-medium" x-text="p.name"></span>
                                <span class="text-[9px] font-mono px-1 py-0.2 rounded-xs bg-slate-100 dark:bg-slate-800 text-slate-500" x-text="p.category"></span>
                            </a>
                        </template>
                        <template x-if="p.action === 'media'">
                            <button type="button" @click="searchOpen = false; $dispatch('open-media-modal', { isPicker: false })"
                                    class="w-full flex items-center justify-between px-2.5 py-1.5 rounded-sm hover:bg-brand-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 hover:text-brand-600 dark:hover:text-brand-400 transition cursor-pointer">
                                <span class="font-medium" x-text="p.name"></span>
                                <span class="text-[9px] font-mono px-1 py-0.2 rounded-xs bg-slate-100 dark:bg-slate-800 text-slate-500" x-text="p.category"></span>
                            </button>
                        </template>
                    </div>
                </template>
            </div>
        </div> --}}

        <!-- Language Selector Switcher (BN/EN) -->
        {{-- <div class="flex items-center bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-sm p-0.5 text-xs font-semibold">
            <button type="button"
                    @click="activeLang = 'bn'"
                    :class="activeLang === 'bn' ? 'bg-white dark:bg-slate-700 text-brand-600 dark:text-brand-300 shadow-2xs font-bold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200'"
                    class="px-2 py-0.5 rounded-xs transition-colors text-[11px] cursor-pointer">
                বাংলা
            </button>
            <button type="button"
                    @click="activeLang = 'en'"
                    :class="activeLang === 'en' ? 'bg-white dark:bg-slate-700 text-brand-600 dark:text-brand-300 shadow-2xs font-bold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-slate-200'"
                    class="px-2 py-0.5 rounded-xs transition-colors text-[11px] cursor-pointer">
                EN
            </button>
        </div> --}}

        <!-- Dark / Light Mode Toggle Button -->
        <button type="button" onclick="toggleDarkMode()" title="Toggle Dark/Light Mode"
                class="p-1.5 rounded-sm border border-slate-200 dark:border-slate-700/80 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
            <i id="themeToggleIcon" data-lucide="moon" class="w-4 h-4"></i>
        </button>

        <!-- Notification Bell with Ping Badge & Interactive Dropdown -->
        <div class="relative" @click.outside="notifOpen = false">
            <button type="button" @click="notifOpen = !notifOpen"
                    class="relative p-1.5 rounded-sm border border-slate-200 dark:border-slate-700/80 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                <i data-lucide="bell" class="w-4 h-4"></i>
                <!-- Ping Dot -->
                <span class="absolute top-1 right-1 flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                </span>
            </button>

            <!-- Notifications Dropdown Box -->
            <div x-show="notifOpen" x-cloak
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="absolute right-0 mt-2 w-80 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xl rounded-sm z-50 overflow-hidden text-xs">
                <div class="p-3 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/40">
                    <span class="font-bold text-slate-900 dark:text-white">Recent Alerts</span>
                    <span class="text-[10px] font-semibold text-brand-600 dark:text-brand-400 cursor-pointer hover:underline">Mark all read</span>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-800/60 max-h-64 overflow-y-auto">
                    @forelse($notifications as $item)
                        <div class="p-3 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors cursor-pointer">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold text-slate-800 dark:text-slate-200 truncate">{{ $item['title'] }}</span>
                                <span class="text-[10px] text-slate-400 shrink-0 ml-2">{{ $item['time'] }}</span>
                            </div>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 truncate">{{ $item['subtitle'] }}</p>
                        </div>
                    @empty
                        <div class="p-4 text-center text-slate-400">No new alerts</div>
                    @endforelse
                </div>
                <div class="p-2 text-center border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30">
                    <a href="#" class="text-[11px] font-semibold text-brand-600 dark:text-brand-400 hover:underline">View System Activity Log</a>
                </div>
            </div>
        </div>

        <!-- User Profile Dropdown Component -->
        @if (auth()->check())
            <div class="relative ml-1" @click.outside="userOpen = false">
                <button type="button" @click="userOpen = !userOpen"
                        class="flex items-center gap-2 p-1 rounded-sm border border-slate-200 dark:border-slate-700/80 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                    @if (auth()->user()->avatar_url)
                        <img class="h-6 w-6 rounded-sm object-cover" src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}">
                    @else
                        <div class="h-6 w-6 rounded-sm bg-gradient-to-br from-brand-600 to-indigo-700 flex items-center justify-center text-[10px] font-bold text-white shrink-0">
                            {{ auth()->user()->initials() }}
                        </div>
                    @endif
                    <span class="hidden md:inline-block font-semibold text-xs text-slate-800 dark:text-slate-200 max-w-[120px] truncate">
                        {{ auth()->user()->name }}
                    </span>
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-slate-400"></i>
                </button>

                <!-- User Menu Dropdown -->
                <div x-show="userOpen" x-cloak
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute right-0 mt-2 w-56 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xl rounded-sm z-50 py-1 text-xs">
                    <div class="px-3 py-2 border-b border-slate-100 dark:border-slate-800">
                        <p class="font-bold text-slate-800 dark:text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-slate-400 font-mono truncate">{{ auth()->user()->email }}</p>
                    </div>

                    <a href="{{ route('settings.profile') }}" wire:navigate @click="userOpen = false"
                       class="flex items-center gap-2 px-3 py-2 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <i data-lucide="user" class="w-3.5 h-3.5 text-slate-400"></i>
                        <span>{{ __('My Profile') }}</span>
                    </a>

                    <a href="{{ route('settings.password') }}" wire:navigate @click="userOpen = false"
                       class="flex items-center gap-2 px-3 py-2 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <i data-lucide="key" class="w-3.5 h-3.5 text-slate-400"></i>
                        <span>{{ __('Password & Security') }}</span>
                    </a>

                    <a href="{{ route('settings.appearance') }}" wire:navigate @click="userOpen = false"
                       class="flex items-center gap-2 px-3 py-2 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <i data-lucide="sliders-horizontal" class="w-3.5 h-3.5 text-slate-400"></i>
                        <span>{{ __('Appearance Settings') }}</span>
                    </a>

                    <div class="h-px bg-slate-100 dark:border-slate-800 my-1"></div>

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition-colors text-left cursor-pointer">
                            <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                            <span>{{ __('Sign Out') }}</span>
                        </button>
                    </form>
                </div>
            </div>
        @endif

    </div>
</header>
