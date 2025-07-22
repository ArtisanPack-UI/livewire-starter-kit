<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px]">
        <x-artisanpack-navlist>
            <x-artisanpack-navlist.item :href="route('settings.profile')" wire:navigate>{{ __('Profile') }}</x-artisanpack-navlist.item>
            <x-artisanpack-navlist.item :href="route('settings.password')" wire:navigate>{{ __('Password') }}</x-artisanpack-navlist.item>
            <x-artisanpack-navlist.item :href="route('settings.appearance')" wire:navigate>{{ __('Appearance') }}</x-artisanpack-navlist.item>
        </x-artisanpack-navlist>
    </div>

    <x-artisanpack-separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <x-artisanpack-heading>{{ $heading ?? '' }}</x-artisanpack-heading>
        <x-artisanpack-subheading>{{ $subheading ?? '' }}</x-artisanpack-subheading>

        <div class="mt-5 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>
