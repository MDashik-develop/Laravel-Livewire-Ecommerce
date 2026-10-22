<div>

    {{-- ── Heading Row: Title left | Category tabs right ── --}}
    <div class="flex flex-wrap justify-between items-center gap-4 mb-6 border-b border-gray-100 pb-4">
        <h2 class="text-2xl font-bold text-gray-800">Popular Products</h2>

        {{-- Category Tabs with proper gap and pill styling --}}
        <div class="flex flex-wrap items-center gap-2 sm:gap-3">
            <button
                type="button"
                wire:key="cat-pill-all"
                wire:click="selectCategory(null)"
                wire:loading.attr="disabled"
                class="px-4 py-1.5 rounded-full text-sm font-semibold transition-all duration-200 cursor-pointer disabled:opacity-60
                    {{ $selectedCategory === null
                        ? 'bg-emerald-500 text-white shadow-sm shadow-emerald-200'
                        : 'bg-gray-100 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600' }}"
            >
                All
            </button>

            @foreach($categories as $category)
                <button
                    type="button"
                    wire:key="cat-pill-{{ $category->id }}"
                    wire:click="selectCategory({{ $category->id }})"
                    wire:loading.attr="disabled"
                    class="px-4 py-1.5 rounded-full text-sm font-semibold transition-all duration-200 cursor-pointer disabled:opacity-60
                        {{ $selectedCategory === $category->id
                            ? 'bg-emerald-500 text-white shadow-sm shadow-emerald-200'
                            : 'bg-gray-100 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600' }}"
                >
                    {{ $category->name }}
                    <span class="ml-1 text-xs opacity-75">({{ $category->products_count }})</span>
                </button>
            @endforeach
        </div>
    </div>

    {{-- ── Skeleton Placeholder Grid (shown while switching category) ── --}}
    <div wire:loading wire:target="selectCategory" class="w-full">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
        @for($i = 0; $i < 5; $i++)
            <div class="bg-white rounded-xl overflow-hidden border border-gray-100 p-4 space-y-3 animate-pulse">
                {{-- Image Area Skeleton --}}
                <div class="bg-gray-100 rounded-lg w-full h-44 flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                {{-- Details Skeleton --}}
                <div class="space-y-2 pt-1">
                    <div class="h-3 bg-gray-100 rounded w-1/3"></div>
                    <div class="h-4 bg-gray-100 rounded w-4/5"></div>
                    <div class="h-3 bg-gray-100 rounded w-1/2"></div>
                </div>
                {{-- Price & Button Skeleton --}}
                <div class="flex items-center justify-between pt-2 border-t border-gray-50">
                    <div class="h-5 bg-gray-100 rounded w-16"></div>
                    <div class="h-7 bg-gray-100 rounded-lg w-14"></div>
                </div>
            </div>
        @endfor
        </div>
    </div>

    {{-- ── Real Products Grid (hidden while selectCategory is loading) ── --}}
    <div wire:loading.remove wire:target="selectCategory"
         class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 transition-opacity duration-200">

        @forelse($products as $product)
            @php
                $hasDiscount = $product->discount_price && $product->discount_price < $product->price;
                $discountPct = $hasDiscount
                    ? round((($product->price - $product->discount_price) / $product->price) * 100)
                    : 0;
                $imgUrl = $product->media?->url 
                    ?? $product->galleries->first()?->media?->url 
                    ?? 'https://placehold.co/300x300/f3f4f6/9ca3af?text=' . urlencode($product->name);
                $categoryName = $product->category?->name ?? '';
            @endphp

            <div wire:key="popular-prod-{{ $product->id }}"
                 class="group bg-white rounded-xl overflow-hidden border border-gray-100 hover:border-emerald-200 hover:shadow-lg transition-all duration-300 flex flex-col">

                {{-- ── Image Area ── --}}
                <div class="relative bg-gray-50 flex items-center justify-center overflow-hidden" style="height:190px">

                    <a href="{{ $product->slug ? route('product.show', $product->slug) : '#' }}" class="w-full h-full flex items-center justify-center">
                        <img
                            src="{{ $imgUrl }}"
                            alt="{{ $product->name }}"
                            class="max-h-full max-w-full object-contain p-3 transition-transform duration-500 group-hover:scale-105"
                            loading="lazy"
                        >
                    </a>

                    {{-- Badge --}}
                    @if($hasDiscount)
                        <span class="absolute top-2 left-2 bg-orange-400 text-white text-[10px] font-bold px-2 py-0.5 rounded">
                            -{{ $discountPct }}%
                        </span>
                    @else
                        <span class="absolute top-2 left-2 bg-pink-500 text-white text-[10px] font-bold px-2 py-0.5 rounded">
                            Hot
                        </span>
                    @endif

                    {{-- Hover Action Icons --}}
                    <div class="absolute top-2 right-2 flex flex-col gap-1.5 z-20 pointer-events-auto
                                opacity-0 group-hover:opacity-100 translate-x-4 group-hover:translate-x-0
                                transition-all duration-300">
                        {{-- Wishlist --}}
                        <button type="button" class="w-7 h-7 bg-white rounded-full shadow flex items-center justify-center text-gray-500 hover:text-red-500 transition-colors cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </button>

                        {{-- Quick View Button --}}
                        <button
                            type="button"
                            wire:click.stop="openQuickView({{ $product->id }})"
                            wire:loading.attr="disabled"
                            wire:target="openQuickView({{ $product->id }})"
                            title="Quick View"
                            class="w-7 h-7 bg-white rounded-full shadow flex items-center justify-center text-gray-500 hover:text-emerald-500 transition-colors cursor-pointer disabled:opacity-60"
                        >
                            <svg wire:loading.remove wire:target="openQuickView({{ $product->id }})" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg wire:loading wire:target="openQuickView({{ $product->id }})" class="w-3.5 h-3.5 animate-spin text-emerald-500" fill="none" viewBox="0 0 24 24" style="display: none;">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- ── Card Body ── --}}
                <div class="p-3 flex flex-col flex-1">
                    {{-- Category --}}
                    @if($categoryName)
                        <span class="text-xs text-gray-400 mb-0.5">{{ $categoryName }}</span>
                    @endif

                    {{-- Name --}}
                    @if($product->slug)
                        <a href="{{ route('product.show', $product->slug) }}"
                           class="block text-sm font-semibold text-gray-800 hover:text-emerald-600 leading-snug mb-1 line-clamp-2 transition-colors">
                            {{ $product->name }}
                        </a>
                    @else
                        <h3 class="text-sm font-semibold text-gray-800 leading-snug mb-1 line-clamp-2">{{ $product->name }}</h3>
                    @endif

                    {{-- Stars --}}
                    <div class="flex items-center gap-1 mb-1">
                        @for($s = 1; $s <= 5; $s++)
                            <svg class="w-3 h-3 {{ $s <= 4 ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                        <span class="text-[10px] text-gray-400 ml-0.5">(4.0)</span>
                    </div>

                    {{-- Vendor --}}
                    <p class="text-[11px] text-gray-400 mb-2">By <span class="text-emerald-500">NestShop</span></p>

                    {{-- ── Price + Add button ── --}}
                    <div class="flex items-center justify-between mt-auto pt-2 border-t border-gray-50">
                        <div class="flex items-baseline gap-1.5">
                            @if($hasDiscount)
                                <span class="text-emerald-600 font-bold text-base">${{ number_format($product->discount_price, 2) }}</span>
                                <span class="text-gray-400 text-xs line-through">${{ number_format($product->price, 2) }}</span>
                            @else
                                <span class="text-emerald-600 font-bold text-base">${{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>

                        <button
                            type="button"
                            wire:click.stop="addToCart({{ $product->id }})"
                            wire:loading.attr="disabled"
                            wire:target="addToCart({{ $product->id }})"
                            class="flex items-center gap-1 bg-emerald-50 hover:bg-emerald-500
                                   text-emerald-600 hover:text-white text-[11px] font-bold
                                   px-2.5 py-1.5 rounded-lg transition-all duration-200 border border-emerald-100 hover:border-emerald-500 cursor-pointer disabled:opacity-60"
                        >
                            <svg wire:loading.remove wire:target="addToCart({{ $product->id }})" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <svg wire:loading wire:target="addToCart({{ $product->id }})" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24" style="display: none;">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="addToCart({{ $product->id }})">Add</span>
                            <span wire:loading wire:target="addToCart({{ $product->id }})" style="display: none;">Adding...</span>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-5 text-center py-12 text-gray-400">
                <svg class="mx-auto w-12 h-12 mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="font-medium text-sm">এই category-তে কোনো product নেই।</p>
                <p class="text-xs text-gray-400 mt-1">অন্য category বেছে নিন</p>
            </div>
        @endforelse
    </div>

    {{-- ── Quick View Modal ── --}}
    @if($quickViewProduct)
        @php
            // Collect all unique images for this product (main image, galleries, variants)
            $qvImages = collect();
            if ($quickViewProduct->media?->url) {
                $qvImages->push($quickViewProduct->media->url);
            }
            foreach ($quickViewProduct->galleries as $gallery) {
                if ($gallery->media?->url && !$qvImages->contains($gallery->media->url)) {
                    $qvImages->push($gallery->media->url);
                }
            }
            foreach ($quickViewProduct->variants as $v) {
                if ($v->media?->url && !$qvImages->contains($v->media->url)) {
                    $qvImages->push($v->media->url);
                }
            }
            if ($qvImages->isEmpty()) {
                $qvImages->push('https://placehold.co/600x600/f3f4f6/9ca3af?text=' . urlencode($quickViewProduct->name));
            }

            $firstImg = $qvImages->first();
            $qvPrice = $displayPrice > 0 ? $displayPrice : (float) $quickViewProduct->price;
            $qvOffer = $displayOffer ?? ($quickViewProduct->discount_price ? (float) $quickViewProduct->discount_price : null);

            $hasVariants = ($quickViewProduct->variants->isNotEmpty() || $quickViewProduct->attributes->isNotEmpty());
            $currentStock = $hasVariants ? (int) $displayStock : (int) $quickViewProduct->stock;

            $qvHasDiscount = $qvOffer && $qvOffer < $qvPrice;
            $qvDiscountPct = $qvHasDiscount && $qvPrice > 0 ? round((($qvPrice - $qvOffer) / $qvPrice) * 100) : 0;

            $reviewsCount = $quickViewProduct->reviews ? $quickViewProduct->reviews->count() : 0;
            $avgRating = $reviewsCount > 0 ? round($quickViewProduct->reviews->avg('rating')) : 5;
            $avgRatingFormatted = $reviewsCount > 0 ? number_format($quickViewProduct->reviews->avg('rating'), 1) : '5.0';
        @endphp

        <div
            x-data="{ activeImg: '{{ $firstImg }}' }"
            class="fixed inset-0 z-[9999] flex items-center justify-center p-3 sm:p-6 bg-black/60 backdrop-blur-xs overflow-y-auto"
            wire:keydown.escape="closeQuickView"
        >
            {{-- Backdrop click to close --}}
            <div class="fixed inset-0 cursor-pointer" wire:click="closeQuickView"></div>

            {{-- Large Modal Box --}}
            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-4xl lg:max-w-5xl overflow-hidden z-10 border border-gray-100 my-auto animate-in fade-in zoom-in-95 duration-200">

                {{-- Close Button --}}
                <button
                    type="button"
                    wire:click="closeQuickView"
                    class="absolute top-4 right-4 z-20 w-10 h-10 bg-white/90 hover:bg-white text-gray-500 hover:text-gray-800 rounded-full shadow-md hover:shadow-lg flex items-center justify-center transition-all duration-200 hover:scale-105 active:scale-95 cursor-pointer border border-gray-100"
                    title="Close (Esc)"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-0">
                    {{-- ── Left Column: Main Image & Gallery Thumbnails (50% on desktop) ── --}}
                    <div class="lg:col-span-6 bg-gradient-to-b from-gray-50/80 to-gray-100/40 p-5 sm:p-7 flex flex-col justify-between border-b lg:border-b-0 lg:border-r border-gray-100">
                        <div>
                            {{-- Main Preview Container: Professional flexible sizing --}}
                            <div class="relative bg-white rounded-2xl overflow-hidden mb-4 border border-gray-100 flex items-center justify-center shadow-xs p-3 sm:p-5 w-full h-[320px] sm:h-[400px] md:h-[420px] group">
                                <img
                                    :src="activeImg"
                                    alt="{{ $quickViewProduct->name }}"
                                    class="w-full h-full object-contain transition-transform duration-500 ease-out group-hover:scale-105 select-none"
                                >

                                {{-- Badges: Horizontal flex with gap and glassmorphic blur --}}
                                <div class="absolute top-3.5 left-3.5 flex flex-row items-center gap-2 z-10 pointer-events-none">
                                    @if($qvHasDiscount)
                                        <span class="bg-red-500/75 backdrop-blur-md text-white text-xs font-extrabold px-3 py-1 rounded-full shadow-xs border border-white/20 tracking-wide flex items-center gap-1">
                                            -{{ $qvDiscountPct }}%
                                        </span>
                                    @endif
                                    @if($currentStock > 0)
                                        <span class="bg-emerald-500/75 backdrop-blur-md text-white text-xs font-bold px-3 py-1 rounded-full shadow-xs border border-white/20 flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                            <span>In Stock</span>
                                        </span>
                                    @else
                                        <span class="bg-gray-800/75 backdrop-blur-md text-white text-xs font-bold px-3 py-1 rounded-full shadow-xs border border-white/20 flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                                            <span>Out of Stock</span>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Gallery Thumbnails Beneath Main Image --}}
                            @if($qvImages->count() > 1)
                                <div class="mt-2">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Product Gallery</span>
                                        <span class="text-[11px] text-gray-400">{{ $qvImages->count() }} images</span>
                                    </div>
                                    <div class="flex gap-2.5 overflow-x-auto pb-2 pt-0.5 scrollbar-thin">
                                        @foreach($qvImages as $thumb)
                                            <button
                                                type="button"
                                                @click="activeImg = '{{ $thumb }}'"
                                                class="flex-shrink-0 w-16 h-16 rounded-xl overflow-hidden border-2 bg-white p-1 transition-all duration-200 cursor-pointer shadow-xs"
                                                :class="activeImg === '{{ $thumb }}' ? 'border-emerald-500 ring-2 ring-emerald-200 scale-105' : 'border-gray-200 hover:border-emerald-300 opacity-80 hover:opacity-100'"
                                            >
                                                <img src="{{ $thumb }}" alt="Thumbnail" class="w-full h-full object-contain rounded-lg">
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Subtle Hint --}}
                        <div class="pt-4 border-t border-gray-100 hidden sm:flex items-center justify-between text-xs text-gray-400">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                100% Authentic Product
                            </span>
                            @if($quickViewProduct->sku)
                                <span class="font-mono text-[11px]">SKU: {{ $quickViewProduct->sku }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- ── Right Column: Product Information & Controls (50% on desktop) ── --}}
                    <div class="lg:col-span-6 p-6 sm:p-8 flex flex-col justify-between max-h-[85vh] overflow-y-auto">
                        <div>
                            {{-- Product Title --}}
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight leading-snug mb-2 pr-8">
                                {{ $quickViewProduct->name }}
                            </h2>

                            {{-- Line 1: Category: ctgry > sub ctgry > child ctgry --}}
                            @if($quickViewProduct->category)
                                <div class="flex items-center gap-1.5 text-xs text-gray-500 mb-1">
                                    <span class="font-semibold text-gray-400">Category:</span>
                                    <span class="font-medium text-gray-700 hover:text-emerald-600 transition">{{ $quickViewProduct->category->name }}</span>
                                    @if($quickViewProduct->subCategory)
                                        <span class="text-gray-300 font-semibold">&gt;</span>
                                        <span class="font-medium text-gray-700 hover:text-emerald-600 transition">{{ $quickViewProduct->subCategory->name }}</span>
                                    @endif
                                    @if(isset($quickViewProduct->childCategory) && $quickViewProduct->childCategory)
                                        <span class="text-gray-300 font-semibold">&gt;</span>
                                        <span class="font-medium text-gray-700 hover:text-emerald-600 transition">{{ $quickViewProduct->childCategory->name }}</span>
                                    @endif
                                </div>
                            @endif

                            {{-- Line 2: Brand: brand_name --}}
                            @if($quickViewProduct->brand)
                                <div class="flex items-center gap-1.5 text-xs text-gray-500 mb-2">
                                    <span class="font-semibold text-gray-400">Brand:</span>
                                    <span class="font-semibold text-gray-800">{{ $quickViewProduct->brand->name }}</span>
                                </div>
                            @endif

                            {{-- Line 3: Reviews --}}
                            <div class="flex items-center gap-3 mb-4 pb-3 border-b border-gray-100">
                                <div class="flex items-center gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $avgRating ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-sm font-bold text-gray-800">{{ $avgRatingFormatted }}</span>
                                <span class="text-gray-300">|</span>
                                <span class="text-sm text-gray-500 hover:text-emerald-600 transition cursor-pointer">
                                    {{ $reviewsCount }} {{ Str::plural('review', $reviewsCount) }}
                                </span>
                            </div>

                            {{-- Price Section (Reacts to selected variant) --}}
                            <div class="flex items-baseline gap-3 mb-4">
                                @if($qvHasDiscount)
                                    <span class="text-3xl font-extrabold text-emerald-600">${{ number_format($qvOffer, 2) }}</span>
                                    <span class="text-lg text-gray-400 line-through font-medium">${{ number_format($qvPrice, 2) }}</span>
                                    <span class="text-xs font-bold text-red-600 bg-red-50 border border-red-200 px-2.5 py-1 rounded-full uppercase tracking-wider">
                                        Save {{ $qvDiscountPct }}%
                                    </span>
                                @else
                                    <span class="text-3xl font-extrabold text-emerald-600">${{ number_format($qvPrice, 2) }}</span>
                                @endif
                            </div>

                            {{-- ── Variant Attributes (Colors, Sizes, etc.) ── --}}
                            @if(isset($groupedAttributes) && $groupedAttributes->isNotEmpty())
                                <div class="space-y-4 mb-6 pt-1">
                                    @foreach($groupedAttributes as $attrGroup)
                                        @php
                                            $validValues = collect($attrGroup['values'])->filter(fn($v) => !empty(trim((string)($v['value'] ?? ''))));
                                        @endphp

                                        @if($validValues->isNotEmpty())
                                            @php
                                                $isColor = ($attrGroup['type'] === 'color') || str_contains(strtolower($attrGroup['name']), 'color');
                                                $currentValId = $selectedAttributes[$attrGroup['id']] ?? null;
                                                $selectedValObj = $validValues->firstWhere('id', $currentValId);
                                            @endphp

                                            <div>
                                                <div class="flex items-center justify-between mb-2">
                                                    <span class="text-xs font-bold text-gray-800 uppercase tracking-wider">
                                                        {{ $attrGroup['name'] }}:
                                                        @if($selectedValObj)
                                                            <span class="text-emerald-600 font-semibold normal-case ml-1">{{ $selectedValObj['value'] }}</span>
                                                        @endif
                                                    </span>
                                                </div>

                                                <div class="flex flex-wrap gap-2.5">
                                                    @foreach($validValues as $val)
                                                        @php
                                                            $isSelected = ($currentValId == $val['id']);
                                                            $hasColorCode = !empty($val['color_code']);
                                                            $valMedia = $val['media_url'] ?? null;
                                                        @endphp

                                                        @if($isColor && $hasColorCode)
                                                            {{-- Color Swatch Button --}}
                                                            <button
                                                                type="button"
                                                                wire:click="selectAttribute({{ $attrGroup['id'] }}, {{ $val['id'] }})"
                                                                @if($valMedia) @click="activeImg = '{{ $valMedia }}'" @endif
                                                                title="{{ $val['value'] }}"
                                                                class="w-8 h-8 rounded-full border-2 transition-all duration-200 cursor-pointer relative shadow-xs hover:scale-110 {{ $isSelected ? 'border-emerald-500 ring-2 ring-emerald-300 ring-offset-2 scale-105' : 'border-gray-200 hover:border-gray-400' }}"
                                                                style="background-color: {{ $val['color_code'] }}"
                                                            >
                                                                @if($isSelected)
                                                                    <span class="absolute inset-0 flex items-center justify-center">
                                                                        <svg class="w-3.5 h-3.5 {{ in_array(strtolower($val['color_code']), ['#fff', '#ffffff', 'white']) ? 'text-gray-800' : 'text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                                        </svg>
                                                                    </span>
                                                                @endif
                                                            </button>
                                                        @else
                                                            {{-- Size / Attribute Chip Button --}}
                                                            <button
                                                                type="button"
                                                                wire:click="selectAttribute({{ $attrGroup['id'] }}, {{ $val['id'] }})"
                                                                @if($valMedia) @click="activeImg = '{{ $valMedia }}'" @endif
                                                                class="px-3.5 py-1.5 text-xs font-bold rounded-xl border transition-all duration-200 cursor-pointer {{ $isSelected ? 'bg-emerald-500 text-white border-emerald-500 shadow-sm shadow-emerald-200 scale-105' : 'bg-white text-gray-700 border-gray-200 hover:border-emerald-400 hover:text-emerald-600' }}"
                                                            >
                                                                {{ $val['value'] }}
                                                            </button>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @elseif($quickViewProduct->attributes->isNotEmpty())
                                {{-- Fallback to simple ProductAttribute rows --}}
                                @php
                                    $uniqueColors = $quickViewProduct->attributes
                                        ->pluck('color')
                                        ->map(fn($c) => is_string($c) ? trim($c) : '')
                                        ->filter(fn($c) => $c !== '')
                                        ->unique();

                                    $uniqueSizes = $quickViewProduct->attributes
                                        ->pluck('size')
                                        ->map(fn($s) => is_string($s) ? trim($s) : '')
                                        ->filter(fn($s) => $s !== '')
                                        ->unique();
                                @endphp

                                @if($uniqueColors->isNotEmpty() || $uniqueSizes->isNotEmpty())
                                    <div class="space-y-4 mb-6 pt-1">
                                        @if($uniqueColors->isNotEmpty())
                                            <div>
                                                <span class="text-xs font-bold text-gray-800 uppercase tracking-wider block mb-2">Available Colors:</span>
                                                <div class="flex flex-wrap gap-2.5">
                                                    @foreach($uniqueColors as $clr)
                                                        @php
                                                            $attrItem = $quickViewProduct->attributes->firstWhere('color', $clr);
                                                            $isHex = preg_match('/^#[0-9A-Fa-f]{3,6}$/', $clr);
                                                        @endphp
                                                        @if($isHex)
                                                            <button
                                                                type="button"
                                                                wire:click="selectSimpleAttribute({{ $attrItem->id }})"
                                                                title="{{ $clr }}"
                                                                class="w-8 h-8 rounded-full border-2 border-gray-200 hover:border-emerald-400 hover:ring-2 hover:ring-emerald-200 transition-all cursor-pointer shadow-xs"
                                                                style="background-color: {{ $clr }}"
                                                            ></button>
                                                        @else
                                                            <button
                                                                type="button"
                                                                wire:click="selectSimpleAttribute({{ $attrItem->id }})"
                                                                class="px-3 py-1.5 text-xs font-bold rounded-xl border border-gray-200 bg-white text-gray-700 hover:border-emerald-400 hover:text-emerald-600 transition-all cursor-pointer"
                                                            >
                                                                {{ $clr }}
                                                            </button>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        @if($uniqueSizes->isNotEmpty())
                                            <div>
                                                <span class="text-xs font-bold text-gray-800 uppercase tracking-wider block mb-2">Available Sizes:</span>
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($uniqueSizes as $sz)
                                                        @php $attrItem = $quickViewProduct->attributes->firstWhere('size', $sz); @endphp
                                                        <button
                                                            type="button"
                                                            wire:click="selectSimpleAttribute({{ $attrItem->id }})"
                                                            class="px-3 py-1.5 text-xs font-bold rounded-xl border border-gray-200 bg-white text-gray-700 hover:border-emerald-400 hover:text-emerald-600 transition-all cursor-pointer"
                                                        >
                                                            {{ $sz }}
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @endif

                            {{-- Short Description (placed below variants with auto scroll & hidden scrollbar) --}}
                            @if(!empty(trim($quickViewProduct->short_description ?? '')))
                                <div class="mt-3 mb-2">
                                    <span class="text-xs font-bold text-gray-800 uppercase tracking-wider block mb-1.5">Description:</span>
                                    <div class="text-xs sm:text-sm text-gray-600 leading-relaxed bg-gray-50/70 p-3 rounded-2xl border border-gray-100 max-h-24 sm:max-h-28 overflow-y-auto [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                                        <p class="whitespace-pre-line">{{ $quickViewProduct->short_description }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- ── Action Section: Quantity, Add to Cart, Buy Now & Details ── --}}
                        <div class="pt-5 border-t border-gray-100 mt-2">
                            {{-- Stock Indicator right near Quantity --}}
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold text-gray-500">Quantity:</span>
                                @if($currentStock > 0)
                                    <span class="text-xs font-bold text-emerald-600 flex items-center gap-1.5 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        {{ $currentStock }} in stock
                                    </span>
                                @else
                                    <span class="text-xs font-bold text-red-600 flex items-center gap-1.5 bg-red-50 px-2.5 py-0.5 rounded-full border border-red-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        Out of Stock
                                    </span>
                                @endif
                            </div>

                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 mb-3">
                                {{-- Quantity Stepper --}}
                                <div class="flex items-center justify-between sm:justify-center border border-gray-200 rounded-2xl bg-gray-50/70 p-1 min-w-[120px]">
                                    <button
                                        type="button"
                                        wire:click="decrementQty"
                                        class="w-9 h-9 rounded-xl flex items-center justify-center text-gray-600 hover:bg-white hover:shadow-xs transition font-bold text-lg cursor-pointer"
                                    >−</button>
                                    <span class="w-10 text-center font-bold text-gray-800 text-sm">{{ $quantity }}</span>
                                    <button
                                        type="button"
                                        wire:click="incrementQty"
                                        class="w-9 h-9 rounded-xl flex items-center justify-center text-gray-600 hover:bg-white hover:shadow-xs transition font-bold text-lg cursor-pointer"
                                    >+</button>
                                </div>

                                {{-- Add to Cart Button --}}
                                <button
                                    type="button"
                                    wire:click="addToCart({{ $quickViewProduct->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="addToCart({{ $quickViewProduct->id }})"
                                    @if($currentStock <= 0) disabled @endif
                                    class="flex-1 bg-emerald-500 hover:bg-emerald-600 active:scale-98 text-white font-bold py-3 px-5 rounded-2xl transition-all duration-150 flex items-center justify-center gap-2 text-sm cursor-pointer shadow-md shadow-emerald-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <svg wire:loading.remove wire:target="addToCart({{ $quickViewProduct->id }})" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <svg wire:loading wire:target="addToCart({{ $quickViewProduct->id }})" class="w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24" style="display: none;">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                    <span wire:loading.remove wire:target="addToCart({{ $quickViewProduct->id }})">Add to cart</span>
                                    <span wire:loading wire:target="addToCart({{ $quickViewProduct->id }})" style="display: none;">Adding...</span>
                                </button>

                                {{-- Buy Now Button --}}
                                <button
                                    type="button"
                                    wire:click="buyNow({{ $quickViewProduct->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="buyNow({{ $quickViewProduct->id }})"
                                    @if($currentStock <= 0) disabled @endif
                                    class="flex-1 bg-gray-900 hover:bg-black active:scale-98 text-white font-bold py-3 px-5 rounded-2xl transition-all duration-150 flex items-center justify-center gap-2 text-sm cursor-pointer shadow-md shadow-gray-300 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                                    </svg>
                                    <span wire:loading.remove wire:target="buyNow({{ $quickViewProduct->id }})">Buy Now</span>
                                    <span wire:loading wire:target="buyNow({{ $quickViewProduct->id }})" style="display: none;">Processing...</span>
                                </button>
                            </div>

                            {{-- Bottom Secondary Actions --}}
                            <div class="flex items-center justify-between pt-2 text-xs">
                                @if($quickViewProduct->slug)
                                    <a
                                        href="{{ route('product.show', $quickViewProduct->slug) }}"
                                        class="inline-flex items-center gap-1.5 text-emerald-600 hover:text-emerald-700 font-bold transition group"
                                    >
                                        <span>View Complete Details & Specifications</span>
                                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                        </svg>
                                    </a>
                                @endif

                                <span class="text-gray-400 flex items-center gap-1 ml-auto">
                                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    Safe & Secure Checkout
                                </span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    @endif

</div>
