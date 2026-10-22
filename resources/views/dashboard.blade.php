<x-layouts.app :title="__('Dashboard Overview')">
    @php
        $totalProducts = \App\Models\Product::count();
        $totalCategories = \App\Models\Category::count();
        $totalBrands = \App\Models\Brand::count();
        $totalBanners = \App\Models\Banner::count();
        $recentProducts = \App\Models\Product::with(['media', 'category'])->latest()->take(5)->get();
    @endphp

    <!-- Action bar & Section Headline (Matching demo.html) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white dark:bg-slate-900 p-4 border border-slate-200 dark:border-slate-800 rounded-sm shadow-2xs">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="font-bold text-lg text-slate-900 dark:text-white">Enterprise Management Dashboard</h1>
                <span class="text-[10px] font-semibold uppercase bg-emerald-50 dark:bg-emerald-950/70 text-emerald-700 dark:text-emerald-400 px-2 py-0.5 rounded-xs border border-emerald-200 dark:border-emerald-800">
                    System: Online
                </span>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                Overview of catalog metrics, system cache layer, inventory and quick management actions.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('home') }}" target="_blank" class="px-3 py-1.5 text-xs font-medium border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 rounded-sm transition-colors flex items-center gap-1.5">
                <i data-lucide="external-link" class="w-3.5 h-3.5 text-emerald-500"></i> Live Store
            </a>
            <button onclick="showToast('Application cache successfully refreshed.')" class="px-3.5 py-1.5 text-xs font-semibold bg-brand-600 hover:bg-brand-700 text-white rounded-sm transition-colors flex items-center gap-1.5 shadow-2xs cursor-pointer">
                <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i> Purge Cache
            </button>
        </div>
    </div>

    <!-- Metrics Overview Cards (Matching demo.html) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <!-- Products -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-3.5 rounded-sm">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">Total Products</span>
                <i data-lucide="package" class="w-4 h-4 text-brand-600"></i>
            </div>
            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ $totalProducts }}</p>
            <p class="text-[10px] text-slate-400 mt-1 font-mono">Catalog active items</p>
        </div>

        <!-- Categories -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-3.5 rounded-sm">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">Categories</span>
                <i data-lucide="folder-tree" class="w-4 h-4 text-emerald-600"></i>
            </div>
            <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">{{ $totalCategories }}</p>
            <p class="text-[10px] text-slate-400 mt-1">Hierarchical taxonomy</p>
        </div>

        <!-- Brands -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-3.5 rounded-sm">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">Brands & Vendors</span>
                <i data-lucide="tag" class="w-4 h-4 text-amber-500"></i>
            </div>
            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ $totalBrands }}</p>
            <p class="text-[10px] text-slate-400 mt-1 font-mono">Verified partners</p>
        </div>

        <!-- Banners -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-3.5 rounded-sm">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">Banners & Sliders</span>
                <i data-lucide="presentation" class="w-4 h-4 text-purple-600"></i>
            </div>
            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ $totalBanners }}</p>
            <p class="text-[10px] text-slate-400 mt-1">Hero, featured & aside</p>
        </div>
    </div>

    <!-- Quick Navigation & Recent Products Container (Matching demo.html styling) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Recent Products Table (2 Cols) -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-sm">
            <div class="p-3.5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/40">
                <div>
                    <h2 class="font-bold text-xs uppercase tracking-wider text-slate-800 dark:text-slate-200">
                        Latest Catalog Products
                    </h2>
                    <p class="text-[11px] text-slate-400">Recently created inventory items</p>
                </div>
                <a href="{{ route('backend.products.index') }}" wire:navigate class="text-[11px] font-semibold text-brand-600 dark:text-brand-400 hover:underline flex items-center gap-1">
                    <span>View All</span>
                    <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </a>
            </div>

            <div class="p-3 overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800 text-[10px] uppercase font-bold text-slate-400">
                            <th class="pb-2">Product</th>
                            <th class="pb-2">Category</th>
                            <th class="pb-2">Price</th>
                            <th class="pb-2">Stock</th>
                            <th class="pb-2 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($recentProducts as $prod)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                                <td class="py-2.5 font-medium text-slate-800 dark:text-slate-200 flex items-center gap-2">
                                    @php
                                        $img = $prod->media?->url ?? 'https://placehold.co/40x40/f3f4f6/9ca3af?text=' . urlencode(substr($prod->name, 0, 1));
                                    @endphp
                                    <img src="{{ $img }}" alt="{{ $prod->name }}" class="w-7 h-7 object-cover rounded-xs border border-slate-200 dark:border-slate-700">
                                    <span class="truncate max-w-[180px]">{{ $prod->name }}</span>
                                </td>
                                <td class="py-2.5 text-slate-500">{{ $prod->category?->name ?? 'Unassigned' }}</td>
                                <td class="py-2.5 font-semibold text-emerald-600">${{ number_format($prod->price, 2) }}</td>
                                <td class="py-2.5">
                                    <span class="px-1.5 py-0.5 rounded-xs text-[10px] font-bold font-mono {{ $prod->stock > 5 ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-rose-50 text-rose-600 border border-rose-200' }}">
                                        {{ $prod->stock }} pcs
                                    </span>
                                </td>
                                <td class="py-2.5 text-right">
                                    <a href="{{ route('backend.products.index') }}" wire:navigate class="p-1 text-slate-400 hover:text-brand-600 transition">
                                        <i data-lucide="external-link" class="w-3.5 h-3.5 inline"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-slate-400">No products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Management Modules (1 Col) -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-sm">
            <div class="p-3.5 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40">
                <h2 class="font-bold text-xs uppercase tracking-wider text-slate-800 dark:text-slate-200">
                    Quick Management
                </h2>
                <p class="text-[11px] text-slate-400">Direct shortcuts to critical workflows</p>
            </div>

            <div class="p-3 space-y-2">
                <a href="{{ route('backend.categories.index') }}" wire:navigate class="flex items-center justify-between p-2.5 rounded-sm border border-slate-200 dark:border-slate-800 hover:border-brand-500 hover:bg-brand-50/30 dark:hover:bg-brand-950/20 transition group">
                    <div class="flex items-center gap-2.5">
                        <i data-lucide="folder-tree" class="w-4 h-4 text-brand-600"></i>
                        <div>
                            <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 group-hover:text-brand-600">Manage Categories</p>
                            <p class="text-[10px] text-slate-400">Organize store taxonomy</p>
                        </div>
                    </div>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400 group-hover:translate-x-0.5 transition"></i>
                </a>

                <a href="{{ route('backend.banners.index') }}" wire:navigate class="flex items-center justify-between p-2.5 rounded-sm border border-slate-200 dark:border-slate-800 hover:border-brand-500 hover:bg-brand-50/30 dark:hover:bg-brand-950/20 transition group">
                    <div class="flex items-center gap-2.5">
                        <i data-lucide="presentation" class="w-4 h-4 text-purple-600"></i>
                        <div>
                            <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 group-hover:text-purple-600">Banners & Sliders</p>
                            <p class="text-[10px] text-slate-400">Sliders, hero & sidebar banners</p>
                        </div>
                    </div>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400 group-hover:translate-x-0.5 transition"></i>
                </a>

                <a href="{{ route('backend.stores.index') }}" wire:navigate class="flex items-center justify-between p-2.5 rounded-sm border border-slate-200 dark:border-slate-800 hover:border-brand-500 hover:bg-brand-50/30 dark:hover:bg-brand-950/20 transition group">
                    <div class="flex items-center gap-2.5">
                        <i data-lucide="store" class="w-4 h-4 text-emerald-600"></i>
                        <div>
                            <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 group-hover:text-emerald-600">Store Configuration</p>
                            <p class="text-[10px] text-slate-400">Store profile, contact & settings</p>
                        </div>
                    </div>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400 group-hover:translate-x-0.5 transition"></i>
                </a>

                <a href="{{ route('backend.permissions.index') }}" wire:navigate class="flex items-center justify-between p-2.5 rounded-sm border border-slate-200 dark:border-slate-800 hover:border-brand-500 hover:bg-brand-50/30 dark:hover:bg-brand-950/20 transition group">
                    <div class="flex items-center gap-2.5">
                        <i data-lucide="shield-check" class="w-4 h-4 text-amber-600"></i>
                        <div>
                            <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 group-hover:text-amber-600">Security & Roles</p>
                            <p class="text-[10px] text-slate-400">Manage user access permissions</p>
                        </div>
                    </div>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400 group-hover:translate-x-0.5 transition"></i>
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
