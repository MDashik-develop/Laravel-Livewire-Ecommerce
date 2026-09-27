<div>
    @if (isset($banners) && $banners->count() > 0)
        <!-- Hero Banner Slider (Ordered by Position) -->
        <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-2"
            x-data="{
                current: 0,
                total: {{ $banners->count() }},
                autoplayInterval: null,
                init() {
                    this.startAutoplay();
                },
                startAutoplay() {
                    if (this.total > 1) {
                        this.autoplayInterval = setInterval(() => {
                            this.next();
                        }, 5000);
                    }
                },
                stopAutoplay() {
                    if (this.autoplayInterval) {
                        clearInterval(this.autoplayInterval);
                    }
                },
                next() {
                    this.current = (this.current + 1) % this.total;
                },
                prev() {
                    this.current = (this.current - 1 + this.total) % this.total;
                },
                goTo(index) {
                    this.current = index;
                }
            }"
            @mouseenter="stopAutoplay()"
            @mouseleave="startAutoplay()">

            <div class="relative h-[220px] sm:h-[340px] md:h-[420px] lg:h-[480px] w-full rounded-3xl overflow-hidden shadow-xl bg-slate-900 border border-gray-100 dark:border-zinc-800">
                @foreach ($banners as $index => $banner)
                    @php
                        $targetUrl = $banner->link;
                        if (!$targetUrl && $banner->product) {
                            $targetUrl = '#';
                        } elseif (!$targetUrl && $banner->category) {
                            $targetUrl = '#';
                        }
                    @endphp

                    <div x-show="current === {{ $index }}"
                        x-transition:enter="transition ease-out duration-700"
                        x-transition:enter-start="opacity-0 scale-105"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-500"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="absolute inset-0 w-full h-full"
                        wire:key="frontend-banner-{{ $banner->id }}">

                        @if ($targetUrl)
                            <a href="{{ $targetUrl }}" class="block w-full h-full relative group">
                        @else
                            <div class="w-full h-full relative group">
                        @endif

                            @if ($banner->videoMedia)
                                <video autoplay muted loop playsinline class="w-full h-full object-cover">
                                    <source src="{{ $banner->videoMedia->url }}" type="{{ $banner->videoMedia->mime_type ?: 'video/mp4' }}">
                                </video>
                            @elseif ($banner->media)
                                <img src="{{ $banner->media->urls['large'] ?? $banner->media->url }}"
                                    alt="Promotional Banner"
                                    class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-700 ease-out"
                                    loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                            @endif

                            <!-- Subtle gradient overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-black/10 pointer-events-none"></div>

                        @if ($targetUrl)
                            </a>
                        @else
                            </div>
                        @endif
                    </div>
                @endforeach

                <!-- Prev / Next Navigation Arrows -->
                @if ($banners->count() > 1)
                    <button type="button"
                        @click="prev()"
                        class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/70 hover:bg-white dark:bg-black/50 dark:hover:bg-black/80 text-gray-800 dark:text-white backdrop-blur-md flex items-center justify-center shadow-lg transition-all transform hover:scale-110 z-10 cursor-pointer"
                        title="Previous Banner">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>

                    <button type="button"
                        @click="next()"
                        class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/70 hover:bg-white dark:bg-black/50 dark:hover:bg-black/80 text-gray-800 dark:text-white backdrop-blur-md flex items-center justify-center shadow-lg transition-all transform hover:scale-110 z-10 cursor-pointer"
                        title="Next Banner">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>

                    <!-- Indicators Dots -->
                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2 z-10 bg-black/30 backdrop-blur-sm px-3 py-1.5 rounded-full">
                        @foreach ($banners as $index => $banner)
                            <button type="button"
                                @click="goTo({{ $index }})"
                                class="h-2 rounded-full transition-all duration-300 cursor-pointer"
                                :class="current === {{ $index }} ? 'w-6 bg-white shadow-sm' : 'w-2 bg-white/50 hover:bg-white/80'"
                                title="Go to banner {{ $index + 1 }}">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif

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
            <div class="text-center mb-12">
                <h2 class="text-4xl font-extrabold text-slate-800 mb-2">Featured Products</h2>
                <p class="text-lg text-gray-500">Amader collection-er sera product-gulo dekhe nin</p>
                <div class="mt-4 w-24 h-1 bg-indigo-600 mx-auto rounded"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($products as $product)
                    @include('livewire.partials.products', ['product' => $product])
                @endforeach
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