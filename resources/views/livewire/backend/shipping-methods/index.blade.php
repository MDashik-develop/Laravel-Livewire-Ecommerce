<section class="space-y-5">
    <livewire:utilities.toast-modal />

    <!-- Top Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <x-admin.breadcrumb :items="[
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Shipping Methods']
            ]" />
            <h1 class="mt-2 text-lg font-bold text-slate-900 dark:text-zinc-100">Shipping Methods</h1>
            <p class="text-xs text-slate-500 dark:text-zinc-400">Configure delivery options, base fees, weight rates, and COD settings.</p>
        </div>

        <x-admin.button wire:click="create" variant="primary" icon="plus">
            Add Shipping Method
        </x-admin.button>
    </div>

    <!-- Search & Table Card -->
    <x-admin.card class="p-0 overflow-hidden">
        <!-- Search bar -->
        <div class="p-3.5 border-b border-slate-200 dark:border-zinc-800 bg-slate-50/60 dark:bg-zinc-900/40">
            <div class="relative max-w-md">
                <input wire:model.live.debounce.300ms="search" type="text"
                    placeholder="Search shipping methods by name, code, or description..."
                    class="w-full pl-9 pr-3 py-1.5 text-xs bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-700 rounded-[3px] text-slate-800 dark:text-zinc-100 placeholder-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500/30 transition">
                <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-2.5"></i>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-zinc-800 text-left text-xs">
                <thead class="bg-slate-50 dark:bg-zinc-900/60 text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-zinc-400">
                    <tr>
                        <th wire:click="sortBy('id')" class="px-4 py-3 cursor-pointer hover:text-brand-600 transition">
                            <div class="flex items-center space-x-1">
                                <span>ID</span>
                                @if ($sortField === 'id')
                                    <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </div>
                        </th>
                        <th wire:click="sortBy('name')" class="px-4 py-3 cursor-pointer hover:text-brand-600 transition">
                            <div class="flex items-center space-x-1">
                                <span>Method Name</span>
                                @if ($sortField === 'name')
                                    <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </div>
                        </th>
                        <th class="px-4 py-3">Code</th>
                        <th wire:click="sortBy('base_charge')" class="px-4 py-3 cursor-pointer hover:text-brand-600 transition">
                            <div class="flex items-center space-x-1">
                                <span>Base Charge</span>
                                @if ($sortField === 'base_charge')
                                    <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </div>
                        </th>
                        <th wire:click="sortBy('per_kg_charge')" class="px-4 py-3 cursor-pointer hover:text-brand-600 transition">
                            <div class="flex items-center space-x-1">
                                <span>Per KG Charge</span>
                                @if ($sortField === 'per_kg_charge')
                                    <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </div>
                        </th>
                        <th class="px-4 py-3 text-center">COD Available</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-zinc-800 bg-white dark:bg-zinc-900">
                    @forelse ($methods as $method)
                        <tr wire:key="shipping-method-{{ $method->id }}" class="hover:bg-slate-50/80 dark:hover:bg-zinc-800/40 transition">
                            <td class="px-4 py-3 text-xs font-mono font-medium text-slate-500 dark:text-zinc-400">
                                #{{ $method->id }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-xs text-slate-900 dark:text-zinc-100">
                                    {{ $method->name }}
                                </div>
                                @if ($method->description)
                                    <p class="text-[11px] text-slate-500 dark:text-zinc-400 mt-0.5 line-clamp-1 max-w-xs">
                                        {{ $method->description }}
                                    </p>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-[3px] text-[11px] font-mono font-medium bg-slate-100 text-slate-700 dark:bg-zinc-800 dark:text-zinc-300 border border-slate-200 dark:border-zinc-700">
                                    {{ $method->code }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs font-semibold text-slate-900 dark:text-zinc-100">
                                ${{ number_format((float) $method->base_charge, 2) }}
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-700 dark:text-zinc-300">
                                ${{ number_format((float) $method->per_kg_charge, 2) }}<span class="text-[10px] text-slate-400">/kg</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button type="button" wire:click="toggleCod({{ $method->id }})" class="cursor-pointer focus:outline-none">
                                    @if ($method->cod_available)
                                        <x-admin.badge variant="success">Available</x-admin.badge>
                                    @else
                                        <x-admin.badge variant="secondary">Unavailable</x-admin.badge>
                                    @endif
                                </button>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button type="button" wire:click="toggleStatus({{ $method->id }})" class="cursor-pointer focus:outline-none">
                                    @if ($method->status)
                                        <x-admin.badge variant="success" dot>Active</x-admin.badge>
                                    @else
                                        <x-admin.badge variant="danger" dot>Inactive</x-admin.badge>
                                    @endif
                                </button>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <x-admin.button wire:click="edit({{ $method->id }})" icon="pencil" size="sm" variant="secondary" title="Edit" />
                                    <x-admin.button wire:click="confirmDelete({{ $method->id }})" icon="trash-2" size="sm" variant="danger" title="Delete" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-500 dark:text-zinc-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <div class="w-10 h-10 rounded-[4px] bg-slate-100 dark:bg-zinc-800 flex items-center justify-center text-slate-400">
                                        <i data-lucide="truck" class="w-5 h-5"></i>
                                    </div>
                                    <p class="text-xs font-medium">No shipping methods found.</p>
                                    <p class="text-[11px] text-slate-400">Click "Add Shipping Method" to set up your first delivery method.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($methods->hasPages())
            <div class="p-3.5 border-t border-slate-200 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-900/30">
                {{ $methods->links() }}
            </div>
        @endif
    </x-admin.card>

    <!-- Create / Edit Shipping Method Modal -->
    <x-admin.modal name="shipping-method-modal" title="{{ $shippingMethodId ? 'Edit Shipping Method' : 'Create Shipping Method' }}" maxWidth="max-w-xl">
        <form wire:submit.prevent="save" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <x-admin.input wire:model.live="name" label="Method Name *" placeholder="e.g. Standard Delivery, Express Shipping" />
                </div>

                <div class="sm:col-span-2">
                    <x-admin.input wire:model="code" label="Unique Code *" placeholder="e.g. standard_delivery, express_shipping" />
                    <p class="text-[10px] text-slate-400 mt-1">Unique identifier code used in orders and checkout calculation.</p>
                </div>

                <div>
                    <x-admin.input wire:model="base_charge" type="number" step="0.01" min="0" label="Base Charge ($) *" placeholder="0.00" />
                </div>

                <div>
                    <x-admin.input wire:model="per_kg_charge" type="number" step="0.01" min="0" label="Per KG Charge ($) *" placeholder="0.00" />
                </div>

                <div class="sm:col-span-2">
                    <x-admin.textarea wire:model="description" label="Description" rows="3" placeholder="Brief note about delivery time, terms, or carrier info..." />
                </div>

                <div class="sm:col-span-2 space-y-3 pt-2 border-t border-slate-100 dark:border-zinc-800">
                    <div class="flex items-center">
                        <x-admin.switch wire:model="cod_available" label="Cash On Delivery (COD) Available" description="Allow buyers to pay when goods are delivered" />
                    </div>

                    <div class="flex items-center">
                        <x-admin.switch wire:model="status" label="Active Status" description="Enable or disable this shipping method for customer checkout" />
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-200 dark:border-zinc-800">
                <x-admin.button type="button" variant="ghost" @click="$dispatch('modal-close', { name: 'shipping-method-modal' })">
                    Cancel
                </x-admin.button>
                <x-admin.button type="submit" variant="primary" icon="check">
                    {{ $shippingMethodId ? 'Update Method' : 'Create Method' }}
                </x-admin.button>
            </div>
        </form>
    </x-admin.modal>

    <!-- Delete Confirmation Modal -->
    <x-admin.modal name="delete-shipping-modal" title="Delete Shipping Method" maxWidth="max-w-md">
        <div class="space-y-4 text-center">
            <div class="w-10 h-10 rounded-[4px] bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 mx-auto flex items-center justify-center">
                <i data-lucide="trash-2" class="w-5 h-5"></i>
            </div>
            <div>
                <h4 class="font-bold text-sm text-slate-800 dark:text-zinc-100">Delete Shipping Method?</h4>
                <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">
                    Are you sure you want to delete this shipping method? This action cannot be undone.
                </p>
            </div>
            <div class="flex justify-center gap-2 pt-2">
                <x-admin.button type="button" variant="ghost" @click="$dispatch('modal-close', { name: 'delete-shipping-modal' })">
                    Cancel
                </x-admin.button>
                <x-admin.button wire:click="delete" type="button" variant="danger">
                    Delete Method
                </x-admin.button>
            </div>
        </div>
    </x-admin.modal>
</section>
