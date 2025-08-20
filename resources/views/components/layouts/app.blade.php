<x-layouts.app.backend :title="$title ?? null" :>
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts.app.backend>