<section>

    <livewire:utilities.toast-modal />

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>Stores Approval</flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>

        <!-- Search -->
        <div class="bg-white dark:bg-zinc-700 border border-gray-200 dark:border-zinc-600 shadow-md rounded-lg overflow-hidden">
            <div class="p-4">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search stores by name..."
                    class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                    <thead class="bg-gray-50 dark:bg-zinc-600  text-center ">
                        <tr>
                            <th class="px-6 py-3">Logo</th>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">Owner</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Approved</th>
                            <th class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-600 divide-y divide-gray-200 dark:divide-zinc-700">
                        @forelse($stores as $store)
                            <tr class=" text-center ">
                                <td class="px-6 py-4 flex justify-center">
                                    <img src="{{ $store->logo ? asset('storage/' . $store->logo) : 'https://placehold.co/64x64?text=No+Logo' }}" class="h-10 w-10 rounded-md object-cover">
                                </td>
                                <td class="px-6 py-4">{{ $store->name }}</td>
                                <td class="px-6 py-4">{{ $store->user->name }}</td>
                                <td class="px-6 py-4">
                                    @if ($store->status)
                                        <flux:badge color="green">Active</flux:badge>
                                    @else
                                        <flux:badge color="red">Inactive</flux:badge>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($store->is_approved)
                                        <flux:badge color="green">Yes</flux:badge>
                                    @else
                                        <flux:badge color="red">No</flux:badge>
                                    @endif
                                </td>
                                <td class="px-6 py-4 flex gap-2 justify-center">
                                    @can ('store.approval')
                                        <flux:modal.trigger name="approve-modal">
                                            <flux:button wire:click="confirmApprove({{ $store->id }})" icon="check" variant="primary" color="green"/>
                                        </flux:modal.trigger>
                                    @endcan

                                    @can ('store.delete') 
                                        <flux:modal.trigger name="delete-modal">
                                            <flux:button wire:click="confirmDelete({{ $store->id }})" icon="trash" variant="danger" />
                                        </flux:modal.trigger>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center">No stores found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4">{{ $stores->links() }}</div>
        </div>

        <!-- Approve Modal -->
        <flux:modal name="approve-modal" class="md:w-96">
            <flux:heading size="lg">Approve store?</flux:heading>
            <flux:text class="my-2">You're about to approve this store. Continue?</flux:text>
            <div class="flex gap-2 justify-end">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button wire:click="approve" variant="primary" color="green">Approve</flux:button>
            </div>
        </flux:modal>

        <!-- Delete Modal -->
        <flux:modal name="delete-modal" class="md:w-96">
            <flux:heading size="lg">Delete store?</flux:heading>
            <flux:text class="my-2">This action cannot be undone.</flux:text>
            <div class="flex gap-2 justify-end">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button wire:click="delete" variant="danger">Delete</flux:button>
            </div>
        </flux:modal>

    </div>
</section>
