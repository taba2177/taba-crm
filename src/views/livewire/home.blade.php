<div x-data="{
        heavyData: @entangle('heavySectionsData'),
        isLoaded: false,
        loadData() {
            if (this.isLoaded) return;
            this.isLoaded = true;
            @this.call('loadRemainingHeavyPosts');
        }
    }" x-init="setTimeout(() => loadData(), 1000)">

    @foreach ($sections as $section)
    @php
    $isHeavy = $section->posts_count > \Taba\Crm\Livewire\Home::HEAVY_SECTION_THRESHOLD || $section->HEAVY_SECTION;
    $componentName = $this->resolveComponentName($section);
    @endphp

    <div class="p-0 m-0" wire:key="section-{{ $section->id }}">
        @if ($componentName && view()->exists('components.' . str_replace('.', '/', $componentName)))

        {{-- CASE 1: LIGHT SECTION --}}
        @if (!$isHeavy)
        <div wire:ignore>
            <x-dynamic-component :component="$componentName" :posts="$section->posts" />
        </div>
        @else
        {{-- CASE 2: HEAVY SECTION --}}
        <div x-data="{ sectionId: {{ $section->id }}, fullData: heavyData[{{ $section->id }}] }"
            x-init="$watch('heavyData', value => fullData = value[sectionId])">

            {{-- Show fake posts (already injected in mount) --}}
            <div x-show="!fullData">
                <x-dynamic-component :component="$componentName" :posts="$section->posts" />
            </div>

            {{-- Replace with real posts when they arrive --}}
            <div x-show="fullData" x-cloak>
                <x-dynamic-component :component="$componentName" :posts="collect()"
                    x-bind:posts-data="fullData ? fullData.posts : []" />
            </div>
        </div>
        @endif
        @endif
    </div>
    @endforeach
</div>
