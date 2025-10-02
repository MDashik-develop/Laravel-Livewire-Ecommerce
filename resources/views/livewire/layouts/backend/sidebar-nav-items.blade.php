<flux:navlist variant="outline"  wire:poll.5s>
    <flux:navlist.group :heading="__('Platform')" class="grid">
        <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
            wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
        <flux:navlist.item icon="key" :href="route('backend.permissions.index')"
            :current="request()->routeIs('backend.permissions.index')" wire:navigate>{{ __('Permissions') }}
        </flux:navlist.item>
        <!-- <flux:navlist.item icon="queue-list" :href="route('backend.categories.index')" :current="request()->routeIs('backend.categories.index')" wire:navigate>{{ __('Categories') }}</flux:navlist.item> -->
    </flux:navlist.group>

    @canany (['category.view', 'subcategory.view']) 
        <flux:navlist.group expandable :expanded="request()->routeIs(['backend.categories.*', 'backend.subcategories.*'])"
            heading="{{ auth()->user()->can('category.view') ? __('Categories') : __('Subcategories') }}"
            class="lg:grid">
            @can ('category.view')
                <flux:navlist.item icon="square-3-stack-3d" badge="{{ $totalCategory }}" :href="route('backend.categories.index')"
                    :current="request()->routeIs('backend.categories.index')" wire:navigate>{{__('All Categories') }}
                </flux:navlist.item>
            @endcan
            @can ('subcategory.view') 
                <flux:navlist.item icon="table-cells" badge="{{ $totalSubCategory }}" :href="route('backend.subcategories.index')"
                    :current="request()->routeIs('backend.subcategories.index')" wire:navigate>{{ __('All Subcategories') }}
                </flux:navlist.item>
            @endcan
        </flux:navlist.group>
    @endcanany

    @can ('banner.view')
        {{-- <flux:navlist.group expandable :expanded="request()->routeIs('backend.banners.*')" heading="Banners" class="lg:grid"> --}}
            <flux:navlist.item icon="squares-2x2" badge="{{ $totalBanners }}" :href="route('backend.banners.index')"
                :current="request()->routeIs('backend.banners.index')" wire:navigate>{{__('Banners') }}
            </flux:navlist.item>
        {{-- </flux:navlist.group> --}}
    @endcan

    @can ('brand.view') 
        <flux:navlist.group expandable :expanded="request()->routeIs('backend.brands.*')" heading="Brands" class="lg:grid">
            <flux:navlist.item icon="squares-2x2" badge="{{ $totalBrands }}" :href="route('backend.brands.index')"
                :current="request()->routeIs('backend.brands.index')" wire:navigate>{{__('All Brands') }}
            </flux:navlist.item>
        </flux:navlist.group>
    @endcan

    @canany (['store.view', 'store.approval']) 
        <flux:navlist.group expandable :expanded="request()->routeIs('backend.stores.*')"
            heading="{{ auth()->user()->can('store.view') ? __('Stores') : __('Stores Approval') }}"
            class="lg:grid">
            @can ('store.view')
                <flux:navlist.item icon="building-storefront" badge="{{ $totalStore }}" :href="route('backend.stores.index')"
                    :current="request()->routeIs('backend.stores.index')" wire:navigate>{{__('All Stores') }}
                </flux:navlist.item>
            @endcan
            @can ('store.approval')
                <flux:navlist.item icon="check-badge" badge="{{ $totalStoreAproval }}" :href="route('backend.stores.approval')"
                    :current="request()->routeIs('backend.stores.approval')" wire:navigate>{{__('Stores Approval') }}
                </flux:navlist.item>
            @endcan
        </flux:navlist.group>
    @endcanany
    @can ('product.view') 
        <flux:navlist.group expandable :expanded="request()->routeIs('backend.products.*')" heading="Products" class="lg:grid">
            <flux:navlist.item icon="shopping-bag" badge="{{ $totalProducts }}" :href="route('backend.products.index')"
                :current="request()->routeIs('backend.products.index')" wire:navigate>{{__('All Products') }}
            </flux:navlist.item>
        </flux:navlist.group>
    @endcan
</flux:navlist>