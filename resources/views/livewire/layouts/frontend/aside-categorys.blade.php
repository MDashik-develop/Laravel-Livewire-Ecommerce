<div class="bg-white p-5 rounded-2xl shadow-[5px_5px_15px_rgba(0,0,0,0.05)] border border-gray-200">
    <div class="flex items-center justify-between pb-3 mb-4 border-b border-gray-100 relative">
        <h3 class="text-lg font-bold text-gray-800">Category</h3>
        <div class="absolute bottom-0 left-0 w-16 h-0.5 bg-emerald-500 -mb-[1px]"></div>
    </div>
    <ul class="space-y-2.5">
        @foreach ($categorys as $category)
            @php
                $imgUrl = $category->media?->url ?? 'https://placehold.co/36x36/ECFDF5/059669?text=' . urlencode(substr($category->name, 0, 1));
            @endphp
            <li class="group flex justify-between items-center p-2 rounded-xl border border-gray-100 hover:border-emerald-200 hover:bg-emerald-50/30 transition">
                <a href="{{ route('home') }}"
                   class="flex items-center space-x-3 text-gray-700 group-hover:text-emerald-600 font-medium text-sm transition">
                    <img src="{{ $imgUrl }}"
                         alt="{{ $category->name }}"
                         class="w-7 h-7 object-contain rounded-md group-hover:scale-105 transition"
                         loading="lazy" />
                    <span>{{ $category->name }}</span>
                </a>
                <span class="text-xs bg-emerald-100 text-emerald-700 font-semibold px-2.5 py-0.5 rounded-full">
                    {{ $category->products_count ?? 0 }}
                </span>
            </li>
        @endforeach
    </ul>
</div>

