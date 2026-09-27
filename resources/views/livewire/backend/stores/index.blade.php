<section>
    <livewire:utilities.toast-modal />

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 max-w-6xl">
        <!-- Breadcrumbs & Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <flux:breadcrumbs>
                    <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
                    <flux:breadcrumbs.item>Store Edit</flux:breadcrumbs.item>
                </flux:breadcrumbs>
                <flux:heading size="xl" class="mt-2">Store Settings & Configuration</flux:heading>
                <flux:subheading>Configure store profile, branding images, SEO meta tags, tracking pixels, and policy pages.</flux:subheading>
            </div>

            <div class="flex items-center gap-3">
                <flux:button wire:click="save" @click="syncSummernoteBeforeSave()" variant="primary" icon="check" class="cursor-pointer shadow-sm">
                    Save All Changes
                </flux:button>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex items-center gap-2 border-b border-gray-200 dark:border-zinc-700 mb-6 overflow-x-auto pb-1 text-sm font-medium">
            <button type="button" wire:key="tab-btn-general" wire:click="setTab('general')"
                class="px-4 py-2.5 border-b-2 rounded-t-xl transition-all cursor-pointer flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'general' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400 bg-indigo-50/50 dark:bg-indigo-950/40' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span>Store Profile</span>
            </button>

            <button type="button" wire:key="tab-btn-media" wire:click="setTab('media')"
                class="px-4 py-2.5 border-b-2 rounded-t-xl transition-all cursor-pointer flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'media' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400 bg-indigo-50/50 dark:bg-indigo-950/40' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Branding & Media</span>
            </button>

            <button type="button" wire:key="tab-btn-seo" wire:click="setTab('seo')"
                class="px-4 py-2.5 border-b-2 rounded-t-xl transition-all cursor-pointer flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'seo' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400 bg-indigo-50/50 dark:bg-indigo-950/40' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <span>SEO & Meta</span>
            </button>

            <button type="button" wire:key="tab-btn-tracking" wire:click="setTab('tracking')"
                class="px-4 py-2.5 border-b-2 rounded-t-xl transition-all cursor-pointer flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'tracking' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400 bg-indigo-50/50 dark:bg-indigo-950/40' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span>Analytics & Pixels</span>
            </button>

            <button type="button" wire:key="tab-btn-pages" wire:click="setTab('pages')"
                class="px-4 py-2.5 border-b-2 rounded-t-xl transition-all cursor-pointer flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'pages' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400 bg-indigo-50/50 dark:bg-indigo-950/40' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Policy & Pages (Summernote)</span>
            </button>

            <button type="button" wire:key="tab-btn-social" wire:click="setTab('social')"
                class="px-4 py-2.5 border-b-2 rounded-t-xl transition-all cursor-pointer flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'social' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400 bg-indigo-50/50 dark:bg-indigo-950/40' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                <span>Social Links</span>
            </button>
        </div>

        <form wire:submit.prevent="save" @submit="syncSummernoteBeforeSave()">
            @if ($activeTab === 'general')
                <!-- TAB 1: GENERAL PROFILE -->
                <div wire:key="tab-panel-general" class="space-y-6">
                <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 shadow-sm rounded-2xl p-6 sm:p-8 space-y-6">
                    <div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">General Information</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Primary store credentials and operational settings.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <flux:input wire:model.blur="name" label="Store Name" placeholder="e.g. My Official Store" />
                        </div>

                        <div>
                            <flux:input wire:model="slug" label="Store Slug / URL Identifier" placeholder="e.g. my-official-store" />
                        </div>

                        <div>
                            <flux:input wire:model="phone" label="Contact Phone" placeholder="e.g. +8801700000000" />
                        </div>

                        <div>
                            <flux:input wire:model="email" type="email" label="Store Email (Optional)" placeholder="e.g. support@mystore.com" />
                        </div>

                        <div>
                            <flux:select wire:model="user_id" label="Store Owner / Administrator" class="cursor-pointer">
                                <option value="">Select Owner</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </flux:select>
                        </div>

                        <div>
                            <flux:input wire:model="address" label="Physical Address" placeholder="e.g. Road 12, Gulshan, Dhaka, Bangladesh" />
                        </div>

                        <div class="sm:col-span-2">
                            <flux:textarea wire:model="description" label="Short Description" rows="3" placeholder="Brief summary of your store..." />
                        </div>
                    </div>

                    <!-- Operational Status -->
                    <div class="pt-4 border-t border-gray-100 dark:border-zinc-700">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 bg-slate-50 dark:bg-zinc-900/50 p-5 rounded-xl border border-gray-200 dark:border-zinc-700">
                            <div>
                                <flux:switch wire:model="status" label="Store Active" class="cursor-pointer" />
                                <p class="text-xs text-gray-400 mt-1">When active, customers can shop from this store.</p>
                            </div>

                            <div>
                                <flux:switch wire:model="is_approved" label="Admin Approved" class="cursor-pointer" />
                                <p class="text-xs text-gray-400 mt-1">Marketplace approval verification status.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @elseif ($activeTab === 'media')
                <!-- TAB 2: BRANDING & MEDIA -->
                <div wire:key="tab-panel-media" class="space-y-6">
                <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 shadow-sm rounded-2xl p-6 sm:p-8 space-y-8">
                    <div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">Media Assets & Branding</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Pick all logos and share images directly from your central Media Library.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- 1. Store Logo -->
                        <div class="border border-gray-200 dark:border-zinc-700 p-5 rounded-2xl bg-gray-50/40 dark:bg-zinc-900/40 flex flex-col justify-between">
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100">Store Logo</h4>
                                <p class="text-[11px] text-gray-400 mb-3">Square or transparent logo (500x500px recommended).</p>

                                @if ($mediaUrl)
                                    <div class="relative w-full h-36 rounded-xl overflow-hidden border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 group shadow-2xs mb-3">
                                        <img src="{{ $mediaUrl }}" alt="Store Logo" class="w-full h-full object-contain p-2">
                                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                            <button type="button"
                                                @click="$dispatch('open-media-modal', { targetEvent: 'store-media-selected', type: 'image' })"
                                                class="px-2.5 py-1 bg-white text-gray-900 rounded-lg text-xs font-semibold shadow hover:bg-gray-100 cursor-pointer">
                                                Change
                                            </button>
                                            <button type="button" wire:click="removeMedia"
                                                class="px-2.5 py-1 bg-rose-600 text-white rounded-lg text-xs font-semibold shadow hover:bg-rose-700 cursor-pointer">
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div @click="$dispatch('open-media-modal', { targetEvent: 'store-media-selected', type: 'image' })"
                                        class="w-full h-36 border-2 border-dashed border-gray-300 dark:border-zinc-700 hover:border-indigo-500 rounded-xl p-4 text-center cursor-pointer bg-white dark:bg-zinc-900 hover:bg-indigo-50/20 transition flex flex-col items-center justify-center mb-3">
                                        <svg class="w-7 h-7 text-indigo-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">Select Store Logo</span>
                                        <span class="text-[10px] text-gray-400">Media Library</span>
                                    </div>
                                @endif
                            </div>

                            <flux:button type="button" @click="$dispatch('open-media-modal', { targetEvent: 'store-media-selected', type: 'image' })"
                                variant="outline" size="sm" class="w-full cursor-pointer">
                                Choose Logo
                            </flux:button>
                        </div>

                        <!-- 2. Favicon -->
                        <div class="border border-gray-200 dark:border-zinc-700 p-5 rounded-2xl bg-gray-50/40 dark:bg-zinc-900/40 flex flex-col justify-between">
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100">Favicon (fav_media_id)</h4>
                                <p class="text-[11px] text-gray-400 mb-3">Browser tab icon (32x32px or 64x64px recommended).</p>

                                @if ($favMediaUrl)
                                    <div class="relative w-full h-36 rounded-xl overflow-hidden border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 group shadow-2xs mb-3 flex items-center justify-center">
                                        <img src="{{ $favMediaUrl }}" alt="Favicon" class="w-12 h-12 object-contain">
                                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                            <button type="button"
                                                @click="$dispatch('open-media-modal', { targetEvent: 'store-fav-media-selected', type: 'image' })"
                                                class="px-2.5 py-1 bg-white text-gray-900 rounded-lg text-xs font-semibold shadow hover:bg-gray-100 cursor-pointer">
                                                Change
                                            </button>
                                            <button type="button" wire:click="removeFavMedia"
                                                class="px-2.5 py-1 bg-rose-600 text-white rounded-lg text-xs font-semibold shadow hover:bg-rose-700 cursor-pointer">
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div @click="$dispatch('open-media-modal', { targetEvent: 'store-fav-media-selected', type: 'image' })"
                                        class="w-full h-36 border-2 border-dashed border-gray-300 dark:border-zinc-700 hover:border-indigo-500 rounded-xl p-4 text-center cursor-pointer bg-white dark:bg-zinc-900 hover:bg-indigo-50/20 transition flex flex-col items-center justify-center mb-3">
                                        <svg class="w-7 h-7 text-indigo-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">Select Favicon</span>
                                        <span class="text-[10px] text-gray-400">Media Library</span>
                                    </div>
                                @endif
                            </div>

                            <flux:button type="button" @click="$dispatch('open-media-modal', { targetEvent: 'store-fav-media-selected', type: 'image' })"
                                variant="outline" size="sm" class="w-full cursor-pointer">
                                Choose Favicon
                            </flux:button>
                        </div>

                        <!-- 3. Open Graph / Social Share Image -->
                        <div class="border border-gray-200 dark:border-zinc-700 p-5 rounded-2xl bg-gray-50/40 dark:bg-zinc-900/40 flex flex-col justify-between">
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100">OG Share Image (og_media_id)</h4>
                                <p class="text-[11px] text-gray-400 mb-3">Social share preview on FB/Twitter (1200x630px recommended).</p>

                                @if ($ogMediaUrl)
                                    <div class="relative w-full h-36 rounded-xl overflow-hidden border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 group shadow-2xs mb-3">
                                        <img src="{{ $ogMediaUrl }}" alt="OG Image" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                            <button type="button"
                                                @click="$dispatch('open-media-modal', { targetEvent: 'store-og-media-selected', type: 'image' })"
                                                class="px-2.5 py-1 bg-white text-gray-900 rounded-lg text-xs font-semibold shadow hover:bg-gray-100 cursor-pointer">
                                                Change
                                            </button>
                                            <button type="button" wire:click="removeOgMedia"
                                                class="px-2.5 py-1 bg-rose-600 text-white rounded-lg text-xs font-semibold shadow hover:bg-rose-700 cursor-pointer">
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div @click="$dispatch('open-media-modal', { targetEvent: 'store-og-media-selected', type: 'image' })"
                                        class="w-full h-36 border-2 border-dashed border-gray-300 dark:border-zinc-700 hover:border-indigo-500 rounded-xl p-4 text-center cursor-pointer bg-white dark:bg-zinc-900 hover:bg-indigo-50/20 transition flex flex-col items-center justify-center mb-3">
                                        <svg class="w-7 h-7 text-indigo-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">Select OG Image</span>
                                        <span class="text-[10px] text-gray-400">Media Library</span>
                                    </div>
                                @endif
                            </div>

                            <flux:button type="button" @click="$dispatch('open-media-modal', { targetEvent: 'store-og-media-selected', type: 'image' })"
                                variant="outline" size="sm" class="w-full cursor-pointer">
                                Choose OG Image
                            </flux:button>
                        </div>
                    </div>
                </div>
            </div>

            @elseif ($activeTab === 'seo')
                <!-- TAB 3: SEO & META TAGS -->
                <div wire:key="tab-panel-seo" class="space-y-6">
                <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 shadow-sm rounded-2xl p-6 sm:p-8 space-y-6">
                    <div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">Search Engine Optimization (SEO)</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Optimize search rankings and rich snippet displays on Google, Bing, and Twitter.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-2">
                            <flux:input wire:model="meta_title" label="SEO Meta Title" placeholder="e.g. My Official Store - Buy Authentic Products Online" />
                        </div>

                        <div class="sm:col-span-2">
                            <flux:textarea wire:model="meta_description" label="SEO Meta Description" rows="3" placeholder="Compelling meta description under 160 characters..." />
                        </div>

                        <div>
                            <flux:input wire:model="meta_keywords" label="Meta Keywords (Comma separated)" placeholder="e.g. ecommerce, fashion, electronics, online shop" />
                        </div>

                        <div>
                            <flux:input wire:model="canonical_url" label="Canonical URL" placeholder="e.g. https://mystore.com" />
                        </div>

                        <div class="sm:col-span-2">
                            <flux:input wire:model="sitemap" label="Sitemap URL (site_map)" placeholder="e.g. https://mystore.com/sitemap.xml" />
                        </div>

                        <div>
                            <flux:input wire:model="twitter_title" label="Twitter Card Title" placeholder="Title for Twitter share cards" />
                        </div>

                        <div>
                            <flux:input wire:model="twitter_description" label="Twitter Card Description" placeholder="Short description for Twitter share cards" />
                        </div>
                    </div>
                </div>
            </div>

            @elseif ($activeTab === 'tracking')
                <!-- TAB 4: MARKETING & TRACKING PIXELS -->
                <div wire:key="tab-panel-tracking" class="space-y-6">
                <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 shadow-sm rounded-2xl p-6 sm:p-8 space-y-6">
                    <div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">Analytics & Conversion Tracking</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Connect Meta Pixel, Google Ads, and Google Analytics to track ecommerce conversions.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div class="border border-gray-100 dark:border-zinc-700 p-4 rounded-xl bg-slate-50/60 dark:bg-zinc-900/40">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                                <span class="font-bold text-xs uppercase tracking-wider text-gray-800 dark:text-gray-200">Meta Pixel</span>
                            </div>
                            <flux:input wire:model="meta_pixel_id" label="Meta Pixel ID" placeholder="e.g. 123456789012345" />
                            <p class="text-[11px] text-gray-400 mt-1">Facebook / Meta Ads tracking code.</p>
                        </div>

                        <div class="border border-gray-100 dark:border-zinc-700 p-4 rounded-xl bg-slate-50/60 dark:bg-zinc-900/40">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                                <span class="font-bold text-xs uppercase tracking-wider text-gray-800 dark:text-gray-200">Google Ads</span>
                            </div>
                            <flux:input wire:model="google_ads_id" label="Google Ads ID (AW / GTAG)" placeholder="e.g. AW-123456789" />
                            <p class="text-[11px] text-gray-400 mt-1">Google Ads conversion tracking ID.</p>
                        </div>

                        <div class="border border-gray-100 dark:border-zinc-700 p-4 rounded-xl bg-slate-50/60 dark:bg-zinc-900/40">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                <span class="font-bold text-xs uppercase tracking-wider text-gray-800 dark:text-gray-200">Google Analytics</span>
                            </div>
                            <flux:input wire:model="google_analytics_id" label="Google Analytics 4 ID" placeholder="e.g. G-ABC123XYZ" />
                            <p class="text-[11px] text-gray-400 mt-1">GA4 measurement tracking ID.</p>
                        </div>
                    </div>
                </div>
            </div>

            @elseif ($activeTab === 'pages')
                <!-- TAB 5: POLICY & CONTENT PAGES (SUMMERNOTE) -->
                <div wire:key="tab-panel-pages" class="space-y-6">
                <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 shadow-sm rounded-2xl p-6 sm:p-8 space-y-8">
                    <div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">Store Policy & Content Pages</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Use the Summernote rich text editor to format your store's information, return, and privacy pages.</p>
                    </div>

                    <!-- 1. About Us Page -->
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-800 dark:text-gray-200">
                            About Us Page (about_page)
                        </label>
                        <flux:field>
                            <div wire:ignore>
                                <textarea class="summernote-init w-full" data-field="about_page" placeholder="Write your About Us story and store background...">{!! $about_page !!}</textarea>
                            </div>
                            <flux:error name="about_page" />
                        </flux:field>
                    </div>

                    <!-- 2. Contact Page Details -->
                    <div class="space-y-2 pt-6 border-t border-gray-100 dark:border-zinc-700">
                        <label class="block text-sm font-bold text-gray-800 dark:text-gray-200">
                            Contact Us Page (contact_page)
                        </label>
                        <flux:field>
                            <div wire:ignore>
                                <textarea class="summernote-init w-full" data-field="contact_page" placeholder="Write contact details, branch locations, customer support hours...">{!! $contact_page !!}</textarea>
                            </div>
                            <flux:error name="contact_page" />
                        </flux:field>
                    </div>

                    <!-- 3. Return & Refund Policy -->
                    <div class="space-y-2 pt-6 border-t border-gray-100 dark:border-zinc-700">
                        <label class="block text-sm font-bold text-gray-800 dark:text-gray-200">
                            Return & Refund Policy (return_page)
                        </label>
                        <flux:field>
                            <div wire:ignore>
                                <textarea class="summernote-init w-full" data-field="return_page" placeholder="State conditions for returns, refund timeline, warranty info...">{!! $return_page !!}</textarea>
                            </div>
                            <flux:error name="return_page" />
                        </flux:field>
                    </div>

                    <!-- 4. Privacy Policy -->
                    <div class="space-y-2 pt-6 border-t border-gray-100 dark:border-zinc-700">
                        <label class="block text-sm font-bold text-gray-800 dark:text-gray-200">
                            Privacy Policy (privacy_page)
                        </label>
                        <flux:field>
                            <div wire:ignore>
                                <textarea class="summernote-init w-full" data-field="privacy_page" placeholder="Explain customer data usage, cookie policy, secure checkout handling...">{!! $privacy_page !!}</textarea>
                            </div>
                            <flux:error name="privacy_page" />
                        </flux:field>
                    </div>

                    <!-- 5. Terms & Conditions -->
                    <div class="space-y-2 pt-6 border-t border-gray-100 dark:border-zinc-700">
                        <label class="block text-sm font-bold text-gray-800 dark:text-gray-200">
                            Terms & Conditions (terms_page)
                        </label>
                        <flux:field>
                            <div wire:ignore>
                                <textarea class="summernote-init w-full" data-field="terms_page" placeholder="Terms of service, shipping clauses, dispute resolution...">{!! $terms_page !!}</textarea>
                            </div>
                            <flux:error name="terms_page" />
                        </flux:field>
                    </div>
                </div>
            </div>

            @elseif ($activeTab === 'social')
                <!-- TAB 6: SOCIAL MEDIA LINKS -->
                <div wire:key="tab-panel-social" class="space-y-6">
                    <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 shadow-sm rounded-2xl p-6 sm:p-8 space-y-6">
                        <div>
                            <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">Social Media Profiles</h3>
                            <p class="text-xs text-gray-400 mt-0.5">Connect your official social media pages for customer trust and footer links.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                            <div>
                                <flux:input wire:model="facebook_url" label="Facebook Page URL" placeholder="https://facebook.com/mystore" />
                            </div>

                            <div>
                                <flux:input wire:model="instagram_url" label="Instagram Profile URL" placeholder="https://instagram.com/mystore" />
                            </div>

                            <div>
                                <flux:input wire:model="youtube_url" label="YouTube Channel URL" placeholder="https://youtube.com/@mystore" />
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Save Bar at bottom -->
            <div class="flex items-center justify-end gap-3 mt-8 pt-4 border-t border-gray-200 dark:border-zinc-700">
                <flux:button type="button" wire:click="loadDefaultStore" variant="ghost" class="cursor-pointer">
                    Reset Changes
                </flux:button>
                <flux:button type="submit" @click="syncSummernoteBeforeSave()" variant="primary" icon="check" class="cursor-pointer px-6">
                    Save Store Information
                </flux:button>
            </div>
        </form>
    </div>
</section>
