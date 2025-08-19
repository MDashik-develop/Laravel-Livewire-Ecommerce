<section>
    <livewire:utilities.toast-modal />

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>Brands</flux:breadcrumbs.item>
            </flux:breadcrumbs>

            <flux:modal.trigger name="brand-modal" @click="$wire.resetForm()">
                <flux:button>
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Create Brand
                </flux:button>
            </flux:modal.trigger>
        </div>

        <!-- Search & Table -->
        <div class="bg-white dark:bg-zinc-700 shadow-md rounded-lg overflow-hidden">
            <div class="p-4">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search brands by name..."
                    class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                    <thead class="bg-gray-50 dark:bg-zinc-600">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium">Image</th>
                            <th class="px-6 py-3 text-left text-xs font-medium">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium">Slug</th>
                            <th class="px-6 py-3 text-left text-xs font-medium">Status</th>
                            <th class="px-6 py-3 relative"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-600 divide-y divide-gray-200 dark:divide-zinc-700">
                        @forelse ($brands as $brand)
                            <tr wire:key="{{ $brand->id }}" class="hover:bg-gray-50 hover:bg-opacity-50 dark:hover:bg-zinc-500">
                                <td class="px-6 py-4">
                                    @if ($brand->image_path)
                                        <img src="{{ asset('storage/'.$brand->image_path) }}" class="w-12 h-12 object-cover rounded-md">
                                    @else
                                        <span class="text-gray-400">No image</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $brand->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $brand->slug }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @if ($brand->status)
                                        <flux:badge color="green">Active</flux:badge>
                                    @else
                                        <flux:badge color="red">Inactive</flux:badge>
                                    @endif
                                </td>
                                <td class="px-6 py-4 flex justify-end gap-2">
                                    <flux:button wire:click="edit({{ $brand->id }})" icon="pencil-square"></flux:button>
                                    <flux:modal.trigger name="delete-modal">
                                        <flux:button wire:click="confirmDelete({{ $brand->id }})" icon="trash" variant="danger"></flux:button>
                                    </flux:modal.trigger>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No brands found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4">
                {{ $brands->links() }}
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <flux:modal name="brand-modal" class="md:w-[32rem]">
            <form wire:submit.prevent="save" class="space-y-6">
                <flux:heading size="lg">{{ $brandId ? 'Edit Brand' : 'Create Brand' }}</flux:heading>

                <flux:input wire:model.live="name" label="Name" placeholder="e.g. Apple" />
                <flux:input wire:model="slug" label="Slug" placeholder="e.g. apple" />

                <div>
                    <label class="block text-sm font-medium text-gray-700">Image</label>
                    <div class="mt-2 flex items-center space-x-4">
                        <div class="shrink-0">
                            @if ($image)
                                <img class="h-16 w-16 object-cover rounded-md" src="{{ $image->temporaryUrl() }}" alt="New Image Preview">
                            @elseif ($image_path)
                                <img class="h-16 w-16 object-cover rounded-md" src="{{ asset('storage/' . $image_path) }}" alt="Current Image">
                            @else
                                <div class="h-16 w-16 bg-gray-100 rounded-md flex items-center justify-center text-gray-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"></path></svg>
                                </div>
                            @endif
                        </div>
                        <flux:input type="file" wire:model="image"/>
                    </div>
                    @error('image') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
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
                    <flux:heading size="lg">Delete brand?</flux:heading>
                    <flux:text class="mt-2">This action cannot be undone.</flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button wire:click="delete" type="button" variant="danger">Delete brand</flux:button>
                </div>
            </div>
        </flux:modal>
    </div>
</section>
