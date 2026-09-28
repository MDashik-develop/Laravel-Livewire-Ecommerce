<section class="min-h-screen pb-16" x-data x-init="
    // Ensure Summernote initializes on this page after Alpine/Livewire boots
    $nextTick(() => {
        if (window.initializePageContent) {
            window.initializePageContent();
            setTimeout(window.initializePageContent, 200);
            setTimeout(window.initializePageContent, 500);
        }
    });
">
    <livewire:utilities.toast-modal />

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <!-- Breadcrumbs & Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-4 border-b border-gray-200 dark:border-zinc-700">
            <div>
                <flux:breadcrumbs class="mb-2">
                    <flux:breadcrumbs.item :href="route('dashboard')" wire:navigate>Dashboard</flux:breadcrumbs.item>
                    <flux:breadcrumbs.item>Catalog</flux:breadcrumbs.item>
                    <flux:breadcrumbs.item :href="route('backend.products.index')" wire:navigate>Products</flux:breadcrumbs.item>
                    <flux:breadcrumbs.item>{{ $productId ? 'Edit Product' : 'Create Product' }}</flux:breadcrumbs.item>
                </flux:breadcrumbs>

                <div class="flex items-center gap-3">
                    <flux:heading size="xl">
                        {{ $productId ? 'Edit Product: ' . ($name ?: '#' . $productId) : 'Create New Product' }}
                    </flux:heading>
                    @if ($productId)
                        <flux:badge :color="$status ? 'green' : 'red'">
                            {{ $status ? 'Active' : 'Inactive' }}
                        </flux:badge>
                    @endif
                </div>
                <flux:subheading>
                    Configure product attributes, manage pricing, upload color-ways media, and control inventory.
                </flux:subheading>
            </div>

            <div class="flex items-center gap-2.5 self-end sm:self-center">
                <flux:button :href="route('backend.products.index')" wire:navigate variant="ghost" icon="arrow-left" class="cursor-pointer">
                    Back to Catalog
                </flux:button>
                <flux:button type="submit" form="product-form" variant="primary" icon="check" class="shadow-sm cursor-pointer" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="save">
                        {{ $productId ? 'Update Product' : 'Save Product' }}
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        Saving...
                    </span>
                </flux:button>
            </div>
        </div>

        <form id="product-form" wire:submit.prevent="save" onsubmit="syncSummernoteBeforeSave()">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Main Content Column (8 cols) -->
                <div class="lg:col-span-8 space-y-6">
                    
                    <!-- Product Type Card -->
                    <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 shadow-xs rounded-2xl p-5 space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 border-b border-gray-100 dark:border-zinc-700/60">
                            <div>
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                    <span class="p-1 rounded-lg bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                    </span>
                                    Product Classification & Type
                                </h3>
                                <p class="text-xs text-gray-500 mt-0.5">Select standalone item or multi-attribute variable item</p>
                            </div>

                            <div class="inline-flex p-1 bg-gray-100/80 dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-700 shadow-2xs">
                                <button type="button"
                                    wire:click="setProductType(false)"
                                    class="px-4 py-1.5 rounded-lg text-xs font-semibold transition-all flex items-center gap-2 cursor-pointer {{ !$has_variants ? 'bg-indigo-600 text-white shadow-xs' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    <span>Single Product</span>
                                </button>

                                <button type="button"
                                    wire:click="setProductType(true)"
                                    class="px-4 py-1.5 rounded-lg text-xs font-semibold transition-all flex items-center gap-2 cursor-pointer {{ $has_variants ? 'bg-indigo-600 text-white shadow-xs' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                    <span>Variable Product</span>
                                </button>
                            </div>
                        </div>

                        <!-- Basic Information -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <flux:input wire:model.live.debounce.300ms="name" label="Product Name" placeholder="e.g. Wireless Bluetooth Headphones" required />
                            <flux:input wire:model="slug" label="URL Slug" placeholder="wireless-bluetooth-headphones" required />
                        </div>

                        <div>
                            <flux:textarea wire:model="short_description" label="Short Summary Description" rows="2" placeholder="Brief highlight of key specifications or features..." />
                        </div>

                        <!-- Long Description with Summernote -->
                        <div class="space-y-1">
                            <label class="block text-xs font-medium text-gray-700 dark:text-zinc-300">
                                Detailed Product Description
                            </label>
                            <div wire:ignore>
                                <textarea id="long_description_editor"
                                    class="summernote-init w-full"
                                    data-field="long_description"
                                    data-height="240"
                                    placeholder="Write rich product description, specifications, and warranty details...">{!! $long_description !!}</textarea>
                            </div>
                            @error('long_description')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Single Product Pricing & Inventory -->
                    @if (!$has_variants)
                        <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 shadow-xs rounded-2xl p-5 space-y-4">
                            <div class="flex items-center gap-2 pb-3 border-b border-gray-100 dark:border-zinc-700/60">
                                <span class="p-1 rounded-lg bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </span>
                                <div>
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Pricing & Inventory</h3>
                                    <p class="text-xs text-gray-500">Configure prices in sequential order and manage stock</p>
                                </div>
                            </div>

                            <!-- Pricing Fields in Serial Order: Cost -> Selling -> Discount -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <flux:input wire:model="cost_price" type="number" step="0.01" label="Cost Price ($)" placeholder="0.00" />
                                <flux:input wire:model="price" type="number" step="0.01" label="Selling Price ($)" placeholder="0.00" required />
                                <flux:input wire:model="discount_price" type="number" step="0.01" label="Discount Price ($)" placeholder="0.00" />
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                                <flux:input wire:model="stock" type="number" label="Stock Quantity" placeholder="0" min="0" required />
                                <flux:input wire:model="weight" type="number" step="0.01" label="Weight (kg)" placeholder="0.00" />
                                
                                <div>
                                    <flux:input wire:model="sku" label="SKU (Read Only)" readonly disabled class="bg-gray-100 dark:bg-zinc-900/60 font-mono text-xs cursor-not-allowed text-gray-500" />
                                    <p class="text-[10px] text-gray-400 mt-1">Auto-generated from title</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                                <div>
                                    <flux:input wire:model="barcode" label="Barcode (Read Only)" readonly disabled class="bg-gray-100 dark:bg-zinc-900/60 font-mono text-xs cursor-not-allowed text-gray-500" />
                                    <p class="text-[10px] text-gray-400 mt-1">Unique standard barcode automatically assigned</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Variable Product Configuration -->
                    @if ($has_variants)
                        <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 shadow-xs rounded-2xl p-5 space-y-6">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-gray-100 dark:border-zinc-700/60">
                                <div>
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                        <span class="p-1 rounded-lg bg-purple-50 dark:bg-purple-950 text-purple-600 dark:text-purple-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                        </span>
                                        Select Attributes & Options
                                    </h3>
                                    <p class="text-xs text-gray-500">Pick attribute options to automatically generate all variant combinations</p>
                                </div>

                                <flux:button type="button" wire:click="generateVariants" variant="primary" size="sm" icon="sparkles" class="cursor-pointer">
                                    Generate Combinations
                                </flux:button>
                            </div>

                            <!-- Attributes List -->
                            <div class="space-y-4">
                                @forelse ($allAttributes as $attr)
                                    @php
                                        $selectedVals = $selectedAttributes[$attr->id] ?? [];
                                    @endphp
                                    <div class="p-3.5 rounded-xl border border-gray-100 dark:border-zinc-700/60 bg-gray-50/50 dark:bg-zinc-900/30 space-y-2.5">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider flex items-center gap-1.5">
                                                <span>{{ $attr->name }}</span>
                                                <span class="text-[10px] font-normal text-gray-400 capitalize">({{ $attr->type }})</span>
                                            </span>
                                            <span class="text-[10px] text-gray-400 font-mono">
                                                {{ count($selectedVals) }} selected
                                            </span>
                                        </div>

                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($attr->values as $val)
                                                @php $isChecked = in_array($val->id, $selectedVals); @endphp
                                                <button type="button"
                                                    wire:click="toggleAttributeValue({{ $attr->id }}, {{ $val->id }})"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-medium border transition cursor-pointer {{ $isChecked ? 'bg-indigo-600 border-indigo-600 text-white shadow-2xs' : 'bg-white dark:bg-zinc-800 border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-zinc-300 hover:border-indigo-400' }}">
                                                    @if ($val->color_code)
                                                        <span class="w-3 h-3 rounded-full border border-gray-300 dark:border-zinc-600 inline-block shrink-0 shadow-2xs" style="background-color: {{ $val->color_code }}"></span>
                                                    @endif
                                                    <span>{{ $val->value }}</span>
                                                    @if ($isChecked)
                                                        <svg class="w-3 h-3 stroke-[3]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                    @endif
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-4 text-center text-xs text-gray-400">
                                        No attributes found. Please create attributes (Color, Size, etc.) first.
                                    </div>
                                @endforelse
                            </div>

                            <!-- Color-ways Photo Mapping Section -->
                            @php
                                $selectedColors = [];
                                foreach ($allAttributes as $a) {
                                    if ($a->type === 'color' && !empty($selectedAttributes[$a->id])) {
                                        foreach ($a->values->whereIn('id', $selectedAttributes[$a->id]) as $cv) {
                                            $selectedColors[] = $cv;
                                        }
                                    }
                                }
                            @endphp

                            @if (!empty($selectedColors))
                                <div class="p-4 rounded-xl border border-indigo-100 dark:border-indigo-900/40 bg-indigo-50/30 dark:bg-indigo-950/20 space-y-3">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="p-1 rounded-md bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </span>
                                            <div>
                                                <h4 class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">Color-way Photos (Attribute Photos)</h4>
                                                <p class="text-[11px] text-gray-500">Add 1 photo per colorway. All variants sharing this color will automatically use this photo unless overridden.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 pt-1">
                                        @foreach ($selectedColors as $cVal)
                                            @php
                                                $colorMedia = $colorMediaMap[$cVal->id] ?? null;
                                                $colorImgUrl = $colorMedia['media_url'] ?? null;
                                            @endphp
                                            <div class="p-2.5 rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 flex items-center justify-between gap-2 shadow-2xs">
                                                <div class="flex items-center gap-2 min-w-0">
                                                    <span class="w-4 h-4 rounded-full border border-gray-300 dark:border-zinc-600 shrink-0" style="background-color: {{ $cVal->color_code }}"></span>
                                                    <div class="truncate">
                                                        <p class="text-xs font-semibold text-gray-800 dark:text-zinc-200 truncate">{{ $cVal->value }}</p>
                                                        <p class="text-[10px] text-gray-400 font-mono">{{ $colorImgUrl ? 'Photo set' : 'No photo' }}</p>
                                                    </div>
                                                </div>

                                                <div class="shrink-0 flex items-center gap-1">
                                                    @if ($colorImgUrl)
                                                        <div class="relative group">
                                                            <img src="{{ $colorImgUrl }}" alt="{{ $cVal->value }}" class="h-9 w-9 rounded-lg object-cover border border-gray-200 dark:border-zinc-700">
                                                            <button type="button"
                                                                wire:click="removeColorPhoto({{ $cVal->id }})"
                                                                title="Remove Color Photo"
                                                                class="absolute -top-1.5 -right-1.5 bg-rose-600 text-white rounded-full p-0.5 opacity-0 group-hover:opacity-100 transition shadow-2xs cursor-pointer">
                                                                <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                                            </button>
                                                        </div>
                                                    @else
                                                        <button type="button"
                                                            wire:click="selectColorPhoto({{ $cVal->id }})"
                                                            class="p-1.5 rounded-lg border border-dashed border-gray-300 dark:border-zinc-600 hover:border-indigo-500 text-gray-400 hover:text-indigo-600 text-xs transition cursor-pointer flex items-center gap-1">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                            <span class="text-[10px]">Photo</span>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Generated Variants Section -->
                            @if (!empty($variants))
                                <div class="space-y-3 pt-2">
                                    <div class="flex items-center justify-between pb-2 border-b border-gray-100 dark:border-zinc-700/60">
                                        <div class="flex items-center gap-2">
                                            <h4 class="text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">
                                                Generated Variants ({{ count($variants) }})
                                            </h4>
                                            <span class="text-[10px] text-gray-400">Pricing serial: Cost ➔ Selling ➔ Discount</span>
                                        </div>
                                    </div>

                                    <div class="space-y-3">
                                        @foreach ($variants as $idx => $v)
                                            <div wire:key="variant-row-{{ $idx }}" class="p-4 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/40 dark:bg-zinc-900/40 space-y-3">
                                                <!-- Variant Header -->
                                                <div class="flex flex-wrap items-center justify-between gap-2 pb-2 border-b border-gray-200/60 dark:border-zinc-700/60">
                                                    <div class="flex flex-wrap items-center gap-1.5">
                                                        <span class="text-xs font-bold text-gray-800 dark:text-zinc-200">#{{ $idx + 1 }}</span>
                                                        @foreach ($v['attributes'] as $attrItem)
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-medium bg-white dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 border border-gray-200 dark:border-zinc-700 shadow-2xs">
                                                                @if (!empty($attrItem['color_code']))
                                                                    <span class="w-2.5 h-2.5 rounded-full border border-gray-300 inline-block" style="background-color: {{ $attrItem['color_code'] }}"></span>
                                                                @endif
                                                                <span class="text-gray-400 text-[10px]">{{ $attrItem['attr_name'] }}:</span>
                                                                <span class="font-semibold">{{ $attrItem['val_name'] }}</span>
                                                            </span>
                                                        @endforeach
                                                    </div>

                                                    <div class="flex items-center gap-3">
                                                        <label class="inline-flex items-center gap-1.5 text-xs text-gray-600 dark:text-zinc-400 cursor-pointer">
                                                            <input type="checkbox" wire:model="variants.{{ $idx }}.status" class="rounded text-indigo-600">
                                                            <span>Active</span>
                                                        </label>

                                                        <button type="button"
                                                            wire:click="removeVariantRow({{ $idx }})"
                                                            title="Delete this variant"
                                                            class="p-1 text-gray-400 hover:text-rose-600 transition cursor-pointer">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- Variant Pricing & Stock Grid -->
                                                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                                                    <div>
                                                        <flux:input wire:model="variants.{{ $idx }}.cost_price" type="number" step="0.01" label="Cost ($)" placeholder="0.00" size="sm" />
                                                    </div>
                                                    <div>
                                                        <flux:input wire:model="variants.{{ $idx }}.selling_price" type="number" step="0.01" label="Selling ($)" placeholder="0.00" size="sm" required />
                                                    </div>
                                                    <div>
                                                        <flux:input wire:model="variants.{{ $idx }}.discount_price" type="number" step="0.01" label="Discount ($)" placeholder="0.00" size="sm" />
                                                    </div>
                                                    <div>
                                                        <flux:input wire:model="variants.{{ $idx }}.stock" type="number" label="Stock" placeholder="0" min="0" size="sm" required />
                                                    </div>
                                                    <div>
                                                        <flux:input wire:model="variants.{{ $idx }}.weight" type="number" step="0.01" label="Weight (kg)" placeholder="0.00" size="sm" />
                                                    </div>
                                                </div>

                                                <!-- Variant SKU, Barcode, and Image -->
                                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end pt-1">
                                                    <div>
                                                        <flux:input wire:model="variants.{{ $idx }}.sku" label="SKU (Read Only)" readonly disabled size="sm" class="bg-gray-100 dark:bg-zinc-900/60 font-mono text-xs cursor-not-allowed text-gray-500" />
                                                    </div>

                                                    <div>
                                                        <flux:input wire:model="variants.{{ $idx }}.barcode" label="Barcode (Read Only)" readonly disabled size="sm" class="bg-gray-100 dark:bg-zinc-900/60 font-mono text-xs cursor-not-allowed text-gray-500" />
                                                    </div>

                                                    <!-- Variant Image Picker -->
                                                    @php
                                                        $varMediaUrl = $v['media_url'] ?? null;
                                                        // Fallback to colorway image if empty
                                                        $colorFallbackUrl = null;
                                                        foreach ($v['attributes'] as $at) {
                                                            if (($at['attr_type'] ?? '') === 'color' && !empty($at['media_url'])) {
                                                                $colorFallbackUrl = $at['media_url'];
                                                                break;
                                                            }
                                                        }
                                                        $displayImg = $varMediaUrl ?: $colorFallbackUrl;
                                                    @endphp
                                                    <div class="flex items-center gap-2">
                                                        <div class="relative group shrink-0">
                                                            @if ($displayImg)
                                                                <img src="{{ $displayImg }}" alt="Variant image" class="h-9 w-9 rounded-lg object-cover border border-gray-200 dark:border-zinc-700">
                                                                @if ($varMediaUrl)
                                                                    <button type="button"
                                                                        wire:click="removeVariantImage({{ $idx }})"
                                                                        title="Remove custom variant image"
                                                                        class="absolute -top-1 -right-1 bg-rose-600 text-white rounded-full p-0.5 opacity-0 group-hover:opacity-100 transition shadow-2xs cursor-pointer">
                                                                        <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                                                    </button>
                                                                @endif
                                                            @else
                                                                <div class="h-9 w-9 bg-gray-100 dark:bg-zinc-800 rounded-lg flex items-center justify-center text-gray-400 text-[9px] font-semibold border border-gray-200 dark:border-zinc-700">
                                                                    No Img
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <div class="flex-1 min-w-0">
                                                            <button type="button"
                                                                wire:click="selectVariantImage({{ $idx }})"
                                                                class="w-full text-left px-2.5 py-1.5 rounded-lg border border-gray-200 dark:border-zinc-700 hover:border-indigo-500 bg-white dark:bg-zinc-800 text-xs text-gray-700 dark:text-zinc-300 transition cursor-pointer flex items-center justify-between">
                                                                <span class="truncate text-[11px]">
                                                                    {{ $varMediaUrl ? 'Custom Image' : ($colorFallbackUrl ? 'Using Color Image' : 'Pick Image') }}
                                                                </span>
                                                                <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                </div>

                <!-- Right Sidebar Column (4 cols) -->
                <div class="lg:col-span-4 space-y-6">

                    <!-- Organization & Classification Card -->
                    <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 shadow-xs rounded-2xl p-5 space-y-4">
                        <div class="flex items-center gap-2 pb-3 border-b border-gray-100 dark:border-zinc-700/60">
                            <span class="p-1 rounded-lg bg-blue-50 dark:bg-blue-950 text-blue-600 dark:text-blue-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </span>
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white">Organization & Status</h3>
                        </div>

                        <div class="space-y-3.5">
                            <flux:select wire:model.live="category_id" label="Category" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </flux:select>

                            <flux:select wire:model="sub_category_id" label="Subcategory (Optional)">
                                <option value="">Select Subcategory</option>
                                @foreach ($subcategories as $sub)
                                    <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                @endforeach
                            </flux:select>

                            <flux:select wire:model="brand_id" label="Brand (Optional)">
                                <option value="">Select Brand</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </flux:select>

                            <div class="pt-2 border-t border-gray-100 dark:border-zinc-700/60 space-y-3">
                                <label class="flex items-center justify-between p-2.5 rounded-xl border border-gray-100 dark:border-zinc-700/60 bg-gray-50/50 dark:bg-zinc-900/30 cursor-pointer">
                                    <span class="text-xs font-semibold text-gray-800 dark:text-zinc-200">Active Status</span>
                                    <input type="checkbox" wire:model="status" class="rounded text-indigo-600 focus:ring-indigo-500">
                                </label>

                                <label class="flex items-center justify-between p-2.5 rounded-xl border border-gray-100 dark:border-zinc-700/60 bg-gray-50/50 dark:bg-zinc-900/30 cursor-pointer">
                                    <span class="text-xs font-semibold text-gray-800 dark:text-zinc-200">Featured Product</span>
                                    <input type="checkbox" wire:model="is_featured" class="rounded text-indigo-600 focus:ring-indigo-500">
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Main Thumbnail Card -->
                    <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 shadow-xs rounded-2xl p-5 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-zinc-700/60">
                            <div class="flex items-center gap-2">
                                <span class="p-1 rounded-lg bg-amber-50 dark:bg-amber-950 text-amber-600 dark:text-amber-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </span>
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Main Product Photo</h3>
                            </div>
                            @if ($media_id)
                                <button type="button" wire:click="removeMedia" class="text-xs text-rose-600 hover:underline cursor-pointer">Remove</button>
                            @endif
                        </div>

                        <div>
                            @if ($mediaUrl)
                                <div class="relative group rounded-xl overflow-hidden border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-900">
                                    <img src="{{ $mediaUrl }}" alt="Product Image" class="w-full h-48 object-cover">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <flux:button type="button"
                                            @click="$dispatch('open-media-modal', { targetEvent: 'product-media-selected', folder: 'products', selectedId: {{ $media_id ?: 'null' }} })"
                                            variant="primary" size="sm">
                                            Change Photo
                                        </flux:button>
                                    </div>
                                </div>
                            @else
                                <div @click="$dispatch('open-media-modal', { targetEvent: 'product-media-selected', folder: 'products', selectedId: {{ $media_id ?: 'null' }} })"
                                    class="border-2 border-dashed border-gray-200 dark:border-zinc-700 hover:border-indigo-500 rounded-xl p-6 text-center cursor-pointer transition bg-gray-50/50 dark:bg-zinc-900/30">
                                    <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <p class="mt-2 text-xs font-semibold text-gray-700 dark:text-zinc-300">Click to upload or choose from media</p>
                                    <p class="text-[10px] text-gray-400">PNG, JPG, WEBP up to 5MB</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Product Gallery Card -->
                    <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 shadow-xs rounded-2xl p-5 space-y-4">
                        @php
                            $galleryIds = [];
                            if ($productId && !empty($existingGallery)) {
                                foreach ($existingGallery as $eg) {
                                    if (!empty($eg['media_id'])) $galleryIds[] = (int) $eg['media_id'];
                                }
                            }
                            if (!empty($galleryMedia)) {
                                foreach ($galleryMedia as $gm) {
                                    if (!empty($gm['id'])) $galleryIds[] = (int) $gm['id'];
                                }
                            }
                            $galleryIds = array_values(array_unique($galleryIds));
                        @endphp

                        <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-zinc-700/60">
                            <div class="flex items-center gap-2">
                                <span class="p-1 rounded-lg bg-pink-50 dark:bg-pink-950 text-pink-600 dark:text-pink-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z"/></svg>
                                </span>
                                <div>
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Product Gallery</h3>
                                    <p class="text-[10px] text-gray-400">Multiple image support</p>
                                </div>
                            </div>

                            <flux:button type="button"
                                @click="$dispatch('open-media-modal', { targetEvent: 'product-gallery-media-selected', folder: 'products', multiple: true, selectedIds: {{ json_encode($galleryIds) }} })"
                                variant="outline" size="sm" icon="plus" class="cursor-pointer">
                                Add Photos
                            </flux:button>
                        </div>

                        <!-- Gallery Grid -->
                        <div class="grid grid-cols-3 gap-2.5 max-h-64 overflow-y-auto pr-1">
                            {{-- Saved Gallery for existing product --}}
                            @if ($productId && !empty($existingGallery))
                                @foreach ($existingGallery as $item)
                                    @php
                                        $mUrl = $item['media']['urls']['small'] ?? $item['media']['urls']['thumb'] ?? $item['media']['url'] ?? '';
                                    @endphp
                                    <div class="relative group rounded-lg overflow-hidden border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-900 aspect-square">
                                        <img src="{{ $mUrl }}" alt="Gallery Image" class="w-full h-full object-cover">
                                        <button type="button"
                                            wire:click="removeGalleryImage({{ $item['id'] }})"
                                            title="Delete image"
                                            class="absolute top-1 right-1 p-1 rounded-full bg-rose-600 text-white opacity-0 group-hover:opacity-100 transition shadow-2xs cursor-pointer">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                @endforeach
                            @endif

                            {{-- Pending Gallery for new or additional uploads --}}
                            @if (!empty($galleryMedia))
                                @foreach ($galleryMedia as $idx => $item)
                                    <div class="relative group rounded-lg overflow-hidden border-2 border-indigo-500/50 bg-gray-50 dark:bg-zinc-900 aspect-square">
                                        <img src="{{ $item['url'] }}" alt="Pending Image" class="w-full h-full object-cover">
                                        <button type="button"
                                            wire:click="removePendingGalleryItem({{ $idx }})"
                                            title="Remove image"
                                            class="absolute top-1 right-1 p-1 rounded-full bg-rose-600 text-white opacity-0 group-hover:opacity-100 transition shadow-2xs cursor-pointer">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                @endforeach
                            @endif

                            @if (empty($existingGallery) && empty($galleryMedia))
                                <div class="col-span-3 py-6 text-center text-xs text-gray-400 border border-dashed border-gray-200 dark:border-zinc-700 rounded-xl">
                                    No gallery images added yet.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Sticky Action Card -->
                    <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 shadow-xs rounded-2xl p-5 space-y-3 sticky top-4">
                        <flux:button type="submit" form="product-form" variant="primary" class="w-full justify-center shadow-sm cursor-pointer" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="save">
                                {{ $productId ? 'Update Product Details' : 'Publish Product' }}
                            </span>
                            <span wire:loading wire:target="save" class="flex items-center justify-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                Saving...
                            </span>
                        </flux:button>

                        <flux:button :href="route('backend.products.index')" wire:navigate variant="ghost" class="w-full justify-center cursor-pointer">
                            Cancel & Return
                        </flux:button>
                    </div>

                </div>

            </div>
        </form>
    </div>
</section>
