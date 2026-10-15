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
            $qvImg = $quickViewProduct->media?->url 
                ?? $quickViewProduct->galleries->first()?->media?->url 
                ?? 'https://placehold.co/400x400/f3f4f6/9ca3af?text=' . urlencode($quickViewProduct->name);
            $qvHasDiscount = $quickViewProduct->discount_price && $quickViewProduct->discount_price < $quickViewProduct->price;
            $qvDiscountPct = $qvHasDiscount ? round((($quickViewProduct->price - $quickViewProduct->discount_price) / $quickViewProduct->price) * 100) : 0;
        @endphp

        <div
            class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
            wire:keydown.escape="closeQuickView"
        >
            {{-- Backdrop click to close --}}
            <div class="fixed inset-0" wire:click="closeQuickView"></div>

            {{-- Modal Box --}}
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden z-10">
                <div class="flex flex-col sm:flex-row">
                    {{-- Left: Image --}}
                    <div class="sm:w-5/12 bg-gray-50 flex items-center justify-center p-6 relative min-h-[280px]">
                        <img src="{{ $qvImg }}" alt="{{ $quickViewProduct->name }}"
                             class="max-h-64 max-w-full object-contain">

                        {{-- Close Button --}}
                        <button
                            type="button"
                            wire:click="closeQuickView"
                            class="absolute top-3 right-3 w-8 h-8 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-gray-100 transition cursor-pointer"
                        >
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Right: Info --}}
                    <div class="sm:w-7/12 p-6 flex flex-col justify-between">
                        <div>
                            @if($qvHasDiscount)
                                <span class="inline-block mb-2 bg-pink-100 text-pink-600 text-xs font-bold px-3 py-0.5 rounded-full w-fit">
                                    Sale -{{ $qvDiscountPct }}%
                                </span>
                            @endif

                            <h3 class="text-lg font-bold text-gray-800 mb-1 leading-snug">{{ $quickViewProduct->name }}</h3>

                            @if($quickViewProduct->category)
                                <span class="text-xs text-emerald-500 font-semibold uppercase tracking-wide">{{ $quickViewProduct->category->name }}</span>
                            @endif

                            {{-- Price --}}
                            <div class="flex items-baseline gap-3 my-3">
                                @if($qvHasDiscount)
                                    <span class="text-emerald-600 font-bold text-2xl">${{ number_format($quickViewProduct->discount_price, 2) }}</span>
                                    <span class="text-gray-400 text-sm line-through">${{ number_format($quickViewProduct->price, 2) }}</span>
                                @else
                                    <span class="text-emerald-600 font-bold text-2xl">${{ number_format($quickViewProduct->price, 2) }}</span>
                                @endif
                            </div>

                            <p class="text-xs text-gray-500 mb-4 line-clamp-3">
                                {{ $quickViewProduct->short_description ?: 'No description available.' }}
                            </p>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex gap-2 pt-3 border-t border-gray-100">
                            <button
                                type="button"
                                wire:click="addToCart({{ $quickViewProduct->id }})"
                                wire:loading.attr="disabled"
                                wire:target="addToCart({{ $quickViewProduct->id }})"
                                class="flex-1 bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2.5 rounded-xl transition-colors duration-150 flex items-center justify-center gap-2 text-sm cursor-pointer shadow-sm shadow-emerald-200 disabled:opacity-60"
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

                            @if($quickViewProduct->slug)
                                <a
                                    href="{{ route('product.show', $quickViewProduct->slug) }}"
                                    class="flex items-center justify-center gap-1.5 px-4 py-2.5 border-2 border-emerald-500 text-emerald-600 font-bold rounded-xl hover:bg-emerald-50 transition-colors duration-150 text-sm whitespace-nowrap cursor-pointer"
                                >
                                    Details
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
