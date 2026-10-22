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

    <div class="space-y-5">
        <!-- Breadcrumbs & Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-3 border-b border-slate-200 dark:border-zinc-800">
            <div>
                <x-admin.breadcrumb :items="[
                    ['label' => 'Dashboard', 'url' => route('dashboard')],
                    ['label' => 'Catalog'],
                    ['label' => 'Products', 'url' => route('backend.products.index')],
                    ['label' => $productId ? 'Edit Product' : 'Create Product']
                ]" class="mb-1" />

                <div class="flex items-center gap-2.5">
                    <h1 class="text-lg font-bold text-slate-900 dark:text-zinc-100">
                        {{ $productId ? 'Edit Product: ' . ($name ?: '#' . $productId) : 'Create New Product' }}
                    </h1>
                    @if ($productId)
                        <x-admin.badge :variant="$status ? 'success' : 'danger'" dot>
                            {{ $status ? 'Active' : 'Inactive' }}
                        </x-admin.badge>
                    @endif
                </div>
                <p class="text-xs text-slate-500 dark:text-zinc-400">
                    Configure product attributes, manage pricing, upload color-ways media, and control inventory.
                </p>
            </div>

            <div class="flex items-center gap-2 self-end sm:self-center">
                <a href="{{ route('backend.products.index') }}" wire:navigate 
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-[3px] text-slate-700 dark:text-zinc-200 hover:bg-slate-100 dark:hover:bg-zinc-800 transition cursor-pointer">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Back to Catalog</span>
                </a>
                <x-admin.button type="submit" form="product-form" variant="primary" icon="check" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="save">
                        {{ $productId ? 'Update Product' : 'Save Product' }}
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center gap-1.5">
                        <i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin"></i>
                        Saving...
                    </span>
                </x-admin.button>
            </div>
        </div>

        <form id="product-form" wire:submit.prevent="save" onsubmit="syncSummernoteBeforeSave()">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
                
                <!-- Main Content Column (8 cols) -->
                <div class="lg:col-span-8 space-y-5">
                    
                    <!-- Product Type Card -->
                    <x-admin.card class="space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 border-b border-slate-100 dark:border-zinc-800">
                            <div>
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-zinc-200 flex items-center gap-1.5">
                                    <i data-lucide="tag" class="w-3.5 h-3.5 text-brand-600"></i>
                                    Product Classification & Type
                                </h3>
                                <p class="text-[11px] text-slate-400 mt-0.5">Select standalone item or multi-attribute variable item</p>
                            </div>

                            <div class="inline-flex p-0.5 bg-slate-100 dark:bg-zinc-800 rounded-[3px] border border-slate-200 dark:border-zinc-700">
                                <button type="button"
                                    wire:click="setProductType(false)"
                                    class="px-3 py-1 rounded-[2px] text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer {{ !$has_variants ? 'bg-brand-600 text-white shadow-xs' : 'text-slate-600 dark:text-zinc-400 hover:text-slate-900 dark:hover:text-white' }}">
                                    <i data-lucide="box" class="w-3 h-3"></i>
                                    <span>Single Product</span>
                                </button>

                                <button type="button"
                                    wire:click="setProductType(true)"
                                    class="px-3 py-1 rounded-[2px] text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer {{ $has_variants ? 'bg-brand-600 text-white shadow-xs' : 'text-slate-600 dark:text-zinc-400 hover:text-slate-900 dark:hover:text-white' }}">
                                    <i data-lucide="boxes" class="w-3 h-3"></i>
                                    <span>Variable Product</span>
                                </button>
                            </div>
                        </div>

                        <!-- Basic Information -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <x-admin.input wire:model.live.debounce.300ms="name" label="Product Name *" placeholder="e.g. Wireless Bluetooth Headphones" required />
                            <x-admin.input wire:model="slug" label="URL Slug *" placeholder="wireless-bluetooth-headphones" required />
                        </div>

                        <div>
                            <x-admin.textarea wire:model="short_description" label="Short Summary Description" rows="2" placeholder="Brief highlight of key specifications or features..." />
                        </div>

                        <!-- Long Description with Summernote -->
                        <div class="space-y-1">
                            <label class="block text-xs font-semibold text-slate-700 dark:text-zinc-300">
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
                    </x-admin.card>

                    <!-- Single Product Pricing & Inventory -->
                    @if (!$has_variants)
                        <x-admin.card class="space-y-4">
                            <div class="flex items-center gap-1.5 pb-2 border-b border-slate-100 dark:border-zinc-800">
                                <i data-lucide="dollar-sign" class="w-3.5 h-3.5 text-emerald-600"></i>
                                <div>
                                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-zinc-200">Pricing & Inventory</h3>
                                    <p class="text-[11px] text-slate-400">Configure prices in sequential order and manage stock</p>
                                </div>
                            </div>

                            <!-- Pricing Fields in Serial Order: Cost -> Selling -> Discount -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <x-admin.input wire:model="cost_price" type="number" step="0.01" label="Cost Price ($)" placeholder="0.00" />
                                <x-admin.input wire:model="price" type="number" step="0.01" label="Selling Price ($) *" placeholder="0.00" required />
                                <x-admin.input wire:model="discount_price" type="number" step="0.01" label="Discount Price ($)" placeholder="0.00" />
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
                                <x-admin.input wire:model="stock" type="number" label="Stock Quantity *" placeholder="0" min="0" required />
                                <x-admin.input wire:model="weight" type="number" step="0.01" label="Weight (kg)" placeholder="0.00" />
                                
                                <div>
                                    <x-admin.input wire:model="sku" label="SKU (Read Only)" readonly disabled class="bg-slate-100 dark:bg-zinc-800 font-mono text-xs cursor-not-allowed text-slate-500" />
                                    <p class="text-[10px] text-slate-400 mt-1">Auto-generated from title</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                                <div>
                                    <x-admin.input wire:model="barcode" label="Barcode (Read Only)" readonly disabled class="bg-slate-100 dark:bg-zinc-800 font-mono text-xs cursor-not-allowed text-slate-500" />
                                    <p class="text-[10px] text-slate-400 mt-1">Unique standard barcode automatically assigned</p>
                                </div>
                            </div>
                        </x-admin.card>
                    @endif

                    <!-- Variable Product Configuration -->
                    @if ($has_variants)
                        <x-admin.card class="space-y-5">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-slate-100 dark:border-zinc-800">
                                <div>
                                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-zinc-200 flex items-center gap-1.5">
                                        <i data-lucide="layers" class="w-3.5 h-3.5 text-brand-600"></i>
                                        Select Attributes & Options
                                    </h3>
                                    <p class="text-[11px] text-slate-400">Pick attribute options to automatically generate all variant combinations</p>
                                </div>

                                <x-admin.button type="button" wire:click="generateVariants" variant="primary" size="sm" icon="sparkles">
                                    Generate Combinations
                                </x-admin.button>
                            </div>

                            <!-- Attributes List -->
                            <div class="space-y-3">
                                @forelse ($allAttributes as $attr)
                                    @php
                                        $selectedVals = $selectedAttributes[$attr->id] ?? [];
                                    @endphp
                                    <div class="p-3 rounded-[3px] border border-slate-200 dark:border-zinc-700 bg-slate-50/50 dark:bg-zinc-900/40 space-y-2">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-bold text-slate-800 dark:text-zinc-200 uppercase tracking-wider flex items-center gap-1.5">
                                                <span>{{ $attr->name }}</span>
                                                <span class="text-[10px] font-normal text-slate-400 capitalize">({{ $attr->type }})</span>
                                            </span>
                                            <span class="text-[10px] text-slate-400 font-mono">
                                                {{ count($selectedVals) }} selected
                                            </span>
                                        </div>

                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach ($attr->values as $val)
                                                @php $isChecked = in_array($val->id, $selectedVals); @endphp
                                                <button type="button"
                                                    wire:click="toggleAttributeValue({{ $attr->id }}, {{ $val->id }})"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-[3px] text-xs font-medium border transition cursor-pointer {{ $isChecked ? 'bg-brand-600 border-brand-600 text-white shadow-xs' : 'bg-white dark:bg-zinc-800 border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-300 hover:border-brand-400' }}">
                                                    @if ($val->color_code)
                                                        <span class="w-2.5 h-2.5 rounded-full border border-slate-300 dark:border-zinc-600 inline-block shrink-0" style="background-color: {{ $val->color_code }}"></span>
                                                    @endif
                                                    <span>{{ $val->value }}</span>
                                                    @if ($isChecked)
                                                        <i data-lucide="check" class="w-3 h-3 stroke-[3]"></i>
                                                    @endif
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-4 text-center text-xs text-slate-400">
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
                                <div class="p-3.5 rounded-[4px] border border-brand-100 dark:border-brand-900/40 bg-brand-50/30 dark:bg-brand-950/20 space-y-3">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-1.5">
                                            <i data-lucide="image" class="w-3.5 h-3.5 text-brand-600"></i>
                                            <div>
                                                <h4 class="text-xs font-bold text-slate-800 dark:text-zinc-200 uppercase tracking-wider">Color-way Photos</h4>
                                                <p class="text-[10px] text-slate-500">1 photo per colorway automatically used by all matching variants.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 pt-1">
                                        @foreach ($selectedColors as $cVal)
                                            @php
                                                $colorMedia = $colorMediaMap[$cVal->id] ?? null;
                                            @endphp
                                            <div class="p-2 rounded-[3px] border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 space-y-1.5">
                                                <div class="flex items-center gap-1.5">
                                                    @if ($cVal->color_code)
                                                        <span class="w-3 h-3 rounded-full border border-slate-300" style="background-color: {{ $cVal->color_code }}"></span>
                                                    @endif
                                                    <span class="text-xs font-bold text-slate-800 dark:text-zinc-200 truncate">{{ $cVal->value }}</span>
                                                </div>

                                                <div class="relative w-full h-20 rounded-[2px] overflow-hidden border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 flex items-center justify-center">
                                                    @if ($colorMedia)
                                                        <img src="{{ $colorMedia['url'] }}" alt="{{ $cVal->value }}" class="w-full h-full object-cover">
                                                        <button type="button"
                                                            wire:click="removeColorMedia({{ $cVal->id }})"
                                                            class="absolute top-1 right-1 p-0.5 rounded-[2px] bg-rose-600 text-white hover:bg-rose-700 cursor-pointer">
                                                            <i data-lucide="x" class="w-3 h-3"></i>
                                                        </button>
                                                    @else
                                                        <button type="button"
                                                            @click="$dispatch('open-media-modal', { targetEvent: 'color-media-selected', type: 'image', customPayload: { color_val_id: {{ $cVal->id }} } })"
                                                            class="text-[10px] text-brand-600 hover:underline flex flex-col items-center gap-1 cursor-pointer">
                                                            <i data-lucide="image-plus" class="w-4 h-4 text-brand-600"></i>
                                                            <span>Pick Photo</span>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Variants Combinations Table / Repeater -->
                            @if (!empty($variants))
                                <div class="space-y-3 pt-3 border-t border-slate-100 dark:border-zinc-800">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-xs font-bold text-slate-800 dark:text-zinc-200 uppercase tracking-wider">
                                            Variants Matrix ({{ count($variants) }})
                                        </h4>
                                    </div>

                                    <div class="space-y-3">
                                        @foreach ($variants as $idx => $v)
                                            <div wire:key="variant-edit-box-{{ $idx }}" class="p-3.5 rounded-[4px] border border-slate-200 dark:border-zinc-700 bg-slate-50/60 dark:bg-zinc-900/40 space-y-3">
                                                <div class="flex items-center justify-between pb-2 border-b border-slate-200 dark:border-zinc-800">
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-xs font-mono font-bold text-brand-600">#{{ $idx + 1 }}</span>
                                                        <div class="flex items-center gap-1">
                                                            @foreach ($v['attributes'] as $attrItem)
                                                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-[2px] text-[10px] font-semibold bg-slate-200 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300">
                                                                    @if (!empty($attrItem['color_code']))
                                                                        <span class="w-2 h-2 rounded-full inline-block" style="background-color: {{ $attrItem['color_code'] }}"></span>
                                                                    @endif
                                                                    <span>{{ $attrItem['value'] }}</span>
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    <div class="flex items-center gap-3">
                                                        <label class="inline-flex items-center gap-1 text-xs cursor-pointer select-none text-slate-700 dark:text-zinc-300">
                                                            <input type="checkbox" wire:model="variants.{{ $idx }}.status" class="rounded-[2px] text-brand-600">
                                                            <span>Active</span>
                                                        </label>

                                                        <button type="button"
                                                            wire:click="removeVariantRow({{ $idx }})"
                                                            title="Delete this variant"
                                                            class="p-1 text-slate-400 hover:text-rose-600 transition cursor-pointer">
                                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- Variant Pricing & Stock Grid -->
                                                <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5">
                                                    <div>
                                                        <x-admin.input wire:model="variants.{{ $idx }}.cost_price" type="number" step="0.01" label="Cost ($)" placeholder="0.00" />
                                                    </div>
                                                    <div>
                                                        <x-admin.input wire:model="variants.{{ $idx }}.selling_price" type="number" step="0.01" label="Selling ($) *" placeholder="0.00" required />
                                                    </div>
                                                    <div>
                                                        <x-admin.input wire:model="variants.{{ $idx }}.discount_price" type="number" step="0.01" label="Discount ($)" placeholder="0.00" />
                                                    </div>
                                                    <div>
                                                        <x-admin.input wire:model="variants.{{ $idx }}.stock" type="number" label="Stock *" placeholder="0" min="0" required />
                                                    </div>
                                                    <div>
                                                        <x-admin.input wire:model="variants.{{ $idx }}.weight" type="number" step="0.01" label="Weight (kg)" placeholder="0.00" />
                                                    </div>
                                                </div>

                                                <!-- Variant SKU, Barcode, and Image -->
                                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 items-end pt-1">
                                                    <div>
                                                        <x-admin.input wire:model="variants.{{ $idx }}.sku" label="SKU (Read Only)" readonly disabled class="bg-slate-100 dark:bg-zinc-800 font-mono text-xs cursor-not-allowed text-slate-500" />
                                                    </div>

                                                    <div>
                                                        <x-admin.input wire:model="variants.{{ $idx }}.barcode" label="Barcode (Read Only)" readonly disabled class="bg-slate-100 dark:bg-zinc-800 font-mono text-xs cursor-not-allowed text-slate-500" />
                                                    </div>

                                                    <!-- Variant Image Picker -->
                                                    @php
                                                        $varMediaUrl = $v['media_url'] ?? null;
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
                                                                <img src="{{ $displayImg }}" alt="Variant image" class="h-8 w-8 rounded-[2px] object-cover border border-slate-200 dark:border-zinc-700">
                                                                @if ($varMediaUrl)
                                                                    <button type="button"
                                                                        wire:click="removeVariantMedia({{ $idx }})"
                                                                        class="absolute -top-1 -right-1 p-0.5 rounded-[2px] bg-rose-600 text-white opacity-0 group-hover:opacity-100 cursor-pointer">
                                                                        <i data-lucide="x" class="w-2.5 h-2.5"></i>
                                                                    </button>
                                                                @endif
                                                            @else
                                                                <div class="h-8 w-8 rounded-[2px] bg-slate-100 dark:bg-zinc-800 flex items-center justify-center text-slate-400">
                                                                    <i data-lucide="image" class="w-4 h-4"></i>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <button type="button"
                                                            @click="$dispatch('open-media-modal', { targetEvent: 'variant-media-selected', type: 'image', customPayload: { variant_index: {{ $idx }} } })"
                                                            class="px-2 py-1 bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-[3px] text-[11px] font-semibold text-slate-700 dark:text-zinc-200 hover:bg-slate-50 cursor-pointer">
                                                            <span>{{ $varMediaUrl ? 'Change' : 'Override Photo' }}</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </x-admin.card>
                    @endif

                </div>

                <!-- Right Sidebar Column (4 cols) -->
                <div class="lg:col-span-4 space-y-5">
                    
                    <!-- Organization & Category Card -->
                    <x-admin.card class="space-y-4">
                        <div class="flex items-center gap-1.5 pb-2 border-b border-slate-100 dark:border-zinc-800">
                            <i data-lucide="folder" class="w-3.5 h-3.5 text-brand-600"></i>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-zinc-200">Organization & Status</h3>
                        </div>

                        <div class="space-y-3">
                            <x-admin.select wire:model.live="category_id" label="Category *" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </x-admin.select>

                            <x-admin.select wire:model="sub_category_id" label="Subcategory (Optional)">
                                <option value="">Select Subcategory</option>
                                @foreach ($subcategories as $sub)
                                    <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                @endforeach
                            </x-admin.select>

                            <x-admin.select wire:model="brand_id" label="Brand (Optional)">
                                <option value="">Select Brand</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </x-admin.select>

                            <div class="pt-2 border-t border-slate-100 dark:border-zinc-800 space-y-2.5">
                                <div class="p-2.5 rounded-[3px] border border-slate-200 dark:border-zinc-700 bg-slate-50/50 dark:bg-zinc-900/30 flex items-center justify-between">
                                    <x-admin.switch wire:model="status" label="Active Status" description="Display product in catalog and search" />
                                </div>

                                <div class="p-2.5 rounded-[3px] border border-slate-200 dark:border-zinc-700 bg-slate-50/50 dark:bg-zinc-900/30 flex items-center justify-between">
                                    <x-admin.switch wire:model="is_featured" label="Featured Product" description="Highlight on homepage showcase" />
                                </div>
                            </div>
                        </div>
                    </x-admin.card>

                    <!-- Main Thumbnail Card -->
                    <x-admin.card class="space-y-3">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-zinc-800">
                            <div class="flex items-center gap-1.5">
                                <i data-lucide="image" class="w-3.5 h-3.5 text-amber-500"></i>
                                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-zinc-200">Main Product Photo</h3>
                            </div>
                            @if ($media_id)
                                <button type="button" wire:click="removeMedia" class="text-[11px] text-rose-600 hover:underline cursor-pointer">Remove</button>
                            @endif
                        </div>

                        <div>
                            @if ($mediaUrl)
                                <div class="relative group rounded-[3px] overflow-hidden border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-900">
                                    <img src="{{ $mediaUrl }}" alt="Product Image" class="w-full h-44 object-cover">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <x-admin.button type="button"
                                            @click="$dispatch('open-media-modal', { targetEvent: 'product-media-selected', folder: 'products', selectedId: {{ $media_id ?: 'null' }} })"
                                            variant="primary" size="sm">
                                            Change Photo
                                        </x-admin.button>
                                    </div>
                                </div>
                            @else
                                <div @click="$dispatch('open-media-modal', { targetEvent: 'product-media-selected', folder: 'products', selectedId: {{ $media_id ?: 'null' }} })"
                                    class="border-2 border-dashed border-slate-200 dark:border-zinc-700 hover:border-brand-500 rounded-[3px] p-5 text-center cursor-pointer transition bg-slate-50/50 dark:bg-zinc-900/30">
                                    <i data-lucide="image-plus" class="mx-auto w-8 h-8 text-slate-400 mb-1"></i>
                                    <p class="text-xs font-semibold text-slate-700 dark:text-zinc-300">Click to choose from media</p>
                                    <p class="text-[10px] text-slate-400">PNG, JPG, WEBP up to 5MB</p>
                                </div>
                            @endif
                        </div>
                    </x-admin.card>

                    <!-- Product Gallery Card -->
                    <x-admin.card class="space-y-3">
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

                        <div class="flex items-center justify-between pb-2 border-b border-slate-100 dark:border-zinc-800">
                            <div class="flex items-center gap-1.5">
                                <i data-lucide="images" class="w-3.5 h-3.5 text-brand-600"></i>
                                <div>
                                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-zinc-200">Gallery</h3>
                                    <p class="text-[10px] text-slate-400">Multiple photos</p>
                                </div>
                            </div>

                            <x-admin.button type="button"
                                @click="$dispatch('open-media-modal', { targetEvent: 'product-gallery-media-selected', folder: 'products', multiple: true, selectedIds: {{ json_encode($galleryIds) }} })"
                                variant="secondary" size="sm" icon="plus">
                                Add Photos
                            </x-admin.button>
                        </div>

                        <!-- Gallery Grid -->
                        <div class="grid grid-cols-3 gap-2 max-h-56 overflow-y-auto pr-1">
                            {{-- Saved Gallery for existing product --}}
                            @if ($productId && !empty($existingGallery))
                                @foreach ($existingGallery as $item)
                                    @php
                                        $mUrl = $item['media']['urls']['small'] ?? $item['media']['urls']['thumb'] ?? $item['media']['url'] ?? '';
                                    @endphp
                                    <div class="relative group rounded-[2px] overflow-hidden border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-900 aspect-square">
                                        <img src="{{ $mUrl }}" alt="Gallery Image" class="w-full h-full object-cover">
                                        <button type="button"
                                            wire:click="removeGalleryImage({{ $item['id'] }})"
                                            title="Delete image"
                                            class="absolute top-1 right-1 p-0.5 rounded-[2px] bg-rose-600 text-white opacity-0 group-hover:opacity-100 transition cursor-pointer">
                                            <i data-lucide="x" class="w-3 h-3"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @endif

                            {{-- Pending Gallery for new or additional uploads --}}
                            @if (!empty($galleryMedia))
                                @foreach ($galleryMedia as $idx => $item)
                                    <div class="relative group rounded-[2px] overflow-hidden border-2 border-brand-500/50 bg-slate-50 dark:bg-zinc-900 aspect-square">
                                        <img src="{{ $item['url'] }}" alt="Pending Image" class="w-full h-full object-cover">
                                        <button type="button"
                                            wire:click="removePendingGalleryItem({{ $idx }})"
                                            title="Remove image"
                                            class="absolute top-1 right-1 p-0.5 rounded-[2px] bg-rose-600 text-white opacity-0 group-hover:opacity-100 transition cursor-pointer">
                                            <i data-lucide="x" class="w-3 h-3"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @endif

                            @if (empty($existingGallery) && empty($galleryMedia))
                                <div class="col-span-3 py-5 text-center text-xs text-slate-400 border border-dashed border-slate-200 dark:border-zinc-700 rounded-[3px]">
                                    No gallery images added yet.
                                </div>
                            @endif
                        </div>
                    </x-admin.card>

                    <!-- Sticky Action Card -->
                    <x-admin.card class="space-y-2 sticky top-4">
                        <x-admin.button type="submit" form="product-form" variant="primary" class="w-full justify-center" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="save">
                                {{ $productId ? 'Update Product Details' : 'Publish Product' }}
                            </span>
                            <span wire:loading wire:target="save" class="flex items-center justify-center gap-1.5">
                                <i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin"></i>
                                Saving...
                            </span>
                        </x-admin.button>

                        <a href="{{ route('backend.products.index') }}" wire:navigate 
                            class="inline-flex items-center justify-center w-full px-3 py-1.5 text-xs font-semibold rounded-[3px] text-slate-600 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-800 transition cursor-pointer">
                            Cancel & Return
                        </a>
                    </x-admin.card>

                </div>

            </div>
        </form>
    </div>
</section>
