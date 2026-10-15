<div class="bg-white p-5 rounded-2xl shadow-[5px_5px_15px_rgba(0,0,0,0.05)] border border-gray-200">
    {{-- Header --}}
    <div class="flex items-center justify-between pb-3 mb-4 border-b border-gray-100 relative">
        <h3 class="text-lg font-bold text-gray-800">Fill by price</h3>
        <div class="absolute bottom-0 left-0 w-16 h-0.5 bg-emerald-500 -mb-[1px]"></div>
    </div>

    {{-- Price Range Filter --}}
    <div x-data="{ price: {{ (int)$currentPrice }}, min: {{ (int)$minPrice }}, max: {{ (int)$maxPrice }} }" class="space-y-4">
        <div>
            <input 
                type="range" 
                :min="min" 
                :max="max" 
                x-model="price" 
                class="w-full h-2 bg-emerald-100 rounded-lg appearance-none cursor-pointer accent-emerald-500"
            >
            <div class="flex justify-between items-center text-xs text-gray-500 mt-2 font-medium">
                <span>From: <strong class="text-emerald-600 font-bold">${{ number_format($minPrice, 0) }}</strong></span>
                <span>To: <strong class="text-emerald-600 font-bold" x-text="'$' + Number(price).toLocaleString()">${{ number_format($currentPrice, 0) }}</strong></span>
            </div>
        </div>

        {{-- Color Filter --}}
        @if(!empty($colors))
            <div class="pt-3 border-t border-gray-100">
                <h4 class="text-sm font-bold text-gray-700 mb-3">Color</h4>
                <div class="space-y-2">
                    @foreach($colors as $color)
                        <label class="flex items-center justify-between text-sm text-gray-600 hover:text-emerald-600 cursor-pointer transition-colors group">
                            <div class="flex items-center space-x-2.5">
                                <input 
                                    type="checkbox" 
                                    class="w-4 h-4 rounded text-emerald-500 focus:ring-emerald-400 border-gray-300"
                                >
                                @if(!empty($color['color_code']))
                                    <span class="w-3 h-3 rounded-full inline-block border border-gray-200 shadow-xs" style="background-color: {{ $color['color_code'] }}"></span>
                                @endif
                                <span class="group-hover:text-emerald-600">{{ $color['name'] }}</span>
                            </div>
                            <span class="text-xs text-gray-400">({{ $color['count'] ?? 0 }})</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Filter Action Button --}}
        <div class="pt-2">
            <button 
                type="button" 
                class="w-full flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold py-2.5 px-4 rounded-xl text-sm transition-all duration-200 shadow-sm shadow-emerald-200 hover:shadow-md cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <span>Filter</span>
            </button>
        </div>
    </div>
</div>
