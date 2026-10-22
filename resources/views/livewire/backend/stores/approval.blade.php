<section class="space-y-5">
    <livewire:utilities.toast-modal />

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <x-admin.breadcrumb :items="[
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Stores Approval']
            ]" />
            <h1 class="mt-2 text-lg font-bold text-slate-900 dark:text-zinc-100">Stores Approval</h1>
            <p class="text-xs text-slate-500 dark:text-zinc-400">Review pending marketplace vendor store registrations and handle approvals.</p>
        </div>
    </div>

    <!-- Search & Table Card -->
    <x-admin.card class="p-0 overflow-hidden">
        <!-- Search bar -->
        <div class="p-3.5 border-b border-slate-200 dark:border-zinc-800 bg-slate-50/60 dark:bg-zinc-900/40">
            <div class="relative max-w-md">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search stores by name..."
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
                        <th class="px-4 py-3">Store Name</th>
                        <th class="px-4 py-3">Owner</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Approved</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-zinc-800 bg-white dark:bg-zinc-900">
                    @forelse($stores as $store)
                        <tr wire:key="store-row-{{ $store->id }}" class="hover:bg-slate-50/80 dark:hover:bg-zinc-800/40 transition">
                            <td class="px-4 py-3 flex justify-center">
                                <img src="{{ $store->logo ? asset('storage/' . $store->logo) : 'https://placehold.co/64x64?text=No+Logo' }}" 
                                    class="h-9 w-9 rounded-[3px] object-cover border border-slate-200 dark:border-zinc-700">
                            </td>
                            <td class="px-4 py-3 font-semibold text-xs text-slate-900 dark:text-zinc-100">
                                {{ $store->name }}
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-600 dark:text-zinc-300">
                                {{ $store->user->name ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if ($store->status)
                                    <x-admin.badge variant="success" dot>Active</x-admin.badge>
                                @else
                                    <x-admin.badge variant="danger" dot>Inactive</x-admin.badge>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if ($store->is_approved)
                                    <x-admin.badge variant="success">Approved</x-admin.badge>
                                @else
                                    <x-admin.badge variant="warning">Pending</x-admin.badge>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    @can ('store.approval')
                                        <x-admin.button wire:click="confirmApprove({{ $store->id }})" 
                                            @click="$dispatch('modal-show', { name: 'approve-modal' })" 
                                            icon="check" 
                                            size="sm" 
                                            variant="primary" 
                                            title="Approve Store" />
                                    @endcan

                                    @can ('store.delete') 
                                        <x-admin.button wire:click="confirmDelete({{ $store->id }})" 
                                            @click="$dispatch('modal-show', { name: 'delete-modal' })" 
                                            icon="trash-2" 
                                            size="sm" 
                                            variant="danger" 
                                            title="Delete Store" />
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500 dark:text-zinc-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <div class="w-10 h-10 rounded-[4px] bg-slate-100 dark:bg-zinc-800 flex items-center justify-center text-slate-400">
                                        <i data-lucide="store" class="w-5 h-5"></i>
                                    </div>
                                    <p class="text-xs font-medium">No stores found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($stores->hasPages())
            <div class="p-3.5 border-t border-slate-200 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-900/30">
                {{ $stores->links() }}
            </div>
        @endif
    </x-admin.card>

    <!-- Approve Modal -->
    <x-admin.modal name="approve-modal" title="Approve Store" maxWidth="max-w-md">
        <div class="space-y-4 text-center">
            <div class="w-10 h-10 rounded-[4px] bg-brand-50 dark:bg-brand-950/50 text-brand-600 mx-auto flex items-center justify-center">
                <i data-lucide="check" class="w-5 h-5"></i>
            </div>
            <div>
                <h4 class="font-bold text-sm text-slate-800 dark:text-zinc-100">Approve this store?</h4>
                <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">You are about to approve this store for marketplace selling. Continue?</p>
            </div>
            <div class="flex justify-center gap-2 pt-2">
                <x-admin.button type="button" variant="ghost" @click="$dispatch('modal-close', { name: 'approve-modal' })">
                    Cancel
                </x-admin.button>
                <x-admin.button wire:click="approve" variant="primary">
                    Approve Store
                </x-admin.button>
            </div>
        </div>
    </x-admin.modal>

    <!-- Delete Modal -->
    <x-admin.modal name="delete-modal" title="Delete Store" maxWidth="max-w-md">
        <div class="space-y-4 text-center">
            <div class="w-10 h-10 rounded-[4px] bg-rose-50 dark:bg-rose-950/50 text-rose-600 mx-auto flex items-center justify-center">
                <i data-lucide="trash-2" class="w-5 h-5"></i>
            </div>
            <div>
                <h4 class="font-bold text-sm text-slate-800 dark:text-zinc-100">Delete this store?</h4>
                <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">This action cannot be undone.</p>
            </div>
            <div class="flex justify-center gap-2 pt-2">
                <x-admin.button type="button" variant="ghost" @click="$dispatch('modal-close', { name: 'delete-modal' })">
                    Cancel
                </x-admin.button>
                <x-admin.button wire:click="delete" variant="danger">
                    Delete
                </x-admin.button>
            </div>
        </div>
    </x-admin.modal>
</section>
