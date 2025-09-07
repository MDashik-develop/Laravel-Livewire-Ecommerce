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
                    <flux:button>
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
        <div class="bg-white dark:bg-zinc-700 border border-gray-200 dark:border-zinc-600 shadow-md rounded-lg overflow-hidden">
            <div class="p-4 flex justify-between items-center gap-2">
                <input wire:model.live.debounce.300ms="search" type="text"
                       placeholder="Search products by name..."
                       class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                <flux:dropdown>
                    <flux:button icon:trailing="funnel"></flux:button>

                    <flux:menu>
                        <flux:menu.submenu heading="Columns">
                            @foreach($columns as $col)
                                <flux:menu.checkbox wire:click="toggleColumn('{{ $col['key'] }}')" 
                                                    :checked="in_array($col['key'], $visibleColumns)">
                                    {{ $col['label'] }}
                                </flux:menu.checkbox>
                            @endforeach
                        </flux:menu.submenu>

                        <flux:menu.separator />
                        <flux:menu.item variant="danger">Recent</flux:menu.item>
                    </flux:menu>
                </flux:dropdown>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                    <thead class="bg-gray-50 dark:bg-zinc-600">
                        <tr>
                            @foreach($columns as $col)
                                @if(in_array($col['key'], $visibleColumns))
                                    <th 
                                        class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center cursor-pointer"
                                        @if(!empty($col['sortable']))
                                            wire:click="sortBy('{{ $col['key'] }}')"
                                        @endif
                                    >
                                        {{ $col['label'] }}
                                        @if($sortField === $col['key'])
                                            @if($sortDirection === 'asc')
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
                        @forelse($products as $product)
                            <tr wire:key="{{ $product->id }}"
                                class="hover:bg-gray-50 hover:bg-opacity-50 hover:border-b dark:hover:bg-zinc-500">

                                @foreach($columns as $col)
                                    @if(in_array($col['key'], $visibleColumns))
                                        <td class="px-6 py-4 text-center">

                                            @if($col['key'] === 'id')
                                                {{ $product->id }}
                                            @elseif($col['key'] === 'thumbnail')
                                                <div class="flex justify-center">
                                                    <img src="{{ $product->thumbnail_image
                    ? asset('storage/' . $product->thumbnail_image)
                    : 'https://placehold.co/64x64/e2e8f0/e2e8f0?text=No+Image' }}"
                                                        class="h-10 w-10 rounded-md object-cover">
                                                </div>
                                            @elseif($col['key'] === 'name')
                                                {{ $product->name }}
                                            @elseif($col['key'] === 'store')
                                                {{ $product->store->name ?? '-' }}
                                            @elseif($col['key'] === 'category')
                                                {{ $product->category->name ?? '-' }}
                                            @elseif($col['key'] === 'brand')
                                                {{ $product->brand->name ?? '-' }}
                                            @elseif($col['key'] === 'status')
                                                @if ($product->status)
                                                    <flux:badge color="green">Active</flux:badge>
                                                @else
                                                    <flux:badge color="red">Inactive</flux:badge>
                                                @endif
                                            @elseif($col['key'] === 'actions')
                                                <div class="flex items-center justify-center gap-2 text-sm font-medium">
                                                    @can('product.edit')
                                                        <flux:button wire:click="edit({{ $product->id }})" icon="pencil-square"></flux:button>
                                                    @endcan
                                                    @can('product.delete')
                                                        <flux:modal.trigger name="delete-modal">
                                                            <flux:button wire:click="confirmDelete({{ $product->id }})" icon="trash" variant="danger">
                                                            </flux:button>
                                                        </flux:modal.trigger>
                                                    @endcan
                                                </div>
                                            @endif

                                        </td>
                                    @endif
                                @endforeach

                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($visibleColumns) }}" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No products found.
                                </td>
                            </tr>
                        @endforelse
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

                <div class="grid grid-cols-2 gap-4">
                    <flux:select wire:model="store_id" label="Store">
                        @if (!$productId)
                            <option value="">Select Store</option>
                        @endif
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}" {{ $store_id == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                        @endforeach
                    </flux:select>

                    <flux:select wire:model="brand_id" label="Brand">
                        <option value="">Select Brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </flux:select>

                    <flux:select wire:model.live="category_id" label="Category">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </flux:select>

                    <flux:select wire:model="sub_category_id" label="Subcategory">
                        <option value="">Select Subcategory</option>
                        @foreach($subcategories as $sub)
                            <option value="{{ $sub->id }}" {{ $sub_category_id == $sub->id ? 'selected' : '' }}>
                                {{ $sub->name }}
                            </option>
                        @endforeach
                    </flux:select>
                </div>

                <flux:input wire:model.live="name" label="Product Name" placeholder="Product Name" />
                <flux:input wire:model="slug" label="Slug" placeholder="product-slug" />

                <flux:input wire:model.live="short_description" label="Short Description" placeholder="Short description..." />

                <!-- Summernote for long description -->
                <flux:field>
                    <div x-data x-init="
                        const summernote = $($refs.editor).summernote({
                            height: 250,
                            callbacks: {
                                onChange: (description) => $wire.long_description = description
                            }
                        });

                        $watch('$wire.long_description', value => {
                            if (value !== $($refs.editor).summernote('code')) {
                                $($refs.editor).summernote('code', value);
                            }
                        });
                    ">
                        <div wire:ignore>
                            <textarea x-ref="editor">{!! $long_description !!}</textarea>
                        </div>
                    </div>
                    <flux:error name="long_description" />
                </flux:field>

                <!-- Thumbnail Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Thumbnail</label>
                    <div class="mt-2 flex items-center space-x-4">
                        <div class="shrink-0">
                            @if ($thumbnail_image)
                                <img class="h-16 w-16 object-cover rounded-md" src="{{ $thumbnail_image->temporaryUrl() }}">
                            @elseif ($thumbnail_path)
                                <img class="h-16 w-16 object-cover rounded-md" src="{{ asset('storage/' . $thumbnail_path) }}">
                            @else
                                <div class="h-16 w-16 bg-gray-100 rounded-md flex items-center justify-center text-gray-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <flux:input type="file" wire:model="thumbnail_image"/>
                    </div>
                    @error('thumbnail_image') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Gallery Images -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Gallery Images</label>
                    <div class="mt-2 flex gap-2 overflow-x-auto">
                        @foreach($existingGallery as $img)
                            <div class="relative">
                                <img src="{{ asset('storage/' . $img['image_path']) }}" class="h-20 w-20 object-cover rounded-md">
                                <button type="button" wire:click="removeGalleryImage({{ $img['id'] }})" class="absolute top-0 right-0 bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center">X</button>
                            </div>
                        @endforeach
                        @foreach($gallery_images as $img)
                            <img src="{{ $img->temporaryUrl() }}" class="h-20 w-20 object-cover rounded-md">
                        @endforeach
                        <flux:input type="file" wire:model="gallery_images" multiple/>
                    </div>
                    @error('gallery_images.*') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Product Attributes -->
                <div>
                    <label class="block font-medium mb-1">Product Attributes</label>

                    @foreach($productAttributes as $index => $attr)
                        <div class="flex gap-2 mb-2">
                            <flux:input 
                                wire:model.lazy="productAttributes.{{ $index }}.color" 
                                placeholder="Color" 
                                wire:change="generateSKU({{ $index }})" 
                            />

                            <flux:input 
                                wire:model.lazy="productAttributes.{{ $index }}.size" 
                                placeholder="Size" 
                                wire:change="generateSKU({{ $index }})" 
                            />

                            <flux:input 
                                wire:model.lazy="productAttributes.{{ $index }}.price" 
                                type="number" 
                                placeholder="Price"
                            />

                            <flux:input 
                                wire:model.lazy="productAttributes.{{ $index }}.quantity" 
                                type="number" 
                                placeholder="Quantity"
                            />

                            <flux:input 
                                wire:model="productAttributes.{{ $index }}.sku" 
                                placeholder="SKU" 
                                readonly
                            />

                            <flux:button 
                                variant="danger" 
                                wire:click="removeAttribute({{ $index }})" 
                                icon="trash"
                                class="p-2.5"
                            ></flux:button>
                        </div>
                    @endforeach

                    <flux:button 
                        variant="primary" 
                        wire:click="addAttribute()" 
                        icon="plus" 
                        class="mt-2"
                    > Attribute</flux:button>
                </div>
                



                <div class="flex items-center gap-4 mt-4">
                    <flux:field variant="inline">
                        <flux:checkbox wire:model="status"/>
                        <flux:label>Active</flux:label>
                    </flux:field>
                    <flux:field variant="inline">
                        <flux:checkbox wire:model="is_featured"/>
                        <flux:label>Featured</flux:label>
                    </flux:field>
                </div>

                <div class="flex mt-4">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button type="button" variant="filled">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="primary" class="ml-3">Save</flux:button>
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