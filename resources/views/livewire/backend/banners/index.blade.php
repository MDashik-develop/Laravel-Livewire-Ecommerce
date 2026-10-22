<section class="space-y-5">
    <livewire:utilities.toast-modal />

    <!-- Header & Breadcrumbs -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <x-admin.breadcrumb :items="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Banners']
        ]" />

        @can('banner.create')
            <x-admin.button 
                variant="primary" 
                icon="plus" 
                @click="$wire.resetForm(); $dispatch('modal-show', { name: 'banner-modal' })">
                Create Banner
            </x-admin.button>
        @endcan
    </div>

    <!-- Search & Table Card -->
    <x-admin.card class="p-0 overflow-hidden">
        <!-- Search bar -->
        <div class="p-3.5 border-b border-slate-200 dark:border-zinc-800 bg-slate-50/60 dark:bg-zinc-900/40">
            <div class="relative max-w-md">
                <input wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Search banners by link, category, section, product..."
                    class="w-full pl-9 pr-3 py-1.5 text-xs bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-700 rounded-[3px] text-slate-800 dark:text-zinc-100 placeholder-slate-400 focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500/30 transition">
                <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-2.5"></i>
            </div>
        </div>

        <!-- Banners Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-zinc-800 text-left text-xs">
                <thead class="bg-slate-50 dark:bg-zinc-900/60 text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-zinc-400">
                    <tr>
                        <th class="px-4 py-3 text-center w-20">Order</th>
                        <th class="px-4 py-3">Preview</th>
                        <th class="px-4 py-3">Section</th>
                        <th class="px-4 py-3">Target / Link</th>
                        <th class="px-4 py-3 text-center w-28">Status</th>
                        <th class="px-4 py-3 text-center w-28">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-zinc-800 bg-white dark:bg-zinc-900">
                    @forelse ($banners as $banner)
                        <tr wire:key="banner-row-{{ $banner->id }}" class="hover:bg-slate-50/80 dark:hover:bg-zinc-800/40 transition">
                            <!-- Order / Position Controls -->
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <div class="inline-flex items-center space-x-1 bg-slate-100 dark:bg-zinc-800 px-2 py-0.5 rounded-[3px] border border-slate-200 dark:border-zinc-700">
                                    <button type="button"
                                        wire:click="moveUp({{ $banner->id }})"
                                        title="Move Up"
                                        class="text-slate-400 hover:text-brand-600 transition p-0.5 cursor-pointer">
                                        <i data-lucide="chevron-up" class="w-3.5 h-3.5"></i>
                                    </button>
                                    <span class="font-mono font-bold text-xs text-slate-800 dark:text-zinc-200 px-1">{{ $banner->position }}</span>
                                    <button type="button"
                                        wire:click="moveDown({{ $banner->id }})"
                                        title="Move Down"
                                        class="text-slate-400 hover:text-brand-600 transition p-0.5 cursor-pointer">
                                        <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                                    </button>
                                </div>
                            </td>

                            <!-- Preview Thumbnail -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    @if ($banner->media)
                                        <div class="w-20 h-12 rounded-[3px] overflow-hidden bg-slate-100 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 shrink-0">
                                            <img src="{{ $banner->media->urls['small'] ?? $banner->media->url }}"
                                                alt="Banner Image"
                                                class="w-full h-full object-cover">
                                        </div>
                                    @elseif ($banner->videoMedia)
                                        <div class="w-20 h-12 rounded-[3px] overflow-hidden bg-slate-900 border border-slate-200 dark:border-zinc-700 shrink-0 flex items-center justify-center text-white text-[11px]">
                                            <i data-lucide="video" class="w-4 h-4 text-brand-400 mr-1"></i>
                                            <span>Video</span>
                                        </div>
                                    @else
                                        <div class="w-20 h-12 rounded-[3px] bg-slate-100 dark:bg-zinc-800 border border-dashed border-slate-300 dark:border-zinc-700 flex items-center justify-center text-slate-400 text-[10px] font-semibold">
                                            No Media
                                        </div>
                                    @endif

                                    <!-- Media Badges -->
                                    <div class="space-y-1">
                                        @if ($banner->media)
                                            <x-admin.badge variant="primary">Image</x-admin.badge>
                                        @endif
                                        @if ($banner->videoMedia)
                                            <x-admin.badge variant="secondary">Video</x-admin.badge>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Section Placement Badge -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                @php
                                    $sec = $banner->section ?: 'slider';
                                    $variantMap = [
                                        'slider' => 'primary',
                                        'featured' => 'warning',
                                        'sidebar' => 'success',
                                        'footer' => 'info',
                                    ];
                                    $v = $variantMap[$sec] ?? 'primary';
                                    $secName = $availableSections[$sec] ?? ucfirst($sec);
                                @endphp
                                <x-admin.badge :variant="$v">
                                    {{ $secName }}
                                </x-admin.badge>
                            </td>

                            <!-- Target / Link -->
                            <td class="px-4 py-3">
                                <div class="text-xs space-y-1">
                                    @if ($banner->product)
                                        <div class="flex items-center gap-1.5 text-slate-800 dark:text-zinc-200 font-semibold">
                                            <span class="text-slate-400 text-[11px]">Product:</span>
                                            <span class="truncate max-w-xs">{{ $banner->product->name }}</span>
                                        </div>
                                    @elseif ($banner->category)
                                        <div class="flex items-center gap-1.5 text-slate-800 dark:text-zinc-200 font-semibold">
                                            <span class="text-slate-400 text-[11px]">Category:</span>
                                            <span>{{ $banner->category->name }}</span>
                                            @if ($banner->sub_category)
                                                <span class="text-slate-400">&rsaquo;</span>
                                                <span>{{ $banner->sub_category->name }}</span>
                                            @endif
                                        </div>
                                    @endif

                                    @if ($banner->link)
                                        <a href="{{ $banner->link }}" target="_blank" class="inline-flex items-center gap-1 text-brand-600 dark:text-brand-400 hover:underline max-w-sm truncate text-[11px]">
                                            <i data-lucide="external-link" class="w-3 h-3 shrink-0"></i>
                                            <span class="truncate">{{ $banner->link }}</span>
                                        </a>
                                    @elseif (!$banner->product && !$banner->category)
                                        <span class="text-slate-400 italic text-[11px]">No link assigned</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Status Badge -->
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <button type="button"
                                    wire:click="toggleStatus({{ $banner->id }})"
                                    class="cursor-pointer transition">
                                    @if ($banner->status)
                                        <x-admin.badge variant="success" dot>Active</x-admin.badge>
                                    @else
                                        <x-admin.badge variant="danger" dot>Inactive</x-admin.badge>
                                    @endif
                                </button>
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    @can('banner.edit')
                                        <x-admin.button 
                                            variant="secondary" 
                                            size="sm" 
                                            icon="pencil"
                                            wire:click="edit({{ $banner->id }})"
                                            @click="$dispatch('modal-show', { name: 'banner-modal' })"
                                            title="Edit Banner" />
                                    @endcan

                                    @can('banner.delete')
                                        <x-admin.button 
                                            variant="danger" 
                                            size="sm" 
                                            icon="trash-2"
                                            wire:click="confirmDelete({{ $banner->id }})"
                                            @click="$dispatch('modal-show', { name: 'delete-modal' })"
                                            title="Delete Banner" />
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500 dark:text-zinc-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <div class="w-10 h-10 rounded-[4px] bg-slate-100 dark:bg-zinc-800 flex items-center justify-center text-slate-400">
                                        <i data-lucide="image" class="w-5 h-5"></i>
                                    </div>
                                    <p class="font-medium text-xs">No banners found.</p>
                                    @can('banner.create')
                                        <x-admin.button 
                                            variant="primary" 
                                            size="sm"
                                            @click="$wire.resetForm(); $dispatch('modal-show', { name: 'banner-modal' })">
                                            Create First Banner
                                        </x-admin.button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($banners->hasPages())
            <div class="p-3.5 border-t border-slate-200 dark:border-zinc-800">
                {{ $banners->links() }}
            </div>
        @endif
    </x-admin.card>

    <!-- Create / Edit Banner Modal -->
    <x-admin.modal name="banner-modal" title="{{ $bannerId ? 'Edit Banner #' . $bannerId : 'Create New Banner' }}" maxWidth="max-w-5xl">
        <form wire:submit.prevent="save" class="space-y-5">
            <!-- Responsive 2-Column Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
                <!-- Left Column: Visual Assets & Media (6 cols) -->
                <div class="lg:col-span-6 space-y-4">
                    <div class="flex items-center justify-between pb-1 border-b border-slate-100 dark:border-zinc-800">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-zinc-400 flex items-center gap-1.5">
                            <i data-lucide="image" class="w-3.5 h-3.5 text-brand-600"></i>
                            Visual Assets & Media
                        </span>
                        <span class="text-[10px] text-slate-400 font-medium">Image is required</span>
                    </div>

                    <!-- Banner Image Selector & Preview -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-zinc-300 mb-1.5">
                            Banner Image <span class="text-rose-500">*</span>
                        </label>

                        @if ($mediaUrl)
                            <div class="relative w-full h-48 rounded-[4px] overflow-hidden border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 group shadow-xs">
                                <img src="{{ $mediaUrl }}" alt="Selected Banner Image" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                    <button type="button"
                                        @click="$dispatch('open-media-modal', { targetEvent: 'banner-media-selected', type: 'image' })"
                                        class="px-3 py-1.5 bg-white text-slate-900 rounded-[3px] text-xs font-semibold hover:bg-slate-100 cursor-pointer inline-flex items-center gap-1.5">
                                        <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                                        <span>Change Image</span>
                                    </button>
                                    <button type="button"
                                        wire:click="removeMedia"
                                        class="px-3 py-1.5 bg-rose-600 text-white rounded-[3px] text-xs font-semibold hover:bg-rose-700 cursor-pointer inline-flex items-center gap-1.5">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        <span>Remove</span>
                                    </button>
                                </div>
                            </div>
                        @else
                            <div @click="$dispatch('open-media-modal', { targetEvent: 'banner-media-selected', type: 'image' })"
                                class="border-2 border-dashed border-slate-300 dark:border-zinc-700 hover:border-brand-500 rounded-[4px] p-6 text-center cursor-pointer bg-slate-50/60 dark:bg-zinc-900/40 transition">
                                <div class="w-10 h-10 rounded-[4px] bg-brand-50 dark:bg-brand-950/50 text-brand-600 mx-auto mb-2 flex items-center justify-center">
                                    <i data-lucide="image-plus" class="w-5 h-5"></i>
                                </div>
                                <p class="text-xs font-bold text-slate-700 dark:text-zinc-200">Click to Select Banner Image</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">Pick high-resolution image from Media Library</p>
                            </div>
                        @endif
                        @error('media_id') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Video Media Selector (Optional) -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-zinc-300 mb-1.5">
                            Video Media <span class="text-slate-400 font-normal">(Optional background video)</span>
                        </label>

                        @if ($videoMediaUrl)
                            <div class="relative w-full h-24 rounded-[4px] overflow-hidden border border-slate-200 dark:border-zinc-700 bg-slate-900 group flex items-center justify-center text-white">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="video" class="w-5 h-5 text-brand-400"></i>
                                    <span class="text-xs font-bold">Video Attached</span>
                                </div>
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                    <button type="button"
                                        @click="$dispatch('open-media-modal', { targetEvent: 'banner-video-selected', type: 'video' })"
                                        class="px-3 py-1.5 bg-white text-slate-900 rounded-[3px] text-xs font-semibold hover:bg-slate-100 cursor-pointer inline-flex items-center gap-1.5">
                                        <span>Change Video</span>
                                    </button>
                                    <button type="button"
                                        wire:click="removeVideoMedia"
                                        class="px-3 py-1.5 bg-rose-600 text-white rounded-[3px] text-xs font-semibold hover:bg-rose-700 cursor-pointer inline-flex items-center gap-1.5">
                                        <span>Remove</span>
                                    </button>
                                </div>
                            </div>
                        @else
                            <div @click="$dispatch('open-media-modal', { targetEvent: 'banner-video-selected', type: 'video' })"
                                class="border border-dashed border-slate-300 dark:border-zinc-700 hover:border-brand-500 rounded-[4px] p-3 text-center cursor-pointer bg-slate-50/50 dark:bg-zinc-900/30 transition flex items-center justify-center gap-2">
                                <i data-lucide="video" class="w-4 h-4 text-brand-600"></i>
                                <span class="text-xs font-medium text-slate-600 dark:text-zinc-300">Attach MP4 / WebM from Media Library</span>
                            </div>
                        @endif
                        @error('video_media_id') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Aspect ratio guidance -->
                    <div class="bg-brand-50/50 dark:bg-brand-950/30 rounded-[3px] p-2.5 border border-brand-100 dark:border-brand-900/40 text-[11px] text-brand-800 dark:text-brand-300 flex items-start gap-2">
                        <i data-lucide="info" class="w-4 h-4 shrink-0 mt-0.5 text-brand-600"></i>
                        <span>Recommended: 1920x600 for Home Slider, 1200x400 for Category Banners, 800x450 for Promos.</span>
                    </div>
                </div>

                <!-- Right Column: Settings, Placement & Link (6 cols) -->
                <div class="lg:col-span-6 space-y-4">
                    <div class="flex items-center justify-between pb-1 border-b border-slate-100 dark:border-zinc-800">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-zinc-400 flex items-center gap-1.5">
                            <i data-lucide="settings" class="w-3.5 h-3.5 text-brand-600"></i>
                            Placement & Targeting
                        </span>
                    </div>

                    <!-- Banner Placement Section & Position -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 bg-slate-50 dark:bg-zinc-900/60 p-3 rounded-[4px] border border-slate-200 dark:border-zinc-700">
                        <div>
                            <x-admin.select wire:model="section" label="Placement Section">
                                @foreach ($availableSections as $secKey => $secLabel)
                                    <option value="{{ $secKey }}">{{ $secLabel }}</option>
                                @endforeach
                            </x-admin.select>
                            <p class="text-[10px] text-slate-400 mt-1">Slider, Featured, or Footer</p>
                        </div>

                        <div>
                            <x-admin.input wire:model="position"
                                type="number"
                                min="0"
                                label="Display Order"
                                placeholder="0" />
                            <p class="text-[10px] text-slate-400 mt-1">Lower numbers appear first</p>
                        </div>
                    </div>

                    <!-- Direct URL / Destination -->
                    <div>
                        <x-admin.input wire:model="link"
                            type="url"
                            label="Direct URL / Link (Optional)"
                            placeholder="https://example.com/campaign or /shop" />
                    </div>

                    <!-- Display Status Card -->
                    <div class="p-3 bg-slate-50 dark:bg-zinc-900/50 rounded-[4px] border border-slate-200 dark:border-zinc-700 flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-slate-800 dark:text-zinc-200">Display Status</p>
                            <p class="text-[11px] text-slate-400">Enable or disable banner visibility across the store</p>
                        </div>
                        <x-admin.switch wire:model="status" />
                    </div>

                    <!-- Relations: Category, SubCategory, Product -->
                    <div class="space-y-3 pt-2 border-t border-slate-100 dark:border-zinc-800">
                        <p class="text-xs font-bold text-slate-700 dark:text-zinc-300">Target Resource Linking (Optional)</p>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <x-admin.select wire:model.live="category_id" label="Linked Category">
                                    <option value="">None (Select Category)</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </x-admin.select>
                            </div>

                            <div>
                                <x-admin.select wire:model="sub_category_id" label="Linked Sub-Category">
                                    <option value="">None (Select Sub-Category)</option>
                                    @foreach ($subcategories as $sub)
                                        <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                    @endforeach
                                </x-admin.select>
                            </div>
                        </div>

                        <div>
                            <x-admin.select wire:model="product_id" label="Linked Product">
                                <option value="">None (Select Product)</option>
                                @foreach ($products as $prod)
                                    <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                                @endforeach
                            </x-admin.select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Actions Footer -->
            <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-200 dark:border-zinc-800">
                <x-admin.button type="button" variant="ghost" @click="$dispatch('modal-close', { name: 'banner-modal' })">
                    Cancel
                </x-admin.button>
                <x-admin.button type="submit" variant="primary" icon="check">
                    {{ $bannerId ? 'Update Banner' : 'Create Banner' }}
                </x-admin.button>
            </div>
        </form>
    </x-admin.modal>

    <!-- Delete Confirmation Modal -->
    <x-admin.modal name="delete-modal" title="Delete Banner" maxWidth="max-w-md">
        <div class="space-y-4 text-center">
            <div class="w-10 h-10 rounded-[4px] bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 mx-auto flex items-center justify-center">
                <i data-lucide="trash-2" class="w-5 h-5"></i>
            </div>

            <div>
                <h4 class="font-bold text-sm text-slate-800 dark:text-zinc-100">Delete Banner Permanently?</h4>
                <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">Are you sure you want to delete this banner? This action cannot be undone.</p>
            </div>

            <div class="flex justify-center gap-2 pt-2">
                <x-admin.button type="button" variant="ghost" @click="$dispatch('modal-close', { name: 'delete-modal' })">
                    Cancel
                </x-admin.button>
                <x-admin.button type="button" wire:click="delete" variant="danger">
                    Delete Forever
                </x-admin.button>
            </div>
        </div>
    </x-admin.modal>
</section>
