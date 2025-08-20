<section>
    <livewire:utilities.toast-modal />

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>Vendors</flux:breadcrumbs.item>
            </flux:breadcrumbs>

            <flux:modal.trigger name="vendor-modal" @click="$wire.resetForm()">
                <flux:button>
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Vendor
                </flux:button>
            </flux:modal.trigger>
        </div>

        <!-- Search and Table Section -->
        <div class="bg-white dark:bg-zinc-700 shadow-md rounded-lg overflow-hidden">
            <div class="p-4">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search vendors by name..."
                    class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                    <thead class="bg-gray-50 dark:bg-zinc-600">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Logo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 relative">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-600 divide-y divide-gray-200 dark:divide-zinc-700">
                        @forelse($vendors as $vendor)
                            <tr wire:key="{{ $vendor->id }}" class="hover:bg-gray-50 hover:bg-opacity-50 dark:hover:bg-zinc-500">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <img src="{{ $vendor->logo ? asset('storage/' . $vendor->logo) : 'https://placehold.co/64x64/e2e8f0/e2e8f0?text=No+Logo' }}" class="h-10 w-10 rounded-md object-cover">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $vendor->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $vendor->slug }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $vendor->phone }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if ($vendor->status)
                                        <flux:badge color="green">Active</flux:badge>
                                    @else
                                        <flux:badge color="red">Inactive</flux:badge>
                                    @endif
                                </td>
                                <td class="px-6 py-4 flex items-center justify-end gap-2 text-sm font-medium">
                                    <flux:button wire:click="edit({{ $vendor->id }})" icon="pencil-square"></flux:button>
                                    <flux:modal.trigger name="delete-modal">
                                        <flux:button wire:click="confirmDelete({{ $vendor->id }})" icon="trash" variant="danger"></flux:button>
                                    </flux:modal.trigger>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No vendors found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4">
                {{ $vendors->links() }}
            </div>
        </div>

        <!-- Create/Edit Vendor Modal -->
        <flux:modal name="vendor-modal" class="md:w-[32rem]">
            <form wire:submit.prevent="save" class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ $storeId ? 'Edit Vendor' : 'Create Vendor' }}</flux:heading>
                    <flux:text class="mt-2">Fill in the details for the vendor.</flux:text>
                </div>

                <flux:input wire:model.live="name" label="Name" placeholder="Vendor Name" />
                <flux:input wire:model="slug" label="Slug" placeholder="vendor-slug" />
                <flux:input wire:model="phone" label="Phone" placeholder="+880..." />
                <flux:input wire:model="address" label="Address" placeholder="Vendor Address" />

                <div>
                    <label class="block text-sm font-medium text-gray-700">Logo</label>
                    <div class="mt-2 flex items-center space-x-4">
                        <div class="shrink-0">
                            @if ($logo)
                                <img class="h-16 w-16 object-cover rounded-md" src="{{ $logo->temporaryUrl() }}">
                            @elseif ($logo_path)
                                <img class="h-16 w-16 object-cover rounded-md" src="{{ asset('storage/' . $logo_path) }}">
                            @else
                                <div class="h-16 w-16 bg-gray-100 rounded-md flex items-center justify-center text-gray-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <flux:input type="file" wire:model="logo"/>
                    </div>
                    @error('logo') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
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
                    <flux:heading size="lg">Delete vendor?</flux:heading>
                    <flux:text class="mt-2">
                        <p>You're about to delete this vendor.</p>
                        <p>This action cannot be reversed.</p>
                    </flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button wire:click="delete" type="button" variant="danger">Delete vendor</flux:button>
                </div>
            </div>
        </flux:modal>
    </div>
</section>
