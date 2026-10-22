<section>
    <livewire:utilities.toast-modal />

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-5">
        <!-- Breadcrumbs & Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-2 border-b border-slate-200 dark:border-zinc-800">
            <div>
                {{-- <x-admin.breadcrumb :items="[
                    ['label' => 'Dashboard', 'url' => route('dashboard')],
                    ['label' => 'Catalog'],
                    ['label' => 'Products']
                ]" class="mb-1" /> --}}
                <div class="flex items-center gap-3">
                    <h1 class="text-lg font-bold text-slate-900 dark:text-zinc-100">Product Catalog</h1>
                    <!-- Mode Indicator -->
                    @if ($viewTrash)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-xs text-[10px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                            <i data-lucide="trash-2" class="w-3 h-3"></i> Trash Archive
                        </span>
                    @endif
                </div>
                <p class="text-xs text-slate-500 dark:text-zinc-400">
                    Manage catalog products, variants, multi-column visibility, inventory stock logs, and bulk actions.
                </p>
            </div>

            <div class="flex items-center gap-2">
                @if ($viewTrash)
                    <button type="button" wire:click="$set('viewTrash', false)"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-[3px] border border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-700 dark:text-zinc-200 hover:bg-slate-50 dark:hover:bg-zinc-700 transition cursor-pointer">
                        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                        <span>Back to Active Catalog</span>
                    </button>
                @else
                    @can('product.create')
                        <a href="{{ route('backend.products.create') }}" wire:navigate 
                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold rounded-[3px] bg-brand-600 hover:bg-brand-700 text-white shadow-xs transition">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            <span>Create Product</span>
                        </a>
                    @endcan
                @endif
            </div>
        </div>

        <!-- Catalog Status Tabs (Active vs Trash) -->
        <div class="flex items-center justify-between border-b border-slate-200 dark:border-zinc-800">
            <div class="flex items-center space-x-1">
                <button type="button" wire:click="$set('viewTrash', false)"
                    class="flex items-center gap-2 px-3 py-2 text-xs font-semibold border-b-2 transition cursor-pointer {{ !$viewTrash ? 'border-brand-600 text-brand-600 dark:text-brand-400' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-zinc-300' }}">
                    <i data-lucide="package" class="w-3.5 h-3.5"></i>
                    <span>All Products</span>
                    <span class="px-1.5 py-0.2 rounded-xs text-[10px] font-mono {{ !$viewTrash ? 'bg-brand-50 text-brand-700 dark:bg-brand-950 dark:text-brand-300 font-bold' : 'bg-slate-100 text-slate-600 dark:bg-zinc-800 dark:text-zinc-400' }}">
                        {{ $activeCount }}
                    </span>
                </button>

                <button type="button" wire:click="$set('viewTrash', true)"
                    class="flex items-center gap-2 px-3 py-2 text-xs font-semibold border-b-2 transition cursor-pointer {{ $viewTrash ? 'border-rose-600 text-rose-600 dark:text-rose-400' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-zinc-300' }}">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                    <span>Trash</span>
                    <span class="px-1.5 py-0.2 rounded-xs text-[10px] font-mono {{ $viewTrash ? 'bg-rose-50 text-rose-700 dark:bg-rose-950 dark:text-rose-300 font-bold' : 'bg-slate-100 text-slate-600 dark:bg-zinc-800 dark:text-zinc-400' }}">
                        {{ $trashedCount }}
                    </span>
                </button>
            </div>

            <!-- Total Results Summary -->
            <div class="text-[11px] text-slate-400 hidden sm:block">
                Showing <span class="font-bold text-slate-700 dark:text-zinc-200">{{ $products->total() }}</span> {{ $viewTrash ? 'archived' : 'active' }} products
            </div>
        </div>

        <!-- Filter Controls & Actions Bar (Clean, Low Radius, Dynamic Theme) -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-sm p-3 shadow-2xs space-y-2.5">
            <!-- Row 1: Search & Right Actions (Columns + Reset) -->
            <div class="flex flex-col sm:flex-row gap-2.5 items-stretch sm:items-center justify-between">
                <!-- Search Input with icon and clear button -->
                <div class="relative flex-1 min-w-[260px]">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                    <input wire:model.live.debounce.300ms="search" 
                        type="text" 
                        placeholder="Search products by name, slug, barcode, or SKU..." 
                        class="w-full pl-9 pr-8 py-2 text-xs bg-slate-50/80 dark:bg-zinc-800/70 border border-slate-200 dark:border-zinc-700 rounded-xs text-slate-800 dark:text-zinc-100 placeholder-slate-400 focus:outline-none focus:bg-white dark:focus:bg-zinc-900 focus:border-brand-500 focus:ring-1 focus:ring-brand-500/20 transition">
                    @if (!empty($search))
                        <button type="button" wire:click="$set('search', '')" title="Clear search" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-zinc-200 p-0.5">
                            <i data-lucide="x" class="w-3.5 h-3.5"></i>
                        </button>
                    @endif
                </div>

                <!-- Right Action Cluster: Columns & Reset -->
                <div class="flex items-center gap-2 shrink-0">
                    <!-- ============================================== -->
                    <!-- REDESIGNED COLUMNS DROPDOWN WITH ICON & LABELS -->
                    <!-- ============================================== -->
                    <div x-data="{ open: false }" @click.outside="open = false" class="relative">
                        <button type="button" @click="open = !open; $nextTick(() => { if (window.lucide) window.lucide.createIcons(); });" 
                            class="h-9 inline-flex items-center gap-1.5 px-3 text-xs font-semibold rounded-xs border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-700 dark:text-zinc-200 hover:bg-slate-50 dark:hover:bg-zinc-700 transition cursor-pointer shadow-2xs">
                            <i data-lucide="columns-3" class="w-3.5 h-3.5 text-brand-600"></i>
                            <span>Columns</span>
                            <span class="text-[10px] font-mono bg-brand-50 dark:bg-brand-950 text-brand-700 dark:text-brand-300 font-bold px-1.5 py-0.2 rounded-xs border border-brand-200 dark:border-brand-800">
                                {{ count($visibleColumns) }}/{{ count($columns) }}
                            </span>
                            <i data-lucide="chevron-down" class="w-3 h-3 text-slate-400"></i>
                        </button>

                        <!-- Dropdown Box (Styled cleanly with max 4px radius, custom checkmarks, full width) -->
                        <div x-show="open" 
                             x-cloak
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                             class="absolute right-0 mt-1.5 w-64 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-sm shadow-xl z-50 overflow-hidden text-xs">
                            
                            <!-- Header with Quick Action Buttons -->
                            <div class="px-3 py-2 bg-slate-50 dark:bg-zinc-800/60 border-b border-slate-200 dark:border-zinc-800 flex items-center justify-between">
                                <span class="text-[11px] font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider flex items-center gap-1.5">
                                    <i data-lucide="sliders-horizontal" class="w-3 h-3 text-brand-600"></i>
                                    <span>Visible Columns</span>
                                </span>
                                <div class="flex items-center gap-1">
                                    <button type="button" wire:click="showAllColumns" title="Show all columns"
                                        class="px-1.5 py-0.5 text-[10px] font-semibold text-brand-600 dark:text-brand-400 hover:bg-brand-50 dark:hover:bg-brand-950/50 rounded-xs transition">
                                        All
                                    </button>
                                    <span class="text-slate-300 dark:text-zinc-700">|</span>
                                    <button type="button" wire:click="resetColumns" title="Reset default columns"
                                        class="px-1.5 py-0.5 text-[10px] font-semibold text-slate-500 hover:text-slate-800 dark:hover:text-zinc-200 hover:bg-slate-100 dark:hover:bg-zinc-800 rounded-xs transition">
                                        Reset
                                    </button>
                                </div>
                            </div>

                            <!-- List of Columns with Custom Checkboxes & Icons -->
                            <div class="p-1.5 space-y-0.5 max-h-72 overflow-y-auto text-left">
                                @foreach ($columns as $col)
                                    @php 
                                        $isSelected = in_array($col['key'], $visibleColumns);
                                    @endphp
                                    <button type="button"
                                        wire:click="toggleColumn('{{ $col['key'] }}')"
                                        class="w-full flex items-center justify-between px-2.5 py-1.5 text-xs rounded-xs transition text-left cursor-pointer group {{ $isSelected ? 'bg-brand-50/70 dark:bg-brand-950/40 text-brand-900 dark:text-brand-200 font-semibold' : 'text-slate-600 dark:text-zinc-400 hover:bg-slate-50 dark:hover:bg-zinc-800' }}">
                                        
                                        <div class="flex items-center gap-2.5">
                                            <!-- Reliable Clean SVG Checkbox -->
                                            <span class="w-4 h-4 rounded-xs flex items-center border transition-colors shrink-0 {{ $isSelected ? 'bg-brand-600 border-brand-600 text-white' : 'border-slate-300 dark:border-zinc-600 bg-white dark:bg-zinc-800' }}">
                                                @if ($isSelected)
                                                    <svg class="w-2.5 h-2.5 stroke-current" viewBox="0 0 12 12" fill="none">
                                                        <path d="M2.5 6L5 8.5L9.5 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                @endif
                                            </span>
                                            
                                            <!-- Column Name & Icon -->
                                            <span class="flex items-center gap-1.5">
                                                <i data-lucide="{{ $col['icon'] ?? 'circle' }}" class="w-3.5 h-3.5 {{ $isSelected ? 'text-brand-600' : 'text-slate-400' }}"></i>
                                                <span>{{ $col['label'] }}</span>
                                            </span>
                                        </div>

                                        @if ($isSelected)
                                            <span class="text-[10px] text-brand-600 dark:text-brand-400 font-mono">shown</span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Reset All Filters Button -->
                    @if ($this->activeFiltersCount > 0)
                        <button type="button" wire:click="resetFilters" title="Clear all active filters and search"
                            class="h-9 px-3 text-xs font-semibold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 border border-rose-200 dark:border-rose-900 rounded-xs transition cursor-pointer flex items-center gap-1.5 shadow-2xs">
                            <i data-lucide="rotate-ccw" class="w-3 h-3"></i>
                            <span>Reset ({{ $this->activeFiltersCount }})</span>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Row 2: Secondary Filters Strip (With Icons & Active Indicators) -->
            <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-slate-100 dark:border-zinc-800/80">
                <div class="flex items-center gap-1.5 text-slate-400 dark:text-zinc-500 text-[11px] font-semibold uppercase tracking-wider pr-1">
                    <i data-lucide="filter" class="w-3 h-3 text-slate-400"></i>
                    <span>Filters:</span>
                </div>

                <!-- Category Filter -->
                <div class="relative inline-flex items-center">
                    <i data-lucide="folder-tree" class="w-3.5 h-3.5 {{ $selectedCategory ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400' }} absolute left-2.5 pointer-events-none"></i>
                    <select wire:model.live="selectedCategory" 
                        class="h-8 pl-8 pr-7 text-xs rounded-xs border appearance-none cursor-pointer transition focus:outline-none focus:ring-1 focus:ring-brand-500/30
                        {{ $selectedCategory 
                            ? 'bg-brand-50/70 dark:bg-brand-950/40 border-brand-500/50 text-brand-700 dark:text-brand-300 font-semibold' 
                            : 'bg-slate-50/70 dark:bg-zinc-800/70 border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-200 hover:bg-white dark:hover:bg-zinc-800 hover:border-slate-300' }}">
                        <option value="">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <i data-lucide="chevron-down" class="w-3 h-3 {{ $selectedCategory ? 'text-brand-500' : 'text-slate-400' }} absolute right-2 pointer-events-none"></i>
                </div>

                <!-- Brand Filter -->
                <div class="relative inline-flex items-center">
                    <i data-lucide="tag" class="w-3.5 h-3.5 {{ $selectedBrand ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400' }} absolute left-2.5 pointer-events-none"></i>
                    <select wire:model.live="selectedBrand" 
                        class="h-8 pl-8 pr-7 text-xs rounded-xs border appearance-none cursor-pointer transition focus:outline-none focus:ring-1 focus:ring-brand-500/30
                        {{ $selectedBrand 
                            ? 'bg-brand-50/70 dark:bg-brand-950/40 border-brand-500/50 text-brand-700 dark:text-brand-300 font-semibold' 
                            : 'bg-slate-50/70 dark:bg-zinc-800/70 border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-200 hover:bg-white dark:hover:bg-zinc-800 hover:border-slate-300' }}">
                        <option value="">All Brands</option>
                        @foreach ($brands as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                    <i data-lucide="chevron-down" class="w-3 h-3 {{ $selectedBrand ? 'text-brand-500' : 'text-slate-400' }} absolute right-2 pointer-events-none"></i>
                </div>

                <!-- Stock Status Filter -->
                <div class="relative inline-flex items-center">
                    <i data-lucide="boxes" class="w-3.5 h-3.5 {{ $selectedStock ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400' }} absolute left-2.5 pointer-events-none"></i>
                    <select wire:model.live="selectedStock" 
                        class="h-8 pl-8 pr-7 text-xs rounded-xs border appearance-none cursor-pointer transition focus:outline-none focus:ring-1 focus:ring-brand-500/30
                        {{ $selectedStock 
                            ? 'bg-brand-50/70 dark:bg-brand-950/40 border-brand-500/50 text-brand-700 dark:text-brand-300 font-semibold' 
                            : 'bg-slate-50/70 dark:bg-zinc-800/70 border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-200 hover:bg-white dark:hover:bg-zinc-800 hover:border-slate-300' }}">
                        <option value="">All Stock</option>
                        <option value="in_stock">In Stock (>5)</option>
                        <option value="low_stock">Low Stock (1-5)</option>
                        <option value="out_of_stock">Out of Stock (0)</option>
                    </select>
                    <i data-lucide="chevron-down" class="w-3 h-3 {{ $selectedStock ? 'text-brand-500' : 'text-slate-400' }} absolute right-2 pointer-events-none"></i>
                </div>

                <!-- Status Filter -->
                <div class="relative inline-flex items-center">
                    <i data-lucide="toggle-right" class="w-3.5 h-3.5 {{ $selectedStatus !== '' ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400' }} absolute left-2.5 pointer-events-none"></i>
                    <select wire:model.live="selectedStatus" 
                        class="h-8 pl-8 pr-7 text-xs rounded-xs border appearance-none cursor-pointer transition focus:outline-none focus:ring-1 focus:ring-brand-500/30
                        {{ $selectedStatus !== '' 
                            ? 'bg-brand-50/70 dark:bg-brand-950/40 border-brand-500/50 text-brand-700 dark:text-brand-300 font-semibold' 
                            : 'bg-slate-50/70 dark:bg-zinc-800/70 border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-200 hover:bg-white dark:hover:bg-zinc-800 hover:border-slate-300' }}">
                        <option value="">All Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    <i data-lucide="chevron-down" class="w-3 h-3 {{ $selectedStatus !== '' ? 'text-brand-500' : 'text-slate-400' }} absolute right-2 pointer-events-none"></i>
                </div>

                <!-- Type Filter -->
                <div class="relative inline-flex items-center">
                    <i data-lucide="layers" class="w-3.5 h-3.5 {{ $selectedType ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400' }} absolute left-2.5 pointer-events-none"></i>
                    <select wire:model.live="selectedType" 
                        class="h-8 pl-8 pr-7 text-xs rounded-xs border appearance-none cursor-pointer transition focus:outline-none focus:ring-1 focus:ring-brand-500/30
                        {{ $selectedType 
                            ? 'bg-brand-50/70 dark:bg-brand-950/40 border-brand-500/50 text-brand-700 dark:text-brand-300 font-semibold' 
                            : 'bg-slate-50/70 dark:bg-zinc-800/70 border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-200 hover:bg-white dark:hover:bg-zinc-800 hover:border-slate-300' }}">
                        <option value="">All Types</option>
                        <option value="single">Single Item</option>
                        <option value="variable">Variable Item</option>
                    </select>
                    <i data-lucide="chevron-down" class="w-3 h-3 {{ $selectedType ? 'text-brand-500' : 'text-slate-400' }} absolute right-2 pointer-events-none"></i>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- TABLE CONTAINER WITH MULTI-SELECT (SHIFT & CTRL SUPPORTED)      -->
        <!-- ============================================================== -->
        <div x-data="{
            selected: @entangle('selectedProducts').live,
            allIds: @js($products->pluck('id')->toArray()),
            lastClickedIndex: null,
            selectAll: false,
            init() {
                this.checkSelectAllState();
                $watch('selected', () => this.checkSelectAllState());
            },
            toggleSelectAll() {
                if (this.selectAll) {
                    this.selected = [...this.allIds];
                } else {
                    this.selected = [];
                }
            },
            checkSelectAllState() {
                this.selectAll = this.allIds.length > 0 && this.allIds.every(id => this.selected.includes(id));
            },
            handleRowSelect(index, id, event) {
                if (event.shiftKey && this.lastClickedIndex !== null) {
                    const start = Math.min(this.lastClickedIndex, index);
                    const end = Math.max(this.lastClickedIndex, index);
                    const rangeIds = this.allIds.slice(start, end + 1);
                    const set = new Set(this.selected);
                    rangeIds.forEach(i => set.add(i));
                    this.selected = Array.from(set);
                } else {
                    const idx = this.selected.indexOf(id);
                    if (idx > -1) {
                        this.selected.splice(idx, 1);
                    } else {
                        this.selected.push(id);
                    }
                    this.lastClickedIndex = index;
                }
                this.checkSelectAllState();
            }
        }" class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-sm shadow-2xs overflow-hidden relative">

            <!-- Sticky / Floating Bulk Actions Bar (Appears when items are selected) -->
            <div x-show="selected.length > 0" 
                 x-cloak
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="p-2.5 px-4 bg-brand-50 dark:bg-brand-950/80 border-b border-brand-200 dark:border-brand-900/60 flex flex-wrap items-center justify-between gap-3 text-xs">
                
                <div class="flex items-center gap-2.5">
                    <span class="w-5 h-5 rounded-xs bg-brand-600 text-white flex items-center justify-center font-bold text-[10px]">
                        <span x-text="selected.length"></span>
                    </span>
                    <span class="font-bold text-brand-900 dark:text-brand-200">
                        Products Selected
                    </span>
                    <span class="text-[11px] text-brand-600 dark:text-brand-400">
                        (Shift+Click for range, Ctrl+Click for multiple)
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    @if (!$viewTrash)
                        <!-- Bulk Mark Active -->
                        <button type="button" wire:click="bulkUpdateStatus(true)"
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-xs bg-emerald-600 hover:bg-emerald-700 text-white transition shadow-2xs cursor-pointer">
                            <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i>
                            <span>Mark Active</span>
                        </button>

                        <!-- Bulk Mark Inactive -->
                        <button type="button" wire:click="bulkUpdateStatus(false)"
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-xs bg-slate-600 hover:bg-slate-700 text-white transition shadow-2xs cursor-pointer">
                            <i data-lucide="x-circle" class="w-3.5 h-3.5"></i>
                            <span>Mark Inactive</span>
                        </button>

                        <!-- Bulk Soft Delete -->
                        <button type="button" @click="$dispatch('modal-show', { name: 'bulk-delete-modal' })"
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-xs bg-rose-600 hover:bg-rose-700 text-white transition shadow-2xs cursor-pointer">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                            <span>Move to Trash</span>
                        </button>
                    @else
                        <!-- Bulk Restore from Trash -->
                        <button type="button" wire:click="bulkRestore"
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-xs bg-emerald-600 hover:bg-emerald-700 text-white transition shadow-2xs cursor-pointer">
                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                            <span>Restore Selected</span>
                        </button>

                        <!-- Bulk Force Delete -->
                        <button type="button" @click="$dispatch('modal-show', { name: 'bulk-delete-modal' })"
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-xs bg-rose-600 hover:bg-rose-700 text-white transition shadow-2xs cursor-pointer">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                            <span>Permanently Erase</span>
                        </button>
                    @endif

                    <!-- Deselect Button -->
                    <button type="button" @click="selected = []; checkSelectAllState()"
                        class="px-2 py-1 text-xs font-medium text-slate-500 hover:text-slate-800 dark:hover:text-zinc-200 cursor-pointer">
                        Deselect
                    </button>
                </div>
            </div>

            <!-- Table with Generous Min-Height & Low-Radius Overflow Scroll -->
            <div class="overflow-x-auto min-h-[460px]">
                <table class="w-full text-left text-xs divide-y divide-slate-200 dark:divide-zinc-800">
                    <thead class="bg-slate-50/90 dark:bg-zinc-800/70 text-[11px] font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-wider sticky top-0 z-10 backdrop-blur-xs">
                        <tr>
                            <!-- Master Multi-select Checkbox Column -->
                            <th class="w-10 px-4 py-3 text-center">
                                <input type="checkbox" 
                                    x-model="selectAll" 
                                    @change="toggleSelectAll" 
                                    title="Select all on this page"
                                    class="w-3.5 h-3.5 rounded-xs text-brand-600 border-slate-300 dark:border-zinc-600 focus:ring-brand-500/30 cursor-pointer">
                            </th>

                            @foreach ($columns as $col)
                                @if (in_array($col['key'], $visibleColumns))
                                    <th class="px-4 py-3 whitespace-nowrap {{ in_array($col['key'], ['image', 'type', 'status', 'actions']) ? 'text-center' : '' }}"
                                        @if (!empty($col['sortable'])) wire:click="sortBy('{{ $col['key'] }}')" class="cursor-pointer select-none hover:text-brand-600" @endif>
                                        <div class="inline-flex items-center gap-1">
                                            <span>{{ $col['label'] }}</span>
                                            @if ($sortField === $col['key'])
                                                <i data-lucide="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-3 h-3 text-brand-600"></i>
                                            @endif
                                        </div>
                                    </th>
                                @endif
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800/80 bg-white dark:bg-zinc-900">
                        @forelse($products as $index => $product)
                            @php
                                $isSearching = !empty($search);
                                $hasVariants = $product->has_variants && $product->variants->where('is_default', false)->isNotEmpty();
                                $variantsList = $hasVariants ? $product->variants->where('is_default', false) : collect();

                                if ($isSearching && $hasVariants) {
                                    $searchLower = strtolower(trim($search));
                                    $matching = $variantsList->filter(function($v) use ($searchLower) {
                                        if (str_contains(strtolower($v->sku ?? ''), $searchLower)) return true;
                                        if (str_contains(strtolower($v->barcode ?? ''), $searchLower)) return true;
                                        foreach ($v->variantAttributes as $va) {
                                            if (str_contains(strtolower($va->attributeValue?->value ?? ''), $searchLower)) return true;
                                            if (str_contains(strtolower($va->attribute?->name ?? ''), $searchLower)) return true;
                                        }
                                        return false;
                                    });
                                    if ($matching->isNotEmpty()) {
                                        $variantsList = $matching;
                                    }
                                }
                            @endphp

                            <!-- Single / Master Product Row -->
                            <tr wire:key="product-row-{{ $product->id }}" 
                                :class="selected.includes({{ $product->id }}) ? 'bg-brand-50/50 dark:bg-brand-950/30 font-medium' : 'hover:bg-slate-50/70 dark:hover:bg-zinc-800/40'"
                                @click="if (!$event.target.closest('button, a, input, select, label')) handleRowSelect({{ $index }}, {{ $product->id }}, $event)"
                                class="transition select-none">
                                
                                <!-- Row Checkbox (Supports Shift+Click & Ctrl+Click) -->
                                <td class="w-10 px-4 py-3.5 text-center">
                                    <input type="checkbox" 
                                        :checked="selected.includes({{ $product->id }})" 
                                        @click.stop="handleRowSelect({{ $index }}, {{ $product->id }}, $event)"
                                        class="w-3.5 h-3.5 rounded-xs text-brand-600 border-slate-300 dark:border-zinc-600 focus:ring-brand-500/30 cursor-pointer">
                                </td>

                                @foreach ($columns as $col)
                                    @if (in_array($col['key'], $visibleColumns))
                                        <td class="px-4 py-3.5 whitespace-nowrap {{ in_array($col['key'], ['image', 'type', 'status', 'actions']) ? 'text-center' : '' }}">
                                            @switch($col['key'])
                                                @case('id')
                                                    <span class="font-mono text-xs text-slate-500">#{{ $product->id }}</span>
                                                @break

                                                @case('image')
                                                    @php
                                                        $mainMedia = $product->media;
                                                        $mediaThumb = $mainMedia ? ($mainMedia->urls['thumb'] ?? $mainMedia->urls['small'] ?? $mainMedia->url) : null;
                                                    @endphp
                                                    <div class="flex justify-center">
                                                        @if ($mediaThumb)
                                                            <img src="{{ $mediaThumb }}"
                                                                alt="{{ $product->name }}"
                                                                class="h-10 w-10 rounded-xs object-cover border border-slate-200 dark:border-zinc-700 shadow-2xs">
                                                        @else
                                                            <div class="h-10 w-10 bg-slate-100 dark:bg-zinc-800 rounded-xs flex items-center justify-center text-slate-400 text-[10px] font-semibold border border-slate-200 dark:border-zinc-700">
                                                                No Img
                                                            </div>
                                                        @endif
                                                    </div>
                                                @break

                                                @case('name')
                                                    <div class="max-w-xs">
                                                        <a href="{{ route('backend.products.edit', $product->id) }}" wire:navigate 
                                                            class="font-bold text-slate-900 dark:text-zinc-100 hover:text-brand-600 transition block truncate">
                                                            {{ $product->name }}
                                                        </a>
                                                        <span class="text-[10px] text-slate-400 font-mono">{{ $product->slug }}</span>
                                                    </div>
                                                @break

                                                @case('type')
                                                    @if ($product->has_variants)
                                                        <button type="button"
                                                            wire:click.stop="toggleExpand({{ $product->id }})"
                                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-xs text-[10px] font-bold bg-purple-50 text-purple-700 dark:bg-purple-950/60 dark:text-purple-300 border border-purple-200 dark:border-purple-800 hover:bg-purple-100 cursor-pointer transition">
                                                            <i data-lucide="layers" class="w-3 h-3"></i>
                                                            <span>Variable</span>
                                                            <span class="px-1 py-0.2 rounded-xs bg-purple-200/60 dark:bg-purple-800 text-[9px]">{{ $product->variants->count() }}</span>
                                                        </button>
                                                    @else
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-xs text-[10px] font-semibold bg-slate-100 text-slate-700 dark:bg-zinc-800 dark:text-zinc-300 border border-slate-200 dark:border-zinc-700">
                                                            <i data-lucide="box" class="w-3 h-3"></i>
                                                            <span>Single</span>
                                                        </span>
                                                    @endif
                                                @break

                                                @case('category')
                                                    <span class="text-xs text-slate-700 dark:text-zinc-300 font-medium">
                                                        {{ $product->category?->name ?? 'Unassigned' }}
                                                    </span>
                                                @break

                                                @case('brand')
                                                    <span class="text-xs text-slate-500 dark:text-zinc-400">
                                                        {{ $product->brand?->name ?? '-' }}
                                                    </span>
                                                @break

                                                @case('sku')
                                                    <span class="font-mono text-xs text-slate-600 dark:text-zinc-300">
                                                        {{ $product->sku ?: '-' }}
                                                    </span>
                                                @break

                                                @case('price')
                                                    @php
                                                        $defaultVar = $product->variants->where('is_default', true)->first();
                                                        $sellingPrice = $defaultVar ? $defaultVar->selling_price : $product->price;
                                                        $discPrice = $defaultVar ? $defaultVar->discount_price : $product->discount_price;
                                                    @endphp
                                                    <div class="space-y-0.5">
                                                        <span class="font-bold text-slate-900 dark:text-zinc-100">
                                                            ${{ number_format($sellingPrice, 2) }}
                                                        </span>
                                                        @if ($discPrice > 0)
                                                            <span class="text-[10px] text-slate-400 line-through block">
                                                                ${{ number_format($discPrice, 2) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                @break

                                                @case('stock')
                                                    @php
                                                        $stockVal = $product->has_variants ? $product->variants->sum('stock') : $product->stock;
                                                    @endphp
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-xs text-[10px] font-bold font-mono border
                                                        {{ $stockVal > 5 ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-800' : '' }}
                                                        {{ $stockVal >= 1 && $stockVal <= 5 ? 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-800' : '' }}
                                                        {{ $stockVal == 0 ? 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/60 dark:text-rose-300 dark:border-rose-800' : '' }}">
                                                        {{ $stockVal }} pcs
                                                    </span>
                                                @break

                                                @case('status')
                                                    @if (!$viewTrash)
                                                        <button type="button" 
                                                            wire:click.stop="openStatusModal({{ $product->id }})" 
                                                            title="Click to manage status & visibility"
                                                            class="cursor-pointer transition hover:opacity-80">
                                                            @if ($product->status)
                                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-xs text-[10px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                                    Active
                                                                </span>
                                                            @else
                                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-xs text-[10px] font-semibold bg-slate-100 text-slate-600 dark:bg-zinc-800 dark:text-zinc-400 border border-slate-200 dark:border-zinc-700">
                                                                    Inactive
                                                                </span>
                                                            @endif
                                                        </button>
                                                    @else
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-xs text-[10px] font-semibold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 dark:border-rose-900">
                                                            Archived
                                                        </span>
                                                    @endif
                                                @break

                                                @case('actions')
                                                    <div class="flex items-center justify-center gap-1" @click.stop>
                                                        @if (!$viewTrash)
                                                            <!-- Stock Activity Log -->
                                                            <button type="button"
                                                                wire:click="viewStockHistory({{ $product->id }})"
                                                                title="View Stock Activity History"
                                                                class="p-1.5 text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 rounded-xs hover:bg-brand-50 dark:hover:bg-zinc-800 transition cursor-pointer">
                                                                <i data-lucide="history" class="w-3.5 h-3.5"></i>
                                                            </button>

                                                            @can('product.edit')
                                                                <a href="{{ route('backend.products.edit', $product->id) }}"
                                                                    wire:navigate
                                                                    title="Edit Product"
                                                                    class="p-1.5 text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 rounded-xs hover:bg-slate-100 dark:hover:bg-zinc-800 transition cursor-pointer">
                                                                    <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                                                </a>
                                                            @endcan

                                                            @can('product.delete')
                                                                <button type="button"
                                                                    wire:click="confirmDelete({{ $product->id }})"
                                                                    title="Move to Trash"
                                                                    class="p-1.5 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 rounded-xs hover:bg-rose-50 dark:hover:bg-rose-950/40 transition cursor-pointer">
                                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                                </button>
                                                            @endcan
                                                        @else
                                                            <!-- In Trash Mode: Restore and Permanent Delete -->
                                                            <button type="button"
                                                                wire:click="restore({{ $product->id }})"
                                                                title="Restore Product"
                                                                class="p-1.5 text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 rounded-xs transition cursor-pointer flex items-center gap-1 font-semibold text-[11px]">
                                                                <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                                                                <span>Restore</span>
                                                            </button>

                                                            <button type="button"
                                                                wire:click="confirmDelete({{ $product->id }})"
                                                                title="Delete Permanently"
                                                                class="p-1.5 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-xs transition cursor-pointer flex items-center gap-1 font-semibold text-[11px]">
                                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                                <span>Delete Forever</span>
                                                            </button>
                                                        @endif
                                                    </div>
                                                @break
                                            @endswitch
                                        </td>
                                    @endif
                                @endforeach
                            </tr>

                            {{-- Expanded Variants Sub-Rows --}}
                            @if ($hasVariants && in_array($product->id, $expandedProducts))
                                @foreach ($variantsList as $var)
                                    <tr wire:key="variant-row-{{ $product->id }}-{{ $var->id }}" 
                                        class="bg-purple-50/20 dark:bg-zinc-800/40 hover:bg-purple-50/40 dark:hover:bg-zinc-700/40 transition border-l-2 border-purple-500">
                                        
                                        <td class="w-10 px-4 py-2 text-center text-slate-300 dark:text-zinc-700">
                                            ↳
                                        </td>

                                        @foreach ($columns as $col)
                                            @if (in_array($col['key'], $visibleColumns))
                                                <td class="px-4 py-2 whitespace-nowrap {{ in_array($col['key'], ['image', 'type', 'status', 'actions']) ? 'text-center' : '' }}">
                                                    @switch($col['key'])
                                                        @case('id')
                                                            <span class="text-[10px] text-purple-600 dark:text-purple-400 font-mono font-semibold">var#{{ $var->id }}</span>
                                                        @break

                                                        @case('image')
                                                            @php
                                                                $displayMedia = $var->resolved_media ?: $product->media;
                                                                $mediaThumb = $displayMedia ? ($displayMedia->urls['thumb'] ?? $displayMedia->urls['small'] ?? $displayMedia->url) : null;
                                                            @endphp
                                                            <div class="flex justify-center">
                                                                @if ($mediaThumb)
                                                                    <img src="{{ $mediaThumb }}"
                                                                        alt="{{ $product->name }}"
                                                                        class="h-8 w-8 rounded-xs object-cover border border-slate-200 dark:border-zinc-700 shadow-2xs">
                                                                @else
                                                                    <div class="h-8 w-8 bg-slate-100 dark:bg-zinc-800 rounded-xs flex items-center justify-center text-slate-400 text-[9px] font-semibold">
                                                                        No Img
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @break

                                                        @case('name')
                                                            <div class="pl-2">
                                                                <span class="font-semibold text-slate-800 dark:text-zinc-200 text-xs block">
                                                                    {{ $var->attribute_summary ?: 'Default Variant' }}
                                                                </span>
                                                                <span class="text-[10px] text-slate-400 font-mono">Barcode: {{ $var->barcode ?: '-' }}</span>
                                                            </div>
                                                        @break

                                                        @case('type')
                                                            <span class="text-[10px] text-slate-400 font-mono">Variant Option</span>
                                                        @break

                                                        @case('category')
                                                            <span class="text-slate-400 text-xs">-</span>
                                                        @break

                                                        @case('brand')
                                                            <span class="text-slate-400 text-xs">-</span>
                                                        @break

                                                        @case('sku')
                                                            <span class="font-mono text-xs text-purple-700 dark:text-purple-300 font-semibold">
                                                                {{ $var->sku ?: '-' }}
                                                            </span>
                                                        @break

                                                        @case('price')
                                                            <div class="space-y-0.5">
                                                                <span class="font-bold text-slate-800 dark:text-zinc-200">
                                                                    ${{ number_format($var->selling_price, 2) }}
                                                                </span>
                                                                @if ($var->discount_price > 0)
                                                                    <span class="text-[10px] text-slate-400 line-through block">
                                                                        ${{ number_format($var->discount_price, 2) }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        @break

                                                        @case('stock')
                                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-xs text-[10px] font-mono font-bold border
                                                                {{ $var->stock > 5 ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-300' : '' }}
                                                                {{ $var->stock >= 1 && $var->stock <= 5 ? 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/60 dark:text-amber-300' : '' }}
                                                                {{ $var->stock == 0 ? 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/60 dark:text-rose-300' : '' }}">
                                                                {{ $var->stock }} pcs
                                                            </span>
                                                        @break

                                                        @case('status')
                                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.2 rounded-xs text-[10px] font-semibold {{ $var->status ? 'text-emerald-600' : 'text-slate-400' }}">
                                                                {{ $var->status ? 'Active' : 'Inactive' }}
                                                            </span>
                                                        @break

                                                        @case('actions')
                                                            <div class="text-slate-400 text-xs text-center">-</div>
                                                        @break
                                                    @endswitch
                                                </td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endif

                        @empty
                            <tr>
                                <td colspan="{{ count($visibleColumns) + 1 }}" class="px-6 py-16 text-center">
                                    <div class="w-12 h-12 rounded-xs bg-slate-100 dark:bg-zinc-800 text-slate-400 flex items-center justify-center mx-auto mb-3 border border-slate-200 dark:border-zinc-700">
                                        <i data-lucide="{{ $viewTrash ? 'trash-2' : 'package-search' }}" class="w-6 h-6"></i>
                                    </div>
                                    <h4 class="text-sm font-bold text-slate-800 dark:text-zinc-200">
                                        {{ $viewTrash ? 'Trash is Empty' : 'No Products Found' }}
                                    </h4>
                                    <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">
                                        {{ $viewTrash ? 'There are no soft-deleted products in the archive.' : 'Try adjusting your search keywords, filters, or create a new product.' }}
                                    </p>
                                    @if ($search || $selectedCategory || $selectedBrand || $selectedStock || $selectedStatus !== '' || $selectedType)
                                        <button type="button" wire:click="resetFilters" class="mt-3 inline-flex items-center gap-1 text-xs font-semibold text-brand-600 hover:underline cursor-pointer">
                                            <i data-lucide="rotate-ccw" class="w-3 h-3"></i>
                                            <span>Clear All Filters</span>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Footer with Pagination Controls -->
            <div class="px-4 py-3 bg-slate-50/60 dark:bg-zinc-800/40 border-t border-slate-200 dark:border-zinc-800 flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-zinc-400">
                    <span>Show</span>
                    <select wire:model.live="perPage" class="px-2 py-1 text-xs bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-700 rounded-xs focus:outline-none focus:border-brand-500 cursor-pointer">
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span>per page</span>
                </div>

                <div class="w-full sm:w-auto">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- STOCK ACTIVITY HISTORY MODAL                   -->
    <!-- ============================================== -->
    <x-admin.modal name="stock-history-modal" title="Stock Activity History" maxWidth="4xl">
        <div class="space-y-4">
            <p class="text-xs text-slate-500 dark:text-zinc-400">Audit log of stock movements for: <strong class="text-brand-600">{{ $historyProductName }}</strong></p>

            <div class="overflow-x-auto max-h-96 border border-slate-200 dark:border-zinc-800 rounded-xs">
                <table class="w-full text-left text-xs divide-y divide-slate-200 dark:divide-zinc-800">
                    <thead class="bg-slate-50 dark:bg-zinc-900/60 text-[11px] font-bold uppercase tracking-wider text-slate-500 dark:text-zinc-400">
                        <tr>
                            <th class="px-3.5 py-2.5">Timestamp</th>
                            <th class="px-3.5 py-2.5">Variant / Item</th>
                            <th class="px-3.5 py-2.5">Type</th>
                            <th class="px-3.5 py-2.5 text-right">Change</th>
                            <th class="px-3.5 py-2.5 text-center">Old ➔ New</th>
                            <th class="px-3.5 py-2.5">Reason</th>
                            <th class="px-3.5 py-2.5">User</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-zinc-800 bg-white dark:bg-zinc-900">
                        @forelse ($productStockLogs as $log)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-zinc-800/40 text-xs">
                                <td class="px-3.5 py-2.5 font-mono text-slate-500">
                                    {{ $log->created_at->format('M d, Y H:i:s') }}
                                </td>
                                <td class="px-3.5 py-2.5 font-semibold text-slate-800 dark:text-zinc-200">
                                    {{ $log->variant ? $log->variant->attribute_summary : 'Default' }}
                                </td>
                                <td class="px-3.5 py-2.5">
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-xs text-[10px] font-semibold
                                        {{ $log->type === 'sale' ? 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300' : '' }}
                                        {{ $log->type === 'initial' ? 'bg-sky-100 text-sky-800 dark:bg-sky-950 dark:text-sky-300' : '' }}
                                        {{ $log->type === 'manual_adjustment' ? 'bg-purple-100 text-purple-800 dark:bg-purple-950 dark:text-purple-300' : '' }}
                                        {{ $log->type === 'restock' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : '' }}">
                                        {{ ucfirst(str_replace('_', ' ', $log->type)) }}
                                    </span>
                                </td>
                                <td class="px-3.5 py-2.5 text-right font-mono font-bold {{ $log->quantity_change >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $log->quantity_change >= 0 ? '+' . $log->quantity_change : $log->quantity_change }}
                                </td>
                                <td class="px-3.5 py-2.5 text-center font-mono text-slate-600 dark:text-zinc-400">
                                    {{ $log->old_stock }} ➔ <span class="font-bold text-slate-900 dark:text-zinc-100">{{ $log->new_stock }}</span>
                                </td>
                                <td class="px-3.5 py-2.5 text-slate-600 dark:text-zinc-400">
                                    {{ $log->reason ?: '-' }}
                                </td>
                                <td class="px-3.5 py-2.5 text-slate-600 dark:text-zinc-400">
                                    {{ $log->user?->name ?: 'System' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-xs text-slate-400">
                                    No stock activity logged yet for this product.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end pt-2">
                <x-admin.button type="button" variant="ghost" @click="$dispatch('modal-close', { name: 'stock-history-modal' })">
                    Close
                </x-admin.button>
            </div>
        </div>
    </x-admin.modal>

    <!-- ============================================== -->
    <!-- SINGLE DELETE CONFIRMATION MODAL               -->
    <!-- ============================================== -->
    <x-admin.modal name="delete-modal" title="Delete Product" maxWidth="md">
        <div class="space-y-4 text-center">
            <div class="w-10 h-10 rounded-xs bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 mx-auto flex items-center justify-center">
                <i data-lucide="trash-2" class="w-5 h-5"></i>
            </div>
            <div>
                <h4 class="font-bold text-sm text-slate-800 dark:text-zinc-100">
                    {{ $viewTrash ? 'Permanently Erase Product?' : 'Move Product to Trash?' }}
                </h4>
                <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">
                    @if ($viewTrash)
                        This will permanently delete this product and its associated variants and images from storage. This action cannot be reversed!
                    @else
                        This product and all its variants will be moved to the Trash archive. You can restore them anytime.
                    @endif
                </p>
            </div>
            <div class="flex justify-center gap-2 pt-2">
                <x-admin.button type="button" variant="ghost" @click="$dispatch('modal-close', { name: 'delete-modal' })">
                    Cancel
                </x-admin.button>
                <x-admin.button wire:click="delete" type="button" variant="danger">
                    {{ $viewTrash ? 'Delete Permanently' : 'Move to Trash' }}
                </x-admin.button>
            </div>
        </div>
    </x-admin.modal>

    <!-- ============================================== -->
    <!-- BULK DELETE CONFIRMATION MODAL                 -->
    <!-- ============================================== -->
    <x-admin.modal name="bulk-delete-modal" title="Bulk Delete Products" maxWidth="md">
        <div class="space-y-4 text-center">
            <div class="w-10 h-10 rounded-xs bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 mx-auto flex items-center justify-center">
                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
            </div>
            <div>
                <h4 class="font-bold text-sm text-slate-800 dark:text-zinc-100">
                    Confirm Bulk Action
                </h4>
                <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1">
                    @if ($viewTrash)
                        Are you sure you want to permanently erase all selected products and their variants? This action cannot be undone.
                    @else
                        Are you sure you want to move all selected products and their variants to the Trash archive?
                    @endif
                </p>
            </div>
            <div class="flex justify-center gap-2 pt-2">
                <x-admin.button type="button" variant="ghost" @click="$dispatch('modal-close', { name: 'bulk-delete-modal' })">
                    Cancel
                </x-admin.button>
                <x-admin.button wire:click="bulkDelete" type="button" variant="danger">
                    {{ $viewTrash ? 'Erase Forever' : 'Move to Trash' }}
                </x-admin.button>
            </div>
        </div>
    </x-admin.modal>

    <!-- ============================================== -->
    <!-- PRODUCT STATUS & QUICK TRASH MODAL             -->
    <!-- ============================================== -->
    <x-admin.modal name="product-status-modal" title="Product Visibility & Status" maxWidth="md">
        <div class="space-y-4">
            <!-- Product Brief Header Card -->
            <div class="p-3 rounded-xs border border-slate-200 dark:border-zinc-800 bg-slate-50/60 dark:bg-zinc-800/40 flex items-center gap-3">
                @if ($statusModalProductImage)
                    <img src="{{ $statusModalProductImage }}" alt="{{ $statusModalProductName }}" class="w-11 h-11 rounded-xs object-cover border border-slate-200 dark:border-zinc-700 shrink-0">
                @else
                    <div class="w-11 h-11 rounded-xs bg-slate-200 dark:bg-zinc-700 flex items-center justify-center text-slate-400 shrink-0">
                        <i data-lucide="package" class="w-5 h-5"></i>
                    </div>
                @endif
                <div class="min-w-0 flex-1">
                    <h4 class="text-xs font-bold text-slate-900 dark:text-zinc-100 truncate leading-tight">
                        {{ $statusModalProductName }}
                    </h4>
                    <div class="flex items-center gap-2 mt-1 text-[11px] text-slate-500 dark:text-zinc-400">
                        <span class="font-mono">SKU: {{ $statusModalProductSku }}</span>
                        <span>•</span>
                        <span>Stock: {{ $statusModalProductStock }} pcs</span>
                    </div>
                </div>
            </div>

            <!-- Status Choice Radio Cards -->
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider">
                    Select Public Status
                </label>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <!-- Option 1: Active -->
                    <label class="relative flex flex-col p-3 rounded-xs border cursor-pointer transition select-none
                        {{ $statusModalSelectedStatus == 1 
                            ? 'border-emerald-500 bg-emerald-50/40 dark:bg-emerald-950/20 ring-1 ring-emerald-500/30' 
                            : 'border-slate-200 dark:border-zinc-700 hover:border-slate-300 dark:hover:border-zinc-600 bg-white dark:bg-zinc-900' }}">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 dark:text-emerald-400">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Active
                            </span>
                            <input type="radio" wire:model="statusModalSelectedStatus" value="1" class="text-emerald-600 focus:ring-emerald-500/20">
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-zinc-400 mt-1.5 leading-snug">
                            Published & live. Visible on storefront and ready for customer orders.
                        </p>
                    </label>

                    <!-- Option 2: Inactive -->
                    <label class="relative flex flex-col p-3 rounded-xs border cursor-pointer transition select-none
                        {{ $statusModalSelectedStatus == 0 
                            ? 'border-slate-500 bg-slate-100/60 dark:bg-zinc-800 ring-1 ring-slate-500/30' 
                            : 'border-slate-200 dark:border-zinc-700 hover:border-slate-300 dark:hover:border-zinc-600 bg-white dark:bg-zinc-900' }}">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-700 dark:text-zinc-300">
                                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                Inactive
                            </span>
                            <input type="radio" wire:model="statusModalSelectedStatus" value="0" class="text-slate-600 focus:ring-slate-500/20">
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-zinc-400 mt-1.5 leading-snug">
                            Hidden from customer catalog. Unpurchasable on storefront.
                        </p>
                    </label>
                </div>
            </div>

            <!-- Sync Variants Option -->
            <label class="flex items-center gap-2 cursor-pointer p-2 rounded-xs bg-slate-50 dark:bg-zinc-800/50 border border-slate-200 dark:border-zinc-700/60">
                <input type="checkbox" wire:model="statusModalSyncVariants" class="rounded-[2px] border-slate-300 text-brand-600 focus:ring-brand-500/20">
                <span class="text-xs text-slate-700 dark:text-zinc-300">
                    Apply this status to all product variants automatically
                </span>
            </label>

            <!-- Bottom Actions Bar: Move to Trash (Left) & Cancel/Save (Right) -->
            <div class="flex flex-col-reverse sm:flex-row sm:items-center justify-between gap-2.5 pt-3 border-t border-slate-100 dark:border-zinc-800">
                <!-- Move to Trash Button -->
                <button type="button" 
                    wire:click="moveProductToTrashFromModal" 
                    wire:loading.attr="disabled"
                    class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-xs text-xs font-semibold text-rose-600 hover:text-white bg-rose-50 hover:bg-rose-600 dark:bg-rose-950/40 dark:hover:bg-rose-600 border border-rose-200 dark:border-rose-900/60 transition cursor-pointer">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                    <span>Move to Trash</span>
                </button>

                <!-- Cancel & Save Status Buttons -->
                <div class="flex items-center justify-end gap-2">
                    <x-admin.button type="button" variant="ghost" size="sm" @click="$dispatch('modal-close', { name: 'product-status-modal' })">
                        Cancel
                    </x-admin.button>
                    <x-admin.button type="button" wire:click="saveProductStatus" variant="primary" size="sm" icon="check">
                        <span wire:loading.remove wire:target="saveProductStatus">Save Status</span>
                        <span wire:loading wire:target="saveProductStatus">Updating...</span>
                    </x-admin.button>
                </div>
            </div>
        </div>
    </x-admin.modal>
</section>
