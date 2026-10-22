<div class="bg-white p-5 rounded-2xl shadow-[5px_5px_15px_rgba(0,0,0,0.05)] border border-gray-200">
    {{-- Header --}}
    <div class="flex items-center justify-between pb-3 mb-4 border-b border-gray-100 relative">
        <h3 class="text-lg font-bold text-gray-800">New Products</h3>
        <div class="absolute bottom-0 left-0 w-16 h-0.5 bg-emerald-500 -mb-[1px]"></div>
    </div>

    {{-- 4 Products List --}}
    <div class="space-y-3.5">
        @forelse($newProducts as $product)
            @php
                $img = $product->media?->url 
                    ?? $product->galleries->first()?->media?->url 
                    ?? 'https://placehold.co/100x100/f3f4f6/9ca3af?text=' . urlencode($product->name);
                $hasDiscount = $product->discount_price && $product->discount_price < $product->price;
                $effectivePrice = $hasDiscount ? $product->discount_price : $product->price;
                $rating = $product->reviews->count() > 0 ? round($product->reviews->avg('rating')) : 5;
            @endphp
            <div class="flex items-center gap-3 p-1.5 rounded-xl hover:bg-gray-50/80 transition duration-150 group">
                {{-- Thumbnail --}}
                <a href="{{ $product->slug ? route('product.show', $product->slug) : '#' }}"
                   class="w-16 h-16 rounded-xl bg-gray-50 border border-gray-100 overflow-hidden flex items-center justify-center flex-shrink-0 p-1 group-hover:border-emerald-300 transition duration-200">
                    <img src="{{ $img }}" alt="{{ $product->name }}" class="w-full h-full object-contain group-hover:scale-105 transition duration-200" loading="lazy">
                </a>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <a href="{{ $product->slug ? route('product.show', $product->slug) : '#' }}"
                       class="text-xs sm:text-sm font-semibold text-gray-800 hover:text-emerald-600 line-clamp-1 transition duration-150 block"
                       title="{{ $product->name }}">
                        {{ $product->name }}
                    </a>

                    {{-- Stars --}}
                    <div class="flex items-center gap-0.5 my-1">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-3 h-3 {{ $i <= $rating ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>

                    {{-- Price --}}
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-xs sm:text-sm font-bold text-emerald-600">${{ number_format($effectivePrice, 2) }}</span>
                        @if($hasDiscount)
                            <span class="text-[11px] text-gray-400 line-through">${{ number_format($product->price, 2) }}</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p class="text-xs text-gray-400 text-center py-4">No new products found</p>
        @endforelse
    </div>
</div>
