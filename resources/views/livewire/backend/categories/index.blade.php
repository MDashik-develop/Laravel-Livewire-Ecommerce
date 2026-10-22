<section class="space-y-4">
    <livewire:utilities.toast-modal />

    <!-- Page Header (Breadcrumbs + Actions) -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <x-admin.breadcrumb :items="['Categories' => null]" />

        @can('category.create')
            <x-admin.button variant="primary" icon="plus" @click="$wire.resetForm(); $dispatch('modal-show', { name: 'category-modal' })">
                {{ __('Create Category') }}
            </x-admin.button>
        @endcan
    </div>

    <!-- Main Card & Data Table -->
    <x-admin.card title="Categories Directory" description="Manage shop categories and taxonomy.">
        <!-- Search Bar -->
        <div class="p-3 border-b border-slate-100 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-900/40">
            <div class="relative max-w-sm">
                <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search categories by name..."
                    class="w-full bg-white dark:bg-slate-800 text-xs text-slate-800 dark:text-slate-200 pl-8 pr-3 py-1.5 rounded-sm border border-slate-200 dark:border-slate-700 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none transition-colors">
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 text-[10px] uppercase font-bold text-slate-500 dark:text-slate-400">
                        <th class="py-2.5 px-4 text-center w-16">Image</th>
                        <th class="py-2.5 px-4">Name</th>
                        <th class="py-2.5 px-4">Slug</th>
                        <th class="py-2.5 px-4 text-center">Status</th>
                        <th class="py-2.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($categories as $category)
                        <tr wire:key="{{ $category->id }}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-2.5 px-4 text-center">
                                <div class="flex justify-center">
                                    @if ($category->media)
                                        <img src="{{ $category->media->urls['thumb'] ?? $category->media->url }}"
                                            alt="{{ $category->name }}" class="h-8 w-8 rounded-xs object-cover border border-slate-200 dark:border-slate-700">
                                    @else
                                        <div class="w-8 h-8 bg-slate-100 dark:bg-slate-800 rounded-xs flex items-center justify-center text-slate-400 text-[9px] font-semibold border border-slate-200 dark:border-slate-700">
                                            N/A
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="py-2.5 px-4 font-semibold text-slate-900 dark:text-slate-100">
                                {{ $category->name }}
                            </td>
                            <td class="py-2.5 px-4 font-mono text-[11px] text-slate-500">
                                {{ $category->slug }}
                            </td>
                            <td class="py-2.5 px-4 text-center">
                                @if ($category->status)
                                    <x-admin.badge color="green">Active</x-admin.badge>
                                @else
                                    <x-admin.badge color="red">Inactive</x-admin.badge>
                                @endif
                            </td>
                            <td class="py-2.5 px-4 text-right">
                                <div class="inline-flex items-center gap-1.5 justify-end">
                                    @can('category.edit')
                                        <button type="button" wire:click="edit({{ $category->id }})" title="Edit"
                                                class="p-1.5 rounded-xs border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer">
                                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        </button>
                                    @endcan
                                    @can('category.delete')
                                        <button type="button" wire:click="confirmDelete({{ $category->id }})" title="Delete"
                                                class="p-1.5 rounded-xs border border-rose-200 dark:border-rose-900/60 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition cursor-pointer">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 text-xs">
                                No categories found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($categories->hasPages())
            <div class="p-3 border-t border-slate-100 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-900/40">
                {{ $categories->links() }}
            </div>
        @endif
    </x-admin.card>

    <!-- Create/Edit Modal -->
    <x-admin.modal name="category-modal" :title="$categoryId ? 'Edit Category' : 'Create Category'" description="Fill in the details for the category." maxWidth="xl">
        <form wire:submit.prevent="save" class="space-y-4">
            <x-admin.input wire:model.live="name" label="Name" name="name" placeholder="e.g. Electronics" />
            <x-admin.input wire:model="slug" label="Slug" name="slug" placeholder="e.g. electronics" />

            <!-- Category Thumbnail / Media -->
            <div class="space-y-1">
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Category Image / Icon</label>
                <div class="flex items-center gap-3 p-3 rounded-sm border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                    <div class="shrink-0">
                        @if ($mediaUrl)
                            <img class="h-14 w-14 object-cover rounded-xs border border-slate-200 dark:border-slate-700" src="{{ $mediaUrl }}" alt="Category Image">
                        @else
                            <div class="h-14 w-14 bg-white dark:bg-slate-800 rounded-xs flex items-center justify-center text-slate-400 border border-dashed border-slate-300 dark:border-slate-700">
                                <i data-lucide="image" class="w-6 h-6"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0 space-y-1.5">
                        <div class="flex items-center gap-2">
                            <x-admin.button type="button" variant="primary" size="sm" icon="images"
                                wire:click="$dispatch('open-media-modal', { targetEvent: 'category-media-selected', folder: 'categories', selectedId: {{ $media_id ?: 'null' }} })">
                                Choose Media
                            </x-admin.button>
                            @if ($media_id)
                                <x-admin.button type="button" variant="ghost" size="sm" wire:click="removeMedia" class="text-rose-600 hover:text-rose-700">
                                    Remove
                                </x-admin.button>
                            @endif
                        </div>
                        <p class="text-[10px] text-slate-400">Pick from Media Manager or upload new image.</p>
                    </div>
                </div>
                @error('media_id')
                    <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Status Checkbox -->
            <div class="pt-1">
                <label class="inline-flex items-center gap-2 cursor-pointer text-xs text-slate-700 dark:text-slate-300 font-medium">
                    <input type="checkbox" wire:model="status" class="rounded-xs text-brand-600 focus:ring-brand-500 border-slate-300 dark:border-slate-600 h-4 w-4">
                    <span>Active Status</span>
                </label>
                @error('status')
                    <span class="text-rose-500 text-[11px] block mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2">
                <x-admin.button type="button" variant="outline" @click="$dispatch('modal-close', { name: 'category-modal' })">
                    Cancel
                </x-admin.button>
                <x-admin.button type="submit" variant="primary">
                    Save Changes
                </x-admin.button>
            </div>
        </form>
    </x-admin.modal>

    <!-- Delete Confirmation Modal -->
    <x-admin.modal name="delete-modal" title="Delete Category?" description="This action cannot be reversed." maxWidth="sm">
        <div class="space-y-4">
            <p class="text-xs text-slate-600 dark:text-slate-400">
                Are you sure you want to delete this category? All associated data might be affected.
            </p>
            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                <x-admin.button type="button" variant="outline" @click="$dispatch('modal-close', { name: 'delete-modal' })">
                    Cancel
                </x-admin.button>
                <x-admin.button type="button" variant="danger" wire:click="delete">
                    Delete Category
                </x-admin.button>
            </div>
        </div>
    </x-admin.modal>
</section>
