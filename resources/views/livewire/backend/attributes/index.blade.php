<div class="space-y-4">
    <!-- Breadcrumbs & Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <x-admin.breadcrumb :items="['Catalog' => null, 'Attributes' => null]" />

        @can('attribute.create')
            <x-admin.button variant="primary" icon="plus" @click="$wire.resetAttributeForm(); $dispatch('modal-show', { name: 'attribute-modal' })">
                {{ __('Create Attribute') }}
            </x-admin.button>
        @endcan
    </div>

    <!-- Main Card & Data Table -->
    <x-admin.card title="Product Attributes" description="Manage reusable attributes (e.g. Color, Size, Storage) and option values.">
        <!-- Search Bar -->
        <div class="p-3 border-b border-slate-100 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-900/40">
            <div class="relative max-w-sm">
                <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search attributes..."
                    class="w-full bg-white dark:bg-slate-800 text-xs text-slate-800 dark:text-slate-200 pl-8 pr-3 py-1.5 rounded-sm border border-slate-200 dark:border-slate-700 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none transition-colors">
            </div>
        </div>

        <!-- Attributes Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 text-[10px] uppercase font-bold text-slate-500 dark:text-slate-400">
                        <th class="py-2.5 px-4">Name</th>
                        <th class="py-2.5 px-4">Slug</th>
                        <th class="py-2.5 px-4">Type</th>
                        <th class="py-2.5 px-4">Option Values</th>
                        <th class="py-2.5 px-4 text-center">Filterable</th>
                        <th class="py-2.5 px-4 text-center">Order</th>
                        <th class="py-2.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($attributes as $attr)
                        <tr wire:key="attr-row-{{ $attr->id }}" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-2.5 px-4 font-semibold text-slate-900 dark:text-slate-100">
                                {{ $attr->name }}
                            </td>
                            <td class="py-2.5 px-4 font-mono text-[11px] text-slate-500">
                                {{ $attr->slug }}
                            </td>
                            <td class="py-2.5 px-4">
                                <x-admin.badge :color="$attr->type === 'color' ? 'purple' : ($attr->type === 'image' ? 'blue' : 'amber')">
                                    {{ ucfirst($attr->type) }}
                                </x-admin.badge>
                            </td>
                            <td class="py-2.5 px-4">
                                <div class="flex flex-wrap items-center gap-1.5 max-w-xs sm:max-w-md">
                                    @forelse ($attr->values->take(6) as $val)
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-xs text-[11px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                            @if ($attr->type === 'color' && $val->color_code)
                                                <span class="w-2.5 h-2.5 rounded-full border border-black/10 shrink-0" style="background-color: {{ $val->color_code }};"></span>
                                            @elseif ($attr->type === 'image' && $val->media)
                                                <img src="{{ $val->media->url }}" class="w-3.5 h-3.5 rounded-xs object-cover">
                                            @endif
                                            {{ $val->value }}
                                        </span>
                                    @empty
                                        <span class="text-[11px] text-slate-400 italic">No values yet</span>
                                    @endforelse

                                    @if ($attr->values->count() > 6)
                                        <span class="text-[10px] text-slate-400 font-semibold">+{{ $attr->values->count() - 6 }} more</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-2.5 px-4 text-center">
                                @if ($attr->is_filterable)
                                    <x-admin.badge color="green">Yes</x-admin.badge>
                                @else
                                    <span class="text-[11px] text-slate-400">No</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-4 text-center font-mono text-[11px] text-slate-500">
                                {{ $attr->sort_order }}
                            </td>
                            <td class="py-2.5 px-4 text-right">
                                <div class="inline-flex items-center gap-1.5 justify-end">
                                    <x-admin.button type="button" variant="outline" size="xs" wire:click="openValuesModal({{ $attr->id }})" class="text-brand-600">
                                        Values ({{ $attr->values->count() }})
                                    </x-admin.button>

                                    @can('attribute.edit')
                                        <button type="button" wire:click="editAttribute({{ $attr->id }})" title="Edit"
                                                class="p-1.5 rounded-xs border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer">
                                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        </button>
                                    @endcan

                                    @can('attribute.delete')
                                        <button type="button" wire:click="deleteAttribute({{ $attr->id }})" wire:confirm="Are you sure you want to delete this attribute and all its values?" title="Delete"
                                                class="p-1.5 rounded-xs border border-rose-200 dark:border-rose-900/60 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition cursor-pointer">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 text-xs">
                                No attributes found. Click "Create Attribute" to add your first attribute.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($attributes->hasPages())
            <div class="p-3 border-t border-slate-100 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-900/40">
                {{ $attributes->links() }}
            </div>
        @endif
    </x-admin.card>

    <!-- Attribute Create/Edit Modal -->
    <x-admin.modal name="attribute-modal" :title="$attributeId ? 'Edit Attribute' : 'Create New Attribute'" description="Configure attribute name, type, and visibility." maxWidth="lg">
        <form wire:submit="saveAttribute" class="space-y-4">
            <x-admin.input wire:model.live="name" label="Attribute Name" name="name" placeholder="e.g. Color, Size, Storage" required />
            <x-admin.input wire:model="slug" label="Slug / Identifier" name="slug" placeholder="e.g. color, size" required />

            <div class="space-y-1">
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Attribute Type</label>
                <div class="grid grid-cols-3 gap-2.5">
                    <label class="flex items-center gap-2 p-2.5 rounded-sm border cursor-pointer transition {{ $type === 'text' ? 'border-brand-600 bg-brand-50/50 dark:bg-brand-950/30 ring-1 ring-brand-500' : 'border-slate-200 dark:border-slate-700' }}">
                        <input type="radio" wire:model.live="type" value="text" class="text-brand-600">
                        <div>
                            <span class="text-xs font-bold block">Text / Button</span>
                            <span class="text-[10px] text-slate-400">e.g. S, M, XL</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-2 p-2.5 rounded-sm border cursor-pointer transition {{ $type === 'color' ? 'border-brand-600 bg-brand-50/50 dark:bg-brand-950/30 ring-1 ring-brand-500' : 'border-slate-200 dark:border-slate-700' }}">
                        <input type="radio" wire:model.live="type" value="color" class="text-brand-600">
                        <div>
                            <span class="text-xs font-bold block">Color Swatch</span>
                            <span class="text-[10px] text-slate-400">Hex code</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-2 p-2.5 rounded-sm border cursor-pointer transition {{ $type === 'image' ? 'border-brand-600 bg-brand-50/50 dark:bg-brand-950/30 ring-1 ring-brand-500' : 'border-slate-200 dark:border-slate-700' }}">
                        <input type="radio" wire:model.live="type" value="image" class="text-brand-600">
                        <div>
                            <span class="text-xs font-bold block">Image Swatch</span>
                            <span class="text-[10px] text-slate-400">Thumbnail</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex-1">
                    <x-admin.input wire:model="sort_order" type="number" label="Sort Order" name="sort_order" min="0" />
                </div>
                <div class="pt-5">
                    <label class="inline-flex items-center gap-2 cursor-pointer text-xs text-slate-700 dark:text-slate-300 font-medium">
                        <input type="checkbox" wire:model="is_filterable" class="rounded-xs text-brand-600 focus:ring-brand-500 border-slate-300 dark:border-slate-600 h-4 w-4">
                        <span>Use in Catalog Filters</span>
                    </label>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2">
                <x-admin.button type="button" variant="outline" @click="$dispatch('modal-close', { name: 'attribute-modal' })">
                    Cancel
                </x-admin.button>
                <x-admin.button type="submit" variant="primary">
                    Save Attribute
                </x-admin.button>
            </div>
        </form>
    </x-admin.modal>

    <!-- Attribute Values Management Modal -->
    <x-admin.modal name="attribute-values-modal" :title="$managingAttribute ? 'Manage Values: ' . $managingAttribute->name : 'Manage Values'" description="Add, reorder or delete option values." maxWidth="2xl">
        @if ($managingAttribute)
            <div class="space-y-4">
                <!-- Current Values List -->
                <div class="space-y-2">
                    <h4 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Current Values</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-52 overflow-y-auto p-1">
                        @forelse ($managingAttribute->values as $v)
                            <div class="flex items-center justify-between p-2 rounded-sm border border-slate-200 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-800/60 shadow-2xs">
                                <div class="flex items-center gap-2 min-w-0">
                                    @if ($managingAttribute->type === 'color')
                                        <span class="w-4 h-4 rounded-full border border-slate-300 dark:border-slate-600 shrink-0 shadow-xs" style="background-color: {{ $v->color_code ?: '#fff' }};"></span>
                                    @elseif ($managingAttribute->type === 'image' && $v->media)
                                        <img src="{{ $v->media->url }}" class="w-5 h-5 rounded-xs object-cover shrink-0 border">
                                    @endif

                                    <div class="truncate text-xs font-semibold text-slate-900 dark:text-white">
                                        {{ $v->value }}
                                        @if ($v->color_code)
                                            <span class="text-[10px] text-slate-400 font-mono">({{ $v->color_code }})</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center gap-1 shrink-0">
                                    <button type="button" wire:click="editValue({{ $v->id }})" title="Edit"
                                        class="p-1 text-slate-400 hover:text-brand-600 rounded-xs transition cursor-pointer">
                                        <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                    </button>
                                    <button type="button" wire:click="deleteValue({{ $v->id }})" wire:confirm="Delete this value?" title="Delete"
                                        class="p-1 text-slate-400 hover:text-rose-600 rounded-xs transition cursor-pointer">
                                        <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 text-center p-4 text-xs text-slate-400">
                                No values configured yet. Use the form below to add.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Add/Edit Value Form -->
                <form wire:submit="saveValue" class="p-3.5 rounded-sm bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 space-y-3">
                    <div class="flex justify-between items-center">
                        <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200">
                            {{ $valueId ? 'Edit Value' : 'Add New Value' }}
                        </h4>
                        @if ($valueId)
                            <button type="button" wire:click="resetValueForm" class="text-[11px] text-brand-600 dark:text-brand-400 hover:underline cursor-pointer">
                                + Add New Instead
                            </button>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <x-admin.input wire:model="valueText" label="Value Label" name="valueText" placeholder="e.g. Red, XL, 256GB" required />

                        @if ($managingAttribute->type === 'color')
                            <div class="space-y-1">
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Color Code</label>
                                <div class="flex items-center gap-2">
                                    <input type="color" wire:model.live="colorCode" class="w-8 h-8 rounded-xs border border-slate-200 cursor-pointer p-0.5">
                                    <input type="text" wire:model="colorCode" placeholder="#EF4444" class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs px-2.5 py-1.5 rounded-sm font-mono">
                                </div>
                            </div>
                        @elseif ($managingAttribute->type === 'image')
                            <div class="space-y-1">
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Swatch Image</label>
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-xs border border-slate-200 overflow-hidden bg-white shrink-0 flex items-center justify-center">
                                        @if ($valueMediaUrl)
                                            <img src="{{ $valueMediaUrl }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-[9px] text-slate-300">None</span>
                                        @endif
                                    </div>
                                    <x-admin.button type="button" variant="outline" size="xs"
                                        @click="$dispatch('open-media-modal', { targetEvent: 'attribute-value-media-selected', type: 'image', selectedId: {{ $valueMediaId ?: 'null' }} })">
                                        Choose Media
                                    </x-admin.button>
                                    @if ($valueMediaId)
                                        <button type="button" wire:click="removeValueMedia" class="text-rose-500 text-xs hover:underline cursor-pointer">
                                            Remove
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @else
                            <x-admin.input wire:model="valueSortOrder" type="number" label="Sort Order" name="valueSortOrder" min="0" />
                        @endif
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t border-slate-200 dark:border-slate-700">
                        <x-admin.button variant="primary" type="submit" size="sm">
                            {{ $valueId ? 'Update Value' : 'Add Value' }}
                        </x-admin.button>
                    </div>
                </form>
            </div>
        @endif
    </x-admin.modal>
</div>
