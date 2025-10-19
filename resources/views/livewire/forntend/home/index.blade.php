<div>
    <section class="py-20 bg-slate-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-extrabold text-slate-800 mb-2">Featured Products</h2>
                <p class="text-lg text-gray-500">Amader collection-er sera product-gulo dekhe nin</p>
                <div class="mt-4 w-24 h-1 bg-indigo-600 mx-auto rounded"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($products as $product)
                    {{-- @livewire('partials.products', ['product' => $product], key($product->id)) --}}
                    <livewire:partials.products :product="$product" :key="$product->id" lazy />
                @endforeach
            </div>
        </div>
    </section>
</div>