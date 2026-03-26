<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
        <div class="grid grid-cols-2 gap-4">
            @foreach($options as $value => $label)
                <div
                    x-on:click="
                        $wire.set('data.homepage_section_component', '{{ $value }}');
                        $wire.set('data.homepage_section_content', [{}]); // Initialize repeater with one empty item
                    "
                    class="cursor-pointer border p-4 rounded-lg text-center"
                    :class="state === '{{ $value }}' ? 'border-primary-500 ring-2 ring-primary-500' : 'border-gray-300 dark:border-gray-700'">
                    <p class="font-semibold">{{ $label }}</p>
                </div>
            @endforeach
        </div>
    </div>
</x-dynamic-component>