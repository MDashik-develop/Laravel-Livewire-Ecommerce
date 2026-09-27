<section>

    <livewire:utilities.toast-modal />

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>Products</flux:breadcrumbs.item>
            </flux:breadcrumbs>

            @can('product.create')
                <flux:modal.trigger name="product-modal" @click="$wire.resetForm()">
                    <flux:button variant="filled">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create Product
                    </flux:button>
                </flux:modal.trigger>
            @endcan
        </div>

        <!-- Search and Table -->
        <div
            class="bg-white dark:bg-zinc-700 border border-gray-200 dark:border-zinc-600 shadow-md rounded-lg overflow-hidden">
            <div class="p-4 flex justify-between items-center gap-2">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search products by name..."
                    class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                <flux:dropdown>
                    <flux:button icon:trailing="funnel" variant="outline"></flux:button>

                    <flux:menu keep-open>
                        <flux:menu.submenu keep-open heading="Columns">
                            @foreach ($columns as $col)
                                <flux:menu.checkbox wire:click="toggleColumn('{{ $col['key'] }}')"
                                    :checked="in_array($col['key'], $visibleColumns)">
                                    {{ $col['label'] }}
                                </flux:menu.checkbox>
                            @endforeach
                        </flux:menu.submenu>

                        {{-- <flux:menu.separator /> --}}
                        {{-- <flux:menu.item variant="danger">Recent</flux:menu.item> --}}
                    </flux:menu>
                </flux:dropdown>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                    <thead class="bg-gray-50 dark:bg-zinc-600">
                        <tr>
                            @foreach ($columns as $col)
                                @if (in_array($col['key'], $visibleColumns))
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-nowrap tracking-wider text-center cursor-pointer"
                                        @if (!empty($col['sortable'])) wire:click="sortBy('{{ $col['key'] }}')" @endif>
                                        {{ $col['label'] }}
                                        @if ($sortField === $col['key'])
                                            @if ($sortDirection === 'asc')
                                                <flux:icon name="chevron-up" class="inline size-3.5 ml-1" />
                                            @else
                                                <flux:icon name="chevron-down" class="inline size-3.5 ml-1" />
                                            @endif
                                        @endif
                                    </th>
                                @endif
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-600 divide-y divide-gray-200 dark:divide-zinc-700">
                        @if (empty($search))
                            {{--  Default view (without search)  --}}
                            @forelse($products as $product)
                                <tr wire:key="product-{{ $product->id }}"
                                    class="hover:bg-gray-50 dark:hover:bg-zinc-500">
                                    @foreach ($columns as $col)
                                        @if (in_array($col['key'], $visibleColumns))
                                            <td
                                                class="px-6 py-4 whitespace-nowrap {{ $col['key'] === 'name' ? '' : 'text-center' }}">
                                                @switch($col['key'])
                                                    @case('id')
                                                        {{ $product->id }}
                                                    @break

                                                    @case('image')
                                                        <div class="flex justify-center">
                                                            @if ($product->media)
                                                                <img src="{{ $product->media->urls['thumb'] ?? $product->media->url }}"
                                                                    alt="{{ $product->name }}"
                                                                    class="h-10 w-10 rounded-lg object-cover border border-gray-200 dark:border-zinc-600">
                                                            @else
                                                                <div class="h-10 w-10 bg-gray-100 dark:bg-zinc-700 rounded-lg flex items-center justify-center text-gray-400 text-[10px] font-semibold">
                                                                    No Image
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @break

                                                    @case('name')
                                                        {{ $product->name }}
                                                    @break

                                                    @case('category')
                                                        {{ $product->category->name ?? '-' }}
                                                    @break

                                                    @case('brand')
                                                        {{ $product->brand->name ?? '-' }}
                                                    @break

                                                    @case('sku')
                                                        {{ $product->attributes->pluck('sku')->filter()->unique()->join(', ') }}
                                                    @break

                                                    @case('color')
                                                        {{ ucwords($product->attributes->pluck('color')->filter()->unique()->join(', ')) }}
                                                    @break

                                                    @case('size')
                                                        {{ ucwords($product->attributes->pluck('size')->filter()->unique()->join(', ')) }}
                                                    @break

                                                    @case('quantity')
                                                        {{ $product->attributes->pluck('quantity')->filter()->unique()->join(', ') ?: '0' }}
                                                    @break

                                                    @case('price')
                                                        {{ $product->attributes->pluck('price')->filter()->unique()->join(', ') ?: '' }}
                                                    @break

                                                    @case('offer_price')
                                                        {{ $product->attributes->pluck('offer_price')->filter()->unique()->join(', ') ?: '' }}
                                                    @break

                                                    @case('offer_end_date')
                                                        {{ $product->attributes->pluck('offer_end_date')->filter()->unique()->join(', ') ?: '' }}
                                                    @break

                                                    @case('status')
                                                        <flux:badge :color="$product->status ? 'green' : 'red'">
                                                            {{ $product->status ? 'Active' : 'Inactive' }}
                                                        </flux:badge>
                                                    @break

                                                    @case('actions')
                                                        <div class="flex items-center justify-center gap-2 text-sm font-medium">
                                                            <flux:button wire:click="edit({{ $product->id }})"
                                                                icon="pencil-square" class="cursor-pointer"></flux:button>
                                                            <flux:modal.trigger name="delete-modal">
                                                                <flux:button wire:click="confirmDelete({{ $product->id }})"
                                                                    icon="trash" variant="danger" class="cursor-pointer"></flux:button>
                                                            </flux:modal.trigger>
                                                        </div>
                                                    @break

                                                    @default
                                                        -
                                                @endswitch
                                            </td>
                                        @endif
                                    @endforeach
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($visibleColumns) }}"
                                            class="px-6 py-4 text-center text-sm text-gray-500">
                                            No products found.
                                        </td>
                                    </tr>
                                @endforelse
                            @else
                                {{--  Search View  --}}
                                @forelse($products as $attribute)
                                    <tr wire:key="attribute-{{ $attribute->id }}"
                                        class="hover:bg-gray-50 dark:hover:bg-zinc-500 bg-blue-50 dark:bg-blue-900/20">
                                        @foreach ($columns as $col)
                                            @if (in_array($col['key'], $visibleColumns))
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap {{ $col['key'] === 'name' ? '' : 'text-center word' }}">
                                                    @switch($col['key'])
                                                        @case('id')
                                                            {{ $attribute->product->id }}
                                                        @break

                                                        @case('image')
                                                            <div class="flex justify-center">
                                                                @if ($attribute->product->media)
                                                                    <img src="{{ $attribute->product->media->urls['thumb'] ?? $attribute->product->media->url }}"
                                                                        alt="{{ $attribute->product->name }}"
                                                                        class="h-10 w-10 rounded-lg object-cover border border-gray-200 dark:border-zinc-600">
                                                                @else
                                                                    <div class="h-10 w-10 bg-gray-100 dark:bg-zinc-700 rounded-lg flex items-center justify-center text-gray-400 text-[10px] font-semibold">
                                                                        No Image
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @break

                                                        @case('name')
                                                            {{ $attribute->product->name }}
                                                        @break

                                                        @case('category')
                                                            {{ $attribute->product->category->name ?? '-' }}
                                                        @break

                                                        @case('brand')
                                                            {{ $attribute->product->brand->name ?? '-' }}
                                                        @break

                                                        @case('sku')
                                                            {{ $attribute->sku }}
                                                        @break

                                                        @case('color')
                                                            <flux:badge color="{{ $attribute->color }}">
                                                                {{ ucfirst($attribute->color) }}</flux:badge>
                                                        @break

                                                        @case('size')
                                                            <flux:badge color="{{ $attribute->color }}">
                                                                {{ ucfirst($attribute->size) }}</flux:badge>
                                                        @break

                                                        @case('quantity')
                                                            {{ $attribute->quantity }}
                                                        @break

                                                        @case('price')
                                                            {{ $attribute->price }}
                                                        @break

                                                        @case('offer_price')
                                                            {{ $attribute->offer_price }}
                                                        @break

                                                        @case('offer_end_date')
                                                            {{ $attribute->offer_end_date ? \Carbon\Carbon::parse($attribute->offer_end_date)->format('d M, Y') : '-' }}
                                                        @break

                                                        @case('status')
                                                            <flux:badge :color="$attribute->product->status ? 'green' : 'red'">
                                                                {{ $attribute->product->status ? 'Active' : 'Inactive' }}
                                                            </flux:badge>
                                                        @break

                                                        @case('actions')
                                                            <div class="flex items-center justify-center gap-2 text-sm font-medium">
                                                                <flux:button wire:click="edit({{ $attribute->product->id }})"
                                                                    icon="pencil-square" class="cursor-pointer"></flux:button>
                                                                <flux:modal.trigger name="delete-modal">
                                                                    <flux:button
                                                                        wire:click="confirmDelete({{ $attribute->product->id }})"
                                                                        icon="trash" variant="danger" class="cursor-pointer"></flux:button>
                                                                </flux:modal.trigger>
                                                            </div>
                                                        @break

                                                        @default
                                                            -
                                                    @endswitch
                                                </td>
                                            @endif
                                        @endforeach
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ count($visibleColumns) }}"
                                                class="px-6 py-4 text-center text-sm text-gray-500">
                                                No matching variants found.
                                            </td>
                                        </tr>
                                    @endforelse
                                @endif
                            </tbody>

                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="p-4">
                        {{ $products->links() }}
                    </div>
                </div>

                <!-- Create/Edit Modal -->
                <flux:modal name="product-modal" class="md:max-w-7xl md:min-w-3xl">
                    <form wire:submit.prevent="save" class="space-y-6">
                        <div>
                            <flux:heading size="lg">{{ $productId ? 'Edit Product' : 'Create Product' }}</flux:heading>
                            <flux:text class="mt-2">Fill in the details for the product.</flux:text>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <flux:select wire:model="brand_id" label="Brand" class="cursor-pointer">
                                <option value="">Select Brand</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </flux:select>

                            <flux:select wire:model.live="category_id" label="Category">
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </flux:select>

                            <flux:select wire:model="sub_category_id" label="Subcategory">
                                <option value="">Select Subcategory</option>
                                @foreach ($subcategories as $sub)
                                    <option value="{{ $sub->id }}" {{ $sub_category_id == $sub->id ? 'selected' : '' }}>
                                        {{ $sub->name }}
                                    </option>
                                @endforeach
                            </flux:select>
                        </div>

                        <flux:input wire:model.blur="name" label="Product Name" placeholder="Product Name" />
                        <flux:input wire:model="slug" label="Slug" placeholder="product-slug" />

                        <flux:input wire:model="short_description" label="Short Description"
                            placeholder="Short description..." />

                        <!-- Summernote for long description -->
                        <flux:field>
                            <div wire:ignore>
                                <textarea class="summernote-init w-full" data-field="long_description" data-height="250" placeholder="Product detailed description...">{!! $long_description !!}</textarea>
                            </div>
                            <flux:error name="long_description" />
                        </flux:field>

                        <!-- Thumbnail Image (Media Selector) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Product Primary Image / Thumbnail</label>
                            <div class="flex items-center space-x-4 p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800/50">
                                <div class="shrink-0">
                                    @if ($mediaUrl)
                                        <img class="h-16 w-16 object-cover rounded-xl border border-gray-200 dark:border-zinc-700 shadow-2xs" src="{{ $mediaUrl }}" alt="Product Image">
                                    @else
                                        <div class="h-16 w-16 bg-gray-100 dark:bg-zinc-800 rounded-xl flex items-center justify-center text-gray-400 border border-dashed border-gray-300 dark:border-zinc-700">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <button type="button"
                                            wire:click="$dispatch('open-media-modal', { targetEvent: 'product-media-selected', folder: 'products' })"
                                            class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-xs transition flex items-center space-x-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <span>Choose Thumbnail</span>
                                        </button>
                                        @if ($media_id)
                                            <button type="button" wire:click="removeMedia" class="px-2.5 py-2 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-xl text-xs font-medium transition">
                                                Remove
                                            </button>
                                        @endif
                                    </div>
                                    <p class="text-[11px] text-gray-500">Pick the main product photo from Media Manager.</p>
                                </div>
                            </div>
                            @error('media_id')
                                <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Gallery Images (Media Selector) -->
                        @if ($productId)
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Product Gallery</label>
                                    <button type="button"
                                        wire:click="$dispatch('open-media-modal', { targetEvent: 'product-gallery-media-selected', folder: 'products' })"
                                        class="px-3 py-1.5 bg-indigo-50 dark:bg-indigo-950/50 hover:bg-indigo-100 text-indigo-600 dark:text-indigo-400 rounded-lg text-xs font-semibold transition flex items-center space-x-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        <span>Add Gallery Image</span>
                                    </button>
                                </div>
                                <div class="flex flex-wrap gap-3 p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800/50">
                                    @forelse ($existingGallery as $img)
                                        <div class="relative group rounded-xl overflow-hidden border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-2xs">
                                            @php
                                                $gMedia = $img['media'] ?? null;
                                                $gUrl = $gMedia['urls']['thumb'] ?? $gMedia['url'] ?? (isset($img['image_path']) ? asset('storage/' . $img['image_path']) : null);
                                            @endphp
                                            <img src="{{ $gUrl }}" class="h-20 w-20 object-cover">
                                            <button type="button"
                                                wire:click="removeGalleryImage({{ $img['id'] }})"
                                                class="absolute top-1 right-1 p-1 bg-rose-600/90 text-white rounded-md opacity-0 group-hover:opacity-100 transition shadow-xs hover:bg-rose-700">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                    @empty
                                        <p class="text-xs text-gray-400 py-3 text-center w-full">No gallery images added yet. Click 'Add Gallery Image' to select.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endif

                        <!-- Product Attributes -->
                        <div>
                            <label class="block font-medium mb-1">Product Attributes</label>

                            @foreach ($productAttributes as $index => $attr)
                                <div
                                    class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-2 pb-3 border-b border-gray-200 mb-4 items-end justify-items-end">
                                    <flux:input wire:model.lazy="productAttributes.{{ $index }}.color" label="Color"
                                        class="min-w-[stretch]" placeholder="e.g. (Green) Color"
                                        wire:change="generateSKU({{ $index }})" />

                                    <flux:input wire:model.lazy="productAttributes.{{ $index }}.size" label="Size"
                                        class="min-w-[stretch]" placeholder="e.g. (L) Size"
                                        wire:change="generateSKU({{ $index }})" />

                                    <flux:input wire:model.lazy="productAttributes.{{ $index }}.price" type="number"
                                        label="Price" class="min-w-[stretch]" placeholder="Price" />

                                    <flux:input wire:model.lazy="productAttributes.{{ $index }}.offer_price"
                                        type="number" label="Offer Price" class="min-w-[stretch]"
                                        placeholder="Offer Price" />

                                    <flux:input wire:model.lazy="productAttributes.{{ $index }}.offer_end_date"
                                        type="date" label="Offer End Date" class="min-w-[stretch]"
                                        placeholder="Offer End Date" />

                                    <flux:input wire:model.lazy="productAttributes.{{ $index }}.quantity"
                                        type="number" label="Quantity" class="min-w-[stretch]" placeholder="QNTY"
                                        min="0" />

                                    <flux:input wire:model="productAttributes.{{ $index }}.sku" label="SKU"
                                        class="min-w-[stretch]" placeholder="SKU" readonly />
                                    <flux:error name="productAttributes.{{ $index }}.sku" class="min-w-[stretch]" />

                                    <flux:button variant="danger" wire:click="removeAttribute({{ $index }})"
                                        icon="trash" class="p-2.5 items-end"></flux:button>
                                </div>
                            @endforeach

                            <flux:button variant="primary" wire:click="addAttribute()" icon="plus" class="mt-2">
                                Attribute</flux:button>
                        </div>




                        <div class="flex items-center gap-4 mt-4">
                            <flux:field variant="inline">
                                <flux:checkbox wire:model="status" />
                                <flux:label>Active</flux:label>
                            </flux:field>
                            <flux:field variant="inline">
                                <flux:checkbox wire:model="is_featured" />
                                <flux:label>Featured</flux:label>
                            </flux:field>
                        </div>

                        <div class="flex mt-4">
                            <flux:spacer />
                            <flux:modal.close>
                                <flux:button type="button" variant="filled">Cancel</flux:button>
                            </flux:modal.close>
                            <flux:button type="submit" @click="syncSummernoteBeforeSave()" variant="primary" class="ml-3">Save</flux:button>
                        </div>
                    </form>
                </flux:modal>

                <!-- Delete Modal -->
                <flux:modal name="delete-modal" class="md:w-96">
                    <div class="space-y-6">
                        <div>
                            <flux:heading size="lg">Delete product?</flux:heading>
                            <flux:text class="mt-2">
                                <p>You're about to delete this product.</p>
                                <p>This action cannot be reversed.</p>
                            </flux:text>
                        </div>
                        <div class="flex gap-2">
                            <flux:spacer />
                            <flux:modal.close>
                                <flux:button variant="ghost">Cancel</flux:button>
                            </flux:modal.close>
                            <flux:button wire:click="delete" type="button" variant="danger">Delete product</flux:button>
                        </div>
                    </div>
                </flux:modal>

            </div>
        </section>
