<div class="relative w-full h-full min-h-[260px] sm:min-h-[340px] md:min-h-[420px] lg:min-h-[460px] rounded-2xl overflow-hidden shadow-sm bg-slate-900 border border-gray-100 dark:border-zinc-800"
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

    @if ($banners->count() > 0)
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
                wire:key="hero-slider-banner-{{ $banner->id }}">

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
    @else
        <!-- Fallback if no banner exists -->
        <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-r from-emerald-500/10 via-teal-500/10 to-indigo-500/10 p-12 text-center">
            <h2 class="text-3xl font-extrabold text-gray-800 dark:text-gray-100 mb-2">Welcome to Our Store</h2>
            <p class="text-sm text-gray-500 max-w-md">Discover fresh organic products, exclusive discounts, and top-tier deals every day.</p>
        </div>
    @endif
</div>
