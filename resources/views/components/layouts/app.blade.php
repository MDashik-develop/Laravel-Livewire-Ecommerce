@if (request()->routeIs('backend.*') || request()->is('dashboard*') || request()->is('settings*') || request()->is('admin*'))
    <x-layouts.app.backend :title="$title ?? null">
        <flux:main>
            {{ $slot }}
        </flux:main>
    </x-layouts.app.backend>
@else
    <x-layouts.app.frontend :title="$title ?? null">
        <flux:main>
            {{ $slot }}
        </flux:main>
    </x-layouts.app.frontend>
@endif