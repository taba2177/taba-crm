<div>
    {{-- We loop through all sections to maintain the correct order --}}
    @foreach ($this->allSections as $section)

    {{-- Add a wire:key to help Livewire track each section correctly --}}
    <div class="p-0 m-0" wire:key="section-{{ $section->id }}">
        @if($section->section_component && view()->exists('components.homepage.' . $section->section_component))

        {{-- CASE 1: Eager-loaded section. Render it directly and wrap it with wire:ignore. --}}
        {{-- This tells Livewire to "not touch" this HTML during subsequent updates, preserving its JS. --}}
        @if ($eagerSections->has($section->id))
        <div class="p-0 m-0" wire:ignore>
            <x-dynamic-component :component="'homepage.' . $section->section_component"
                :posts="$eagerSections[$section->id]->posts" />
        </div>

        {{-- CASE 2: Lazy-loaded section. Render a placeholder that triggers loading. --}}
        @else
        <div class="p-0 m-0" x-data="{ loaded: {{ isset($loadedSections[$section->id]) ? 'true' : 'false' }} }" x-init="
                setTimeout(() => {
                    if (!loaded) {
                        @this.call('loadSection', {{ $section->id }});
                        loaded = true;
                    }
                }, 5000);
                " x-intersect:enter.once="
                if (!loaded) {
                    @this.call('loadSection', {{ $section->id }});
                    loaded = true;
                }
             " class="min-h-[300px] flex items-center justify-center" {{-- Placeholder with minimum height --}}>
            <div x-show="loaded" x-transition.opacity.scale.duration.500ms class="w-full">
                {{-- Check if this specific lazy section's data has arrived --}}
                @if (isset($loadedSections[$section->id]))
                {{-- If loaded, render the actual component --}}
                <x-dynamic-component :component="'homepage.' . $section->section_component"
                    :posts="$loadedSections[$section->id]->posts" />
                @endif
            </div>

            <div x-show="!loaded" x-transition.opacity.duration.500ms
                class="w-screen h-screen flex justify-center items-center">
                <div class="skeleton w-16 h-16 border-4 border-dashed rounded-full animate-spin border-primary"></div>
            </div>

        </div>
        @endif

        @endif
    </div>
    @endforeach

    {{--
        IMPORTANT: Ensure the AlpineJS Intersection Observer plugin is loaded in your main layout file (e.g., app.blade.php).
        You only need to add this script once per project.
    --}}
    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.directive('intersect', (el, {
            value,
            expression,
            modifiers
        }, {
            evaluateLater,
            cleanup
        }) => {
            let options = {
                rootMargin: '0px',
                threshold: 0.1
            };
            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        evaluateLater(expression)();
                        if (modifiers.includes('once')) {
                            observer.unobserve(el);
                        }
                    }
                })
            }, options);
            observer.observe(el);
            cleanup(() => {
                observer.disconnect();
            })
        })
    })
    </script>
</div>