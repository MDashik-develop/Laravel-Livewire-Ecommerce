<section>
    <livewire:utilities.toast-modal />

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-2 border-b border-gray-200 dark:border-zinc-700">
            <div>
                <flux:breadcrumbs class="mb-2">
                    <flux:breadcrumbs.item :href="route('dashboard')" wire:navigate>Dashboard</flux:breadcrumbs.item>
                    <flux:breadcrumbs.item>Catalog</flux:breadcrumbs.item>
                    <flux:breadcrumbs.item>Products</flux:breadcrumbs.item>
                </flux:breadcrumbs>
                <flux:heading size="xl">Product Catalog</flux:heading>
                <flux:subheading>Manage single products, variable multi-attribute items, gallery media, and inventory stock tracking.</flux:subheading>
            </div>

            @can('product.create')
                <flux:button :href="route('backend.products.create')" wire:navigate variant="primary" icon="plus" class="shadow-sm cursor-pointer">
                    Create Product
                </flux:button>
            @endcan
        </div>

        <!-- Search and Filters -->
        <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 shadow-xs rounded-2xl overflow-hidden">
            <div class="p-4 flex flex-col sm:flex-row justify-between items-center gap-3">
                <div class="w-full sm:max-w-md">
                    <flux:input wire:model.live.debounce.300ms="search" placeholder="Search by name, slug, or SKU..." icon="magnifying-glass" clearable />
                </div>

                <div class="flex items-center gap-2">
                    <flux:dropdown>
                        <flux:button icon:trailing="funnel" variant="outline" size="sm">Columns</flux:button>
                        <flux:menu keep-open class="w-56 p-2 space-y-1">
                            <div class="px-2 py-1.5 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100 dark:border-zinc-700/60 mb-1 flex items-center justify-between">
                                <span>Visible Columns</span>
                                <span class="text-[10px] font-normal text-indigo-600 dark:text-indigo-400 font-mono">{{ count($visibleColumns) }}/{{ count($columns) }}</span>
                            </div>
                            <div class="space-y-0.5 max-h-72 overflow-y-auto pr-1">
                                @foreach ($columns as $col)
                                    @php $isSelected = in_array($col['key'], $visibleColumns); @endphp
                                    <button type="button"
                                        wire:click="toggleColumn('{{ $col['key'] }}')"
                                        class="w-full flex items-center justify-between px-2.5 py-1.5 text-xs rounded-lg transition text-left cursor-pointer group {{ $isSelected ? 'bg-indigo-50/80 dark:bg-indigo-950/40 text-indigo-900 dark:text-indigo-200 font-medium' : 'text-gray-600 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-700/60' }}">
                                        <span class="flex items-center gap-2.5">
                                            <span class="size-4 rounded flex items-center justify-center transition border {{ $isSelected ? 'bg-indigo-600 border-indigo-600 text-white shadow-2xs' : 'border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 group-hover:border-indigo-400' }}">
                                                @if ($isSelected)
                                                    <svg class="size-3 stroke-[3]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                @endif
                                            </span>
                                            <span>{{ $col['label'] }}</span>
                                        </span>
                                        @if ($isSelected)
                                            <span class="size-1.5 rounded-full bg-indigo-600 dark:bg-indigo-400"></span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </flux:menu>
                    </flux:dropdown>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm divide-y divide-gray-200 dark:divide-zinc-700">
                    <thead class="bg-gray-50/80 dark:bg-zinc-800/80 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <tr>
                            @foreach ($columns as $col)
                                @if (in_array($col['key'], $visibleColumns))
                                    <th class="px-5 py-3.5 whitespace-nowrap {{ in_array($col['key'], ['image', 'type', 'status', 'actions']) ? 'text-center' : '' }}"
                                        @if (!empty($col['sortable'])) wire:click="sortBy('{{ $col['key'] }}')" class="cursor-pointer select-none hover:text-indigo-600" @endif>
                                        {{ $col['label'] }}
                                        @if ($sortField === $col['key'])
                                            <flux:icon :name="$sortDirection === 'asc' ? 'chevron-up' : 'chevron-down'" class="inline size-3.5 ml-1 text-indigo-600" />
                                        @endif
                                    </th>
                                @endif
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-zinc-700 bg-white dark:bg-zinc-800">
                        @forelse($products as $product)
                            @php
                                $isSearching = !empty($search);
                                $hasVariants = $product->has_variants && $product->variants->where('is_default', false)->isNotEmpty();
                                $variantsList = $hasVariants ? $product->variants->where('is_default', false) : collect();

                                if ($isSearching && $hasVariants) {
                                    $searchLower = strtolower(trim($search));
                                    $matching = $variantsList->filter(function($v) use ($searchLower) {
                                        if (str_contains(strtolower($v->sku ?? ''), $searchLower)) return true;
                                        if (str_contains(strtolower($v->barcode ?? ''), $searchLower)) return true;
                                        foreach ($v->variantAttributes as $va) {
                                            if (str_contains(strtolower($va->attributeValue?->value ?? ''), $searchLower)) return true;
                                            if (str_contains(strtolower($va->attribute?->name ?? ''), $searchLower)) return true;
                                        }
                                        return false;
                                    });
                                    if ($matching->isNotEmpty()) {
                                        $variantsList = $matching;
                                    }
                                }
                            @endphp

                            @if ($isSearching && $hasVariants)
                                {{-- Search Mode: Render each variant as its own row --}}
                                @foreach ($variantsList as $var)
                                    <tr wire:key="variant-search-row-{{ $product->id }}-{{ $var->id }}" class="hover:bg-indigo-50/40 dark:hover:bg-zinc-700/40 transition">
                                        @foreach ($columns as $col)
                                            @if (in_array($col['key'], $visibleColumns))
                                                <td class="px-5 py-4 whitespace-nowrap {{ in_array($col['key'], ['image', 'type', 'status', 'actions']) ? 'text-center' : '' }}">
                                                    @switch($col['key'])
                                                        @case('id')
                                                            <div>
                                                                <span class="font-mono text-xs text-gray-500">#{{ $product->id }}</span>
                                                                <span class="text-[10px] text-purple-600 dark:text-purple-400 font-mono block font-semibold">var#{{ $var->id }}</span>
                                                            </div>
                                                        @break

                                                        @case('image')
                                                            @php
                                                                $displayMedia = $var->resolved_media ?: $product->media;
                                                                $mediaThumb = $displayMedia ? ($displayMedia->urls['thumb'] ?? $displayMedia->urls['small'] ?? $displayMedia->url) : null;
                                                            @endphp
                                                            <div class="flex justify-center">
                                                                @if ($mediaThumb)
                                                                    <img src="{{ $mediaThumb }}"
                                                                        alt="{{ $product->name }}"
                                                                        class="h-11 w-11 rounded-xl object-cover border border-gray-200 dark:border-zinc-700 shadow-2xs">
                                                                @else
                                                                    <div class="h-11 w-11 bg-gray-100 dark:bg-zinc-700 rounded-xl flex items-center justify-center text-gray-400 text-[10px] font-semibold">
                                                                        No Img
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @break

                                                        @case('name')
                                                            <div>
                                                                <span class="font-bold text-gray-900 dark:text-white block hover:text-indigo-600 transition">
                                                                    {{ $product->name }}
                                                                </span>
                                                                <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                                                    @foreach ($var->variantAttributes as $va)
                                                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[11px] font-medium bg-gray-100 dark:bg-zinc-700 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-zinc-600">
                                                                            @if ($va->attributeValue?->color_code)
                                                                                <span class="w-2.5 h-2.5 rounded-full border border-gray-300 inline-block" style="background-color: {{ $va->attributeValue->color_code }}"></span>
                                                                            @endif
                                                                            <span class="text-gray-400 text-[10px]">{{ $va->attribute?->name }}:</span>
                                                                            <span class="font-semibold">{{ $va->attributeValue?->value }}</span>
                                                                        </span>
                                                                    @endforeach
                                                                    @if ($var->barcode)
                                                                        <span class="text-[10px] text-gray-400 font-mono">({{ $var->barcode }})</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @break

                                                        @case('type')
                                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                                                                Variant
                                                            </span>
                                                        @break

                                                        @case('category')
                                                            <span class="text-xs text-gray-600 dark:text-gray-300 font-medium">
                                                                {{ $product->category->name ?? '-' }}
                                                            </span>
                                                        @break

                                                        @case('brand')
                                                            <span class="text-xs text-gray-600 dark:text-gray-300">
                                                                {{ $product->brand->name ?? '-' }}
                                                            </span>
                                                        @break

                                                        @case('sku')
                                                            <span class="font-mono text-xs text-gray-800 dark:text-gray-200 font-semibold bg-gray-100 dark:bg-zinc-700/80 px-1.5 py-0.5 rounded border border-gray-200 dark:border-zinc-600">
                                                                {{ $var->sku ?: ($product->sku ?: '-') }}
                                                            </span>
                                                        @break

                                                        @case('price')
                                                            <div>
                                                                <span class="font-bold text-gray-900 dark:text-white">
                                                                    ${{ number_format((float) ($var->selling_price ?: 0), 2) }}
                                                                </span>
                                                                @if ($var->discount_price)
                                                                    <span class="text-xs text-gray-400 line-through block">
                                                                        ${{ number_format((float) $var->discount_price, 2) }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        @break

                                                        @case('stock')
                                                            @if ($var->stock > 0)
                                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">
                                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                                    {{ $var->stock }} in stock
                                                                </span>
                                                            @else
                                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300">
                                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                                    Out of stock
                                                                </span>
                                                            @endif
                                                        @break

                                                        @case('status')
                                                            <flux:badge :color="($var->status && $product->status) ? 'green' : 'red'">
                                                                {{ ($var->status && $product->status) ? 'Active' : 'Inactive' }}
                                                            </flux:badge>
                                                        @break

                                                        @case('actions')
                                                            <div class="flex items-center justify-center gap-1.5">
                                                                <button type="button"
                                                                    wire:click="viewStockHistory({{ $product->id }})"
                                                                    title="View Stock Activity History"
                                                                    class="p-1.5 text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-lg hover:bg-indigo-50 dark:hover:bg-zinc-700 transition cursor-pointer">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                                                </button>

                                                                @can('product.edit')
                                                                    <button type="button"
                                                                        wire:click="edit({{ $product->id }})"
                                                                        class="p-1.5 text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-700 transition cursor-pointer">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                                    </button>
                                                                @endcan

                                                                @can('product.delete')
                                                                    <button type="button"
                                                                        wire:click="confirmDelete({{ $product->id }})"
                                                                        class="p-1.5 text-gray-500 hover:text-rose-600 dark:hover:text-rose-400 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/40 transition cursor-pointer">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                                    </button>
                                                                @endcan
                                                            </div>
                                                        @break
                                                    @endswitch
                                                </td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                            @else
                                {{-- Standard Catalog Mode: Product Row --}}
                                <tr wire:key="product-row-{{ $product->id }}" class="hover:bg-gray-50/60 dark:hover:bg-zinc-700/40 transition">
                                    @foreach ($columns as $col)
                                        @if (in_array($col['key'], $visibleColumns))
                                            <td class="px-5 py-4 whitespace-nowrap {{ in_array($col['key'], ['image', 'type', 'status', 'actions']) ? 'text-center' : '' }}">
                                                @switch($col['key'])
                                                    @case('id')
                                                        <span class="font-mono text-xs text-gray-500">#{{ $product->id }}</span>
                                                    @break

                                                    @case('image')
                                                        @php
                                                            $mainMedia = $product->media;
                                                            $mediaThumb = $mainMedia ? ($mainMedia->urls['thumb'] ?? $mainMedia->urls['small'] ?? $mainMedia->url) : null;
                                                        @endphp
                                                        <div class="flex justify-center">
                                                            @if ($mediaThumb)
                                                                <img src="{{ $mediaThumb }}"
                                                                    alt="{{ $product->name }}"
                                                                    class="h-11 w-11 rounded-xl object-cover border border-gray-200 dark:border-zinc-700 shadow-2xs">
                                                            @else
                                                                <div class="h-11 w-11 bg-gray-100 dark:bg-zinc-700 rounded-xl flex items-center justify-center text-gray-400 text-[10px] font-semibold">
                                                                    No Img
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @break

                                                    @case('name')
                                                        <div>
                                                            <span class="font-bold text-gray-900 dark:text-white block hover:text-indigo-600 transition">
                                                                {{ $product->name }}
                                                            </span>
                                                            <span class="text-xs text-gray-400 font-mono">{{ $product->slug }}</span>
                                                        </div>
                                                    @break

                                                    @case('type')
                                                        @if ($hasVariants)
                                                            <button type="button"
                                                                wire:click="toggleExpand({{ $product->id }})"
                                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-100 hover:bg-purple-200 dark:bg-purple-950 dark:hover:bg-purple-900 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800 transition cursor-pointer">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                                                                Variable ({{ $variantsList->count() }})
                                                                <svg class="w-3 h-3 transition-transform duration-200 {{ in_array($product->id, $expandedProducts) ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                                            </button>
                                                        @else
                                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                                                Single
                                                            </span>
                                                        @endif
                                                    @break

                                                    @case('category')
                                                        <span class="text-xs text-gray-600 dark:text-gray-300 font-medium">
                                                            {{ $product->category->name ?? '-' }}
                                                        </span>
                                                    @break

                                                    @case('brand')
                                                        <span class="text-xs text-gray-600 dark:text-gray-300">
                                                            {{ $product->brand->name ?? '-' }}
                                                        </span>
                                                    @break

                                                    @case('sku')
                                                        <span class="font-mono text-xs text-gray-700 dark:text-gray-300 font-semibold">
                                                            {{ $product->sku ?: '-' }}
                                                        </span>
                                                    @break

                                                    @case('price')
                                                        <div>
                                                            <span class="font-bold text-gray-900 dark:text-white">
                                                                ${{ number_format((float) ($product->price ?: 0), 2) }}
                                                            </span>
                                                            @if ($product->discount_price)
                                                                <span class="text-xs text-gray-400 line-through block">
                                                                    ${{ number_format((float) $product->discount_price, 2) }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    @break

                                                    @case('stock')
                                                        @if ($product->stock > 0)
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">
                                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                                {{ $product->stock }} in stock
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300">
                                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                                Out of stock
                                                            </span>
                                                        @endif
                                                    @break

                                                    @case('status')
                                                        <flux:badge :color="$product->status ? 'green' : 'red'">
                                                            {{ $product->status ? 'Active' : 'Inactive' }}
                                                        </flux:badge>
                                                    @break

                                                    @case('actions')
                                                        <div class="flex items-center justify-center gap-1.5">
                                                            <!-- Stock Activity Log Button -->
                                                            <button type="button"
                                                                wire:click="viewStockHistory({{ $product->id }})"
                                                                title="View Stock Activity History"
                                                                class="p-1.5 text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-lg hover:bg-indigo-50 dark:hover:bg-zinc-700 transition cursor-pointer">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                                            </button>

                                                            @can('product.edit')
                                                                <a href="{{ route('backend.products.edit', $product->id) }}"
                                                                    wire:navigate
                                                                    title="Edit Product"
                                                                    class="p-1.5 text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-700 transition cursor-pointer inline-flex items-center justify-center">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                                </a>
                                                            @endcan

                                                            @can('product.delete')
                                                                <button type="button"
                                                                    wire:click="confirmDelete({{ $product->id }})"
                                                                    class="p-1.5 text-gray-500 hover:text-rose-600 dark:hover:text-rose-400 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/40 transition cursor-pointer">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                                </button>
                                                            @endcan
                                                        </div>
                                                    @break
                                                @endswitch
                                            </td>
                                        @endif
                                    @endforeach
                                </tr>

                                {{-- If Expanded when not searching, show variant sub-rows --}}
                                @if ($hasVariants && in_array($product->id, $expandedProducts))
                                    @foreach ($variantsList as $var)
                                        <tr wire:key="expanded-variant-{{ $product->id }}-{{ $var->id }}" class="bg-indigo-50/20 dark:bg-zinc-800/40 hover:bg-indigo-50/40 dark:hover:bg-zinc-700/40 transition border-l-4 border-purple-500">
                                            @foreach ($columns as $col)
                                                @if (in_array($col['key'], $visibleColumns))
                                                    <td class="px-5 py-3 whitespace-nowrap {{ in_array($col['key'], ['image', 'type', 'status', 'actions']) ? 'text-center' : '' }}">
                                                        @switch($col['key'])
                                                            @case('id')
                                                                <span class="text-[10px] text-purple-600 dark:text-purple-400 font-mono font-semibold">↳ var#{{ $var->id }}</span>
                                                            @break

                                                            @case('image')
                                                                @php
                                                                    $displayMedia = $var->resolved_media ?: $product->media;
                                                                    $mediaThumb = $displayMedia ? ($displayMedia->urls['thumb'] ?? $displayMedia->urls['small'] ?? $displayMedia->url) : null;
                                                                @endphp
                                                                <div class="flex justify-center">
                                                                    @if ($mediaThumb)
                                                                        <img src="{{ $mediaThumb }}"
                                                                            alt="{{ $product->name }}"
                                                                            class="h-9 w-9 rounded-lg object-cover border border-gray-200 dark:border-zinc-700 shadow-2xs">
                                                                    @else
                                                                        <div class="h-9 w-9 bg-gray-100 dark:bg-zinc-700 rounded-lg flex items-center justify-center text-gray-400 text-[9px] font-semibold">
                                                                            No Img
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @break

                                                            @case('name')
                                                                <div class="pl-2">
                                                                    <div class="flex flex-wrap items-center gap-1.5">
                                                                        @foreach ($var->variantAttributes as $va)
                                                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[11px] font-medium bg-white dark:bg-zinc-700 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-zinc-600 shadow-2xs">
                                                                                @if ($va->attributeValue?->color_code)
                                                                                    <span class="w-2.5 h-2.5 rounded-full border border-gray-300 inline-block" style="background-color: {{ $va->attributeValue->color_code }}"></span>
                                                                                @endif
                                                                                <span class="text-gray-400 text-[10px]">{{ $va->attribute?->name }}:</span>
                                                                                <span class="font-semibold">{{ $va->attributeValue?->value }}</span>
                                                                            </span>
                                                                        @endforeach
                                                                        @if ($var->barcode)
                                                                            <span class="text-[10px] text-gray-400 font-mono">({{ $var->barcode }})</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @break

                                                            @case('type')
                                                                <span class="text-[10px] uppercase font-bold tracking-wider text-purple-600 dark:text-purple-400">Variant</span>
                                                            @break

                                                            @case('category')
                                                                <span class="text-xs text-gray-400">-</span>
                                                            @break

                                                            @case('brand')
                                                                <span class="text-xs text-gray-400">-</span>
                                                            @break

                                                            @case('sku')
                                                                <span class="font-mono text-xs text-gray-800 dark:text-gray-200 font-medium">
                                                                    {{ $var->sku ?: '-' }}
                                                                </span>
                                                            @break

                                                            @case('price')
                                                                <div>
                                                                    <span class="font-semibold text-gray-900 dark:text-white text-xs">
                                                                        ${{ number_format((float) ($var->selling_price ?: 0), 2) }}
                                                                    </span>
                                                                    @if ($var->discount_price)
                                                                        <span class="text-[11px] text-gray-400 line-through block">
                                                                            ${{ number_format((float) $var->discount_price, 2) }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            @break

                                                            @case('stock')
                                                                @if ($var->stock > 0)
                                                                    <span class="text-xs font-medium text-emerald-600 dark:text-emerald-400">
                                                                        {{ $var->stock }} in stock
                                                                    </span>
                                                                @else
                                                                    <span class="text-xs font-medium text-rose-500">
                                                                        Out of stock
                                                                    </span>
                                                                @endif
                                                            @break

                                                            @case('status')
                                                                <span class="inline-block w-2 h-2 rounded-full {{ ($var->status && $product->status) ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                                            @break

                                                            @case('actions')
                                                                <span class="text-xs text-gray-300 dark:text-zinc-600">-</span>
                                                            @break
                                                        @endswitch
                                                    </td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endif
                            @endif
                        @empty
                            <tr>
                                <td colspan="{{ count($visibleColumns) }}" class="px-6 py-12 text-center text-sm text-gray-400">
                                    No products found matching your search.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($products->hasPages())
                <div class="p-4 border-t border-gray-200 dark:border-zinc-700">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Stock Activity History Modal -->
    <flux:modal name="stock-history-modal" class="md:max-w-4xl">
        <div class="space-y-4">
            <div class="pb-3 border-b border-gray-200 dark:border-zinc-700 flex justify-between items-center">
                <div>
                    <flux:heading size="lg">Stock Activity History</flux:heading>
                    <flux:subheading>Audit log of all stock movements for: <strong class="text-indigo-600">{{ $historyProductName }}</strong></flux:subheading>
                </div>
            </div>

            <div class="overflow-x-auto max-h-96">
                <table class="w-full text-left text-sm divide-y divide-gray-200 dark:divide-zinc-700">
                    <thead class="bg-gray-50/80 dark:bg-zinc-800/80 text-xs font-semibold text-gray-500 uppercase">
                        <tr>
                            <th class="px-4 py-3">Timestamp</th>
                            <th class="px-4 py-3">Variant / Item</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3 text-right">Change</th>
                            <th class="px-4 py-3 text-center">Old ➔ New</th>
                            <th class="px-4 py-3">Reason</th>
                            <th class="px-4 py-3">User</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                        @forelse ($productStockLogs as $log)
                            <tr class="hover:bg-gray-50/60 dark:hover:bg-zinc-700/40 text-xs">
                                <td class="px-4 py-3 font-mono text-gray-500">
                                    {{ $log->created_at->format('M d, Y H:i:s') }}
                                </td>
                                <td class="px-4 py-3 font-semibold text-gray-800 dark:text-gray-200">
                                    @if ($log->variant)
                                        {{ $log->variant->attribute_summary }}
                                    @else
                                        Default
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold
                                        {{ $log->type === 'sale' ? 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300' : '' }}
                                        {{ $log->type === 'initial' ? 'bg-sky-100 text-sky-800 dark:bg-sky-950 dark:text-sky-300' : '' }}
                                        {{ $log->type === 'manual_adjustment' ? 'bg-purple-100 text-purple-800 dark:bg-purple-950 dark:text-purple-300' : '' }}
                                        {{ $log->type === 'restock' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : '' }}">
                                        {{ ucfirst(str_replace('_', ' ', $log->type)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right font-mono font-bold {{ $log->quantity_change >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $log->quantity_change >= 0 ? '+' . $log->quantity_change : $log->quantity_change }}
                                </td>
                                <td class="px-4 py-3 text-center font-mono text-gray-600 dark:text-gray-400">
                                    {{ $log->old_stock }} ➔ <span class="font-bold text-gray-900 dark:text-white">{{ $log->new_stock }}</span>
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                    {{ $log->reason ?: '-' }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                    {{ $log->user?->name ?: 'System' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-xs text-gray-400">
                                    No stock activity logged yet for this product.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end pt-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Close</flux:button>
                </flux:modal.close>
            </div>
        </div>
    </flux:modal>

    <!-- Delete Confirmation Modal -->
    <flux:modal name="delete-modal" class="md:w-96">
        <div class="space-y-4">
            <div>
                <flux:heading size="lg">Delete Product?</flux:heading>
                <flux:text class="mt-2">
                    Are you sure you want to permanently delete this product, all its variants, galleries, and inventory history?
                </flux:text>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button wire:click="delete" type="button" variant="danger">
                    Delete
                </flux:button>
            </div>
        </div>
    </flux:modal>
</section>
