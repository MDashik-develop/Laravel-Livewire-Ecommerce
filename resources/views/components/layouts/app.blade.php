@if (request()->routeIs('backend.*') || request()->is('dashboard*') || request()->is('settings*') || request()->is('admin*'))
    <x-layouts.app.backend :title="$title ?? null">
        <main class="flex-1 overflow-y-auto p-3 sm:p-5 lg:p-6 space-y-5">
            {{ $slot }}
        </main>
    </x-layouts.app.backend>
@else
    <x-layouts.app.frontend :title="$title ?? null">
        <div class="w-full">
            {{ $slot }}
        </div>
    </x-layouts.app.frontend>
@endif