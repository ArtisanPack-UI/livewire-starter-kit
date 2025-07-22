<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Appearance')" :subheading=" __('Update the appearance settings for your account')">
        <x-artisanpack-radio.group x-data variant="segmented" x-model="$flux.appearance">
            <x-artisanpack-radio value="light" icon="sun">{{ __('Light') }}</x-artisanpack-radio>
            <x-artisanpack-radio value="dark" icon="moon">{{ __('Dark') }}</x-artisanpack-radio>
            <x-artisanpack-radio value="system" icon="computer-desktop">{{ __('System') }}</x-artisanpack-radio>
        </x-artisanpack-radio.group>
    </x-settings.layout>
</section>
