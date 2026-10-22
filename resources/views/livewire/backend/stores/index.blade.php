<section class="space-y-5">
    <livewire:utilities.toast-modal />

    <!-- Breadcrumbs & Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <x-admin.breadcrumb :items="[
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Store Edit']
            ]" />
            <h1 class="mt-2 text-lg font-bold text-slate-900 dark:text-zinc-100">Store Settings & Configuration</h1>
            <p class="text-xs text-slate-500 dark:text-zinc-400">Configure store profile, branding images, SEO meta tags, tracking pixels, and policy pages.</p>
        </div>

        <div class="flex items-center gap-2">
            <x-admin.button wire:click="save" @click="syncSummernoteBeforeSave()" variant="primary" icon="check">
                Save All Changes
            </x-admin.button>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex items-center gap-1.5 border-b border-slate-200 dark:border-zinc-800 overflow-x-auto pb-1 text-xs font-semibold">
        <button type="button" wire:key="tab-btn-general" wire:click="setTab('general')"
            class="px-3.5 py-2 border-b-2 rounded-t-[3px] transition-all cursor-pointer flex items-center gap-1.5 whitespace-nowrap {{ $activeTab === 'general' ? 'border-brand-600 text-brand-600 dark:text-brand-400 bg-brand-50/60 dark:bg-brand-950/40' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-zinc-400' }}">
            <i data-lucide="store" class="w-3.5 h-3.5"></i>
            <span>Store Profile</span>
        </button>

        <button type="button" wire:key="tab-btn-media" wire:click="setTab('media')"
            class="px-3.5 py-2 border-b-2 rounded-t-[3px] transition-all cursor-pointer flex items-center gap-1.5 whitespace-nowrap {{ $activeTab === 'media' ? 'border-brand-600 text-brand-600 dark:text-brand-400 bg-brand-50/60 dark:bg-brand-950/40' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-zinc-400' }}">
            <i data-lucide="image" class="w-3.5 h-3.5"></i>
            <span>Branding & Media</span>
        </button>

        <button type="button" wire:key="tab-btn-seo" wire:click="setTab('seo')"
            class="px-3.5 py-2 border-b-2 rounded-t-[3px] transition-all cursor-pointer flex items-center gap-1.5 whitespace-nowrap {{ $activeTab === 'seo' ? 'border-brand-600 text-brand-600 dark:text-brand-400 bg-brand-50/60 dark:bg-brand-950/40' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-zinc-400' }}">
            <i data-lucide="search" class="w-3.5 h-3.5"></i>
            <span>SEO & Meta</span>
        </button>

        <button type="button" wire:key="tab-btn-tracking" wire:click="setTab('tracking')"
            class="px-3.5 py-2 border-b-2 rounded-t-[3px] transition-all cursor-pointer flex items-center gap-1.5 whitespace-nowrap {{ $activeTab === 'tracking' ? 'border-brand-600 text-brand-600 dark:text-brand-400 bg-brand-50/60 dark:bg-brand-950/40' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-zinc-400' }}">
            <i data-lucide="bar-chart-2" class="w-3.5 h-3.5"></i>
            <span>Analytics & Pixels</span>
        </button>

        <button type="button" wire:key="tab-btn-pages" wire:click="setTab('pages')"
            class="px-3.5 py-2 border-b-2 rounded-t-[3px] transition-all cursor-pointer flex items-center gap-1.5 whitespace-nowrap {{ $activeTab === 'pages' ? 'border-brand-600 text-brand-600 dark:text-brand-400 bg-brand-50/60 dark:bg-brand-950/40' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-zinc-400' }}">
            <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
            <span>Policy & Pages (Summernote)</span>
        </button>

        <button type="button" wire:key="tab-btn-social" wire:click="setTab('social')"
            class="px-3.5 py-2 border-b-2 rounded-t-[3px] transition-all cursor-pointer flex items-center gap-1.5 whitespace-nowrap {{ $activeTab === 'social' ? 'border-brand-600 text-brand-600 dark:text-brand-400 bg-brand-50/60 dark:bg-brand-950/40' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-zinc-400' }}">
            <i data-lucide="share-2" class="w-3.5 h-3.5"></i>
            <span>Social Links</span>
        </button>
    </div>

    <form wire:submit.prevent="save" @submit="syncSummernoteBeforeSave()">
        @if ($activeTab === 'general')
            <!-- TAB 1: GENERAL PROFILE -->
            <div wire:key="tab-panel-general" class="space-y-4">
                <x-admin.card class="space-y-5">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-zinc-100">General Information</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Primary store credentials and operational settings.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <x-admin.input wire:model.blur="name" label="Store Name" placeholder="e.g. My Official Store" />
                        </div>

                        <div>
                            <x-admin.input wire:model="slug" label="Store Slug / URL Identifier" placeholder="e.g. my-official-store" />
                        </div>

                        <div>
                            <x-admin.input wire:model="phone" label="Contact Phone" placeholder="e.g. +8801700000000" />
                        </div>

                        <div>
                            <x-admin.input wire:model="email" type="email" label="Store Email (Optional)" placeholder="e.g. support@mystore.com" />
                        </div>

                        <div>
                            <x-admin.select wire:model="user_id" label="Store Owner / Administrator">
                                <option value="">Select Owner</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </x-admin.select>
                        </div>

                        <div>
                            <x-admin.input wire:model="address" label="Physical Address" placeholder="e.g. Road 12, Gulshan, Dhaka, Bangladesh" />
                        </div>

                        <div class="sm:col-span-2">
                            <x-admin.textarea wire:model="description" label="Short Description" rows="3" placeholder="Brief summary of your store..." />
                        </div>
                    </div>

                    <!-- Operational Status -->
                    <div class="pt-4 border-t border-slate-100 dark:border-zinc-800">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 bg-slate-50 dark:bg-zinc-900/50 p-4 rounded-[4px] border border-slate-200 dark:border-zinc-700">
                            <div>
                                <x-admin.switch wire:model="status" label="Store Active" description="When active, customers can shop from this store." />
                            </div>

                            <div>
                                <x-admin.switch wire:model="is_approved" label="Admin Approved" description="Marketplace approval verification status." />
                            </div>
                        </div>
                    </div>
                </x-admin.card>
            </div>

        @elseif ($activeTab === 'media')
            <!-- TAB 2: BRANDING & MEDIA -->
            <div wire:key="tab-panel-media" class="space-y-4">
                <x-admin.card class="space-y-6">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-zinc-100">Media Assets & Branding</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Pick all logos and share images directly from your central Media Library.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- 1. Store Logo -->
                        <div class="border border-slate-200 dark:border-zinc-700 p-4 rounded-[4px] bg-slate-50/50 dark:bg-zinc-900/40 flex flex-col justify-between">
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 dark:text-zinc-100">Store Logo</h4>
                                <p class="text-[10px] text-slate-400 mb-2">Square or transparent logo (500x500px recommended).</p>

                                @if ($mediaUrl)
                                    <div class="relative w-full h-32 rounded-[3px] overflow-hidden border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 group mb-3">
                                        <img src="{{ $mediaUrl }}" alt="Store Logo" class="w-full h-full object-contain p-2">
                                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                            <button type="button"
                                                @click="$dispatch('open-media-modal', { targetEvent: 'store-media-selected', type: 'image' })"
                                                class="px-2 py-1 bg-white text-slate-900 rounded-[3px] text-xs font-semibold hover:bg-slate-100 cursor-pointer">
                                                Change
                                            </button>
                                            <button type="button" wire:click="removeMedia"
                                                class="px-2 py-1 bg-rose-600 text-white rounded-[3px] text-xs font-semibold hover:bg-rose-700 cursor-pointer">
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div @click="$dispatch('open-media-modal', { targetEvent: 'store-media-selected', type: 'image' })"
                                        class="w-full h-32 border-2 border-dashed border-slate-300 dark:border-zinc-700 hover:border-brand-500 rounded-[3px] p-3 text-center cursor-pointer bg-white dark:bg-zinc-900 transition flex flex-col items-center justify-center mb-3">
                                        <i data-lucide="image-plus" class="w-6 h-6 text-brand-600 mb-1"></i>
                                        <span class="text-xs font-bold text-slate-700 dark:text-zinc-300">Select Store Logo</span>
                                        <span class="text-[10px] text-slate-400">Media Library</span>
                                    </div>
                                @endif
                            </div>

                            <x-admin.button type="button" @click="$dispatch('open-media-modal', { targetEvent: 'store-media-selected', type: 'image' })"
                                variant="secondary" size="sm" class="w-full">
                                Choose Logo
                            </x-admin.button>
                        </div>

                        <!-- 2. Favicon -->
                        <div class="border border-slate-200 dark:border-zinc-700 p-4 rounded-[4px] bg-slate-50/50 dark:bg-zinc-900/40 flex flex-col justify-between">
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 dark:text-zinc-100">Favicon</h4>
                                <p class="text-[10px] text-slate-400 mb-2">Browser tab icon (32x32px or 64x64px recommended).</p>

                                @if ($favMediaUrl)
                                    <div class="relative w-full h-32 rounded-[3px] overflow-hidden border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 group mb-3 flex items-center justify-center">
                                        <img src="{{ $favMediaUrl }}" alt="Favicon" class="w-10 h-10 object-contain">
                                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                            <button type="button"
                                                @click="$dispatch('open-media-modal', { targetEvent: 'store-fav-media-selected', type: 'image' })"
                                                class="px-2 py-1 bg-white text-slate-900 rounded-[3px] text-xs font-semibold hover:bg-slate-100 cursor-pointer">
                                                Change
                                            </button>
                                            <button type="button" wire:click="removeFavMedia"
                                                class="px-2 py-1 bg-rose-600 text-white rounded-[3px] text-xs font-semibold hover:bg-rose-700 cursor-pointer">
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div @click="$dispatch('open-media-modal', { targetEvent: 'store-fav-media-selected', type: 'image' })"
                                        class="w-full h-32 border-2 border-dashed border-slate-300 dark:border-zinc-700 hover:border-brand-500 rounded-[3px] p-3 text-center cursor-pointer bg-white dark:bg-zinc-900 transition flex flex-col items-center justify-center mb-3">
                                        <i data-lucide="image" class="w-6 h-6 text-brand-600 mb-1"></i>
                                        <span class="text-xs font-bold text-slate-700 dark:text-zinc-300">Select Favicon</span>
                                        <span class="text-[10px] text-slate-400">Media Library</span>
                                    </div>
                                @endif
                            </div>

                            <x-admin.button type="button" @click="$dispatch('open-media-modal', { targetEvent: 'store-fav-media-selected', type: 'image' })"
                                variant="secondary" size="sm" class="w-full">
                                Choose Favicon
                            </x-admin.button>
                        </div>

                        <!-- 3. Open Graph / Social Share Image -->
                        <div class="border border-slate-200 dark:border-zinc-700 p-4 rounded-[4px] bg-slate-50/50 dark:bg-zinc-900/40 flex flex-col justify-between">
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 dark:text-zinc-100">OG Share Image</h4>
                                <p class="text-[10px] text-slate-400 mb-2">Social share preview on FB/Twitter (1200x630px recommended).</p>

                                @if ($ogMediaUrl)
                                    <div class="relative w-full h-32 rounded-[3px] overflow-hidden border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 group mb-3">
                                        <img src="{{ $ogMediaUrl }}" alt="OG Image" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                            <button type="button"
                                                @click="$dispatch('open-media-modal', { targetEvent: 'store-og-media-selected', type: 'image' })"
                                                class="px-2 py-1 bg-white text-slate-900 rounded-[3px] text-xs font-semibold hover:bg-slate-100 cursor-pointer">
                                                Change
                                            </button>
                                            <button type="button" wire:click="removeOgMedia"
                                                class="px-2 py-1 bg-rose-600 text-white rounded-[3px] text-xs font-semibold hover:bg-rose-700 cursor-pointer">
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div @click="$dispatch('open-media-modal', { targetEvent: 'store-og-media-selected', type: 'image' })"
                                        class="w-full h-32 border-2 border-dashed border-slate-300 dark:border-zinc-700 hover:border-brand-500 rounded-[3px] p-3 text-center cursor-pointer bg-white dark:bg-zinc-900 transition flex flex-col items-center justify-center mb-3">
                                        <i data-lucide="share-2" class="w-6 h-6 text-brand-600 mb-1"></i>
                                        <span class="text-xs font-bold text-slate-700 dark:text-zinc-300">Select OG Image</span>
                                        <span class="text-[10px] text-slate-400">Media Library</span>
                                    </div>
                                @endif
                            </div>

                            <x-admin.button type="button" @click="$dispatch('open-media-modal', { targetEvent: 'store-og-media-selected', type: 'image' })"
                                variant="secondary" size="sm" class="w-full">
                                Choose OG Image
                            </x-admin.button>
                        </div>
                    </div>
                </x-admin.card>
            </div>

        @elseif ($activeTab === 'seo')
            <!-- TAB 3: SEO & META TAGS -->
            <div wire:key="tab-panel-seo" class="space-y-4">
                <x-admin.card class="space-y-5">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-zinc-100">Search Engine Optimization (SEO)</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Optimize search rankings and rich snippet displays on Google, Bing, and Twitter.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <x-admin.input wire:model="meta_title" label="SEO Meta Title" placeholder="e.g. My Official Store - Buy Authentic Products Online" />
                        </div>

                        <div class="sm:col-span-2">
                            <x-admin.textarea wire:model="meta_description" label="SEO Meta Description" rows="3" placeholder="Compelling meta description under 160 characters..." />
                        </div>

                        <div>
                            <x-admin.input wire:model="meta_keywords" label="Meta Keywords (Comma separated)" placeholder="e.g. ecommerce, fashion, electronics, online shop" />
                        </div>

                        <div>
                            <x-admin.input wire:model="canonical_url" label="Canonical URL" placeholder="e.g. https://mystore.com" />
                        </div>

                        <div class="sm:col-span-2">
                            <x-admin.input wire:model="sitemap" label="Sitemap URL (site_map)" placeholder="e.g. https://mystore.com/sitemap.xml" />
                        </div>

                        <div>
                            <x-admin.input wire:model="twitter_title" label="Twitter Card Title" placeholder="Title for Twitter share cards" />
                        </div>

                        <div>
                            <x-admin.input wire:model="twitter_description" label="Twitter Card Description" placeholder="Short description for Twitter share cards" />
                        </div>
                    </div>
                </x-admin.card>
            </div>

        @elseif ($activeTab === 'tracking')
            <!-- TAB 4: MARKETING & TRACKING PIXELS -->
            <div wire:key="tab-panel-tracking" class="space-y-4">
                <x-admin.card class="space-y-5">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-zinc-100">Analytics & Conversion Tracking</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Connect Meta Pixel, Google Ads, and Google Analytics to track ecommerce conversions.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="border border-slate-200 dark:border-zinc-700 p-3.5 rounded-[4px] bg-slate-50/50 dark:bg-zinc-900/40">
                            <div class="flex items-center gap-1.5 mb-2">
                                <span class="w-2 h-2 rounded-[2px] bg-blue-500"></span>
                                <span class="font-bold text-xs uppercase tracking-wider text-slate-800 dark:text-zinc-200">Meta Pixel</span>
                            </div>
                            <x-admin.input wire:model="meta_pixel_id" label="Meta Pixel ID" placeholder="e.g. 123456789012345" />
                            <p class="text-[10px] text-slate-400 mt-1">Facebook / Meta Ads tracking code.</p>
                        </div>

                        <div class="border border-slate-200 dark:border-zinc-700 p-3.5 rounded-[4px] bg-slate-50/50 dark:bg-zinc-900/40">
                            <div class="flex items-center gap-1.5 mb-2">
                                <span class="w-2 h-2 rounded-[2px] bg-amber-500"></span>
                                <span class="font-bold text-xs uppercase tracking-wider text-slate-800 dark:text-zinc-200">Google Ads</span>
                            </div>
                            <x-admin.input wire:model="google_ads_id" label="Google Ads ID (AW / GTAG)" placeholder="e.g. AW-123456789" />
                            <p class="text-[10px] text-slate-400 mt-1">Google Ads conversion tracking ID.</p>
                        </div>

                        <div class="border border-slate-200 dark:border-zinc-700 p-3.5 rounded-[4px] bg-slate-50/50 dark:bg-zinc-900/40">
                            <div class="flex items-center gap-1.5 mb-2">
                                <span class="w-2 h-2 rounded-[2px] bg-emerald-500"></span>
                                <span class="font-bold text-xs uppercase tracking-wider text-slate-800 dark:text-zinc-200">Google Analytics</span>
                            </div>
                            <x-admin.input wire:model="google_analytics_id" label="Google Analytics 4 ID" placeholder="e.g. G-ABC123XYZ" />
                            <p class="text-[10px] text-slate-400 mt-1">GA4 measurement tracking ID.</p>
                        </div>
                    </div>
                </x-admin.card>
            </div>

        @elseif ($activeTab === 'pages')
            <!-- TAB 5: POLICY & CONTENT PAGES (SUMMERNOTE) -->
            <div wire:key="tab-panel-pages" class="space-y-4">
                <x-admin.card class="space-y-6">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-zinc-100">Store Policy & Content Pages</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Use the Summernote rich text editor to format your store's information, return, and privacy pages.</p>
                    </div>

                    <!-- 1. About Us Page -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-800 dark:text-zinc-200">
                            About Us Page (about_page)
                        </label>
                        <div wire:ignore>
                            <textarea class="summernote-init w-full" data-field="about_page" placeholder="Write your About Us story and store background...">{!! $about_page !!}</textarea>
                        </div>
                        @error('about_page') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- 2. Contact Page Details -->
                    <div class="space-y-1.5 pt-4 border-t border-slate-100 dark:border-zinc-800">
                        <label class="block text-xs font-semibold text-slate-800 dark:text-zinc-200">
                            Contact Us Page (contact_page)
                        </label>
                        <div wire:ignore>
                            <textarea class="summernote-init w-full" data-field="contact_page" placeholder="Write contact details, branch locations, customer support hours...">{!! $contact_page !!}</textarea>
                        </div>
                        @error('contact_page') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- 3. Return & Refund Policy -->
                    <div class="space-y-1.5 pt-4 border-t border-slate-100 dark:border-zinc-800">
                        <label class="block text-xs font-semibold text-slate-800 dark:text-zinc-200">
                            Return & Refund Policy (return_page)
                        </label>
                        <div wire:ignore>
                            <textarea class="summernote-init w-full" data-field="return_page" placeholder="State conditions for returns, refund timeline, warranty info...">{!! $return_page !!}</textarea>
                        </div>
                        @error('return_page') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- 4. Privacy Policy -->
                    <div class="space-y-1.5 pt-4 border-t border-slate-100 dark:border-zinc-800">
                        <label class="block text-xs font-semibold text-slate-800 dark:text-zinc-200">
                            Privacy Policy (privacy_page)
                        </label>
                        <div wire:ignore>
                            <textarea class="summernote-init w-full" data-field="privacy_page" placeholder="Explain customer data usage, cookie policy, secure checkout handling...">{!! $privacy_page !!}</textarea>
                        </div>
                        @error('privacy_page') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- 5. Terms & Conditions -->
                    <div class="space-y-1.5 pt-4 border-t border-slate-100 dark:border-zinc-800">
                        <label class="block text-xs font-semibold text-slate-800 dark:text-zinc-200">
                            Terms & Conditions (terms_page)
                        </label>
                        <div wire:ignore>
                            <textarea class="summernote-init w-full" data-field="terms_page" placeholder="Terms of service, shipping clauses, dispute resolution...">{!! $terms_page !!}</textarea>
                        </div>
                        @error('terms_page') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </x-admin.card>
            </div>

        @elseif ($activeTab === 'social')
            <!-- TAB 6: SOCIAL MEDIA LINKS -->
            <div wire:key="tab-panel-social" class="space-y-4">
                <x-admin.card class="space-y-5">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-zinc-100">Social Media Profiles</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Connect your official social media pages for customer trust and footer links.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <x-admin.input wire:model="facebook_url" label="Facebook Page URL" placeholder="https://facebook.com/mystore" />
                        </div>

                        <div>
                            <x-admin.input wire:model="instagram_url" label="Instagram Profile URL" placeholder="https://instagram.com/mystore" />
                        </div>

                        <div>
                            <x-admin.input wire:model="youtube_url" label="YouTube Channel URL" placeholder="https://youtube.com/@mystore" />
                        </div>
                    </div>
                </x-admin.card>
            </div>
        @endif

        <!-- Save Bar at bottom -->
        <div class="flex items-center justify-end gap-2 mt-6 pt-4 border-t border-slate-200 dark:border-zinc-800">
            <x-admin.button type="button" wire:click="loadDefaultStore" variant="ghost">
                Reset Changes
            </x-admin.button>
            <x-admin.button type="submit" @click="syncSummernoteBeforeSave()" variant="primary" icon="check">
                Save Store Information
            </x-admin.button>
        </div>
    </form>
</section>
