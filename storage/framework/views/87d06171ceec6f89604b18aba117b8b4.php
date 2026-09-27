<div x-data="{
    copied: false,
    copyUrl(url) {
        navigator.clipboard.writeText(url);
        this.copied = true;
        setTimeout(() => this.copied = false, 2000);
    }
}">
    <!-- Large Flux Modal for Media Management -->
    <?php if (isset($component)) { $__componentOriginal8cc9d3143946b992b324617832699c5f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8cc9d3143946b992b324617832699c5f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::modal.index','data' => ['name' => 'media-manager-modal','closable' => false,'class' => '!max-w-7xl !w-[96vw] !h-[92vh] p-0 overflow-hidden bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl shadow-2xl']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'media-manager-modal','closable' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'class' => '!max-w-7xl !w-[96vw] !h-[92vh] p-0 overflow-hidden bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl shadow-2xl']); ?>
        <!--[if BLOCK]><![endif]--><?php if($isOpen): ?>
        <div class="flex flex-col h-full w-full">
        <!-- Top Navigation Bar -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-zinc-800 flex flex-wrap items-center justify-between gap-4 bg-gray-50/70 dark:bg-zinc-900/90 shrink-0">
            <!-- Left Side: 3 Option Tabs -->
            <div class="flex items-center space-x-1.5 bg-gray-200/80 dark:bg-zinc-800 p-1 rounded-xl">
                <button type="button"
                    wire:click="setTab('all')"
                    class="flex items-center space-x-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-150 <?php echo e($tab === 'all' ? 'bg-white dark:bg-zinc-700 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white'); ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>All Media</span>
                </button>

                <button type="button"
                    wire:click="setTab('folders')"
                    class="flex items-center space-x-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-150 <?php echo e($tab === 'folders' ? 'bg-white dark:bg-zinc-700 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white'); ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                    </svg>
                    <span>Folders</span>
                </button>

                <button type="button"
                    wire:click="setTab('upload')"
                    class="flex items-center space-x-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-150 <?php echo e($tab === 'upload' ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white'); ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    <span>Upload</span>
                </button>

                <button type="button"
                    wire:click="setTab('trash')"
                    class="flex items-center space-x-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-150 <?php echo e($tab === 'trash' ? 'bg-rose-600 text-white shadow-sm' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white'); ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    <span>Trash</span>
                    <!--[if BLOCK]><![endif]--><?php if($trashedCount > 0): ?>
                        <span class="ml-1 px-1.5 py-0.2 text-[10px] font-bold rounded-full <?php echo e($tab === 'trash' ? 'bg-white/25 text-white' : 'bg-rose-100 dark:bg-rose-950 text-rose-600 dark:text-rose-400'); ?>">
                            <?php echo e($trashedCount); ?>

                        </span>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </button>
            </div>

            <!-- Right Controls: Search, Filter, and Close -->
            <div class="flex items-center space-x-3">
                <!--[if BLOCK]><![endif]--><?php if($tab === 'all' || $tab === 'trash'): ?>
                    <!-- Search Input -->
                    <div class="relative w-56">
                        <input type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="<?php echo e($tab === 'trash' ? 'Search trash...' : 'Search media...'); ?>"
                            class="w-full pl-9 pr-3 py-1.5 text-xs rounded-lg border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                        <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>

                    <!--[if BLOCK]><![endif]--><?php if($tab === 'all'): ?>
                        <!-- Type Filter -->
                        <div class="flex items-center space-x-1 bg-gray-200/80 dark:bg-zinc-800 p-0.5 rounded-lg text-xs">
                            <button type="button" wire:click="setTypeFilter('all')" class="px-2.5 py-1 rounded-md font-medium <?php echo e($typeFilter === 'all' ? 'bg-white dark:bg-zinc-700 text-indigo-600 dark:text-indigo-400 shadow-xs' : 'text-gray-500 hover:text-gray-800'); ?>">All</button>
                            <button type="button" wire:click="setTypeFilter('image')" class="px-2.5 py-1 rounded-md font-medium <?php echo e($typeFilter === 'image' ? 'bg-white dark:bg-zinc-700 text-indigo-600 dark:text-indigo-400 shadow-xs' : 'text-gray-500 hover:text-gray-800'); ?>">Images</button>
                            <button type="button" wire:click="setTypeFilter('video')" class="px-2.5 py-1 rounded-md font-medium <?php echo e($typeFilter === 'video' ? 'bg-white dark:bg-zinc-700 text-indigo-600 dark:text-indigo-400 shadow-xs' : 'text-gray-500 hover:text-gray-800'); ?>">Videos</button>
                            <button type="button" wire:click="setTypeFilter('gif')" class="px-2.5 py-1 rounded-md font-medium <?php echo e($typeFilter === 'gif' ? 'bg-white dark:bg-zinc-700 text-indigo-600 dark:text-indigo-400 shadow-xs' : 'text-gray-500 hover:text-gray-800'); ?>">GIFs</button>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <?php if (isset($component)) { $__componentOriginalda55eef372798476d918d03158796935 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalda55eef372798476d918d03158796935 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'e60dd9d2c3a62d619c9acb38f20d5aa5::modal.close','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('flux::modal.close'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                    <button type="button" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-zinc-800 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalda55eef372798476d918d03158796935)): ?>
<?php $attributes = $__attributesOriginalda55eef372798476d918d03158796935; ?>
<?php unset($__attributesOriginalda55eef372798476d918d03158796935); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalda55eef372798476d918d03158796935)): ?>
<?php $component = $__componentOriginalda55eef372798476d918d03158796935; ?>
<?php unset($__componentOriginalda55eef372798476d918d03158796935); ?>
<?php endif; ?>
            </div>
        </div>

        <!-- Main Body Content -->
        <div class="flex-1 overflow-hidden flex flex-col md:flex-row">
            <!-- Left / Center Panel -->
            <div class="flex-1 overflow-y-auto p-6">
                <!-- TAB 1: ALL MEDIA -->
                <!--[if BLOCK]><![endif]--><?php if($tab === 'all'): ?>
                    <!-- Active Folder Breadcrumb / Tag -->
                    <!--[if BLOCK]><![endif]--><?php if($currentFolder): ?>
                        <div class="mb-4 flex items-center justify-between bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-200 dark:border-indigo-800/60 px-4 py-2 rounded-xl text-sm">
                            <div class="flex items-center space-x-2 text-indigo-900 dark:text-indigo-200">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                </svg>
                                <span>Folder: <strong class="font-bold text-indigo-700 dark:text-indigo-300"><?php echo e($currentFolder); ?></strong></span>
                            </div>
                            <button type="button" wire:click="clearFolder" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                                View All Media &rarr;
                            </button>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                    <!-- Media Grid -->
                    <!--[if BLOCK]><![endif]--><?php if($mediaItems->count() > 0): ?>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3.5">
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $mediaItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div wire:key="media-item-<?php echo e($item->id); ?>"
                                    wire:click="selectMedia(<?php echo e($item->id); ?>)"
                                    class="group relative rounded-xl overflow-hidden border cursor-pointer transition-all duration-200 bg-gray-50 dark:bg-zinc-800/60 <?php echo e($selectedMediaId === $item->id ? 'border-indigo-600 ring-2 ring-indigo-500 shadow-md' : 'border-gray-200 dark:border-zinc-700 hover:border-indigo-400 hover:shadow-sm'); ?>">

                                    <!-- Media Preview Thumbnail -->
                                    <div class="aspect-square w-full relative overflow-hidden bg-gray-100 dark:bg-zinc-800 flex items-center justify-center">
                                        <!--[if BLOCK]><![endif]--><?php if($item->type === 'image'): ?>
                                            <img src="<?php echo e(asset('storage/media/thumb/' . $item->path)); ?>"
                                                alt="<?php echo e($item->title); ?>"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                                loading="lazy" />
                                        <?php elseif($item->type === 'gif'): ?>
                                            <img src="<?php echo e(asset('storage/media/gif/' . $item->path)); ?>"
                                                alt="<?php echo e($item->title); ?>"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                                            <span class="absolute top-2 left-2 bg-pink-600/90 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider backdrop-blur-xs">GIF</span>
                                        <?php elseif($item->type === 'video'): ?>
                                            <div class="w-full h-full bg-slate-900 flex flex-col items-center justify-center text-white">
                                                <div class="w-10 h-10 rounded-full bg-indigo-600/80 flex items-center justify-center mb-1 group-hover:scale-110 transition">
                                                    <svg class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M6.3 2.841A1.5 1.5 0 004 4.11v11.78a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                                    </svg>
                                                </div>
                                                <span class="text-[10px] uppercase font-semibold text-gray-300 tracking-wider">Video</span>
                                            </div>
                                            <span class="absolute top-2 left-2 bg-indigo-600/90 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider backdrop-blur-xs">VIDEO</span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                        <!-- Selection Checkmark -->
                                        <!--[if BLOCK]><![endif]--><?php if($selectedMediaId === $item->id): ?>
                                            <div class="absolute top-2 right-2 bg-indigo-600 text-white p-1 rounded-full shadow-md">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>

                                    <!-- Caption info -->
                                    <div class="p-2.5 bg-white dark:bg-zinc-800 text-left border-t border-gray-100 dark:border-zinc-700/60">
                                        <p class="text-xs font-semibold text-gray-800 dark:text-gray-200 truncate" title="<?php echo e($item->title); ?>">
                                            <?php echo e($item->title); ?>

                                        </p>
                                        <div class="flex items-center justify-between text-[11px] text-gray-400 mt-1">
                                            <span class="uppercase font-mono"><?php echo e($item->extension); ?></span>
                                            <span><?php echo e($item->sizes['original'] ?? round($item->size / 1024, 1) . ' KB'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            <?php echo e($mediaItems->links()); ?>

                        </div>
                    <?php else: ?>
                        <!-- Empty State -->
                        <div class="text-center py-20">
                            <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-zinc-800 rounded-2xl flex items-center justify-center text-gray-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h4 class="text-base font-semibold text-gray-700 dark:text-gray-300">No media found</h4>
                            <p class="text-sm text-gray-500 mt-1">Upload files or select a different filter.</p>
                            <button type="button" wire:click="setTab('upload')" class="mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm">
                                Upload New Media
                            </button>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <!-- TAB 2: FOLDERS -->
                <!--[if BLOCK]><![endif]--><?php if($tab === 'folders'): ?>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between pb-4 border-b border-gray-200 dark:border-zinc-800">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Media Folders</h3>
                                <p class="text-xs text-gray-500">Organize your images, videos, and gifs into custom folders.</p>
                            </div>

                            <!-- Create New Folder Form -->
                            <div class="flex items-center space-x-2">
                                <input type="text"
                                    wire:model="newFolderName"
                                    wire:keydown.enter="createFolder"
                                    placeholder="New folder name..."
                                    class="px-3 py-1.5 text-xs rounded-lg border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500" />
                                <button type="button"
                                    wire:click="createFolder"
                                    class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition shadow-sm">
                                    + Add Folder
                                </button>
                            </div>
                        </div>

                        <!-- Folders Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $folders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $folder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div wire:key="folder-<?php echo e($folder['name']); ?>"
                                    wire:click="openFolder('<?php echo e($folder['name']); ?>')"
                                    class="p-5 rounded-2xl border border-gray-200 dark:border-zinc-800 bg-white dark:bg-zinc-800/80 hover:border-indigo-400 hover:shadow-md transition-all duration-200 cursor-pointer group text-left flex flex-col justify-between">
                                    <div class="flex items-start justify-between">
                                        <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                                            </svg>
                                        </div>
                                        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-gray-100 dark:bg-zinc-700 text-gray-600 dark:text-gray-300">
                                            <?php echo e($folder['count']); ?>

                                        </span>
                                    </div>
                                    <div class="mt-4">
                                        <h4 class="font-bold text-sm text-gray-800 dark:text-gray-100 group-hover:text-indigo-600 capitalize truncate">
                                            <?php echo e($folder['name']); ?>

                                        </h4>
                                        <p class="text-[11px] text-gray-400 mt-0.5">Click to view items</p>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <!-- TAB 3: UPLOAD -->
                <!--[if BLOCK]><![endif]--><?php if($tab === 'upload'): ?>
                    <div x-data="{
                        isUploading: false,
                        progress: 0,
                        statusText: '',
                        isDragging: false,
                        async handleFiles(files) {
                            const fileList = Array.from(files || []);
                            if (!fileList.length) return;

                            this.isUploading = true;
                            this.progress = 5;
                            this.statusText = 'Optimizing media for ultra-fast upload...';

                            const processed = [];
                            for (let i = 0; i < fileList.length; i++) {
                                const f = fileList[i];
                                if (f.type.startsWith('image/') && !f.type.includes('gif') && !f.type.includes('svg') && !f.name.toLowerCase().endsWith('.gif')) {
                                    try {
                                        const c = await this.compress(f);
                                        processed.push(c);
                                    } catch(err) {
                                        processed.push(f);
                                    }
                                } else {
                                    processed.push(f);
                                }
                            }

                            this.statusText = 'Uploading to server...';
                            this.progress = 20;

                            window.Livewire.find('<?php echo e($_instance->getId()); ?>').uploadMultiple('uploads', processed,
                                () => {
                                    this.isUploading = false;
                                    this.progress = 100;
                                    this.statusText = '';
                                },
                                () => {
                                    this.isUploading = false;
                                    this.statusText = 'Upload failed. Please try again.';
                                },
                                (evt) => {
                                    this.progress = Math.max(20, evt.detail.progress);
                                    this.statusText = `Uploading: ${this.progress}%`;
                                }
                            );
                        },
                        compress(file) {
                            return new Promise((resolve) => {
                                if (file.size < 400 * 1024) return resolve(file);
                                const img = new Image();
                                const src = URL.createObjectURL(file);
                                img.src = src;
                                img.onload = () => {
                                    URL.revokeObjectURL(src);
                                    const max = 1920;
                                    let w = img.naturalWidth || img.width;
                                    let h = img.naturalHeight || img.height;
                                    if (w > max || h > max) {
                                        if (w > h) {
                                            h = Math.round((h * max) / w);
                                            w = max;
                                        } else {
                                            w = Math.round((w * max) / h);
                                            h = max;
                                        }
                                    } else if (file.size < 800 * 1024) {
                                        return resolve(file);
                                    }
                                    const canvas = document.createElement('canvas');
                                    canvas.width = w;
                                    canvas.height = h;
                                    const ctx = canvas.getContext('2d');
                                    ctx.drawImage(img, 0, 0, w, h);
                                    canvas.toBlob((blob) => {
                                        if (!blob || blob.size >= file.size) return resolve(file);
                                        const name = file.name.replace(/\.[^/.]+$/, '') + '.webp';
                                        resolve(new File([blob], name, { type: 'image/webp', lastModified: Date.now() }));
                                    }, 'image/webp', 0.85);
                                };
                                img.onerror = () => {
                                    URL.revokeObjectURL(src);
                                    resolve(file);
                                };
                            });
                        }
                    }" class="max-w-3xl mx-auto space-y-6">
                        <!-- Folder Selection for Upload -->
                        <div class="bg-gray-50 dark:bg-zinc-800/50 p-4 rounded-xl border border-gray-200 dark:border-zinc-800 flex items-center justify-between">
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300">Target Folder:</label>
                            <div class="flex items-center space-x-2">
                                <select wire:model="uploadFolder" class="px-3 py-1.5 text-xs rounded-lg border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500">
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $folders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($f['name']); ?>"><?php echo e(ucfirst($f['name'])); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </select>
                            </div>
                        </div>

                        <!-- Drag and Drop Upload Area -->
                        <div 
                            @dragover.prevent="isDragging = true"
                            @dragleave.prevent="isDragging = false"
                            @drop.prevent="isDragging = false; handleFiles($event.dataTransfer.files)"
                            :class="isDragging ? 'border-indigo-600 bg-indigo-50/50 dark:bg-indigo-950/20' : 'border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900'"
                            class="relative border-2 border-dashed hover:border-indigo-500 dark:hover:border-indigo-400 rounded-2xl p-10 text-center transition-colors group">
                            <input type="file"
                                @change="handleFiles($event.target.files); $event.target.value = ''"
                                multiple
                                accept="image/*,video/*,.gif"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />

                            <div class="flex flex-col items-center justify-center space-y-3 pointer-events-none">
                                <div class="w-16 h-16 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-8 h-8 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                </div>
                                <h4 class="text-base font-bold text-gray-800 dark:text-gray-200">
                                    Drag and drop your files here, or <span class="text-indigo-600 underline">browse</span>
                                </h4>
                                <p class="text-xs text-gray-500 max-w-md">
                                    Supports <strong>Images</strong> (JPEG, PNG, WebP), <strong>GIFs</strong>, and <strong>Videos</strong> (MP4, WEBM, MOV) up to <strong>200MB</strong> per file.
                                </p>
                                <div class="flex flex-wrap items-center justify-center gap-2.5 pt-3">
                                    <!-- Auto WebP Compression -->
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60 shadow-2xs">
                                        <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                        <span>Client &amp; Server WebP Speed Engine</span>
                                    </span>

                                    <!-- Video Direct Storage -->
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/60 shadow-2xs">
                                        <svg class="w-3.5 h-3.5 text-indigo-600 dark:text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        <span>Direct Video Storage</span>
                                    </span>

                                    <!-- GIF Animation Protected -->
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800/60 shadow-2xs">
                                        <svg class="w-3.5 h-3.5 text-purple-600 dark:text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>Animated GIF Protected</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Real-time Upload Progress Bar -->
                        <div x-show="isUploading" x-cloak class="w-full bg-white dark:bg-zinc-800/90 p-4 rounded-xl border border-indigo-100 dark:border-indigo-900/50 shadow-sm space-y-2">
                            <div class="flex items-center justify-between text-xs font-semibold text-indigo-700 dark:text-indigo-300">
                                <span class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4 text-indigo-600 shrink-0" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span x-text="statusText"></span>
                                </span>
                                <span class="font-mono" x-text="progress + '%'"></span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-zinc-700 h-2.5 rounded-full overflow-hidden">
                                <div class="bg-indigo-600 h-full rounded-full transition-all duration-200 ease-out" :style="`width: ${progress}%`"></div>
                            </div>
                        </div>

                        <!-- Selected files queue with Fast Speed Previewer & Animated Disabled Upload Button -->
                        <!--[if BLOCK]><![endif]--><?php if(!empty($uploads)): ?>
                            <div class="space-y-4 bg-gray-50 dark:bg-zinc-800/50 p-5 rounded-2xl border border-gray-200 dark:border-zinc-700 shadow-xs">
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <div class="flex items-center space-x-2">
                                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <h4 class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                            Ready to upload (<?php echo e(count($uploads)); ?> files)
                                        </h4>
                                    </div>

                                    <!-- Upload button: spins, disables during upload, then returns to normal -->
                                    <button type="button"
                                        wire:click="saveUploads"
                                        wire:loading.attr="disabled"
                                        wire:target="saveUploads"
                                        class="shrink-0 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 disabled:cursor-not-allowed text-white text-xs font-bold rounded-xl shadow-md transition-all inline-flex flex-row items-center justify-center gap-2 whitespace-nowrap">
                                        
                                        <span wire:loading.remove wire:target="saveUploads" class="inline-flex flex-row items-center gap-1.5 whitespace-nowrap">
                                            <span>Start Processing &amp; Save</span>
                                            <svg class="w-4 h-4 shrink-0 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </span>

                                        <span wire:loading wire:target="saveUploads" class="inline-flex flex-row items-center gap-2 whitespace-nowrap">
                                            <svg class="animate-spin h-4 w-4 shrink-0 inline-block text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span>Processing &amp; Saving...</span>
                                        </span>
                                    </button>
                                </div>

                                <!-- Fast Speed Previewer List -->
                                <div class="max-h-64 overflow-y-auto divide-y divide-gray-200 dark:divide-zinc-700/60 rounded-xl bg-white dark:bg-zinc-800/80 border border-gray-200 dark:border-zinc-700/50 p-2">
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $uploads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $mime = $u->getMimeType() ?: '';
                                            $isImg = str_starts_with($mime, 'image/') && !str_contains($mime, 'gif');
                                            $isGif = str_contains($mime, 'gif') || str_ends_with(strtolower($u->getClientOriginalName()), '.gif');
                                            $isVid = str_starts_with($mime, 'video/');
                                        ?>
                                        <div wire:key="upload-file-<?php echo e($index); ?>" class="py-2.5 px-2 flex items-center justify-between gap-3 hover:bg-gray-50 dark:hover:bg-zinc-700/40 rounded-lg transition">
                                            <!-- Fast Speed Preview Thumbnail -->
                                            <div class="flex items-center space-x-3 flex-1 min-w-0">
                                                <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-zinc-700 overflow-hidden flex items-center justify-center shrink-0 border border-gray-200 dark:border-zinc-600 shadow-2xs">
                                                    <!--[if BLOCK]><![endif]--><?php if($isImg || $isGif): ?>
                                                        <img src="<?php echo e($u->temporaryUrl()); ?>" 
                                                            alt="<?php echo e($u->getClientOriginalName()); ?>"
                                                            loading="lazy"
                                                            class="w-full h-full object-cover" />
                                                    <?php elseif($isVid): ?>
                                                        <div class="w-full h-full bg-slate-900 flex flex-col items-center justify-center text-indigo-400">
                                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11v11.78a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                                                        </div>
                                                    <?php else: ?>
                                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                </div>

                                                <!-- Editable Title Input and metadata -->
                                                <div class="flex-1 min-w-0 pr-2">
                                                    <div class="flex items-center space-x-1.5">
                                                        <input type="text"
                                                            wire:model="uploadTitles.<?php echo e($index); ?>"
                                                            placeholder="Enter file title..."
                                                            class="w-full text-xs font-semibold py-1 px-2.5 rounded-lg border border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-2xs transition" />
                                                    </div>
                                                    <div class="flex items-center space-x-2 text-[10px] text-gray-400 mt-1 pl-0.5">
                                                        <span class="truncate max-w-[180px]" title="<?php echo e($u->getClientOriginalName()); ?>"><?php echo e($u->getClientOriginalName()); ?></span>
                                                        <span>&bull;</span>
                                                        <span class="uppercase font-mono font-semibold text-indigo-600 dark:text-indigo-400"><?php echo e($u->getClientOriginalExtension()); ?></span>
                                                        <span>&bull;</span>
                                                        <span><?php echo e(round($u->getSize() / 1024, 1)); ?> KB</span>
                                                        <span>&bull;</span>
                                                        <span class="text-gray-500">Folder: <strong class="text-gray-700 dark:text-gray-300 capitalize"><?php echo e($uploadFolder); ?></strong></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Remove action before save -->
                                            <button type="button"
                                                wire:click="removeUpload(<?php echo e($index); ?>)"
                                                title="Remove file"
                                                class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-lg transition shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['uploads.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="p-3 bg-red-50 text-red-700 text-xs rounded-xl font-medium">
                                <?php echo e($message); ?>

                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <!-- TAB 4: TRASH (SOFT DELETED MEDIA) -->
                <!--[if BLOCK]><![endif]--><?php if($tab === 'trash'): ?>
                    <div class="space-y-6">
                        <!-- Trash Header -->
                        <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-gray-200 dark:border-zinc-800">
                            <div>
                                <div class="flex items-center space-x-2">
                                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Trash Bin</h3>
                                    <!--[if BLOCK]><![endif]--><?php if($trashedCount > 0): ?>
                                        <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-rose-100 dark:bg-rose-950/80 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-900">
                                            <?php echo e($trashedCount); ?> deleted
                                        </span>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    Soft-deleted media is safely preserved here. Restore anytime without database load, or permanently delete forever.
                                </p>
                            </div>

                            <!-- Bulk Actions -->
                            <!--[if BLOCK]><![endif]--><?php if($trashedCount > 0): ?>
                                <div class="flex items-center space-x-2">
                                    <button type="button"
                                        wire:click="restoreAllTrash"
                                        wire:confirm="Restore all soft-deleted files<?php echo e($trashFolder ? ' in folder ' . $trashFolder : ''); ?>?"
                                        class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-xs transition flex items-center space-x-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        <span>Restore All</span>
                                    </button>

                                    <button type="button"
                                        wire:click="emptyTrash"
                                        wire:confirm="PERMANENT DELETION: Are you sure you want to empty trash<?php echo e($trashFolder ? ' for folder ' . $trashFolder : ''); ?>? This will permanently delete the files from server storage."
                                        class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-xl shadow-xs transition flex items-center space-x-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        <span>Empty Trash</span>
                                    </button>
                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <!-- Trash Folder Filters (by folder of deleted items) -->
                        <!--[if BLOCK]><![endif]--><?php if($trashedFolders && count($trashedFolders) > 0): ?>
                            <div class="flex items-center space-x-2 overflow-x-auto pb-1">
                                <span class="text-xs font-bold text-gray-500 shrink-0">Deleted from Folders:</span>
                                
                                <button type="button"
                                    wire:click="setTrashFolder(null)"
                                    class="px-3 py-1 rounded-xl text-xs font-semibold transition flex items-center space-x-1.5 shrink-0 <?php echo e(is_null($trashFolder) ? 'bg-indigo-600 text-white shadow-xs' : 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200'); ?>">
                                    <span>All Folders</span>
                                    <span class="px-1.5 py-0.2 rounded-full text-[10px] <?php echo e(is_null($trashFolder) ? 'bg-white/20 text-white' : 'bg-gray-200 dark:bg-zinc-700'); ?>"><?php echo e($trashedCount); ?></span>
                                </button>

                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $trashedFolders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <button type="button"
                                        wire:key="trash-folder-<?php echo e($tf['name']); ?>"
                                        wire:click="setTrashFolder('<?php echo e($tf['name']); ?>')"
                                        class="px-3 py-1 rounded-xl text-xs font-semibold transition flex items-center space-x-1.5 shrink-0 <?php echo e($trashFolder === $tf['name'] ? 'bg-indigo-600 text-white shadow-xs' : 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200'); ?>">
                                        <svg class="w-3.5 h-3.5 <?php echo e($trashFolder === $tf['name'] ? 'text-white' : 'text-indigo-500'); ?>" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                                        <span class="capitalize"><?php echo e($tf['name']); ?></span>
                                        <span class="px-1.5 py-0.2 rounded-full text-[10px] <?php echo e($trashFolder === $tf['name'] ? 'bg-white/20 text-white' : 'bg-gray-200 dark:bg-zinc-700'); ?>"><?php echo e($tf['count']); ?></span>
                                    </button>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <!-- Trashed Media Items Grid -->
                        <!--[if BLOCK]><![endif]--><?php if($trashedItems && $trashedItems->count() > 0): ?>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3.5">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $trashedItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div wire:key="trashed-item-<?php echo e($item->id); ?>"
                                        wire:click="selectTrashMedia(<?php echo e($item->id); ?>)"
                                        class="group relative rounded-xl overflow-hidden border cursor-pointer transition-all duration-200 bg-gray-50 dark:bg-zinc-800/60 <?php echo e($selectedTrashId === $item->id ? 'border-rose-500 ring-2 ring-rose-400 shadow-md' : 'border-gray-200 dark:border-zinc-700 hover:border-rose-300 hover:shadow-xs'); ?>">
                                        
                                        <!-- Thumbnail -->
                                        <div class="aspect-square w-full relative overflow-hidden bg-gray-100 dark:bg-zinc-800 flex items-center justify-center">
                                            <!--[if BLOCK]><![endif]--><?php if($item->type === 'image'): ?>
                                                <img src="<?php echo e(asset('storage/media/thumb/' . $item->path)); ?>"
                                                    alt="<?php echo e($item->title); ?>"
                                                    class="w-full h-full object-cover grayscale-50 group-hover:grayscale-0 transition duration-300"
                                                    loading="lazy" />
                                            <?php elseif($item->type === 'gif'): ?>
                                                <img src="<?php echo e(asset('storage/media/gif/' . $item->path)); ?>"
                                                    alt="<?php echo e($item->title); ?>"
                                                    class="w-full h-full object-cover grayscale-50 group-hover:grayscale-0 transition duration-300" />
                                            <?php elseif($item->type === 'video'): ?>
                                                <div class="w-full h-full bg-slate-900 flex flex-col items-center justify-center text-white">
                                                    <svg class="w-8 h-8 text-rose-400" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11v11.78a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                                                </div>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                            <!-- Trashed Badge -->
                                            <span class="absolute top-2 left-2 bg-rose-600/90 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full uppercase tracking-wider backdrop-blur-xs">
                                                Deleted
                                            </span>

                                            <!-- Folder Tag -->
                                            <span class="absolute bottom-2 left-2 bg-black/60 text-white text-[9px] font-medium px-1.5 py-0.5 rounded-md backdrop-blur-xs capitalize">
                                                📁 <?php echo e($item->folder); ?>

                                            </span>
                                        </div>

                                        <!-- Item Info & Quick Actions -->
                                        <div class="p-2.5 bg-white dark:bg-zinc-800 border-t border-gray-100 dark:border-zinc-700/60">
                                            <p class="text-xs font-semibold text-gray-800 dark:text-gray-200 truncate" title="<?php echo e($item->title); ?>">
                                                <?php echo e($item->title); ?>

                                            </p>
                                            <div class="flex items-center justify-between text-[10px] text-gray-400 mt-1">
                                                <span><?php echo e($item->deleted_at ? $item->deleted_at->diffForHumans() : 'Deleted'); ?></span>
                                                <span class="uppercase font-mono"><?php echo e($item->extension); ?></span>
                                            </div>

                                            <!-- Quick Buttons: Restore & Delete Forever -->
                                            <div class="mt-2.5 pt-2 border-t border-gray-100 dark:border-zinc-700/40 flex items-center justify-between gap-1">
                                                <button type="button"
                                                    wire:click.stop="restoreMedia(<?php echo e($item->id); ?>)"
                                                    title="Restore file"
                                                    class="flex-1 py-1 px-1.5 bg-emerald-50 dark:bg-emerald-950/40 hover:bg-emerald-100 text-emerald-700 dark:text-emerald-400 text-[10px] font-bold rounded-lg transition flex items-center justify-center space-x-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                    <span>Restore</span>
                                                </button>

                                                <button type="button"
                                                    wire:click.stop="forceDeleteMedia(<?php echo e($item->id); ?>)"
                                                    wire:confirm="Are you sure you want to permanently delete '<?php echo e($item->title); ?>'? This cannot be undone."
                                                    title="Delete forever from server"
                                                    class="p-1 text-gray-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-lg transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            <!-- Pagination -->
                            <div class="mt-6">
                                <?php echo e($trashedItems->links()); ?>

                            </div>
                        <?php else: ?>
                            <!-- Empty Trash State -->
                            <div class="text-center py-20">
                                <div class="w-16 h-16 mx-auto mb-4 bg-emerald-50 dark:bg-emerald-950/50 rounded-2xl flex items-center justify-center text-emerald-600">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <h4 class="text-base font-semibold text-gray-700 dark:text-gray-300">Trash is Empty</h4>
                                <p class="text-xs text-gray-500 mt-1">There are no soft-deleted media files in this folder.</p>
                                <button type="button" wire:click="setTab('all')" class="mt-4 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm">
                                    Back to All Media
                                </button>
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <!-- Right Details Sidebar (When a media item is selected) -->
            <!--[if BLOCK]><![endif]--><?php if($tab === 'all' && $selectedMedia): ?>
                <div class="w-full md:w-80 border-t md:border-t-0 md:border-l border-gray-200 dark:border-zinc-800 p-5 bg-gray-50/50 dark:bg-zinc-900/60 overflow-y-auto flex flex-col justify-between shrink-0">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500">File Details</h4>
                            <button type="button" wire:click="selectMedia(<?php echo e($selectedMedia->id); ?>)" class="text-gray-400 hover:text-gray-600 text-xs">
                                Deselect
                            </button>
                        </div>

                        <!-- Media Preview -->
                        <div class="aspect-video w-full rounded-xl overflow-hidden bg-black/5 dark:bg-white/5 border border-gray-200 dark:border-zinc-700 flex items-center justify-center">
                            <!--[if BLOCK]><![endif]--><?php if($selectedMedia->type === 'image'): ?>
                                <img src="<?php echo e(asset('storage/media/small/' . $selectedMedia->path)); ?>"
                                    alt="<?php echo e($selectedMedia->title); ?>"
                                    class="max-h-full max-w-full object-contain" />
                            <?php elseif($selectedMedia->type === 'gif'): ?>
                                <img src="<?php echo e(asset('storage/media/gif/' . $selectedMedia->path)); ?>"
                                    alt="<?php echo e($selectedMedia->title); ?>"
                                    class="max-h-full max-w-full object-contain" />
                            <?php elseif($selectedMedia->type === 'video'): ?>
                                <video controls class="max-h-full max-w-full">
                                    <source src="<?php echo e(asset('storage/media/videos/' . $selectedMedia->path)); ?>" type="<?php echo e($selectedMedia->mime_type); ?>">
                                    Your browser does not support the video tag.
                                </video>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <!-- Meta Info -->
                        <div class="space-y-2 text-xs">
                            <div>
                                <p class="text-gray-400 text-[10px] uppercase font-bold">Title</p>
                                <p class="font-semibold text-gray-800 dark:text-gray-200 break-words"><?php echo e($selectedMedia->title); ?></p>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <p class="text-gray-400 text-[10px] uppercase font-bold">Type</p>
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold uppercase <?php echo e($selectedMedia->type === 'video' ? 'bg-indigo-100 text-indigo-800' : ($selectedMedia->type === 'gif' ? 'bg-pink-100 text-pink-800' : 'bg-emerald-100 text-emerald-800')); ?>">
                                        <?php echo e($selectedMedia->type); ?>

                                    </span>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-[10px] uppercase font-bold">Folder</p>
                                    <span class="font-mono text-gray-700 dark:text-gray-300"><?php echo e($selectedMedia->folder); ?></span>
                                </div>
                            </div>
                            <div>
                                <p class="text-gray-400 text-[10px] uppercase font-bold">Size</p>
                                <p class="font-mono text-gray-700 dark:text-gray-300">
                                    <?php echo e($selectedMedia->sizes['original'] ?? round($selectedMedia->size / 1024, 1) . ' KB'); ?>

                                </p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-[10px] uppercase font-bold">Direct URL</p>
                                <div class="flex items-center space-x-1 mt-1">
                                    <input type="text"
                                        readonly
                                        value="<?php echo e($selectedMedia->url); ?>"
                                        class="flex-1 text-[11px] px-2 py-1 rounded bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 text-gray-600 dark:text-gray-400 truncate font-mono" />
                                    <button type="button"
                                        @click="copyUrl('<?php echo e($selectedMedia->url); ?>')"
                                        class="px-2 py-1 bg-gray-200 dark:bg-zinc-700 hover:bg-gray-300 text-[11px] rounded font-medium">
                                        <span x-show="!copied">Copy</span>
                                        <span x-show="copied" class="text-emerald-600 font-bold">Copied!</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Actions -->
                    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-zinc-800 space-y-2">
                        <!--[if BLOCK]><![endif]--><?php if($isPicker): ?>
                            <button type="button"
                                wire:click="confirmSelection"
                                class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center justify-center space-x-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Insert / Select Media</span>
                            </button>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <button type="button"
                            wire:click="deleteMedia(<?php echo e($selectedMedia->id); ?>)"
                            wire:confirm="Move this media to Trash?"
                            class="w-full py-2 px-3 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30 text-xs font-semibold rounded-lg transition text-center flex items-center justify-center space-x-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span>Move to Trash</span>
                        </button>
                    </div>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!-- Right Details Sidebar (When a trashed media item is selected) -->
            <!--[if BLOCK]><![endif]--><?php if($tab === 'trash' && $selectedTrashMedia): ?>
                <div class="w-full md:w-80 border-t md:border-t-0 md:border-l border-gray-200 dark:border-zinc-800 p-5 bg-gray-50/50 dark:bg-zinc-900/60 overflow-y-auto flex flex-col justify-between shrink-0">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-rose-500 flex items-center space-x-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                <span>Trashed File</span>
                            </span>
                            <button type="button" wire:click="selectTrashMedia(<?php echo e($selectedTrashMedia->id); ?>)" class="text-gray-400 hover:text-gray-600 text-xs">
                                Deselect
                            </button>
                        </div>

                        <!-- Media Preview -->
                        <div class="aspect-video w-full rounded-xl overflow-hidden bg-black/5 dark:bg-white/5 border border-gray-200 dark:border-zinc-700 flex items-center justify-center">
                            <!--[if BLOCK]><![endif]--><?php if($selectedTrashMedia->type === 'image'): ?>
                                <img src="<?php echo e(asset('storage/media/small/' . $selectedTrashMedia->path)); ?>"
                                    alt="<?php echo e($selectedTrashMedia->title); ?>"
                                    class="max-h-full max-w-full object-contain grayscale-50" />
                            <?php elseif($selectedTrashMedia->type === 'gif'): ?>
                                <img src="<?php echo e(asset('storage/media/gif/' . $selectedTrashMedia->path)); ?>"
                                    alt="<?php echo e($selectedTrashMedia->title); ?>"
                                    class="max-h-full max-w-full object-contain" />
                            <?php elseif($selectedTrashMedia->type === 'video'): ?>
                                <video controls class="max-h-full max-w-full">
                                    <source src="<?php echo e(asset('storage/media/videos/' . $selectedTrashMedia->path)); ?>" type="<?php echo e($selectedTrashMedia->mime_type); ?>">
                                </video>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        <!-- Meta Info -->
                        <div class="space-y-2 text-xs">
                            <div>
                                <p class="text-gray-400 text-[10px] uppercase font-bold">Title</p>
                                <p class="font-semibold text-gray-800 dark:text-gray-200 break-words"><?php echo e($selectedTrashMedia->title); ?></p>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <p class="text-gray-400 text-[10px] uppercase font-bold">Folder</p>
                                    <span class="font-mono text-gray-700 dark:text-gray-300 capitalize"><?php echo e($selectedTrashMedia->folder); ?></span>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-[10px] uppercase font-bold">Deleted</p>
                                    <span class="text-gray-700 dark:text-gray-300 text-[11px]"><?php echo e($selectedTrashMedia->deleted_at ? $selectedTrashMedia->deleted_at->diffForHumans() : '-'); ?></span>
                                </div>
                            </div>
                            <div>
                                <p class="text-gray-400 text-[10px] uppercase font-bold">Size</p>
                                <p class="font-mono text-gray-700 dark:text-gray-300">
                                    <?php echo e($selectedTrashMedia->sizes['original'] ?? round($selectedTrashMedia->size / 1024, 1) . ' KB'); ?>

                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Actions -->
                    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-zinc-800 space-y-2">
                        <button type="button"
                            wire:click="restoreMedia(<?php echo e($selectedTrashMedia->id); ?>)"
                            class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center justify-center space-x-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span>Restore to Active Media</span>
                        </button>

                        <button type="button"
                            wire:click="forceDeleteMedia(<?php echo e($selectedTrashMedia->id); ?>)"
                            wire:confirm="Are you sure you want to permanently delete '<?php echo e($selectedTrashMedia->title); ?>'? The files will be permanently erased from storage."
                            class="w-full py-2 px-3 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30 text-xs font-semibold rounded-lg transition text-center">
                            Delete Forever
                        </button>
                    </div>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
        </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8cc9d3143946b992b324617832699c5f)): ?>
<?php $attributes = $__attributesOriginal8cc9d3143946b992b324617832699c5f; ?>
<?php unset($__attributesOriginal8cc9d3143946b992b324617832699c5f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8cc9d3143946b992b324617832699c5f)): ?>
<?php $component = $__componentOriginal8cc9d3143946b992b324617832699c5f; ?>
<?php unset($__componentOriginal8cc9d3143946b992b324617832699c5f); ?>
<?php endif; ?>
</div>
<?php /**PATH D:\my_codes\code\my_projects_Ashik\Laravel-Ecommerce\resources\views/livewire/media.blade.php ENDPATH**/ ?>