<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    @php
    $id = $getId();
    $isDisabled = $isDisabled();
    $statePath = $getStatePath();
    @endphp

    <div x-data="{
            state: $wire.{{ $applyStateBindingModifiers("entangle('{$statePath}')") }},
            get isDisabled() { return @js($isDisabled) },
        }" class="space-y-4">
        @if (count($getOptions()) > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach ($getOptions() as $value => $label)
            @php
            $optionId = "{$id}-{$value}";
            @endphp
            <div class="relative">
                <input type="radio" name="{{ $id }}" id="{{ $optionId }}" value="{{ $value }}" x-model="state"
                    @disabled($isDisabled) class="sr-only">

                <label for="{{ $optionId }}" x-bind:class="{
                                'ring-2 ring-primary-600 dark:ring-primary-500': state == @js($value)
                            }"
                    class="block w-full h-40 bg-gray-100 dark:bg-gray-800 rounded-lg cursor-pointer focus:outline-none overflow-hidden transition-all">
                    <img src="{{ $label['url'] }}" alt="{{ $label['alt'] }}"
                        title="{{ $label['alt'] }} by {{ $label['author_name'] }}" class="w-full h-full object-cover">
                </label>

                {{-- Author credit --}}
                <a href="{{ $label['author_url'] }}?utm_source=your_app_name&utm_medium=referral" target="_blank"
                    rel="noopener noreferrer"
                    class="absolute bottom-1 right-1 bg-black/50 text-white text-xs px-1.5 py-0.5 rounded transition-opacity opacity-75 hover:opacity-100"
                    title="Photo by {{ $label['author_name'] }} on Unsplash">
                    {{ \Illuminate\Support\Str::limit($label['author_name'], 15) }}
                </a>
            </div>
            @endforeach
        </div>
        @else
        <div class="w-full text-center text-gray-500 dark:text-gray-400">
            {{ __('No images could be found. Please ensure the post has an English title and try again.') }}
        </div>
        @endif
    </div>
</x-dynamic-component>