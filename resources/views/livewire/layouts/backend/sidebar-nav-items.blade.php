<div class="space-y-4" wire:poll.10s>
    {{-- Group 1: Platform --}}
    <div>
        <div class="px-2 pt-2 pb-1 text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest sidebar-text">
            {{ __('Platform') }}
        </div>

        <div class="space-y-0.5">
            {{-- Dashboard --}}
            @php $isDash = request()->routeIs('dashboard'); @endphp
            <div class="relative rail-item group">
                <a href="{{ route('dashboard') }}" wire:navigate
                   class="flex items-center gap-2.5 px-2.5 py-2 rounded-sm text-xs transition-colors border-l-2 {{ $isDash ? 'font-semibold bg-brand-50/80 dark:bg-brand-950/40 text-brand-700 dark:text-brand-400 border-brand-600' : 'font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100/80 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white border-transparent' }}">
                    <i data-lucide="layout-grid" class="w-4 h-4 shrink-0 {{ $isDash ? 'text-brand-600 dark:text-brand-400' : 'text-slate-500 dark:text-slate-400' }}"></i>
                    <span class="sidebar-text truncate">{{ __('Dashboard') }}</span>
                </a>
                <span class="rail-tooltip hidden bg-slate-900 dark:bg-slate-800 text-white text-[11px] font-medium py-1 px-2 rounded-xs shadow-md border border-slate-700">
                    {{ __('Dashboard') }}
                </span>
            </div>

            {{-- Media Library --}}
            <div class="relative rail-item group">
                <button type="button" wire:click.prevent="$dispatch('open-media-modal', { isPicker: false })"
                        class="w-full flex items-center gap-2.5 px-2.5 py-2 rounded-sm text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100/80 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white transition-colors border-l-2 border-transparent cursor-pointer">
                    <i data-lucide="images" class="w-4 h-4 shrink-0 text-slate-500 dark:text-slate-400"></i>
                    <span class="sidebar-text truncate text-left flex-1">{{ __('Media Library') }}</span>
                    @if ($totalMedia > 0)
                        <span class="sidebar-text ml-auto text-[10px] font-mono bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 px-1.5 py-0.5 rounded-xs border border-slate-200 dark:border-slate-700">
                            {{ $totalMedia }}
                        </span>
                    @endif
                </button>
                <span class="rail-tooltip hidden bg-slate-900 dark:bg-slate-800 text-white text-[11px] font-medium py-1 px-2 rounded-xs shadow-md border border-slate-700">
                    {{ __('Media Library') }} ({{ $totalMedia }})
                </span>
            </div>
        </div>
    </div>

    {{-- Group 2: Shop & Catalog --}}
    <div>
        <div class="px-2 pt-2 pb-1 text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest sidebar-text">
            {{ __('Shop & Catalog') }}
        </div>

        <div class="space-y-0.5">
            {{-- Categories --}}
            @can ('category.view')
                @php $isCat = request()->routeIs('backend.categories.*'); @endphp
                <div class="relative rail-item group">
                    <a href="{{ route('backend.categories.index') }}" wire:navigate
                       class="flex items-center gap-2.5 px-2.5 py-2 rounded-sm text-xs transition-colors border-l-2 {{ $isCat ? 'font-semibold bg-brand-50/80 dark:bg-brand-950/40 text-brand-700 dark:text-brand-400 border-brand-600' : 'font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100/80 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white border-transparent' }}">
                        <i data-lucide="folder-tree" class="w-4 h-4 shrink-0 {{ $isCat ? 'text-brand-600 dark:text-brand-400' : 'text-slate-500 dark:text-slate-400' }}"></i>
                        <span class="sidebar-text truncate flex-1">{{ __('Categories') }}</span>
                        @if ($totalCategory > 0)
                            <span class="sidebar-text ml-auto text-[10px] font-mono bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 px-1.5 py-0.5 rounded-xs border border-slate-200 dark:border-slate-700">
                                {{ $totalCategory }}
                            </span>
                        @endif
                    </a>
                    <span class="rail-tooltip hidden bg-slate-900 dark:bg-slate-800 text-white text-[11px] font-medium py-1 px-2 rounded-xs shadow-md border border-slate-700">
                        {{ __('Categories') }} ({{ $totalCategory }})
                    </span>
                </div>
            @endcan

            {{-- Sub-Categories --}}
            @can ('subcategory.view')
                @php $isSubCat = request()->routeIs('backend.subcategories.*'); @endphp
                <div class="relative rail-item group">
                    <a href="{{ route('backend.subcategories.index') }}" wire:navigate
                       class="flex items-center gap-2.5 px-2.5 py-2 rounded-sm text-xs transition-colors border-l-2 {{ $isSubCat ? 'font-semibold bg-brand-50/80 dark:bg-brand-950/40 text-brand-700 dark:text-brand-400 border-brand-600' : 'font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100/80 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white border-transparent' }}">
                        <i data-lucide="git-fork" class="w-4 h-4 shrink-0 {{ $isSubCat ? 'text-brand-600 dark:text-brand-400' : 'text-slate-500 dark:text-slate-400' }}"></i>
                        <span class="sidebar-text truncate flex-1">{{ __('Sub-Categories') }}</span>
                        @if ($totalSubCategory > 0)
                            <span class="sidebar-text ml-auto text-[10px] font-mono bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 px-1.5 py-0.5 rounded-xs border border-slate-200 dark:border-slate-700">
                                {{ $totalSubCategory }}
                            </span>
                        @endif
                    </a>
                    <span class="rail-tooltip hidden bg-slate-900 dark:bg-slate-800 text-white text-[11px] font-medium py-1 px-2 rounded-xs shadow-md border border-slate-700">
                        {{ __('Sub-Categories') }} ({{ $totalSubCategory }})
                    </span>
                </div>
            @endcan

            {{-- Brands --}}
            @can ('brand.view')
                @php $isBrand = request()->routeIs('backend.brands.*'); @endphp
                <div class="relative rail-item group">
                    <a href="{{ route('backend.brands.index') }}" wire:navigate
                       class="flex items-center gap-2.5 px-2.5 py-2 rounded-sm text-xs transition-colors border-l-2 {{ $isBrand ? 'font-semibold bg-brand-50/80 dark:bg-brand-950/40 text-brand-700 dark:text-brand-400 border-brand-600' : 'font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100/80 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white border-transparent' }}">
                        <i data-lucide="tag" class="w-4 h-4 shrink-0 {{ $isBrand ? 'text-brand-600 dark:text-brand-400' : 'text-slate-500 dark:text-slate-400' }}"></i>
                        <span class="sidebar-text truncate flex-1">{{ __('Brands') }}</span>
                        @if ($totalBrands > 0)
                            <span class="sidebar-text ml-auto text-[10px] font-mono bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 px-1.5 py-0.5 rounded-xs border border-slate-200 dark:border-slate-700">
                                {{ $totalBrands }}
                            </span>
                        @endif
                    </a>
                    <span class="rail-tooltip hidden bg-slate-900 dark:bg-slate-800 text-white text-[11px] font-medium py-1 px-2 rounded-xs shadow-md border border-slate-700">
                        {{ __('Brands') }} ({{ $totalBrands }})
                    </span>
                </div>
            @endcan

            {{-- Attributes --}}
            @can ('attribute.view')
                @php $isAttr = request()->routeIs('backend.attributes.*'); @endphp
                <div class="relative rail-item group">
                    <a href="{{ route('backend.attributes.index') }}" wire:navigate
                       class="flex items-center gap-2.5 px-2.5 py-2 rounded-sm text-xs transition-colors border-l-2 {{ $isAttr ? 'font-semibold bg-brand-50/80 dark:bg-brand-950/40 text-brand-700 dark:text-brand-400 border-brand-600' : 'font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100/80 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white border-transparent' }}">
                        <i data-lucide="sliders" class="w-4 h-4 shrink-0 {{ $isAttr ? 'text-brand-600 dark:text-brand-400' : 'text-slate-500 dark:text-slate-400' }}"></i>
                        <span class="sidebar-text truncate flex-1">{{ __('Attributes') }}</span>
                        @if ($totalAttributes > 0)
                            <span class="sidebar-text ml-auto text-[10px] font-mono bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 px-1.5 py-0.5 rounded-xs border border-slate-200 dark:border-slate-700">
                                {{ $totalAttributes }}
                            </span>
                        @endif
                    </a>
                    <span class="rail-tooltip hidden bg-slate-900 dark:bg-slate-800 text-white text-[11px] font-medium py-1 px-2 rounded-xs shadow-md border border-slate-700">
                        {{ __('Attributes') }} ({{ $totalAttributes }})
                    </span>
                </div>
            @endcan

            {{-- Products --}}
            @can ('product.view')
                @php $isProd = request()->routeIs('backend.products.*'); @endphp
                <div class="relative rail-item group">
                    <a href="{{ route('backend.products.index') }}" wire:navigate
                       class="flex items-center gap-2.5 px-2.5 py-2 rounded-sm text-xs transition-colors border-l-2 {{ $isProd ? 'font-semibold bg-brand-50/80 dark:bg-brand-950/40 text-brand-700 dark:text-brand-400 border-brand-600' : 'font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100/80 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white border-transparent' }}">
                        <i data-lucide="package" class="w-4 h-4 shrink-0 {{ $isProd ? 'text-brand-600 dark:text-brand-400' : 'text-slate-500 dark:text-slate-400' }}"></i>
                        <span class="sidebar-text truncate flex-1">{{ __('Products') }}</span>
                        @if ($totalProducts > 0)
                            <span class="sidebar-text ml-auto text-[10px] font-mono bg-brand-50 dark:bg-brand-950 text-brand-600 dark:text-brand-400 font-semibold px-1.5 py-0.5 rounded-xs border border-brand-200 dark:border-brand-800">
                                {{ $totalProducts }}
                            </span>
                        @endif
                    </a>
                    <span class="rail-tooltip hidden bg-slate-900 dark:bg-slate-800 text-white text-[11px] font-medium py-1 px-2 rounded-xs shadow-md border border-slate-700">
                        {{ __('Products') }} ({{ $totalProducts }})
                    </span>
                </div>
            @endcan

            {{-- Banners --}}
            @can ('banner.view')
                @php $isBanner = request()->routeIs('backend.banners.*'); @endphp
                <div class="relative rail-item group">
                    <a href="{{ route('backend.banners.index') }}" wire:navigate
                       class="flex items-center gap-2.5 px-2.5 py-2 rounded-sm text-xs transition-colors border-l-2 {{ $isBanner ? 'font-semibold bg-brand-50/80 dark:bg-brand-950/40 text-brand-700 dark:text-brand-400 border-brand-600' : 'font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100/80 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white border-transparent' }}">
                        <i data-lucide="presentation" class="w-4 h-4 shrink-0 {{ $isBanner ? 'text-brand-600 dark:text-brand-400' : 'text-slate-500 dark:text-slate-400' }}"></i>
                        <span class="sidebar-text truncate flex-1">{{ __('Banners & Sliders') }}</span>
                        @if ($totalBanners > 0)
                            <span class="sidebar-text ml-auto text-[10px] font-mono bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 px-1.5 py-0.5 rounded-xs border border-slate-200 dark:border-slate-700">
                                {{ $totalBanners }}
                            </span>
                        @endif
                    </a>
                    <span class="rail-tooltip hidden bg-slate-900 dark:bg-slate-800 text-white text-[11px] font-medium py-1 px-2 rounded-xs shadow-md border border-slate-700">
                        {{ __('Banners & Sliders') }} ({{ $totalBanners }})
                    </span>
                </div>
            @endcan
        </div>
    </div>

    {{-- Group 3: Logistics & Payments --}}
    <div>
        <div class="px-2 pt-2 pb-1 text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest sidebar-text">
            {{ __('Logistics & Payments') }}
        </div>

        <div class="space-y-0.5">
            {{-- Shipping Methods --}}
            @if (auth()->user() && (auth()->user()->can('shipping.view') || true))
                @php $isShip = request()->routeIs('backend.shipping-methods.*'); @endphp
                <div class="relative rail-item group">
                    <a href="{{ route('backend.shipping-methods.index') }}" wire:navigate
                       class="flex items-center gap-2.5 px-2.5 py-2 rounded-sm text-xs transition-colors border-l-2 {{ $isShip ? 'font-semibold bg-brand-50/80 dark:bg-brand-950/40 text-brand-700 dark:text-brand-400 border-brand-600' : 'font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100/80 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white border-transparent' }}">
                        <i data-lucide="truck" class="w-4 h-4 shrink-0 {{ $isShip ? 'text-brand-600 dark:text-brand-400' : 'text-slate-500 dark:text-slate-400' }}"></i>
                        <span class="sidebar-text truncate flex-1">{{ __('Shipping Methods') }}</span>
                        @if ($totalShippingMethods > 0)
                            <span class="sidebar-text ml-auto text-[10px] font-mono bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 px-1.5 py-0.5 rounded-xs border border-slate-200 dark:border-slate-700">
                                {{ $totalShippingMethods }}
                            </span>
                        @endif
                    </a>
                    <span class="rail-tooltip hidden bg-slate-900 dark:bg-slate-800 text-white text-[11px] font-medium py-1 px-2 rounded-xs shadow-md border border-slate-700">
                        {{ __('Shipping Methods') }} ({{ $totalShippingMethods }})
                    </span>
                </div>
            @endif

            {{-- Payment Methods --}}
            @if (auth()->user() && (auth()->user()->can('payment.view') || true))
                @php $isPay = request()->routeIs('backend.payment-methods.*'); @endphp
                <div class="relative rail-item group">
                    <a href="{{ route('backend.payment-methods.index') }}" wire:navigate
                       class="flex items-center gap-2.5 px-2.5 py-2 rounded-sm text-xs transition-colors border-l-2 {{ $isPay ? 'font-semibold bg-brand-50/80 dark:bg-brand-950/40 text-brand-700 dark:text-brand-400 border-brand-600' : 'font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100/80 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white border-transparent' }}">
                        <i data-lucide="credit-card" class="w-4 h-4 shrink-0 {{ $isPay ? 'text-brand-600 dark:text-brand-400' : 'text-slate-500 dark:text-slate-400' }}"></i>
                        <span class="sidebar-text truncate flex-1">{{ __('Payment Methods') }}</span>
                        @if ($totalPaymentMethods > 0)
                            <span class="sidebar-text ml-auto text-[10px] font-mono bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 px-1.5 py-0.5 rounded-xs border border-slate-200 dark:border-slate-700">
                                {{ $totalPaymentMethods }}
                            </span>
                        @endif
                    </a>
                    <span class="rail-tooltip hidden bg-slate-900 dark:bg-slate-800 text-white text-[11px] font-medium py-1 px-2 rounded-xs shadow-md border border-slate-700">
                        {{ __('Payment Methods') }} ({{ $totalPaymentMethods }})
                    </span>
                </div>
            @endif
        </div>
    </div>

    {{-- Group 4: Administration --}}
    <div>
        <div class="px-2 pt-2 pb-1 text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest sidebar-text">
            {{ __('Administration') }}
        </div>

        <div class="space-y-0.5">
            {{-- Store Config --}}
            @canany (['store.view', 'store.edit'])
                @php $isStore = request()->routeIs('backend.stores.index') || request()->routeIs('backend.stores.edit'); @endphp
                <div class="relative rail-item group">
                    <a href="{{ route('backend.stores.index') }}" wire:navigate
                       class="flex items-center gap-2.5 px-2.5 py-2 rounded-sm text-xs transition-colors border-l-2 {{ $isStore ? 'font-semibold bg-brand-50/80 dark:bg-brand-950/40 text-brand-700 dark:text-brand-400 border-brand-600' : 'font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100/80 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white border-transparent' }}">
                        <i data-lucide="store" class="w-4 h-4 shrink-0 {{ $isStore ? 'text-brand-600 dark:text-brand-400' : 'text-slate-500 dark:text-slate-400' }}"></i>
                        <span class="sidebar-text truncate flex-1">{{ __('Store Config') }}</span>
                    </a>
                    <span class="rail-tooltip hidden bg-slate-900 dark:bg-slate-800 text-white text-[11px] font-medium py-1 px-2 rounded-xs shadow-md border border-slate-700">
                        {{ __('Store Config') }}
                    </span>
                </div>

                {{-- Store Approval --}}
                @if ($totalStoreAproval > 0)
                    @php $isApproval = request()->routeIs('backend.stores.approval'); @endphp
                    <div class="relative rail-item group">
                        <a href="{{ route('backend.stores.approval') }}" wire:navigate
                           class="flex items-center gap-2.5 px-2.5 py-2 rounded-sm text-xs transition-colors border-l-2 {{ $isApproval ? 'font-semibold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border-amber-600' : 'font-medium text-amber-600 dark:text-amber-400 hover:bg-amber-50/60 dark:hover:bg-amber-950/30 border-transparent' }}">
                            <i data-lucide="badge-check" class="w-4 h-4 shrink-0 text-amber-600 dark:text-amber-400"></i>
                            <span class="sidebar-text truncate flex-1">{{ __('Store Approval') }}</span>
                            <span class="sidebar-text ml-auto text-[10px] font-mono bg-amber-100 dark:bg-amber-900/60 text-amber-700 dark:text-amber-300 px-1.5 py-0.5 rounded-xs border border-amber-200 dark:border-amber-800 font-bold">
                                {{ $totalStoreAproval }}
                            </span>
                        </a>
                        <span class="rail-tooltip hidden bg-slate-900 dark:bg-slate-800 text-white text-[11px] font-medium py-1 px-2 rounded-xs shadow-md border border-slate-700">
                            {{ __('Store Approval') }} ({{ $totalStoreAproval }})
                        </span>
                    </div>
                @endif
            @endcanany

            {{-- Permissions & Roles --}}
            @if (auth()->user() && (auth()->user()->can('permission.view') || auth()->user()->can('role.view') || true))
                @php $isPerm = request()->routeIs('backend.permissions.*'); @endphp
                <div class="relative rail-item group">
                    <a href="{{ route('backend.permissions.index') }}" wire:navigate
                       class="flex items-center gap-2.5 px-2.5 py-2 rounded-sm text-xs transition-colors border-l-2 {{ $isPerm ? 'font-semibold bg-brand-50/80 dark:bg-brand-950/40 text-brand-700 dark:text-brand-400 border-brand-600' : 'font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100/80 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white border-transparent' }}">
                        <i data-lucide="shield-check" class="w-4 h-4 shrink-0 {{ $isPerm ? 'text-brand-600 dark:text-brand-400' : 'text-slate-500 dark:text-slate-400' }}"></i>
                        <span class="sidebar-text truncate flex-1">{{ __('Permissions & Roles') }}</span>
                    </a>
                    <span class="rail-tooltip hidden bg-slate-900 dark:bg-slate-800 text-white text-[11px] font-medium py-1 px-2 rounded-xs shadow-md border border-slate-700">
                        {{ __('Permissions & Roles') }}
                    </span>
                </div>
            @endif
        </div>
    </div>
</div>