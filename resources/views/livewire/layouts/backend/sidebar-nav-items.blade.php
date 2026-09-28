<flux:navlist variant="outline" wire:poll.10s>
    <!-- Group: Platform -->
    <flux:navlist.group wire:key="nav-group-platform" :heading="__('Platform')" class="grid">
        <flux:navlist.item wire:key="nav-item-dashboard" icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate class="cursor-pointer">
            {{ __('Dashboard') }}
        </flux:navlist.item>

        <flux:navlist.item wire:key="nav-item-media" icon="photo" badge="{{ $totalMedia }}" href="#" wire:click.prevent="$dispatch('open-media-modal', { isPicker: false })" class="cursor-pointer">
            {{ __('Media Library') }}
        </flux:navlist.item>
    </flux:navlist.group>

    <!-- Group: Catalog & Shop -->
    <flux:navlist.group wire:key="nav-group-catalog" :heading="__('Shop & Catalog')" class="grid">
        @can ('category.view')
            <flux:navlist.item wire:key="nav-item-categories" icon="rectangle-stack" badge="{{ $totalCategory }}" :href="route('backend.categories.index')"
                :current="request()->routeIs('backend.categories.*')" wire:navigate class="cursor-pointer">
                {{ __('Categories') }}
            </flux:navlist.item>
        @endcan

        @can ('subcategory.view')
            <flux:navlist.item wire:key="nav-item-subcategories" icon="queue-list" badge="{{ $totalSubCategory }}" :href="route('backend.subcategories.index')"
                :current="request()->routeIs('backend.subcategories.*')" wire:navigate class="cursor-pointer">
                {{ __('Sub-Categories') }}
            </flux:navlist.item>
        @endcan

        @can ('brand.view')
            <flux:navlist.item wire:key="nav-item-brands" icon="tag" badge="{{ $totalBrands }}" :href="route('backend.brands.index')"
                :current="request()->routeIs('backend.brands.*')" wire:navigate class="cursor-pointer">
                {{ __('Brands') }}
            </flux:navlist.item>
        @endcan

        @can ('attribute.view')
            <flux:navlist.item wire:key="nav-item-attributes" icon="swatch" badge="{{ $totalAttributes }}" :href="route('backend.attributes.index')"
                :current="request()->routeIs('backend.attributes.*')" wire:navigate class="cursor-pointer">
                {{ __('Attributes') }}
            </flux:navlist.item>
        @endcan

        @can ('product.view')
            <flux:navlist.item wire:key="nav-item-products" icon="shopping-bag" badge="{{ $totalProducts }}" :href="route('backend.products.index')"
                :current="request()->routeIs('backend.products.*')" wire:navigate class="cursor-pointer">
                {{ __('Products') }}
            </flux:navlist.item>
        @endcan

        @can ('banner.view')
            <flux:navlist.item wire:key="nav-item-banners" icon="squares-2x2" badge="{{ $totalBanners }}" :href="route('backend.banners.index')"
                :current="request()->routeIs('backend.banners.*')" wire:navigate class="cursor-pointer">
                {{ __('Banners') }}
            </flux:navlist.item>
        @endcan
    </flux:navlist.group>

    <!-- Group: Administration -->
    <flux:navlist.group wire:key="nav-group-admin" :heading="__('Administration')" class="grid">
        @canany (['store.view', 'store.edit'])
            <flux:navlist.item wire:key="nav-item-stores" icon="building-storefront" :href="route('backend.stores.index')"
                :current="request()->routeIs('backend.stores.index') || request()->routeIs('backend.stores.edit')" wire:navigate class="cursor-pointer">
                {{ __('Store Edit') }}
            </flux:navlist.item>

            @if ($totalStoreAproval > 0)
                <flux:navlist.item wire:key="nav-item-store-approval" icon="check-badge" badge="{{ $totalStoreAproval }}" :href="route('backend.stores.approval')"
                    :current="request()->routeIs('backend.stores.approval')" wire:navigate class="cursor-pointer text-amber-600 dark:text-amber-400">
                    {{ __('Store Approval') }}
                </flux:navlist.item>
            @endif
        @endcanany

        @can ('permission.view')
            <flux:navlist.item wire:key="nav-item-permissions" icon="shield-check" :href="route('backend.permissions.index')"
                :current="request()->routeIs('backend.permissions.index')" wire:navigate class="cursor-pointer">
                {{ __('Permissions') }}
            </flux:navlist.item>
        @elsecan ('role.view')
            <flux:navlist.item wire:key="nav-item-permissions-role" icon="shield-check" :href="route('backend.permissions.index')"
                :current="request()->routeIs('backend.permissions.index')" wire:navigate class="cursor-pointer">
                {{ __('Permissions') }}
            </flux:navlist.item>
        @else
            @if (auth()->user())
                <flux:navlist.item wire:key="nav-item-permissions-fallback" icon="shield-check" :href="route('backend.permissions.index')"
                    :current="request()->routeIs('backend.permissions.index')" wire:navigate class="cursor-pointer">
                    {{ __('Permissions') }}
                </flux:navlist.item>
            @endif
        @endcan
    </flux:navlist.group>
</flux:navlist>