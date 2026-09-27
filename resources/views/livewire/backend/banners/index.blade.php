<section>
    <livewire:utilities.toast-modal />

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header & Breadcrumbs -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>Banners</flux:breadcrumbs.item>
            </flux:breadcrumbs>

            @can('banner.create')
                <flux:modal.trigger name="banner-modal" @click="$wire.resetForm()">
                    <flux:button variant="primary" icon="plus" class="shadow-sm cursor-pointer">
                        Create Banner
                    </flux:button>
                </flux:modal.trigger>
            @endcan
        </div>

        <!-- Search & Table Card -->
        <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 shadow-sm rounded-2xl overflow-hidden">
            <!-- Search bar -->
            <div class="p-4 border-b border-gray-200 dark:border-zinc-700/60 bg-gray-50/50 dark:bg-zinc-800/50">
                <div class="relative max-w-md">
                    <input wire:model.live.debounce.300ms="search"
                        type="text"
                        placeholder="Search banners by link, category, section, product..."
                        class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-2xs">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Banners Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                    <thead class="bg-gray-50 dark:bg-zinc-900/50">
                        <tr>
                            <th class="px-5 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-20">Order</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Preview</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Section</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Target / Link</th>
                            <th class="px-5 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-28">Status</th>
                            <th class="px-5 py-3.5 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-28">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-zinc-700 bg-white dark:bg-zinc-800">
                        @forelse ($banners as $banner)
                            <tr wire:key="banner-row-{{ $banner->id }}" class="hover:bg-gray-50/80 dark:hover:bg-zinc-700/40 transition">
                                <!-- Order / Position Controls -->
                                <td class="px-5 py-4 whitespace-nowrap text-center">
                                    <div class="inline-flex items-center space-x-1.5 bg-gray-100 dark:bg-zinc-700/80 px-2 py-1 rounded-lg border border-gray-200 dark:border-zinc-600">
                                        <button type="button"
                                            wire:click="moveUp({{ $banner->id }})"
                                            title="Move Up"
                                            class="text-gray-400 hover:text-indigo-600 transition p-0.5 cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                        </button>
                                        <span class="font-mono font-bold text-xs text-gray-800 dark:text-gray-200 px-1">{{ $banner->position }}</span>
                                        <button type="button"
                                            wire:click="moveDown({{ $banner->id }})"
                                            title="Move Down"
                                            class="text-gray-400 hover:text-indigo-600 transition p-0.5 cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </button>
                                    </div>
                                </td>

                                <!-- Preview Thumbnail -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        @if ($banner->media)
                                            <div class="w-24 h-14 rounded-xl overflow-hidden bg-gray-100 dark:bg-zinc-700 border border-gray-200 dark:border-zinc-600 shrink-0 shadow-2xs">
                                                <img src="{{ $banner->media->urls['small'] ?? $banner->media->url }}"
                                                    alt="Banner Image"
                                                    class="w-full h-full object-cover">
                                            </div>
                                        @elseif ($banner->videoMedia)
                                            <div class="w-24 h-14 rounded-xl overflow-hidden bg-slate-900 border border-gray-200 dark:border-zinc-600 shrink-0 flex items-center justify-center text-white text-xs">
                                                <svg class="w-5 h-5 text-indigo-400 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11v11.78a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                                                <span>Video</span>
                                            </div>
                                        @else
                                            <div class="w-24 h-14 rounded-xl bg-gray-100 dark:bg-zinc-700 border border-dashed border-gray-300 dark:border-zinc-600 flex items-center justify-center text-gray-400 text-xs font-semibold">
                                                No Media
                                            </div>
                                        @endif

                                        <!-- Media Badges -->
                                        <div class="space-y-1">
                                            @if ($banner->media)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800/60">
                                                    Image
                                                </span>
                                            @endif
                                            @if ($banner->videoMedia)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 border border-purple-200 dark:border-purple-800/60">
                                                    Video
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Section Placement Badge -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    @php
                                        $sec = $banner->section ?: 'slider';
                                        $badges = [
                                            'slider'   => 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800/60',
                                            'featured' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800/60',
                                            'footer'   => 'bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 border-teal-200 dark:border-teal-800/60',
                                        ];
                                        $color = $badges[$sec] ?? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border-indigo-200 dark:border-indigo-800/60';
                                        $secName = $availableSections[$sec] ?? ucfirst($sec);
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold border {{ $color }}">
                                        {{ $secName }}
                                    </span>
                                </td>

                                <!-- Target / Link -->
                                <td class="px-5 py-4">
                                    <div class="text-xs space-y-1">
                                        @if ($banner->product)
                                            <div class="flex items-center gap-1.5 text-gray-800 dark:text-gray-200 font-semibold">
                                                <span class="text-gray-400">Product:</span>
                                                <span class="truncate max-w-xs">{{ $banner->product->name }}</span>
                                            </div>
                                        @elseif ($banner->category)
                                            <div class="flex items-center gap-1.5 text-gray-800 dark:text-gray-200 font-semibold">
                                                <span class="text-gray-400">Category:</span>
                                                <span>{{ $banner->category->name }}</span>
                                                @if ($banner->sub_category)
                                                    <span class="text-gray-400">&rsaquo;</span>
                                                    <span>{{ $banner->sub_category->name }}</span>
                                                @endif
                                            </div>
                                        @endif

                                        @if ($banner->link)
                                            <a href="{{ $banner->link }}" target="_blank" class="inline-flex items-center gap-1 text-indigo-600 dark:text-indigo-400 hover:underline max-w-sm truncate">
                                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                <span class="truncate">{{ $banner->link }}</span>
                                            </a>
                                        @elseif (!$banner->product && !$banner->category)
                                            <span class="text-gray-400 italic">No link assigned</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Status Badge -->
                                <td class="px-5 py-4 whitespace-nowrap text-center">
                                    <button type="button"
                                        wire:click="toggleStatus({{ $banner->id }})"
                                        class="cursor-pointer transition transform active:scale-95">
                                        @if ($banner->status)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                <span>Active</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                <span>Inactive</span>
                                            </span>
                                        @endif
                                    </button>
                                </td>

                                <!-- Actions -->
                                <td class="px-5 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @can('banner.edit')
                                            <flux:modal.trigger name="banner-modal">
                                                <flux:button wire:click="edit({{ $banner->id }})" icon="pencil-square" size="sm" class="cursor-pointer" title="Edit Banner">
                                                </flux:button>
                                            </flux:modal.trigger>
                                        @endcan

                                        @can('banner.delete')
                                            <flux:modal.trigger name="delete-modal">
                                                <flux:button wire:click="confirmDelete({{ $banner->id }})" icon="trash" variant="danger" size="sm" class="cursor-pointer" title="Delete Banner">
                                                </flux:button>
                                            </flux:modal.trigger>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center space-y-3">
                                        <div class="w-14 h-14 rounded-2xl bg-gray-100 dark:bg-zinc-700 flex items-center justify-center text-gray-400">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <p class="font-medium text-sm">No banners found.</p>
                                        @can('banner.create')
                                            <flux:modal.trigger name="banner-modal" @click="$wire.resetForm()">
                                                <flux:button variant="primary" size="sm" class="cursor-pointer">Create First Banner</flux:button>
                                            </flux:modal.trigger>
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
                <div class="p-4 border-t border-gray-200 dark:border-zinc-700">
                    {{ $banners->links() }}
                </div>
            @endif
        </div>

        <!-- Create / Edit Banner Modal -->
        <flux:modal name="banner-modal" class="!max-w-5xl !w-[94vw] md:!w-[52rem] lg:!w-[62rem]">
            <form wire:submit.prevent="save" class="space-y-6">
                <!-- Modal Header -->
                <div class="flex items-center gap-3 pb-4 border-b border-gray-100 dark:border-zinc-700/80">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 border border-indigo-100 dark:border-indigo-800/60 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <flux:heading size="lg">{{ $bannerId ? 'Edit Banner #' . $bannerId : 'Create New Banner' }}</flux:heading>
                        <flux:subheading>Upload media assets, assign banner placement, and configure targeting.</flux:subheading>
                    </div>
                </div>

                <!-- Responsive 2-Column Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- Left Column: Visual Assets & Media (6 cols) -->
                    <div class="lg:col-span-6 space-y-4">
                        <div class="flex items-center justify-between pb-1 border-b border-gray-100 dark:border-zinc-800">
                            <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Visual Assets & Media
                            </span>
                            <span class="text-[11px] text-gray-400 font-medium">Image is required</span>
                        </div>

                        <!-- Banner Image Selector & Preview -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2">
                                Banner Image <span class="text-rose-500">*</span>
                            </label>

                            @if ($mediaUrl)
                                <div class="relative w-full h-52 rounded-2xl overflow-hidden border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 group shadow-sm transition hover:shadow-md">
                                    <img src="{{ $mediaUrl }}" alt="Selected Banner Image" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3 backdrop-blur-xs">
                                        <button type="button"
                                            @click="$dispatch('open-media-modal', { targetEvent: 'banner-media-selected', type: 'image' })"
                                            class="px-3.5 py-1.5 bg-white text-gray-900 rounded-lg text-xs font-semibold shadow hover:bg-gray-100 cursor-pointer inline-flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                            <span>Change Image</span>
                                        </button>
                                        <button type="button"
                                            wire:click="removeMedia"
                                            class="px-3.5 py-1.5 bg-rose-600 text-white rounded-lg text-xs font-semibold shadow hover:bg-rose-700 cursor-pointer inline-flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            <span>Remove</span>
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div @click="$dispatch('open-media-modal', { targetEvent: 'banner-media-selected', type: 'image' })"
                                    class="border-2 border-dashed border-gray-300 dark:border-zinc-700 hover:border-indigo-500 dark:hover:border-indigo-400 rounded-2xl p-7 text-center cursor-pointer bg-gray-50/70 dark:bg-zinc-800/40 transition group">
                                    <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-500 mx-auto mb-3 flex items-center justify-center group-hover:scale-105 transition-transform">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-xs font-bold text-gray-700 dark:text-gray-300">Click to Select Banner Image</p>
                                    <p class="text-[11px] text-gray-400 mt-1">Pick high-resolution image from Media Library</p>
                                </div>
                            @endif
                            @error('media_id') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Video Media Selector (Optional) -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2">
                                Video Media <span class="text-gray-400 font-normal">(Optional background / slider video)</span>
                            </label>

                            @if ($videoMediaUrl)
                                <div class="relative w-full h-28 rounded-2xl overflow-hidden border border-gray-200 dark:border-zinc-700 bg-slate-900 group shadow-sm flex items-center justify-center text-white">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-indigo-400" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11v11.78a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                                        <span class="text-xs font-bold">Video Attached</span>
                                    </div>
                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3 backdrop-blur-xs">
                                        <button type="button"
                                            @click="$dispatch('open-media-modal', { targetEvent: 'banner-video-selected', type: 'video' })"
                                            class="px-3.5 py-1.5 bg-white text-gray-900 rounded-lg text-xs font-semibold shadow hover:bg-gray-100 cursor-pointer inline-flex items-center gap-1.5">
                                            <span>Change Video</span>
                                        </button>
                                        <button type="button"
                                            wire:click="removeVideoMedia"
                                            class="px-3.5 py-1.5 bg-rose-600 text-white rounded-lg text-xs font-semibold shadow hover:bg-rose-700 cursor-pointer inline-flex items-center gap-1.5">
                                            <span>Remove</span>
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div @click="$dispatch('open-media-modal', { targetEvent: 'banner-video-selected', type: 'video' })"
                                    class="border border-dashed border-gray-300 dark:border-zinc-700 hover:border-purple-500 dark:hover:border-purple-400 rounded-2xl p-3.5 text-center cursor-pointer bg-gray-50/50 dark:bg-zinc-800/30 transition flex items-center justify-center gap-2 group">
                                    <svg class="w-4 h-4 text-purple-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-xs font-medium text-gray-600 dark:text-gray-300">Attach MP4 / WebM from Media Library</span>
                                </div>
                            @endif
                            @error('video_media_id') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Aspect ratio guidance -->
                        <div class="bg-indigo-50/60 dark:bg-indigo-950/30 rounded-xl p-3 border border-indigo-100 dark:border-indigo-900/40 text-[11px] text-indigo-700 dark:text-indigo-300 flex items-start gap-2">
                            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Recommended: 1920x600 for Home Slider, 1200x400 for Category Banners, 800x450 for Promos.</span>
                        </div>
                    </div>

                    <!-- Right Column: Settings, Placement & Link (6 cols) -->
                    <div class="lg:col-span-6 space-y-4">
                        <div class="flex items-center justify-between pb-1 border-b border-gray-100 dark:border-zinc-800">
                            <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Placement & Targeting
                            </span>
                        </div>

                        <!-- Banner Placement Section & Position -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 bg-slate-50 dark:bg-zinc-900/60 p-3.5 rounded-xl border border-gray-200 dark:border-zinc-700">
                            <div>
                                <flux:select wire:model="section" label="Placement Section" class="cursor-pointer">
                                    @foreach ($availableSections as $secKey => $secLabel)
                                        <option value="{{ $secKey }}">{{ $secLabel }}</option>
                                    @endforeach
                                </flux:select>
                                <p class="text-[10px] text-gray-400 mt-1">Slider, Featured, or Footer</p>
                            </div>

                            <div>
                                <flux:input wire:model="position"
                                    type="number"
                                    min="0"
                                    label="Display Order"
                                    placeholder="0" />
                                <p class="text-[10px] text-gray-400 mt-1">Lower numbers appear first</p>
                            </div>
                        </div>

                        <!-- Direct URL / Destination -->
                        <div>
                            <flux:input wire:model="link"
                                type="url"
                                label="Direct URL / Link (Optional)"
                                placeholder="https://example.com/campaign or /shop" />
                        </div>

                        <!-- Display Status Card -->
                        <div class="p-3 bg-gray-50 dark:bg-zinc-800/40 rounded-xl border border-gray-200 dark:border-zinc-700 flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-gray-700 dark:text-gray-300">Display Status</p>
                                <p class="text-[11px] text-gray-400">Enable or disable banner visibility across the store</p>
                            </div>
                            <flux:switch wire:model="status" class="cursor-pointer" />
                        </div>

                        <!-- Relations: Category, SubCategory, Product -->
                        <div class="space-y-3 pt-2 border-t border-gray-100 dark:border-zinc-700/60">
                            <p class="text-xs font-bold text-gray-700 dark:text-gray-300">Target Resource Linking (Optional)</p>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <flux:select wire:model.live="category_id" label="Linked Category" class="cursor-pointer">
                                        <option value="">None (Select Category)</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </flux:select>
                                </div>

                                <div>
                                    <flux:select wire:model="sub_category_id" label="Linked Sub-Category" class="cursor-pointer">
                                        <option value="">None (Select Sub-Category)</option>
                                        @foreach ($subcategories as $sub)
                                            <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                        @endforeach
                                    </flux:select>
                                </div>
                            </div>

                            <div>
                                <flux:select wire:model="product_id" label="Linked Product" class="cursor-pointer">
                                    <option value="">None (Select Product)</option>
                                    @foreach ($products as $prod)
                                        <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                                    @endforeach
                                </flux:select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Actions Footer -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-zinc-700">
                    <flux:modal.close>
                        <flux:button variant="ghost" class="cursor-pointer">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="primary" icon="check" class="cursor-pointer px-6 shadow-sm">
                        {{ $bannerId ? 'Update Banner' : 'Create Banner' }}
                    </flux:button>
                </div>
            </form>
        </flux:modal>

        <!-- Delete Confirmation Modal -->
        <flux:modal name="delete-modal" class="md:w-96">
            <div class="space-y-6 text-center">
                <div class="w-12 h-12 rounded-full bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 mx-auto flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </div>

                <div>
                    <flux:heading size="lg">Delete Banner?</flux:heading>
                    <flux:subheading>Are you sure you want to delete this banner? This action cannot be undone.</flux:subheading>
                </div>

                <div class="flex justify-center gap-3">
                    <flux:modal.close>
                        <flux:button variant="ghost" class="cursor-pointer">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button wire:click="delete" variant="danger" class="cursor-pointer">
                        Delete Forever
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    </div>
</section>
