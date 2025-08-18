<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
        {{-- <h1 class="text-2xl font-bold text-gray-800">Category Management</h1> --}}
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Categories</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        
        <flux:modal.trigger name="category-modal" @click="$wire.resetForm()">
            <flux:button>
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Create Category
            </flux:button>
        </flux:modal.trigger>
    </div>

    <!-- Search and Table Section -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-4">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search categories by name..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($categories as $category)
                        <tr wire:key="{{ $category->id }}"
                            class="hover:bg-gray-50 hover:bg-opacity-50 hover:border-b">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <img src="{{ $category->image_path ? asset('storage/' . $category->image_path) : 'https://placehold.co/64x64/e2e8f0/e2e8f0?text=No+Image' }}" alt="{{ $category->name }}" class="h-10 w-10 rounded-md object-cover">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $category->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $category->slug }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if ($category->status)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 flex items-center justify-end gap-2 text-sm  font-medium">
                                <flux:button wire:click="edit({{ $category->id }})"
                                              icon="pencil-square">
                                </flux:button>
                                <flux:modal.trigger name="delete-modal">
                                    <flux:button wire:click="confirmDelete({{ $category->id }})" variant="danger">Delete</flux:button>
                                </flux:modal.trigger>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="p-4">
            {{ $categories->links() }}
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <flux:modal name="category-modal" class="md:w-[32rem]">
        <form wire:submit.prevent="save" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $categoryId ? 'Edit Category' : 'Create Category' }}</flux:heading>
                <flux:text class="mt-2">Fill in the details for the category.</flux:text>
            </div>

            <flux:input wire:model.live="name" label="Name" placeholder="e.g. Electronics" />
            <flux:input wire:model="slug" label="Slug" placeholder="e.g. electronics" class="bg-gray-50" />
            
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
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"></path></svg>
                            </div>
                        @endif
                    </div>
                    <label class="block">
                        <span class="sr-only">Choose profile photo</span>
                        <input type="file" wire:model="image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"/>
                    </label>
                </div>
                @error('image_path') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            
            <div class="flex items-center">
                
                <input wire:model="status" type="checkbox" id="status" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <label for="status" class="ml-2 text-sm text-gray-600">Active</label>
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
                <flux:heading size="lg">Delete category?</flux:heading>
                <flux:text class="mt-2">
                    <p>You're about to delete this category.</p>
                    <p>This action cannot be reversed.</p>
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button  wire:click="delete" type="button" variant="danger">Delete category</flux:button>
            </div>
        </div>
    </flux:modal>
</div>