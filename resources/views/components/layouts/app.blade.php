<x-layouts.app.sidebar :title="$title ?? null">
    <x-artisanpack-main>
        {{ $slot }}
    </x-artisanpack-main>
</x-layouts.app.sidebar>
