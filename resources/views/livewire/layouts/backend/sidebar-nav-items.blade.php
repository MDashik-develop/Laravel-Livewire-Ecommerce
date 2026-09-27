<flux:navlist variant="outline" wire:poll.5s>
    <flux:navlist.group wire:key="nav-group-platform" :heading="__('Platform')" class="grid">
        <flux:navlist.item wire:key="nav-item-dashboard" icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate class="cursor-pointer">{{ __('Dashboard') }}</flux:navlist.item>
        <flux:navlist.item wire:key="nav-item-permissions" icon="key" :href="route('backend.permissions.index')"
            :current="request()->routeIs('backend.permissions.index')" wire:navigate class="cursor-pointer">{{ __('Permissions') }}
        </flux:navlist.item>
        <flux:navlist.item wire:key="nav-item-media" icon="photo" badge="{{ $totalMedia }}" href="#" wire:click.prevent="$dispatch('open-media-modal', { isPicker: false })" class="cursor-pointer">
            {{ __('Media Library') }}
        </flux:navlist.item>
    </flux:navlist.group>

    @canany (['category.view', 'subcategory.view']) 
        <flux:navlist.group wire:key="nav-group-categories" expandable :expanded="request()->routeIs(['backend.categories.*', 'backend.subcategories.*'])"
            heading="{{ auth()->user()->can('category.view') ? __('Categories') : __('Subcategories') }}"
            class="lg:grid cursor-pointer">
            @can ('category.view')
                <flux:navlist.item wire:key="nav-item-all-categories" icon="square-3-stack-3d" badge="{{ $totalCategory }}" :href="route('backend.categories.index')"
                    :current="request()->routeIs('backend.categories.index')" wire:navigate class="cursor-pointer">{{__('All Categories') }}
                </flux:navlist.item>
            @endcan
            @can ('subcategory.view') 
                <flux:navlist.item wire:key="nav-item-all-subcategories" icon="table-cells" badge="{{ $totalSubCategory }}" :href="route('backend.subcategories.index')"
                    :current="request()->routeIs('backend.subcategories.index')" wire:navigate class="cursor-pointer">{{ __('All Subcategories') }}
                </flux:navlist.item>
            @endcan
        </flux:navlist.group>
    @endcanany

    @can ('banner.view')
        <flux:navlist.item wire:key="nav-item-banners" icon="squares-2x2" badge="{{ $totalBanners }}" :href="route('backend.banners.index')"
            :current="request()->routeIs('backend.banners.index')" wire:navigate class="cursor-pointer">{{__('Banners') }}
        </flux:navlist.item>
    @endcan

    @can ('brand.view') 
        <flux:navlist.group wire:key="nav-group-brands" expandable :expanded="request()->routeIs('backend.brands.*')" heading="Brands" class="lg:grid cursor-pointer">
            <flux:navlist.item wire:key="nav-item-all-brands" icon="squares-2x2" badge="{{ $totalBrands }}" :href="route('backend.brands.index')"
                :current="request()->routeIs('backend.brands.index')" wire:navigate class="cursor-pointer">{{__('All Brands') }}
            </flux:navlist.item>
        </flux:navlist.group>
    @endcan

    @can ('product.view') 
        <flux:navlist.group wire:key="nav-group-products" expandable :expanded="request()->routeIs('backend.products.*')" heading="Products" class="lg:grid cursor-pointer">
            <flux:navlist.item wire:key="nav-item-all-products" icon="shopping-bag" badge="{{ $totalProducts }}" :href="route('backend.products.index')"
                :current="request()->routeIs('backend.products.index')" wire:navigate class="cursor-pointer">{{__('All Products') }}
            </flux:navlist.item>
        </flux:navlist.group>
    @endcan

    @canany (['store.view', 'store.edit'])
        <flux:navlist.item wire:key="nav-item-stores" icon="building-storefront" :href="route('backend.stores.index')"
            :current="request()->routeIs('backend.stores.*')" wire:navigate class="cursor-pointer">
            {{ __('Store Edit') }}
        </flux:navlist.item>
    @endcanany
</flux:navlist>