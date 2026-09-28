<section>
    <livewire:utilities.toast-modal />

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Top Bar -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <flux:breadcrumbs>
                    <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
                    <flux:breadcrumbs.item>Payment Methods</flux:breadcrumbs.item>
                </flux:breadcrumbs>
                <flux:heading size="xl" class="mt-2 font-bold text-gray-900 dark:text-white">Payment Methods</flux:heading>
                <flux:subheading class="text-sm text-gray-500 dark:text-gray-400">Manage payment gateways, sandbox modes, API credentials, and checkout instructions.</flux:subheading>
            </div>

            <flux:button wire:click="create" variant="primary" icon="plus" class="shadow-sm cursor-pointer">
                Add Payment Method
            </flux:button>
        </div>

        <!-- Search & Table Card -->
        <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 shadow-sm rounded-xl overflow-hidden">
            <!-- Search bar -->
            <div class="p-4 border-b border-gray-100 dark:border-zinc-700/60 bg-gray-50/50 dark:bg-zinc-800/50">
                <div class="relative max-w-md">
                    <input wire:model.live.debounce.300ms="search" type="text"
                        placeholder="Search payment gateways by name, slug, or instruction..."
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
                            <th class="px-6 py-3.5 text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-center">
                                Logo
                            </th>
                            <th wire:click="sortBy('name')" class="px-6 py-3.5 text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:text-indigo-600">
                                <div class="flex items-center space-x-1">
                                    <span>Gateway Name</span>
                                    @if ($sortField === 'name')
                                        <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3.5 text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Slug
                            </th>
                            <th class="px-6 py-3.5 text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Credentials
                            </th>
                            <th class="px-6 py-3.5 text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-center">
                                Mode
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
                            <tr wire:key="payment-method-{{ $method->id }}" class="hover:bg-gray-50/75 dark:hover:bg-zinc-700/40 transition">
                                <td class="px-6 py-4 flex justify-center">
                                    @if ($method->media)
                                        <img src="{{ $method->media->urls['thumb'] ?? $method->media->urls['small'] ?? $method->media->url }}"
                                            alt="{{ $method->name }}"
                                            class="w-12 h-12 object-contain p-1 rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-2xs">
                                    @else
                                        <div class="w-12 h-12 bg-gray-100 dark:bg-zinc-700/80 rounded-lg flex items-center justify-center text-gray-400">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-sm text-gray-900 dark:text-gray-100">
                                        {{ $method->name }}
                                    </div>
                                    @if ($method->instruction)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-1 max-w-xs">
                                            {{ $method->instruction }}
                                        </p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-mono font-medium bg-gray-100 text-gray-800 dark:bg-zinc-700 dark:text-zinc-200">
                                        {{ $method->slug }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $credCount = is_array($method->credentials) ? count($method->credentials) : 0;
                                    @endphp
                                    @if ($credCount > 0)
                                        <div class="flex items-center space-x-1.5 text-xs text-emerald-600 dark:text-emerald-400 font-medium">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                            <span>{{ $credCount }} {{ Str::plural('key', $credCount) }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-500 italic">None</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button type="button" wire:click="toggleSandbox({{ $method->id }})" class="cursor-pointer focus:outline-none">
                                        @if ($method->sandbox_mode)
                                            <flux:badge color="amber" class="cursor-pointer hover:opacity-80">Sandbox</flux:badge>
                                        @else
                                            <flux:badge color="blue" class="cursor-pointer hover:opacity-80">Live</flux:badge>
                                        @endif
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button type="button" wire:click="toggleActive({{ $method->id }})" class="cursor-pointer focus:outline-none">
                                        @if ($method->is_active)
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
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                        <p class="text-sm font-medium">No payment methods found.</p>
                                        <p class="text-xs text-gray-400">Click "Add Payment Method" to configure gateways like Stripe, PayPal, bKash, or Cash on Delivery.</p>
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

        <!-- Create / Edit Payment Method Modal -->
        <flux:modal name="payment-method-modal" class="md:w-[38rem]">
            <form wire:submit.prevent="save" class="space-y-6">
                <flux:heading size="lg">
                    {{ $paymentMethodId ? 'Edit Payment Method' : 'Create Payment Method' }}
                </flux:heading>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <flux:input wire:model.live="name" label="Gateway Name *" placeholder="e.g. Stripe, PayPal, bKash" />
                        </div>
                        <div>
                            <flux:input wire:model="slug" label="Slug *" placeholder="e.g. stripe, paypal, bkash" />
                        </div>
                    </div>

                    <!-- Media / Gateway Logo Selector -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Gateway Logo / Icon</label>
                        <div class="flex items-center space-x-4 p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800/50">
                            <div class="shrink-0">
                                @if ($mediaUrl)
                                    <img class="h-14 w-14 object-contain rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-1 shadow-2xs"
                                        src="{{ $mediaUrl }}" alt="Payment Logo">
                                @else
                                    <div class="h-14 w-14 bg-gray-100 dark:bg-zinc-800 rounded-xl flex items-center justify-center text-gray-400 border border-dashed border-gray-300 dark:border-zinc-700">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex flex-col space-y-1.5">
                                <div class="flex items-center space-x-2">
                                    <button type="button"
                                        wire:click="$dispatch('open-media-modal', { targetEvent: 'payment-media-selected', folder: 'payments', selectedId: {{ $media_id ?: 'null' }} })"
                                        class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold shadow-xs transition flex items-center space-x-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span>Choose Logo</span>
                                    </button>
                                    @if ($media_id)
                                        <button type="button" wire:click="removeMedia" class="px-2.5 py-1.5 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-lg text-xs font-medium transition">
                                            Remove
                                        </button>
                                    @endif
                                </div>
                                <p class="text-[11px] text-gray-500">Pick a payment logo or banner from Media Library.</p>
                            </div>
                        </div>
                        @error('media_id')
                            <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Dynamic JSON Credentials Builder -->
                    <div class="border border-gray-200 dark:border-zinc-700 rounded-xl p-4 bg-gray-50/50 dark:bg-zinc-900/30">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Gateway Credentials (JSON)</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Configure API keys, secret keys, or merchant IDs stored as JSON.</p>
                            </div>
                            <button type="button" wire:click="addCredentialRow"
                                class="inline-flex items-center space-x-1 px-2.5 py-1 text-xs font-medium rounded-lg bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400 hover:bg-indigo-100 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>Add Credential Key</span>
                            </button>
                        </div>

                        <div class="space-y-2 mt-3">
                            @foreach ($credentialRows as $index => $row)
                                <div wire:key="cred-row-{{ $index }}" class="flex items-center space-x-2">
                                    <div class="w-1/3">
                                        <input wire:model="credentialRows.{{ $index }}.key" type="text"
                                            placeholder="Key (e.g. client_id, secret)"
                                            class="w-full px-3 py-1.5 text-xs font-mono rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                    </div>
                                    <div class="flex-1">
                                        <input wire:model="credentialRows.{{ $index }}.value" type="text"
                                            placeholder="Value (e.g. sk_test_...)"
                                            class="w-full px-3 py-1.5 text-xs font-mono rounded-lg border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <button type="button" wire:click="removeCredentialRow({{ $index }})"
                                            class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-lg transition" title="Remove">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div>
                        <flux:textarea wire:model="instruction" label="Payment Instructions" rows="3"
                            placeholder="Instructions shown to customers during checkout (e.g., Send money to 017xxxxxxxx and enter transaction ID)..." />
                    </div>

                    <!-- Toggles: Active & Sandbox Mode -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1 border-t border-gray-100 dark:border-zinc-700">
                        <div class="flex items-center">
                            <flux:field variant="inline">
                                <flux:checkbox wire:model="sandbox_mode" />
                                <flux:label>Sandbox / Test Mode</flux:label>
                                <flux:error name="sandbox_mode" />
                            </flux:field>
                        </div>

                        <div class="flex items-center">
                            <flux:field variant="inline">
                                <flux:checkbox wire:model="is_active" />
                                <flux:label>Active Gateway</flux:label>
                                <flux:error name="is_active" />
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
                        {{ $paymentMethodId ? 'Update Gateway' : 'Create Gateway' }}
                    </flux:button>
                </div>
            </form>
        </flux:modal>

        <!-- Delete Confirmation Modal -->
        <flux:modal name="delete-payment-modal" class="md:w-96">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Delete Payment Method?</flux:heading>
                    <flux:text class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Are you sure you want to delete this payment method? This action cannot be undone.
                    </flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button wire:click="delete" type="button" variant="danger">
                        Delete Gateway
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    </div>
</section>
