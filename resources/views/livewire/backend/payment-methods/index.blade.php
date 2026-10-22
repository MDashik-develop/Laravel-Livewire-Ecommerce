<section class="space-y-5">
    <livewire:utilities.toast-modal />

    <!-- Top Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <x-admin.breadcrumb :items="[
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Payment Methods']
            ]" />
            <h1 class="mt-2 text-lg font-bold text-slate-900 dark:text-zinc-100">Payment Methods</h1>
            <p class="text-xs text-slate-500 dark:text-zinc-400">Manage payment gateways, sandbox modes, API credentials, and checkout instructions.</p>
        </div>

        <x-admin.button wire:click="create" variant="primary" icon="plus">
            Add Payment Method
        </x-admin.button>
    </div>

    <!-- Search & Table Card -->
    <x-admin.card class="p-0 overflow-hidden">
        <!-- Search bar -->
        <div class="p-3.5 border-b border-slate-200 dark:border-zinc-800 bg-slate-50/60 dark:bg-zinc-900/40">
            <div class="relative max-w-md">
                <input wire:model.live.debounce.300ms="search" type="text"
                    placeholder="Search payment gateways by name, slug, or instruction..."
                    class="w-full pl-9 pr-3 py-1.5 text-xs bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-700 rounded-[3px] text-slate-800 dark:text-zinc-100 placeholder-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500/30 transition">
                <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-2.5"></i>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-zinc-800 text-left text-xs">
                <thead class="bg-slate-50 dark:bg-zinc-900/60 text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-zinc-400">
                    <tr>
                        <th class="px-4 py-3 text-center w-16">Logo</th>
                        <th wire:click="sortBy('name')" class="px-4 py-3 cursor-pointer hover:text-brand-600 transition">
                            <div class="flex items-center space-x-1">
                                <span>Gateway Name</span>
                                @if ($sortField === 'name')
                                    <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </div>
                        </th>
                        <th class="px-4 py-3">Slug</th>
                        <th class="px-4 py-3">Credentials</th>
                        <th class="px-4 py-3 text-center">Mode</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-zinc-800 bg-white dark:bg-zinc-900">
                    @forelse ($methods as $method)
                        <tr wire:key="payment-method-{{ $method->id }}" class="hover:bg-slate-50/80 dark:hover:bg-zinc-800/40 transition">
                            <td class="px-4 py-3 flex justify-center">
                                @if ($method->media)
                                    <img src="{{ $method->media->urls['thumb'] ?? $method->media->urls['small'] ?? $method->media->url }}"
                                        alt="{{ $method->name }}"
                                        class="w-10 h-10 object-contain p-1 rounded-[3px] border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                                @else
                                    <div class="w-10 h-10 bg-slate-100 dark:bg-zinc-800 rounded-[3px] flex items-center justify-center text-slate-400">
                                        <i data-lucide="credit-card" class="w-5 h-5"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-xs text-slate-900 dark:text-zinc-100">
                                    {{ $method->name }}
                                </div>
                                @if ($method->instruction)
                                    <p class="text-[11px] text-slate-500 dark:text-zinc-400 mt-0.5 line-clamp-1 max-w-xs">
                                        {{ $method->instruction }}
                                    </p>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-[3px] text-[11px] font-mono font-medium bg-slate-100 text-slate-700 dark:bg-zinc-800 dark:text-zinc-300 border border-slate-200 dark:border-zinc-700">
                                    {{ $method->slug }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $credCount = is_array($method->credentials) ? count($method->credentials) : 0;
                                @endphp
                                @if ($credCount > 0)
                                    <div class="flex items-center space-x-1.5 text-xs text-emerald-600 dark:text-emerald-400 font-medium">
                                        <i data-lucide="key" class="w-3.5 h-3.5"></i>
                                        <span>{{ $credCount }} {{ Str::plural('key', $credCount) }}</span>
                                    </div>
                                @else
                                    <span class="text-[11px] text-slate-400 italic">None</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button type="button" wire:click="toggleSandbox({{ $method->id }})" class="cursor-pointer focus:outline-none">
                                    @if ($method->sandbox_mode)
                                        <x-admin.badge variant="warning">Sandbox</x-admin.badge>
                                    @else
                                        <x-admin.badge variant="info">Live</x-admin.badge>
                                    @endif
                                </button>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button type="button" wire:click="toggleActive({{ $method->id }})" class="cursor-pointer focus:outline-none">
                                    @if ($method->is_active)
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
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500 dark:text-zinc-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <div class="w-10 h-10 rounded-[4px] bg-slate-100 dark:bg-zinc-800 flex items-center justify-center text-slate-400">
                                        <i data-lucide="credit-card" class="w-5 h-5"></i>
                                    </div>
                                    <p class="text-xs font-medium">No payment methods found.</p>
                                    <p class="text-[11px] text-slate-400">Click "Add Payment Method" to configure gateways like Stripe, PayPal, bKash, or Cash on Delivery.</p>
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

    <!-- Create / Edit Payment Method Modal -->
    <x-admin.modal name="payment-method-modal" title="{{ $paymentMethodId ? 'Edit Payment Method' : 'Create Payment Method' }}" maxWidth="max-w-xl">
        <form wire:submit.prevent="save" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-admin.input wire:model.live="name" label="Gateway Name *" placeholder="e.g. Stripe, PayPal, bKash" />
                </div>
                <div>
                    <x-admin.input wire:model="slug" label="Slug *" placeholder="e.g. stripe, paypal, bkash" />
                </div>
            </div>

            <!-- Media / Gateway Logo Selector -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-zinc-300 mb-1.5">Gateway Logo / Icon</label>
                <div class="flex items-center space-x-3 p-3 rounded-[4px] border border-slate-200 dark:border-zinc-700 bg-slate-50/50 dark:bg-zinc-800/40">
                    <div class="shrink-0">
                        @if ($mediaUrl)
                            <img class="h-12 w-12 object-contain rounded-[3px] border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-1"
                                src="{{ $mediaUrl }}" alt="Payment Logo">
                        @else
                            <div class="h-12 w-12 bg-slate-100 dark:bg-zinc-800 rounded-[3px] flex items-center justify-center text-slate-400 border border-dashed border-slate-300 dark:border-zinc-700">
                                <i data-lucide="image" class="w-5 h-5"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex flex-col space-y-1">
                        <div class="flex items-center space-x-2">
                            <button type="button"
                                wire:click="$dispatch('open-media-modal', { targetEvent: 'payment-media-selected', folder: 'payments', selectedId: {{ $media_id ?: 'null' }} })"
                                class="px-2.5 py-1 bg-brand-600 hover:bg-brand-700 text-white rounded-[3px] text-xs font-semibold shadow-xs transition flex items-center space-x-1 cursor-pointer">
                                <i data-lucide="image-plus" class="w-3.5 h-3.5"></i>
                                <span>Choose Logo</span>
                            </button>
                            @if ($media_id)
                                <button type="button" wire:click="removeMedia" class="px-2 py-1 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-[3px] text-xs font-medium transition cursor-pointer">
                                    Remove
                                </button>
                            @endif
                        </div>
                        <p class="text-[10px] text-slate-500">Pick a payment logo or icon from Media Library.</p>
                    </div>
                </div>
                @error('media_id')
                    <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Dynamic JSON Credentials Builder -->
            <div class="border border-slate-200 dark:border-zinc-700 rounded-[4px] p-3.5 bg-slate-50/60 dark:bg-zinc-900/40">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <h4 class="text-xs font-bold text-slate-800 dark:text-zinc-200">Gateway Credentials (JSON)</h4>
                        <p class="text-[11px] text-slate-500 dark:text-zinc-400">Configure API keys, secret keys, or merchant IDs stored as JSON.</p>
                    </div>
                    <button type="button" wire:click="addCredentialRow"
                        class="inline-flex items-center space-x-1 px-2 py-1 text-xs font-medium rounded-[3px] bg-brand-50 text-brand-700 dark:bg-brand-950/50 dark:text-brand-300 hover:bg-brand-100 transition cursor-pointer">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        <span>Add Key</span>
                    </button>
                </div>

                <div class="space-y-2 mt-2.5">
                    @foreach ($credentialRows as $index => $row)
                        <div wire:key="cred-row-{{ $index }}" class="flex items-center space-x-2">
                            <div class="w-1/3">
                                <input wire:model="credentialRows.{{ $index }}.key" type="text"
                                    placeholder="Key (e.g. client_id, secret)"
                                    class="w-full px-2.5 py-1 text-xs font-mono rounded-[3px] border border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-brand-500">
                            </div>
                            <div class="flex-1">
                                <input wire:model="credentialRows.{{ $index }}.value" type="text"
                                    placeholder="Value (e.g. sk_test_...)"
                                    class="w-full px-2.5 py-1 text-xs font-mono rounded-[3px] border border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-brand-500">
                            </div>
                            <div>
                                <button type="button" wire:click="removeCredentialRow({{ $index }})"
                                    class="p-1 text-slate-400 hover:text-rose-600 rounded-[3px] transition cursor-pointer" title="Remove">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Instructions -->
            <div>
                <x-admin.textarea wire:model="instruction" label="Payment Instructions" rows="3"
                    placeholder="Instructions shown to customers during checkout (e.g., Send money to 017xxxxxxxx and enter transaction ID)..." />
            </div>

            <!-- Toggles: Active & Sandbox Mode -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100 dark:border-zinc-800">
                <div class="flex items-center">
                    <x-admin.switch wire:model="sandbox_mode" label="Sandbox / Test Mode" description="Enable test environment without charging real accounts" />
                </div>

                <div class="flex items-center">
                    <x-admin.switch wire:model="is_active" label="Active Gateway" description="Enable or disable for buyers at checkout" />
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-200 dark:border-zinc-800">
                <x-admin.button type="button" variant="ghost" @click="$dispatch('modal-close', { name: 'payment-method-modal' })">
                    Cancel
                </x-admin.button>
                <x-admin.button type="submit" variant="primary" icon="check">
                    {{ $paymentMethodId ? 'Update Gateway' : 'Create Gateway' }}
                </x-admin.button>
            </div>
        </form>
    </x-admin.modal>

    <!-- Delete Confirmation Modal -->
    <x-admin.modal name="delete-payment-modal" title="Delete Payment Method" maxWidth="max-w-md">
        <div class="space-y-4 text-center">
            <div class="w-10 h-10 rounded-[4px] bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 mx-auto flex items-center justify-center">
                <i data-lucide="trash-2" class="w-5 h-5"></i>
            </div>
            <div>
                <h4 class="font-bold text-sm text-slate-800 dark:text-zinc-100">Delete Payment Method?</h4>
                <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">
                    Are you sure you want to delete this payment method? This action cannot be undone.
                </p>
            </div>
            <div class="flex justify-center gap-2 pt-2">
                <x-admin.button type="button" variant="ghost" @click="$dispatch('modal-close', { name: 'delete-payment-modal' })">
                    Cancel
                </x-admin.button>
                <x-admin.button wire:click="delete" type="button" variant="danger">
                    Delete Gateway
                </x-admin.button>
            </div>
        </div>
    </x-admin.modal>
</section>
