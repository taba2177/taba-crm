<x-filament-panels::page>
    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}

        <div class="flex justify-start gap-3 mt-6">
            <x-filament::button type="submit">
                {{ __('حفظ التعديلات') }}
            </x-filament::button>
        </div>
    </x-filament-panels::form>
</x-filament-panels::page>
