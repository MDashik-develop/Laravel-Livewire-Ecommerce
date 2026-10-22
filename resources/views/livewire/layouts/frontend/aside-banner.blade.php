@php
    $targetUrl = '#';
    if ($banner) {
        if ($banner->link) {
            $targetUrl = $banner->link;
        } elseif ($banner->product && $banner->product->slug) {
            $targetUrl = route('product.show', $banner->product->slug);
        } elseif ($banner->category) {
            $targetUrl = route('home');
        }
    }
@endphp

<div class="rounded-2xl overflow-hidden shadow-[5px_5px_15px_rgba(0,0,0,0.05)] border border-gray-200 transition-all duration-300 hover:shadow-lg group">
    @if ($banner && $banner->media)
        <a href="{{ $targetUrl }}" class="block relative overflow-hidden">
            <img src="{{ $banner->media->url }}" 
                 alt="Sidebar Banner" 
                 class="w-full h-auto object-cover group-hover:scale-105 transition-transform duration-500" 
                 loading="lazy">
        </a>
    @else
        {{-- Default Nest-style Promotional Banner --}}
        <div class="relative bg-gradient-to-br from-emerald-50 via-teal-50 to-amber-50 p-6 min-h-[300px] flex flex-col justify-between overflow-hidden">
            {{-- Decorative circles --}}
            <div class="absolute -top-10 -right-10 w-36 h-36 bg-emerald-200/40 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-10 -left-10 w-36 h-36 bg-amber-200/40 rounded-full blur-2xl pointer-events-none"></div>

            {{-- Top Content --}}
            <div class="relative z-10 space-y-2">
                <span class="inline-block text-[11px] font-bold tracking-wider text-amber-600 bg-amber-100/80 px-2.5 py-1 rounded-full uppercase">
                    Save 17% on
                </span>
                <h4 class="text-xl font-extrabold text-gray-800 leading-tight">
                    Organic &amp; <br>
                    <span class="text-emerald-600">Fresh Groceries</span>
                </h4>
                <p class="text-xs text-gray-500 font-medium">Everyday healthy choices at your doorstep</p>
            </div>

            {{-- Illustration / Center decoration --}}
            <div class="relative z-10 flex justify-center my-3">
                <div class="w-20 h-20 bg-emerald-500/10 rounded-full flex items-center justify-center text-4xl group-hover:scale-110 transition-transform duration-300">
                    🥑
                </div>
            </div>

            {{-- CTA Button --}}
            <div class="relative z-10 pt-2">
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold shadow-md shadow-emerald-200 transition-all duration-200 group-hover:shadow-lg">
                    <span>Shop Now</span>
                    <svg class="w-3.5 h-3.5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>
        </div>
    @endif
</div>
