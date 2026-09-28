<section>
    <livewire:utilities.toast-modal />

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Top Bar -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <flux:breadcrumbs>
                    <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
                    <flux:breadcrumbs.item>Shipping Methods</flux:breadcrumbs.item>
                </flux:breadcrumbs>
                <flux:heading size="xl" class="mt-2 font-bold text-gray-900 dark:text-white">Shipping Methods</flux:heading>
                <flux:subheading class="text-sm text-gray-500 dark:text-gray-400">Configure delivery options, base fees, weight rates, and COD settings.</flux:subheading>
            </div>

            <flux:button wire:click="create" variant="primary" icon="plus" class="shadow-sm cursor-pointer">
                Add Shipping Method
            </flux:button>
        </div>

        <!-- Search & Table Card -->
        <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 shadow-sm rounded-xl overflow-hidden">
            <!-- Search bar -->
            <div class="p-4 border-b border-gray-100 dark:border-zinc-700/60 bg-gray-50/50 dark:bg-zinc-800/50">
                <div class="relative max-w-md">
                    <input wire:model.live.debounce.300ms="search" type="text"
                        placeholder="Search shipping methods by name, code, or description..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-zinc-600 rounded-lg text-sm bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700 text-left">
                    <thead class="bg-gray-50 dark:bg-zinc-900/60">
                        <tr>
                            <th wire:click="sortBy('id')" class="px-6 py-3.5 text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:text-indigo-600">
                                <div class="flex items-center space-x-1">
                                    <span>ID</span>
                                    @if ($sortField === 'id')
                                        <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortBy('name')" class="px-6 py-3.5 text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:text-indigo-600">
                                <div class="flex items-center space-x-1">
                                    <span>Method Name</span>
                                    @if ($sortField === 'name')
                                        <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3.5 text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Code
                            </th>
                            <th wire:click="sortBy('base_charge')" class="px-6 py-3.5 text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:text-indigo-600">
                                <div class="flex items-center space-x-1">
                                    <span>Base Charge</span>
                                    @if ($sortField === 'base_charge')
                                        <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortBy('per_kg_charge')" class="px-6 py-3.5 text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:text-indigo-600">
                                <div class="flex items-center space-x-1">
                                    <span>Per KG Charge</span>
                                    @if ($sortField === 'per_kg_charge')
                                        <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3.5 text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-center">
                                COD Available
                            </th>
                            <th class="px-6 py-3.5 text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-center">
                                Status
                            </th>
                            <th class="px-6 py-3.5 text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-right">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700/60">
                        @forelse ($methods as $method)
                            <tr wire:key="shipping-method-{{ $method->id }}" class="hover:bg-gray-50/75 dark:hover:bg-zinc-700/40 transition">
                                <td class="px-6 py-4 text-xs font-medium text-gray-500 dark:text-gray-400">
                                    #{{ $method->id }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-sm text-gray-900 dark:text-gray-100">
                                        {{ $method->name }}
                                    </div>
                                    @if ($method->description)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-1 max-w-xs">
                                            {{ $method->description }}
                                        </p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-mono font-medium bg-slate-100 text-slate-800 dark:bg-zinc-700 dark:text-zinc-200 border border-slate-200 dark:border-zinc-600">
                                        {{ $method->code }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    ${{ number_format((float) $method->base_charge, 2) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    ${{ number_format((float) $method->per_kg_charge, 2) }}<span class="text-xs text-gray-400">/kg</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button type="button" wire:click="toggleCod({{ $method->id }})" class="cursor-pointer focus:outline-none">
                                        @if ($method->cod_available)
                                            <flux:badge color="green" icon="check" class="cursor-pointer hover:opacity-80">Available</flux:badge>
                                        @else
                                            <flux:badge color="zinc" icon="x-mark" class="cursor-pointer hover:opacity-80">Unavailable</flux:badge>
                                        @endif
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button type="button" wire:click="toggleStatus({{ $method->id }})" class="cursor-pointer focus:outline-none">
                                        @if ($method->status)
                                            <flux:badge color="green" class="cursor-pointer hover:opacity-80">Active</flux:badge>
                                        @else
                                            <flux:badge color="red" class="cursor-pointer hover:opacity-80">Inactive</flux:badge>
                                        @endif
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <flux:button wire:click="edit({{ $method->id }})" icon="pencil-square" size="sm" class="cursor-pointer">
                                    </flux:button>
                                    <flux:button wire:click="confirmDelete({{ $method->id }})" icon="trash" size="sm" variant="danger" class="cursor-pointer">
                                    </flux:button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20" />
                                        </svg>
                                        <p class="text-sm font-medium">No shipping methods found.</p>
                                        <p class="text-xs text-gray-400">Click "Add Shipping Method" to set up your first delivery method.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($methods->hasPages())
                <div class="p-4 border-t border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800/50">
                    {{ $methods->links() }}
                </div>
            @endif
        </div>

        <!-- Create / Edit Shipping Method Modal -->
        <flux:modal name="shipping-method-modal" class="md:w-[34rem]">
            <form wire:submit.prevent="save" class="space-y-5">
                <flux:heading size="lg">
                    {{ $shippingMethodId ? 'Edit Shipping Method' : 'Create Shipping Method' }}
                </flux:heading>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <flux:input wire:model.live="name" label="Method Name *" placeholder="e.g. Standard Delivery, Express Shipping" />
                    </div>

                    <div class="sm:col-span-2">
                        <flux:input wire:model="code" label="Unique Code *" placeholder="e.g. standard_delivery, express_shipping" />
                        <p class="text-[11px] text-gray-400 mt-1">Unique identifier code used in orders and checkout calculation.</p>
                    </div>

                    <div>
                        <flux:input wire:model="base_charge" type="number" step="0.01" min="0" label="Base Charge ($) *" placeholder="0.00" />
                    </div>

                    <div>
                        <flux:input wire:model="per_kg_charge" type="number" step="0.01" min="0" label="Per KG Charge ($) *" placeholder="0.00" />
                    </div>

                    <div class="sm:col-span-2">
                        <flux:textarea wire:model="description" label="Description" rows="3" placeholder="Brief note about delivery time, terms, or carrier info..." />
                    </div>

                    <div class="sm:col-span-2 space-y-3 pt-1 border-t border-gray-100 dark:border-zinc-700">
                        <div class="flex items-center">
                            <flux:field variant="inline">
                                <flux:checkbox wire:model="cod_available" />
                                <flux:label>Cash On Delivery (COD) Available</flux:label>
                                <flux:error name="cod_available" />
                            </flux:field>
                        </div>

                        <div class="flex items-center">
                            <flux:field variant="inline">
                                <flux:checkbox wire:model="status" />
                                <flux:label>Active Status</flux:label>
                                <flux:error name="status" />
                            </flux:field>
                        </div>
                    </div>
                </div>

                <div class="flex pt-4">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button type="button" variant="filled">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="primary" class="ml-3">
                        {{ $shippingMethodId ? 'Update Method' : 'Create Method' }}
                    </flux:button>
                </div>
            </form>
        </flux:modal>

        <!-- Delete Confirmation Modal -->
        <flux:modal name="delete-shipping-modal" class="md:w-96">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Delete Shipping Method?</flux:heading>
                    <flux:text class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Are you sure you want to delete this shipping method? This action cannot be undone.
                    </flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button wire:click="delete" type="button" variant="danger">
                        Delete Method
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    </div>
</section>
