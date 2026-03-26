<x-filament-panels::page>
    <form wire:submit.prevent="generate">
        {{ $this->form }}

        {{-- <div class="mt-6">
            <x-filament::button type="submit" icon="heroicon-o-sparkles">
                Generate Seeder
            </x-filament::button>
        </div> --}}
    </form>
</x-filament-panels::page>
