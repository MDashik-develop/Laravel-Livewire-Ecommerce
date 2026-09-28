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