<div wire:poll.5s class="bg-white p-5 rounded-xl shadow-[5px_5px_15px_rgba(0,0,0,0.05)] border border-gray-200">
    <h3 class="text-xl font-bold mb-4 text-gray-800">Category</h3>
    <ul class="space-y-3">
        @foreach ($categorys as $category)
            <li class="group flex justify-between items-center p-2 rounded-md border border-gray-100 hover:border-emerald-200 transition">
                <a href="#"
                   class="flex items-center space-x-3 text-gray-700 group-hover:text-emerald-500 transition">
                    <img src="https://placehold.co/32x32/FEF3C7/F59E0B?text=C"
                         alt="Clothing"
                         class="rounded-md group-hover:scale-105 transition" />
                    <span>{{ $category->name }}</span>
                </a>
                <span class="text-sm bg-green-200 text-gray-600 px-2 py-0.5 rounded-full">
                    {{ $categorysTotalProduct[$category->id] ?? 0 }}
                </span>
            </li>
        @endforeach
    </ul>
</div>
