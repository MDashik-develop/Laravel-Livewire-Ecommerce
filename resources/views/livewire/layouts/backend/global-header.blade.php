<header data-flux-header
    x-data="{
        notifOpen: false,
        msgOpen: false,
        searchOpen: false,
        searchQuery: '',
        pages: [
            { name: 'Dashboard', url: '{{ route('dashboard') }}', icon: 'home', category: 'Main' },
            { name: 'Media Library', action: 'media', icon: 'photo', category: 'Assets' },
            { name: 'All Categories', url: '{{ route('backend.categories.index') }}', icon: 'rectangle-stack', category: 'Catalog' },
            { name: 'All Sub-Categories', url: '{{ route('backend.subcategories.index') }}', icon: 'queue-list', category: 'Catalog' },
            { name: 'All Brands', url: '{{ route('backend.brands.index') }}', icon: 'tag', category: 'Catalog' },
            { name: 'Product Attributes', url: '{{ route('backend.attributes.index') }}', icon: 'swatch', category: 'Catalog' },
            { name: 'All Products', url: '{{ route('backend.products.index') }}', icon: 'shopping-bag', category: 'Catalog' },
            { name: 'Banners & Sliders', url: '{{ route('backend.banners.index') }}', icon: 'squares-2x2', category: 'Marketing' },
            { name: 'Store Configuration', url: '{{ route('backend.stores.index') }}', icon: 'building-storefront', category: 'Settings' },
            { name: 'Shipping Methods', url: '{{ route('backend.shipping-methods.index') }}', icon: 'truck', category: 'Logistics' },
            { name: 'Payment Methods', url: '{{ route('backend.payment-methods.index') }}', icon: 'credit-card', category: 'Settings' },
            { name: 'Permissions & Roles', url: '{{ route('backend.permissions.index') }}', icon: 'shield-check', category: 'Security' },
            { name: 'My Profile Settings', url: '{{ route('settings.profile') }}', icon: 'user', category: 'Account' }
        ],
        get filteredPages() {
            if (!this.searchQuery) return this.pages;
            return this.pages.filter(p => p.name.toLowerCase().includes(this.searchQuery.toLowerCase()) || p.category.toLowerCase().includes(this.searchQuery.toLowerCase()));
        }
    }"
    class="[grid-area:header] w-full min-w-0 sticky top-0 z-30 border-b border-gray-200 dark:border-zinc-700/80 bg-white/95 dark:bg-zinc-900/95 backdrop-blur-md px-3 sm:px-6 py-2.5 flex items-center justify-between shadow-2xs">
    
    <!-- LEFT: Mobile Menu Toggle, Home, Live Website, & Search Pages -->
    <div class="flex items-center gap-2 sm:gap-3 flex-1 min-w-0 mr-3">
        <!-- Sidebar Toggle on Mobile -->
        <flux:sidebar.toggle class="lg:hidden cursor-pointer shrink-0" icon="bars-3" />

        <!-- Home shortcut button -->
        <a href="{{ route('dashboard') }}" wire:navigate title="Dashboard"
            class="p-2 text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-xl transition cursor-pointer shrink-0 flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
        </a>

        <!-- Live Website / Store shortcut button -->
        <a href="{{ route('home') }}" target="_blank" title="View Live Store (Opens in new tab)"
            class="p-2 text-gray-500 hover:text-emerald-600 dark:text-gray-400 dark:hover:text-emerald-400 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-xl transition cursor-pointer shrink-0 flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
            </svg>
        </a>

        <!-- Search Pages Input with Dropdown (matching Screenshot 2) -->
        <div class="relative max-w-xs sm:max-w-sm w-full" @click.outside="searchOpen = false">
            <div class="relative">
                <input type="text"
                    x-model="searchQuery"
                    @focus="searchOpen = true"
                    @keydown.escape="searchOpen = false"
                    placeholder="Search Pages..."
                    class="w-full pl-9 pr-4 py-1.5 text-xs bg-gray-50 dark:bg-zinc-800/80 hover:bg-gray-100/80 focus:bg-white dark:focus:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 transition text-gray-800 dark:text-gray-100 shadow-2xs">
                
                <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>

                <kbd class="hidden sm:inline-block absolute right-2.5 top-1.5 text-[10px] text-gray-400 dark:text-zinc-500 font-mono bg-white dark:bg-zinc-800 px-1.5 py-0.5 rounded border border-gray-200 dark:border-zinc-700">⌘K</kbd>
            </div>

            <!-- Search Results Dropdown -->
            <div x-show="searchOpen"
                x-cloak
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 translate-y-1"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="absolute left-0 mt-1.5 w-80 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-2xl shadow-xl z-50 overflow-hidden py-1.5 max-h-72 overflow-y-auto">
                
                <div class="px-3 py-1.5 text-[10px] font-bold text-gray-400 dark:text-gray-400 uppercase tracking-wider">
                    Quick Navigation
                </div>

                <template x-for="page in filteredPages" :key="page.name">
                    <div class="px-2">
                        <template x-if="page.url">
                            <a :href="page.url"
                                wire:navigate
                                @click="searchOpen = false"
                                class="flex items-center justify-between px-3 py-2 text-xs rounded-xl hover:bg-indigo-50 dark:hover:bg-zinc-700/60 text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400 transition cursor-pointer">
                                <span class="font-medium" x-text="page.name"></span>
                                <span class="text-[10px] px-1.5 py-0.5 rounded-md bg-gray-100 dark:bg-zinc-700 text-gray-500 dark:text-gray-400 font-normal" x-text="page.category"></span>
                            </a>
                        </template>
                        <template x-if="page.action === 'media'">
                            <button type="button"
                                @click="searchOpen = false; $dispatch('open-media-modal', { isPicker: false })"
                                class="w-full flex items-center justify-between px-3 py-2 text-xs rounded-xl hover:bg-indigo-50 dark:hover:bg-zinc-700/60 text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400 transition cursor-pointer">
                                <span class="font-medium" x-text="page.name"></span>
                                <span class="text-[10px] px-1.5 py-0.5 rounded-md bg-gray-100 dark:bg-zinc-700 text-gray-500 dark:text-gray-400 font-normal" x-text="page.category"></span>
                            </button>
                        </template>
                    </div>
                </template>

                <div x-show="filteredPages.length === 0" class="p-4 text-center text-xs text-gray-400">
                    No matching pages found
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT: Live Date & Time Clock, Notifications, Messages, User Profile, & Refresh -->
    <div class="flex items-center gap-2 sm:gap-3 shrink-0">
        <!-- Live Digital Clock (matching Screenshot 2) -->
        <div x-data="{
                time: '',
                date: '',
                updateClock() {
                    const now = new Date();
                    this.date = now.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
                    this.time = now.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', second: '2-digit', hour12: true });
                }
            }"
            x-init="updateClock(); setInterval(() => updateClock(), 1000)"
            class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-gray-100 dark:bg-zinc-800/80 border border-gray-200 dark:border-zinc-700/60 text-xs font-medium text-gray-700 dark:text-gray-200 shadow-2xs">
            
            <span class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span x-text="date" class="whitespace-nowrap"></span>
            </span>

            <span class="text-gray-300 dark:text-zinc-600">|</span>

            <span class="flex items-center gap-1.5 font-mono">
                <svg class="w-3.5 h-3.5 text-indigo-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span x-text="time" class="whitespace-nowrap font-semibold"></span>
            </span>
        </div>

        <!-- NOTIFICATIONS DROPDOWN (Exact replica of Screenshot 2!) -->
        <div class="relative" @click.outside="notifOpen = false">
            <button type="button"
                @click="notifOpen = !notifOpen; msgOpen = false"
                class="relative p-2 rounded-xl text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-100 dark:hover:bg-zinc-800 transition cursor-pointer flex items-center justify-center">
                
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>

                @if ($unreadNotifications > 0)
                    <span class="absolute top-1 right-1 min-w-[18px] h-[18px] px-1 bg-emerald-600 text-white font-bold text-[10px] rounded-full flex items-center justify-center shadow-xs border-2 border-white dark:border-zinc-900 animate-pulse">
                        {{ $unreadNotifications > 99 ? '99+' : $unreadNotifications }}
                    </span>
                @endif
            </button>

            <!-- Notifications Dropdown Box (Styled exactly like Screenshot 2) -->
            <div x-show="notifOpen"
                x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                class="absolute right-0 mt-2 w-80 sm:w-96 bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-2xl shadow-2xl z-50 overflow-hidden">
                
                <!-- Green Header Banner (as in screenshot 2) -->
                <div class="bg-[#0b4d3b] dark:bg-[#08382b] text-white px-4 py-3.5 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <h3 class="text-sm font-bold tracking-wide">Notifications</h3>
                    </div>

                    <button type="button"
                        wire:click="markAllNotificationsAsRead"
                        class="text-[11px] font-semibold px-2.5 py-1 bg-white/15 hover:bg-white/25 rounded-lg transition cursor-pointer text-white">
                        Read All
                    </button>
                </div>

                <!-- Notifications List -->
                <div class="divide-y divide-gray-100 dark:divide-zinc-800 max-h-80 overflow-y-auto">
                    @forelse ($notifications as $n)
                        <div class="p-3.5 hover:bg-gray-50 dark:hover:bg-zinc-800/60 transition flex items-start gap-3 {{ $n['unread'] ? 'bg-emerald-50/20 dark:bg-emerald-950/10' : '' }}">
                            <!-- Thumbnail / Icon -->
                            <div class="w-11 h-11 rounded-xl bg-gray-100 dark:bg-zinc-800 overflow-hidden flex items-center justify-center shrink-0 border border-gray-200 dark:border-zinc-700">
                                @if (!empty($n['image']))
                                    <img src="{{ $n['image'] }}" alt="Order Preview" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-xs font-bold text-gray-800 dark:text-gray-200 truncate">{{ $n['code'] }}</h4>
                                    @if ($n['unread'])
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-700 dark:text-gray-300 font-medium mt-0.5 truncate">{{ $n['title'] }}</p>
                                <p class="text-[11px] text-gray-400 dark:text-gray-400 truncate mt-0.5">{{ $n['subtitle'] }}</p>
                                <div class="flex items-center justify-between mt-1.5">
                                    <span class="text-[10px] text-gray-400">{{ $n['time'] }}</span>
                                    @if ($n['unread'])
                                        <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-950/60 px-1.5 py-0.2 rounded">Unread</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-xs text-gray-400">
                            No notifications at this moment
                        </div>
                    @endforelse
                </div>

                <!-- Footer: See More -->
                <div class="p-2.5 text-center border-t border-gray-100 dark:border-zinc-800 bg-gray-50/50 dark:bg-zinc-900/50">
                    <a href="{{ route('dashboard') }}" class="text-xs font-bold text-emerald-700 dark:text-emerald-400 hover:underline">
                        See More Notifications
                    </a>
                </div>
            </div>
        </div>

        <!-- MESSAGES DROPDOWN (matching Screenshot 2) -->
        <div class="relative" @click.outside="msgOpen = false">
            <button type="button"
                @click="msgOpen = !msgOpen; notifOpen = false"
                class="relative p-2 rounded-xl text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-100 dark:hover:bg-zinc-800 transition cursor-pointer flex items-center justify-center">
                
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>

                @if ($unreadMessages > 0)
                    <span class="absolute top-1 right-1 min-w-[18px] h-[18px] px-1 bg-indigo-600 text-white font-bold text-[10px] rounded-full flex items-center justify-center shadow-xs border-2 border-white dark:border-zinc-900 animate-pulse">
                        {{ $unreadMessages }}
                    </span>
                @endif
            </button>

            <!-- Messages Dropdown Box -->
            <div x-show="msgOpen"
                x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                class="absolute right-0 mt-2 w-80 sm:w-96 bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-2xl shadow-2xl z-50 overflow-hidden">
                
                <!-- Blue Header Banner -->
                <div class="bg-[#183a54] dark:bg-[#10273a] text-white px-4 py-3.5 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                        <h3 class="text-sm font-bold tracking-wide">Customer Messages</h3>
                    </div>

                    <button type="button"
                        wire:click="markAllMessagesAsRead"
                        class="text-[11px] font-semibold px-2.5 py-1 bg-white/15 hover:bg-white/25 rounded-lg transition cursor-pointer text-white">
                        Read All
                    </button>
                </div>

                <!-- Messages List -->
                <div class="divide-y divide-gray-100 dark:divide-zinc-800 max-h-80 overflow-y-auto">
                    @forelse ($messages as $m)
                        <div class="p-3.5 hover:bg-gray-50 dark:hover:bg-zinc-800/60 transition flex items-start gap-3 {{ $m['unread'] ? 'bg-sky-50/20 dark:bg-sky-950/10' : '' }}">
                            <!-- Avatar with initials -->
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white font-bold text-xs flex items-center justify-center shrink-0 shadow-xs">
                                {{ $m['initials'] }}
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-xs font-bold text-gray-800 dark:text-gray-200 truncate">{{ $m['name'] }}</h4>
                                    @if ($m['unread'])
                                        <span class="w-2 h-2 rounded-full bg-sky-500 shrink-0"></span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-600 dark:text-gray-300 mt-1 line-clamp-2">{{ $m['message'] }}</p>
                                <span class="text-[10px] text-gray-400 mt-1.5 block">{{ $m['time'] }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-xs text-gray-400">
                            No unread messages
                        </div>
                    @endforelse
                </div>

                <!-- Footer -->
                <div class="p-2.5 text-center border-t border-gray-100 dark:border-zinc-800 bg-gray-50/50 dark:bg-zinc-900/50">
                    <a href="{{ route('dashboard') }}" class="text-xs font-bold text-sky-700 dark:text-sky-400 hover:underline">
                        See All Inquiries
                    </a>
                </div>
            </div>
        </div>

        <!-- USER PROFILE AVATAR DROPDOWN (Right Side) -->
        <flux:dropdown position="bottom" align="end">
            <button type="button" class="flex items-center gap-2 p-1 rounded-full hover:ring-2 hover:ring-indigo-500/30 transition cursor-pointer">
                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-full bg-neutral-800 dark:bg-neutral-200 text-white dark:text-neutral-900 font-bold items-center justify-center text-xs shadow-2xs">
                    @if (auth()->check() && auth()->user()->avatar_url)
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                    @else
                        {{ auth()->user()?->initials() }}
                    @endif
                </span>
            </button>

            <flux:menu class="w-56">
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

        <!-- REFRESH / RELOAD BUTTON (Far Right, as in Screenshot 2) -->
        <button type="button"
            onclick="window.location.reload()"
            title="Refresh Page"
            class="p-2 rounded-xl text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 hover:bg-gray-100 dark:hover:bg-zinc-800 transition cursor-pointer group flex items-center justify-center">
            <svg class="w-4 h-4 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
        </button>
    </div>
</header>
