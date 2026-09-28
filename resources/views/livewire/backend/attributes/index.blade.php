<div>
    <div class="space-y-6">
        <!-- Breadcrumbs & Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-2 border-b border-gray-200 dark:border-zinc-700">
            <div>
                <flux:breadcrumbs class="mb-2">
                    <flux:breadcrumbs.item :href="route('dashboard')" wire:navigate>Dashboard</flux:breadcrumbs.item>
                    <flux:breadcrumbs.item>Catalog</flux:breadcrumbs.item>
                    <flux:breadcrumbs.item>Attributes</flux:breadcrumbs.item>
                </flux:breadcrumbs>
                <flux:heading size="xl">Product Attributes</flux:heading>
                <flux:subheading>Manage reusable attributes (e.g. Color, Size, Material) and their option values.</flux:subheading>
            </div>

            @can('attribute.create')
                <flux:modal.trigger name="attribute-modal" @click="$wire.resetAttributeForm()">
                    <flux:button variant="primary" icon="plus" class="shadow-sm">
                        Create Attribute
                    </flux:button>
                </flux:modal.trigger>
            @endcan
        </div>

        <!-- Search Bar -->
        <div class="flex items-center justify-between gap-4">
            <div class="relative w-full max-w-sm">
                <flux:input wire:model.live.debounce.300ms="search" placeholder="Search attributes..." icon="magnifying-glass" clearable />
            </div>
        </div>

        <!-- Attributes Table -->
        <div class="bg-white dark:bg-zinc-800 rounded-2xl border border-gray-200 dark:border-zinc-700 overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50/80 dark:bg-zinc-800/80 border-b border-gray-200 dark:border-zinc-700 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3.5">Name</th>
                            <th class="px-6 py-3.5">Slug</th>
                            <th class="px-6 py-3.5">Type</th>
                            <th class="px-6 py-3.5">Values</th>
                            <th class="px-6 py-3.5">Filterable</th>
                            <th class="px-6 py-3.5">Order</th>
                            <th class="px-6 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                        @forelse ($attributes as $attr)
                            <tr wire:key="attr-row-{{ $attr->id }}" class="hover:bg-gray-50/60 dark:hover:bg-zinc-700/40 transition">
                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                    {{ $attr->name }}
                                </td>
                                <td class="px-6 py-4 font-mono text-xs text-gray-500">
                                    {{ $attr->slug }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold
                                        {{ $attr->type === 'color' ? 'bg-purple-100 text-purple-700 dark:bg-purple-950 dark:text-purple-300' : '' }}
                                        {{ $attr->type === 'image' ? 'bg-sky-100 text-sky-700 dark:bg-sky-950 dark:text-sky-300' : '' }}
                                        {{ $attr->type === 'text' ? 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300' : '' }}">
                                        @if ($attr->type === 'color')
                                            <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                                        @elseif ($attr->type === 'image')
                                            <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                                        @else
                                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                        @endif
                                        {{ ucfirst($attr->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap items-center gap-1.5 max-w-md">
                                        @forelse ($attr->values->take(6) as $val)
                                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-lg text-xs font-medium bg-gray-100 dark:bg-zinc-700 text-gray-800 dark:text-gray-200 border border-gray-200 dark:border-zinc-600">
                                                @if ($attr->type === 'color' && $val->color_code)
                                                    <span class="w-2.5 h-2.5 rounded-full border border-black/10 shrink-0" style="background-color: {{ $val->color_code }};"></span>
                                                @elseif ($attr->type === 'image' && $val->media)
                                                    <img src="{{ $val->media->url }}" class="w-3.5 h-3.5 rounded object-cover">
                                                @endif
                                                {{ $val->value }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-gray-400 italic">No values yet</span>
                                        @endforelse

                                        @if ($attr->values->count() > 6)
                                            <span class="text-[11px] text-gray-400 font-semibold">+{{ $attr->values->count() - 6 }} more</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($attr->is_filterable)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300">
                                            Yes
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">No</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-mono text-xs text-gray-600 dark:text-gray-400">
                                    {{ $attr->sort_order }}
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <button type="button"
                                        wire:click="openValuesModal({{ $attr->id }})"
                                        class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 transition cursor-pointer">
                                        Values ({{ $attr->values->count() }})
                                    </button>

                                    @can('attribute.edit')
                                        <button type="button"
                                            wire:click="editAttribute({{ $attr->id }})"
                                            class="p-1.5 text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-700 transition cursor-pointer inline-flex items-center">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                    @endcan

                                    @can('attribute.delete')
                                        <button type="button"
                                            wire:click="deleteAttribute({{ $attr->id }})"
                                            wire:confirm="Are you sure you want to delete this attribute and all its values?"
                                            class="p-1.5 text-gray-500 hover:text-rose-600 dark:hover:text-rose-400 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/40 transition cursor-pointer inline-flex items-center">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-400">
                                    No attributes found. Click "Create Attribute" to add your first attribute.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($attributes->hasPages())
                <div class="p-4 border-t border-gray-200 dark:border-zinc-700">
                    {{ $attributes->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Attribute Create/Edit Modal -->
    <flux:modal name="attribute-modal" class="md:w-[500px]">
        <form wire:submit="saveAttribute" class="space-y-5">
            <div>
                <flux:heading size="lg">{{ $attributeId ? 'Edit Attribute' : 'Create New Attribute' }}</flux:heading>
                <flux:subheading>Configure attribute name, type, and visibility.</flux:subheading>
            </div>

            <flux:input wire:model.live="name" label="Attribute Name" placeholder="e.g. Color, Size, Storage" required />
            <flux:input wire:model="slug" label="Slug / Identifier" placeholder="e.g. color, size" required />

            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">Attribute Type</label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition {{ $type === 'text' ? 'border-indigo-600 bg-indigo-50/50 dark:bg-indigo-950/30 ring-1 ring-indigo-500' : 'border-gray-200 dark:border-zinc-700' }}">
                        <input type="radio" wire:model.live="type" value="text" class="text-indigo-600">
                        <div>
                            <span class="text-xs font-bold block">Text / Button</span>
                            <span class="text-[10px] text-gray-400">e.g. S, M, XL</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition {{ $type === 'color' ? 'border-indigo-600 bg-indigo-50/50 dark:bg-indigo-950/30 ring-1 ring-indigo-500' : 'border-gray-200 dark:border-zinc-700' }}">
                        <input type="radio" wire:model.live="type" value="color" class="text-indigo-600">
                        <div>
                            <span class="text-xs font-bold block">Color Swatch</span>
                            <span class="text-[10px] text-gray-400">Hex code</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition {{ $type === 'image' ? 'border-indigo-600 bg-indigo-50/50 dark:bg-indigo-950/30 ring-1 ring-indigo-500' : 'border-gray-200 dark:border-zinc-700' }}">
                        <input type="radio" wire:model.live="type" value="image" class="text-indigo-600">
                        <div>
                            <span class="text-xs font-bold block">Image Swatch</span>
                            <span class="text-[10px] text-gray-400">Thumbnail</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex-1">
                    <flux:input wire:model="sort_order" type="number" label="Sort Order" min="0" />
                </div>
                <div class="pt-5 flex items-center gap-2">
                    <flux:checkbox wire:model="is_filterable" label="Use in Filters" description="Show in catalog sidebar filter" />
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-3 border-t border-gray-100 dark:border-zinc-700">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button variant="primary" type="submit">
                    Save Attribute
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <!-- Attribute Values Management Modal -->
    <flux:modal name="attribute-values-modal" class="md:w-[720px]">
        @if ($managingAttribute)
            <div class="space-y-6">
                <div class="pb-3 border-b border-gray-200 dark:border-zinc-700 flex justify-between items-center">
                    <div>
                        <flux:heading size="lg">Manage Values: {{ $managingAttribute->name }}</flux:heading>
                        <flux:subheading>Type: <strong class="text-indigo-600">{{ ucfirst($managingAttribute->type) }}</strong>. Add, reorder or delete option values.</flux:subheading>
                    </div>
                </div>

                <!-- Existing Values List -->
                <div class="space-y-2">
                    <h4 class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Current Values</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 max-h-56 overflow-y-auto p-1">
                        @forelse ($managingAttribute->values as $v)
                            <div class="flex items-center justify-between p-2.5 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/60 shadow-2xs">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    @if ($managingAttribute->type === 'color')
                                        <span class="w-5 h-5 rounded-full border border-gray-300 dark:border-zinc-600 shrink-0 shadow-xs" style="background-color: {{ $v->color_code ?: '#fff' }};"></span>
                                    @elseif ($managingAttribute->type === 'image' && $v->media)
                                        <img src="{{ $v->media->url }}" class="w-6 h-6 rounded object-cover shrink-0 border">
                                    @endif

                                    <div class="truncate text-xs font-bold text-gray-900 dark:text-white">
                                        {{ $v->value }}
                                        @if ($v->color_code)
                                            <span class="text-[10px] text-gray-400 font-mono">({{ $v->color_code }})</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center gap-1 shrink-0">
                                    <button type="button"
                                        wire:click="editValue({{ $v->id }})"
                                        class="p-1 text-gray-400 hover:text-indigo-600 rounded transition cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button type="button"
                                        wire:click="deleteValue({{ $v->id }})"
                                        wire:confirm="Delete this value?"
                                        class="p-1 text-gray-400 hover:text-rose-600 rounded transition cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 text-center p-4 text-xs text-gray-400">
                                No values configured yet. Use the form below to add.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Add/Edit Value Form -->
                <form wire:submit="saveValue" class="p-4 rounded-2xl bg-indigo-50/60 dark:bg-zinc-800 border border-indigo-100 dark:border-zinc-700 space-y-4">
                    <div class="flex justify-between items-center">
                        <h4 class="text-xs font-bold text-gray-800 dark:text-gray-200">
                            {{ $valueId ? 'Edit Value' : 'Add New Value' }}
                        </h4>
                        @if ($valueId)
                            <button type="button" wire:click="resetValueForm" class="text-[11px] text-indigo-600 dark:text-indigo-400 hover:underline cursor-pointer">
                                + Add New Instead
                            </button>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <flux:input wire:model="valueText" label="Value Label" placeholder="e.g. Red, XL, 256GB" required />

                        @if ($managingAttribute->type === 'color')
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Color Code</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" wire:model.live="colorCode" class="w-9 h-9 rounded-lg border border-gray-200 cursor-pointer p-0.5">
                                    <flux:input wire:model="colorCode" placeholder="#EF4444" class="font-mono" />
                                </div>
                            </div>
                        @elseif ($managingAttribute->type === 'image')
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Swatch Image</label>
                                <div class="flex items-center gap-2">
                                    <div class="w-9 h-9 rounded-lg border border-gray-200 overflow-hidden bg-white shrink-0 flex items-center justify-center">
                                        @if ($valueMediaUrl)
                                            <img src="{{ $valueMediaUrl }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-[10px] text-gray-300">None</span>
                                        @endif
                                    </div>
                                    <button type="button"
                                        @click="$dispatch('open-media-modal', { targetEvent: 'attribute-value-media-selected', type: 'image', selectedId: {{ $valueMediaId ?: 'null' }} })"
                                        class="px-2.5 py-1.5 bg-white dark:bg-zinc-700 border border-gray-200 dark:border-zinc-600 text-xs font-medium rounded-lg hover:bg-gray-50 transition cursor-pointer">
                                        Choose Media
                                    </button>
                                    @if ($valueMediaId)
                                        <button type="button" wire:click="removeValueMedia" class="text-rose-500 text-xs hover:underline cursor-pointer">
                                            Remove
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @else
                            <flux:input wire:model="valueSortOrder" type="number" label="Sort Order" min="0" />
                        @endif
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <flux:button variant="primary" type="submit" size="sm">
                            {{ $valueId ? 'Update Value' : 'Add Value' }}
                        </flux:button>
                    </div>
                </form>
            </div>
        @endif
    </flux:modal>
</div>
