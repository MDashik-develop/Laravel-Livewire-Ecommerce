<!-- Advanced Product Card 1 -->
<div class="card group">
    <div class="relative overflow-hidden">
        <img class="w-full h-72 object-cover transform group-hover:scale-110 transition-transform duration-500 ease-in-out"
            src="{{ asset('storage') . '/' . $product->thumbnail_image ?? 'https://placehold.co/400x500/DBEAFE/1E3A8A?text=Product+1' }}"
            alt="[Stylish Jacket er chobi]">
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
        <div class="absolute top-4 left-4">
            <span class="bg-indigo-600 text-white text-xs font-bold px-3 py-1.5 rounded-full">Demo</span>
        </div>
        <div
            class="absolute bottom-0 left-0 right-0 p-4 flex justify-around items-center bg-white/90 backdrop-blur-sm transform translate-y-full group-hover:translate-y-0 transition-transform duration-500 ease-in-out">
            <button title="Add to Wishlist" class="text-gray-600 hover:text-red-500 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                </svg>
            </button>
            <button title="Quick View" class="text-gray-600 hover:text-indigo-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
            </button>
        </div>
    </div>
    <div class="p-5 text-center">
        <h3 class="font-bold text-xl text-slate-800 mb-1 truncate">{{ $product->name }}</h3>
        <div class="flex justify-center items-center my-2">
            <!-- Star Rating -->
            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
            </svg>
            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
            </svg>
            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
            </svg>
            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
            </svg>
            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
            </svg>
            <span class="text-xs text-gray-500 ml-2">(32 Reviews)</span>
        </div>
        <p class="text-indigo-600 font-bold text-2xl mb-4">
            {{ $product->attributes->pluck('price')->filter()->first() ?: '' }}</p>
        <button wire:click="addToCart({{ $product->id }})" 
                class="add-to-cart-btn w-full bg-slate-800 text-white font-semibold py-3 rounded-lg hover:bg-indigo-600 transition-colors duration-300">Add
            to Cart</button>
    </div>
</div>