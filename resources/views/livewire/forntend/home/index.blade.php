<div>
    @if (isset($featuredBanners) && $featuredBanners->count() > 0)
        <!-- Featured Promotional Banners -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-4">
            <div class="grid grid-cols-1 md:grid-cols-{{ min($featuredBanners->count(), 3) }} gap-6">
                @foreach ($featuredBanners as $fb)
                    @php
                        $fbUrl = $fb->link ?: ($fb->product ? '#' : ($fb->category ? '#' : null));
                    @endphp
                    <div class="group relative rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 aspect-[16/7] md:aspect-[16/8] bg-slate-900 border border-gray-100 dark:border-zinc-800">
                        @if ($fbUrl)
                            <a href="{{ $fbUrl }}" class="block w-full h-full relative cursor-pointer">
                        @else
                            <div class="w-full h-full relative">
                        @endif

                        @if ($fb->videoMedia)
                            <video autoplay muted loop playsinline class="w-full h-full object-cover">
                                <source src="{{ $fb->videoMedia->url }}" type="{{ $fb->videoMedia->mime_type ?: 'video/mp4' }}">
                            </video>
                        @elseif ($fb->media)
                            <img src="{{ $fb->media->urls['large'] ?? $fb->media->url }}"
                                alt="Featured Banner"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                                loading="lazy">
                        @endif

                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent pointer-events-none"></div>

                        @if ($fbUrl)
                            </a>
                        @else
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <section class="py-16 bg-slate-50">
        <div class="container mx-auto px-4">

            {{-- Section Heading --}}
            <div class="text-center mb-10">
                <h2 class="text-4xl font-extrabold text-slate-800 mb-2">
                    @if($selectedCategory)
                        {{ $categories->firstWhere('id', $selectedCategory)?->name ?? 'Products' }}
                    @else
                        Featured Products
                    @endif
                </h2>
                <p class="text-lg text-gray-500">Amader collection-er sera product-gulo dekhe nin</p>
                <div class="mt-4 w-24 h-1 bg-indigo-600 mx-auto rounded"></div>
            </div>

            {{-- Category Filter Tabs --}}
            @if($categories->count() > 0)
                <div class="flex flex-wrap justify-center gap-3 mb-10">
                    {{-- All Products Button --}}
                    <button
                        wire:click="selectCategory(null)"
                        class="px-5 py-2 rounded-full text-sm font-semibold border transition-all duration-200
                            {{ $selectedCategory === null
                                ? 'bg-indigo-600 text-white border-indigo-600 shadow-md scale-105'
                                : 'bg-white text-slate-600 border-slate-200 hover:bg-indigo-50 hover:border-indigo-400 hover:text-indigo-600' }}"
                    >
                        All Products
                    </button>

                    {{-- Category Buttons --}}
                    @foreach($categories as $category)
                        <button
                            wire:click="selectCategory({{ $category->id }})"
                            class="px-5 py-2 rounded-full text-sm font-semibold border transition-all duration-200
                                {{ $selectedCategory === $category->id
                                    ? 'bg-indigo-600 text-white border-indigo-600 shadow-md scale-105'
                                    : 'bg-white text-slate-600 border-slate-200 hover:bg-indigo-50 hover:border-indigo-400 hover:text-indigo-600' }}"
                        >
                            {{ $category->name }}
                            <span class="ml-1 text-xs opacity-70">({{ $category->products_count }})</span>
                        </button>
                    @endforeach
                </div>
            @endif

            {{-- Skeleton Placeholders (shown while selectCategory is loading) --}}
            <div wire:loading wire:target="selectCategory" class="w-full">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @for($i = 0; $i < 4; $i++)
                        <div class="bg-white rounded-2xl border border-gray-100 p-4 space-y-3 animate-pulse">
                            <div class="bg-gray-100 rounded-xl w-full h-52 flex items-center justify-center">
                                <svg class="w-10 h-10 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="space-y-2">
                                <div class="h-3 bg-gray-100 rounded w-1/3"></div>
                                <div class="h-4 bg-gray-100 rounded w-4/5"></div>
                                <div class="h-3 bg-gray-100 rounded w-1/2"></div>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t border-gray-50">
                                <div class="h-5 bg-gray-100 rounded w-1/4"></div>
                                <div class="h-8 bg-gray-100 rounded-lg w-20"></div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            {{-- Products Grid (hidden while selectCategory is loading) --}}
            <div wire:loading.remove wire:target="selectCategory"
                 class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($products as $product)
                    @include('livewire.partials.products', ['product' => $product])
                @empty
                    <div class="col-span-4 text-center py-16 text-gray-400">
                        <svg class="mx-auto mb-4 w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <p class="text-lg font-medium">এই category-তে কোনো product নেই</p>
                        <p class="text-sm mt-1">অন্য category select করুন</p>
                    </div>
                @endforelse
            </div>

            {{-- Loading Indicator --}}
            <div wire:loading.flex wire:target="selectCategory" class="justify-center mt-8" style="display: none;">
                <div class="flex items-center gap-2 text-indigo-600 font-medium">
                    <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                    </svg>
                    Loading...
                </div>
            </div>

        </div>
    </section>

    @if (isset($footerBanners) && $footerBanners->count() > 0)
        <!-- Footer Promotional Banners -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @foreach ($footerBanners as $ftb)
                @php
                    $ftbUrl = $ftb->link ?: ($ftb->product ? '#' : ($ftb->category ? '#' : null));
                @endphp
                <div class="group relative rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 h-40 sm:h-52 md:h-60 bg-slate-900 border border-gray-100 dark:border-zinc-800 my-4">
                    @if ($ftbUrl)
                        <a href="{{ $ftbUrl }}" class="block w-full h-full relative cursor-pointer">
                    @else
                        <div class="w-full h-full relative">
                    @endif

                    @if ($ftb->videoMedia)
                        <video autoplay muted loop playsinline class="w-full h-full object-cover">
                            <source src="{{ $ftb->videoMedia->url }}" type="{{ $ftb->videoMedia->mime_type ?: 'video/mp4' }}">
                        </video>
                    @elseif ($ftb->media)
                        <img src="{{ $ftb->media->urls['large'] ?? $ftb->media->url }}"
                            alt="Footer Banner"
                            class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-700 ease-out"
                            loading="lazy">
                    @endif

                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-black/10 pointer-events-none"></div>

                    @if ($ftbUrl)
                        </a>
                    @else
                        </div>
                    @endif
                </div>
            @endforeach
        </section>
    @endif
</div>