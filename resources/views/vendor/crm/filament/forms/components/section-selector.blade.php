<div>
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @php
                $options = $field->options ?? [];
                $currentState = $getState();
            @endphp

            @foreach ($options as $value => $label)
                <div
                    x-on:click="state = '{{ $value }}'"
                    class="
                        relative p-4 border rounded-lg cursor-pointer
                        hover:border-primary-500 hover:shadow-lg
                        transition-all duration-200 ease-in-out
                        {{ $value === $currentState ? 'border-primary-500 ring-2 ring-primary-500 shadow-lg' : 'border-gray-300' }}
                    "
                >
                    <h3 class="text-lg font-semibold mb-2">{{ $label }}</h3>
                    {{-- Placeholder for section shape/preview --}}
                    <div class="bg-gray-100 h-32 flex items-center justify-center text-gray-500 rounded-md">
                        {{-- You can replace this with actual SVG/image previews for each section type --}}
                        <p>Preview of {{ $label }}</p>
                    </div>
                    @if ($value === $currentState)
                        <div class="absolute top-2 right-2 text-primary-600">
                            <x-heroicon-s-check-circle class="w-6 h-6" />
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>