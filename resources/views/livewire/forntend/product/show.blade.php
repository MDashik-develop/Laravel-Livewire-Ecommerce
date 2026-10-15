@php
    $defaultImg = $product->media?->url 
        ?? $product->galleries->first()?->media?->url 
        ?? ($product->variants->first(fn($v) => $v->media)?->media?->url)
        ?? 'https://placehold.co/600x600/f3f4f6/9ca3af?text=' . urlencode($product->name);
@endphp
<div x-data="{ activeImg: '{{ $defaultImg }}' }">

    {{-- ── Breadcrumb ── --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-emerald-500 transition-colors">Home</a>
        <span>/</span>
        @if($product->category)
            <span class="hover:text-emerald-500 cursor-pointer">{{ $product->category->name }}</span>
            <span>/</span>
        @endif
        <span class="text-gray-800 font-medium truncate max-w-xs">{{ $product->name }}</span>
    </nav>

    {{-- ── Main Layout: Content on Left + Aside on Right (Large screens only) ── --}}
    <div class="flex flex-col lg:flex-row gap-8 items-start">

        {{-- ── Left / Main Content Column ── --}}
        <div class="w-full lg:w-3/4 min-w-0">

            {{-- ── Main Product Section ── --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-0">

            {{-- Left: Image Gallery --}}
            <div class="p-6 border-r border-gray-50">
                {{-- Main Image --}}
                <div class="relative bg-gray-50 rounded-xl overflow-hidden mb-4 flex items-center justify-center" style="height:380px">
                    <img
                        :src="activeImg"
                        alt="{{ $product->name }}"
                        class="max-h-full max-w-full object-contain p-4 transition-all duration-300"
                    >
                    @php $hasDiscount = $product->discount_price && $product->discount_price < $product->price; @endphp
                    @if($hasDiscount)
                        @php $pct = round((($product->price - $product->discount_price) / $product->price) * 100); @endphp
                        <span class="absolute top-4 left-4 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">-{{ $pct }}%</span>
                    @elseif($product->stock > 0)
                        <span class="absolute top-4 left-4 bg-emerald-500 text-white text-xs font-bold px-3 py-1 rounded-full">In Stock</span>
                    @endif
                </div>

                {{-- Thumbnail Row --}}
                <div class="flex gap-3 overflow-x-auto pb-1">
                    {{-- Main product image as first thumb --}}
                    @if($product->media)
                        <button
                            type="button"
                            @click="activeImg = '{{ $product->media->url }}'"
                            class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 transition-all duration-200 cursor-pointer"
                            :class="activeImg === '{{ $product->media->url }}' ? 'border-emerald-500 ring-2 ring-emerald-200' : 'border-gray-200 hover:border-emerald-300'"
                        >
                            <img src="{{ $product->media->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        </button>
                    @endif

                    {{-- Gallery images --}}
                    @foreach($product->galleries as $gallery)
                        @if($gallery->media)
                            @if($product->media && $gallery->media_id === $product->media_id)
                                @continue
                            @endif
                            <button
                                type="button"
                                @click="activeImg = '{{ $gallery->media->url }}'"
                                class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 transition-all duration-200 cursor-pointer"
                                :class="activeImg === '{{ $gallery->media->url }}' ? 'border-emerald-500 ring-2 ring-emerald-200' : 'border-gray-200 hover:border-emerald-300'"
                            >
                                <img src="{{ $gallery->media->url }}" alt="Gallery" class="w-full h-full object-cover">
                            </button>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Right: Product Info --}}
            <div class="p-6 md:p-8 flex flex-col">

                {{-- Category + SKU --}}
                <div class="flex items-center justify-between mb-2">
                    @if($product->category)
                        <span class="text-xs text-emerald-500 font-semibold uppercase tracking-wide">{{ $product->category->name }}</span>
                    @endif
                    @if($product->sku)
                        <span class="text-xs text-gray-400">SKU: {{ $product->sku }}</span>
                    @endif
                </div>

                {{-- Product Name --}}
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 leading-tight mb-3">{{ $product->name }}</h1>

                {{-- Rating & Reviews --}}
                <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-100">
                    <div class="flex items-center gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= 4 ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <span class="text-sm text-gray-500">{{ $product->reviews->count() }} Reviews</span>
                    <span class="text-gray-300">|</span>
                    @if($displayStock > 0)
                        <span class="text-sm text-emerald-500 font-medium flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            In Stock ({{ $displayStock }})
                        </span>
                    @else
                        <span class="text-sm text-red-500 font-medium">Out of Stock</span>
                    @endif
                </div>

                {{-- Price (reactive to selected variant) --}}
                <div class="flex items-baseline gap-3 mb-4">
                    @if($displayOffer)
                        @php $savePct = round((($displayPrice - $displayOffer) / $displayPrice) * 100); @endphp
                        <span class="text-3xl font-bold text-emerald-600">${{ number_format($displayOffer, 2) }}</span>
                        <span class="text-lg text-gray-400 line-through">${{ number_format($displayPrice, 2) }}</span>
                        <span class="text-sm text-red-500 font-semibold bg-red-50 px-2 py-0.5 rounded">Save {{ $savePct }}%</span>
                    @else
                        <span class="text-3xl font-bold text-emerald-600">${{ number_format($displayPrice, 2) }}</span>
                    @endif
                </div>

                {{-- Short Description --}}
                @if($product->short_description)
                    <p class="text-gray-600 text-sm leading-relaxed mb-5">{{ $product->short_description }}</p>
                @endif

                {{-- ── Variant Attributes (Color, Size etc.) from ProductVariant system ── --}}
                @if($groupedAttributes->isNotEmpty())
                    <div class="space-y-4 mb-5">
                        @foreach($groupedAttributes as $attrGroup)
                            <div>
                                <p class="text-sm font-semibold text-gray-700 mb-2">
                                    {{ $attrGroup['name'] }}:
                                    @if(isset($selectedAttributes[$attrGroup['id']]))
                                        @php
                                            $selVal = collect($attrGroup['values'])->firstWhere('id', $selectedAttributes[$attrGroup['id']]);
                                        @endphp
                                        <span class="text-emerald-600">{{ $selVal['value'] ?? '' }}</span>
                                    @endif
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($attrGroup['values'] as $val)
                                        @if($attrGroup['type'] === 'color' && $val['color_code'])
                                            {{-- Color Swatch --}}
                                            <button
                                                wire:click="selectAttribute({{ $attrGroup['id'] }}, {{ $val['id'] }})"
                                                title="{{ $val['value'] }}"
                                                class="w-8 h-8 rounded-full border-2 transition-all duration-150 {{ isset($selectedAttributes[$attrGroup['id']]) && $selectedAttributes[$attrGroup['id']] == $val['id'] ? 'border-emerald-500 ring-2 ring-emerald-200' : 'border-gray-300 hover:border-emerald-400' }}"
                                                style="background-color: {{ $val['color_code'] }}"
                                            ></button>
                                        @else
                                            {{-- Text Button (Size, Material etc.) --}}
                                            <button
                                                wire:click="selectAttribute({{ $attrGroup['id'] }}, {{ $val['id'] }})"
                                                class="px-3 py-1.5 text-sm font-medium border rounded-lg transition-all duration-150
                                                    {{ isset($selectedAttributes[$attrGroup['id']]) && $selectedAttributes[$attrGroup['id']] == $val['id']
                                                        ? 'bg-emerald-500 text-white border-emerald-500'
                                                        : 'bg-white text-gray-700 border-gray-300 hover:border-emerald-400 hover:text-emerald-600' }}"
                                            >{{ $val['value'] }}</button>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- ── Simple Attributes (ProductAttribute: color, size per row) ── --}}
                @if($product->attributes->isNotEmpty() && $groupedAttributes->isEmpty())
                    @php
                        $uniqueColors = $product->attributes->whereNotNull('color')->pluck('color')->unique();
                        $uniqueSizes  = $product->attributes->whereNotNull('size')->pluck('size')->unique();
                    @endphp

                    <div class="space-y-4 mb-5">
                        {{-- Colors --}}
                        @if($uniqueColors->isNotEmpty())
                            <div>
                                <p class="text-sm font-semibold text-gray-700 mb-2">Color:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($uniqueColors as $color)
                                        @php
                                            $attrRow = $product->attributes->firstWhere('color', $color);
                                            $isHex = preg_match('/^#[0-9A-Fa-f]{3,6}$/', $color);
                                        @endphp
                                        @if($isHex)
                                            <button
                                                wire:click="selectSimpleAttribute({{ $attrRow->id }})"
                                                title="{{ $color }}"
                                                class="w-8 h-8 rounded-full border-2 transition-all hover:ring-2 hover:ring-emerald-200"
                                                style="background-color: {{ $color }}"
                                            ></button>
                                        @else
                                            <button
                                                wire:click="selectSimpleAttribute({{ $attrRow->id }})"
                                                class="px-3 py-1.5 text-sm font-medium border rounded-lg bg-white text-gray-700 border-gray-300 hover:border-emerald-400 hover:text-emerald-600 transition-all"
                                            >{{ $color }}</button>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Sizes --}}
                        @if($uniqueSizes->isNotEmpty())
                            <div>
                                <p class="text-sm font-semibold text-gray-700 mb-2">Size:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($uniqueSizes as $size)
                                        @php $attrRow = $product->attributes->firstWhere('size', $size); @endphp
                                        <button
                                            wire:click="selectSimpleAttribute({{ $attrRow->id }})"
                                            class="px-3 py-1.5 text-sm font-medium border rounded-lg bg-white text-gray-700 border-gray-300 hover:border-emerald-400 hover:text-emerald-600 transition-all"
                                        >{{ $size }}</button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Divider --}}
                <div class="border-t border-gray-100 mb-5"></div>

                {{-- Brand / Vendor --}}
                <div class="grid grid-cols-2 gap-3 text-sm mb-5">
                    @if($product->brand)
                        <div class="flex gap-2">
                            <span class="text-gray-500">Brand:</span>
                            <span class="text-emerald-600 font-semibold">{{ $product->brand->name }}</span>
                        </div>
                    @endif
                    @if($product->category)
                        <div class="flex gap-2">
                            <span class="text-gray-500">Category:</span>
                            <span class="text-gray-700 font-medium">{{ $product->category->name }}</span>
                        </div>
                    @endif
                    @if($product->weight)
                        <div class="flex gap-2">
                            <span class="text-gray-500">Weight:</span>
                            <span class="text-gray-700 font-medium">{{ $product->weight }} kg</span>
                        </div>
                    @endif
                </div>

                {{-- Quantity + Add to Cart --}}
                <div class="flex flex-wrap items-center gap-3 mb-5">
                    {{-- Qty Stepper --}}
                    <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden">
                        <button wire:click="decrementQty"
                                class="w-10 h-11 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition-colors font-bold text-lg">−</button>
                        <span class="w-12 text-center font-bold text-gray-800 text-base">{{ $quantity }}</span>
                        <button wire:click="incrementQty"
                                class="w-10 h-11 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition-colors font-bold text-lg">+</button>
                    </div>

                    {{-- Add to Cart --}}
                    <button
                        wire:click="addToCart"
                        @if($displayStock <= 0) disabled @endif
                        class="flex-1 flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-600
                               disabled:bg-gray-300 disabled:cursor-not-allowed
                               text-white font-bold py-3 px-6 rounded-xl transition-all duration-200 active:scale-95"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Add to Cart
                    </button>

                    {{-- Wishlist --}}
                    <button class="w-12 h-12 border border-gray-200 rounded-xl flex items-center justify-center text-gray-400 hover:text-red-500 hover:border-red-300 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </button>
                </div>

                {{-- Share --}}
                <div class="flex items-center gap-3 text-sm text-gray-500">
                    <span>Share:</span>
                    <a href="#" class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white hover:opacity-80 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="#" class="w-8 h-8 bg-sky-500 rounded-full flex items-center justify-center text-white hover:opacity-80 transition">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Description / Reviews Tabs ── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        {{-- Tab Headers --}}
        <div class="flex border-b border-gray-100">
            @foreach(['description' => 'Description', 'reviews' => 'Reviews (' . $product->reviews->count() . ')'] as $key => $label)
                <button
                    wire:click="setTab('{{ $key }}')"
                    class="px-6 py-4 text-sm font-semibold transition-colors duration-150 border-b-2 -mb-px
                        {{ $activeTab === $key
                            ? 'text-emerald-600 border-emerald-500'
                            : 'text-gray-500 border-transparent hover:text-emerald-500' }}"
                >{{ $label }}</button>
            @endforeach
        </div>

        {{-- Tab Content --}}
        <div class="p-6">
            @if($activeTab === 'description')
                <div class="summernote-content text-gray-700 leading-relaxed text-sm">
                    {!! $product->long_description ?: ($product->short_description ?: '<p class="text-gray-400">No description available.</p>') !!}
                </div>
            @else
                @forelse($product->reviews as $review)
                    <div class="flex gap-4 py-4 border-b border-gray-50 last:border-0">
                        <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 font-bold flex-shrink-0">
                            {{ strtoupper(substr('U', 0, 1)) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-semibold text-sm text-gray-800">Customer</span>
                                <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-gray-600">{{ $review->comment ?? 'Great product!' }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm text-center py-6">No reviews yet. Be the first to review!</p>
                @endforelse
            @endif
        </div>
    </div>

    {{-- ── Related Products ── --}}
    @if($relatedProducts->count() > 0)
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-100">Related Products</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @foreach($relatedProducts as $rp)
                    @php
                        $rpHasDiscount = $rp->discount_price && $rp->discount_price < $rp->price;
                        $rpImg = $rp->media?->url ?? 'https://placehold.co/300x300/f3f4f6/9ca3af?text=' . urlencode($rp->name);
                    @endphp
                    <a href="{{ route('product.show', $rp->slug) }}"
                       class="group bg-white rounded-xl border border-gray-100 hover:border-emerald-200 hover:shadow-lg transition-all duration-300 overflow-hidden flex flex-col">
                        <div class="bg-gray-50 flex items-center justify-center overflow-hidden" style="height:160px">
                            <img src="{{ $rpImg }}" alt="{{ $rp->name }}"
                                 class="max-h-full max-w-full object-contain p-3 group-hover:scale-105 transition-transform duration-300"
                                 loading="lazy">
                        </div>
                        <div class="p-3">
                            @if($rp->category)
                                <span class="text-xs text-gray-400">{{ $rp->category->name }}</span>
                            @endif
                            <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 mb-2">{{ $rp->name }}</h3>
                            <div class="flex items-baseline gap-1.5">
                                @if($rpHasDiscount)
                                    <span class="text-emerald-600 font-bold">${{ number_format($rp->discount_price, 2) }}</span>
                                    <span class="text-gray-400 text-xs line-through">${{ number_format($rp->price, 2) }}</span>
                                @else
                                    <span class="text-emerald-600 font-bold">${{ number_format($rp->price, 2) }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

        </div>

        {{-- ── Right Aside: Category & Filter (Large screen only) ── --}}
        <aside class="hidden lg:block lg:w-1/4 space-y-6 shrink-0">
            {{-- Category Component --}}
            <livewire:layouts.frontend.aside-categorys />

            {{-- Filter Component --}}
            <livewire:layouts.frontend.aside-filter />
        </aside>

    </div>

</div>
