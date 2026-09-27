<section>

    <livewire:utilities.toast-modal />

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>SubCategories</flux:breadcrumbs.item>
            </flux:breadcrumbs>

            @can ('subcategory.create')
                <flux:modal.trigger name="sub-category-modal" @click="$wire.resetForm()">
                    <flux:button>
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create SubCategory
                    </flux:button>
                </flux:modal.trigger>
            @endcan
        </div>

        <!-- Search and Table Section -->
        <div class="bg-white dark:bg-zinc-700 border border-gray-200 dark:border-zinc-600 shadow-md rounded-lg overflow-hidden">
            <div class="p-4">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search subcategories by name..."
                    class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                    <thead class="bg-gray-50 dark:bg-zinc-600">
                        <tr>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-600 divide-y divide-gray-200 dark:divide-zinc-700">
                        @forelse($subCategories as $sub)
                            <tr wire:key="{{ $sub->id }}" class="text-center hover:bg-gray-50 hover:bg-opacity-50 hover:border-b dark:hover:bg-zinc-500">
                                <td class="px-6 py-4 whitespace-nowrap flex justify-center">
                                    @if ($sub->media)
                                        <img src="{{ $sub->media->urls['thumb'] ?? $sub->media->url }}" alt="{{ $sub->name }}" class="h-10 w-10 rounded-lg object-cover border border-gray-200 dark:border-zinc-600">
                                    @else
                                        <div class="w-10 h-10 bg-gray-100 dark:bg-zinc-700 rounded-lg flex items-center justify-center text-gray-400 text-[10px] font-semibold">
                                            No Media
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $sub->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $sub->slug }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $sub->category->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                    @if ($sub->status)
                                        <flux:badge color="green">Active</flux:badge>
                                    @else
                                        <flux:badge color="red">Inactive</flux:badge>
                                    @endif
                                </td>
                                <td class="px-6 py-4 flex items-center justify-center gap-2 text-sm font-medium">
                                    @can ('subcategory.edit')
                                        <flux:button wire:click="edit({{ $sub->id }})" icon="pencil-square"></flux:button>
                                    @endcan
                                    @can ('subcategory.delete')
                                        <flux:modal.trigger name="delete-modal">
                                            <flux:button wire:click="confirmDelete({{ $sub->id }})" icon="trash" variant="danger"></flux:button>
                                        </flux:modal.trigger>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No subcategories found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4">
                {{ $subCategories->links() }}
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <flux:modal name="sub-category-modal" class="md:w-[32rem]">
            <form wire:submit.prevent="save" class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ $subCategoryId ? 'Edit SubCategory' : 'Create SubCategory' }}</flux:heading>
                    <flux:text class="mt-2">Fill in the details for the subcategory.</flux:text>
                </div>

                <flux:select wire:model="category_id" label="Parent Category">
                    <option value="">Select category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </flux:select>

                <flux:input wire:model.live="name" label="Name" placeholder="e.g. Mobile Phones" />
                <flux:input wire:model="slug" label="Slug" placeholder="e.g. mobile-phones" />

                <!-- Media Selector -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">SubCategory Image</label>
                    <div class="flex items-center space-x-4 p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800/50">
                        <div class="shrink-0">
                            @if ($mediaUrl)
                                <img class="h-16 w-16 object-cover rounded-xl border border-gray-200 dark:border-zinc-700 shadow-2xs" src="{{ $mediaUrl }}" alt="SubCategory Image">
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
                                    wire:click="$dispatch('open-media-modal', { targetEvent: 'subcategory-media-selected', folder: 'categories' })"
                                    class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-xs transition flex items-center space-x-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>Choose Media</span>
                                </button>
                                @if ($media_id)
                                    <button type="button" wire:click="removeMedia" class="px-2.5 py-2 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-xl text-xs font-medium transition">
                                        Remove
                                    </button>
                                @endif
                            </div>
                            <p class="text-[11px] text-gray-500">Pick from Media Manager or upload a new image.</p>
                        </div>
                    </div>
                    @error('media_id')
                        <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex items-center">
                    <flux:field variant="inline">
                        <flux:checkbox wire:model="status" />
                        <flux:label>Active</flux:label>
                        <flux:error name="status" />
                    </flux:field>
                </div>

                <div class="flex">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button type="button" variant="filled">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="primary" class="ml-3">Save</flux:button>
                </div>
            </form>
        </flux:modal>

        <!-- Delete Confirmation Modal -->
        <flux:modal name="delete-modal" class="md:w-96">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Delete subcategory?</flux:heading>
                    <flux:text class="mt-2">
                        <p>You're about to delete this subcategory.</p>
                        <p>This action cannot be reversed.</p>
                    </flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button wire:click="delete" type="button" variant="danger">Delete subcategory</flux:button>
                </div>
            </div>
        </flux:modal>
    </div>
</section>