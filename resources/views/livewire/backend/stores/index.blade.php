<section>

    <livewire:utilities.toast-modal />

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>Stores</flux:breadcrumbs.item>
            </flux:breadcrumbs>

            @can ('store.create') 
                <flux:modal.trigger name="store-modal" @click="$wire.resetForm()">
                    <flux:button>
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create Store
                    </flux:button>
                </flux:modal.trigger>
            @endcan
        </div>

        <!-- Search and Table Section -->
        <div class="bg-white dark:bg-zinc-700 border border-gray-200 dark:border-zinc-600 shadow-md rounded-lg overflow-hidden">
            <div class="p-4">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search stores by name..."
                    class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                    <thead class="bg-gray-50 dark:bg-zinc-600">
                        <tr>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Logo</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Owner ID</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Approved</th>
                            <th class="px-6 py-3 relative">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-600 divide-y divide-gray-200 dark:divide-zinc-700">
                        @forelse($stores as $store)
                            <tr wire:key="{{ $store->id }}" class=" hover:bg-gray-50 hover:bg-opacity-50 hover:border-b dark:hover:bg-zinc-500">
                                <td class="px-6 py-4 whitespace-nowrap flex justify-center">
                                    <img src="{{ $store->logo ? asset('storage/' . $store->logo) : 'https://placehold.co/64x64/e2e8f0/e2e8f0?text=No+Logo' }}" class="h-10 w-10 rounded-md object-cover">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-900 dark:text-gray-100">{{ $store->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900 dark:text-gray-100">{{ $store->slug }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900 dark:text-gray-100">{{ $store->user->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900 dark:text-gray-100">{{ $store->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                    @if ($store->status)
                                        <flux:badge color="green">Active</flux:badge>
                                    @else
                                        <flux:badge color="red">Inactive</flux:badge>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap text-sm">
                                    @if ($store->is_approved)
                                        <flux:badge color="green">Yes</flux:badge>
                                    @else
                                        <flux:badge color="red">No</flux:badge>
                                    @endif
                                </td>
                                <td class="px-6 py-4 flex items-center justify-center gap-2 text-sm font-medium">
                                    @can ('store.edit')
                                        <flux:button wire:click="edit({{ $store->id }})" icon="pencil-square"></flux:button>
                                    @endcan
                                    @can ('store.delete') 
                                        <flux:modal.trigger name="delete-modal">
                                            <flux:button wire:click="confirmDelete({{ $store->id }})" icon="trash" variant="danger"></flux:button>
                                        </flux:modal.trigger>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No stores found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4">
                {{ $stores->links() }}
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <flux:modal name="store-modal" class="md:max-w-7xl md:min-w-3xl ">
            <form wire:submit.prevent="save" class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ $storeId ? 'Edit Store' : 'Create Store' }}</flux:heading>
                    <flux:text class="mt-2">Fill in the details for the store.</flux:text>
                </div>

                <flux:select wire:model="user_id" label="Store Owner">
                    <option value="">Select owner</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </flux:select>

                <flux:input wire:model.live="name" label="Name" placeholder="e.g. Mobile Hub" />
                <flux:input wire:model="slug" label="Slug" placeholder="e.g. mobile-hub" />
                <flux:input wire:model="phone" label="Phone" placeholder="e.g. 0123456789" />
                <flux:input wire:model="address" label="Address" placeholder="e.g. Dhaka, Bangladesh" />
                <!-- <flux:textarea wire:model="description" id="summernote" label="Description" placeholder="Store description" /> -->
                <flux:field>
                    <div x-data x-init="
                            const summernote = $($refs.editor).summernote({
                                height: 300,
                                callbacks: {
                                    onChange: (description) => $wire.description = description
                                }
                            });

                            $watch('$wire.description', value => {
                                if (value !== $($refs.editor).summernote('code')) {
                                    $($refs.editor).summernote('code', value);
                                }
                            });
                        ">
                        <div wire:ignore>
                            <textarea x-ref="editor">{!! $description !!}</textarea>
                        </div>
                    </div>
                    <flux:error name="description" />
                </flux:field>

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

                <div class="flex items-center gap-4">
                    @can ('store.status')
                        <flux:field variant="inline">
                            <flux:checkbox wire:model="status" />
                            <flux:label>Active</flux:label>
                            <flux:error name="status" />
                        </flux:field>
                    @endcan

                    @can ('store.approval')
                        <flux:field variant="inline">
                            <flux:checkbox wire:model="is_approved" />
                            <flux:label>Approved</flux:label>
                            <flux:error name="is_approved" />
                        </flux:field>
                    @endcan
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
                    <flux:heading size="lg">Delete store?</flux:heading>
                    <flux:text class="mt-2">
                        <p>You're about to delete this store.</p>
                        <p>This action cannot be reversed.</p>
                    </flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button wire:click="delete" type="button" variant="danger">Delete store</flux:button>
                </div>
            </div>
        </flux:modal>
    </div>
</section>
