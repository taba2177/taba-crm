@props(['posts'])
@if(!empty($posts))
<section class="bg-dark-900 text-gray-200 min-h-screen p-6 font-sans">
    <div class="max-w-6xl mx-auto py-16">
        <!-- Section Header -->
        <div class="text-center mb-20">
            <span
                class="text-neon-purple font-bold tracking-widest text-sm">{{ $posts->first()->postCategory->name ?? 'OUR SERVICES' }}</span>
            <h2
                class="text-4xl md:text-5xl font-bold mt-4 bg-clip-text text-transparent bg-gradient-to-r from-neon-pink to-primary-500">
                {{ $posts->first()->postCategory->subtitle ?? 'Premium Solutions' }}
            </h2>
            <p class="max-w-2xl mx-auto mt-6 text-gray-400">
                {{ $posts->first()->postCategory->description ?? 'Premium Solutions description' }}
            </p>
        </div>

        <!-- Services Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($posts as $index => $post)
            @php
            $delays = [0.3, 0.5, 0.7, 0.9];
            $delay = $delays[$index % 4];
            $neonColors = ['pink', 'blue', 'purple'];
            $color = $neonColors[$index % 3];
            @endphp

            <div class="group relative bg-dark-800 rounded-xl p-8 border border-dark-700 hover:border-primary-500 transition-all duration-300 hover:shadow-glow overflow-hidden wow fadeInUp"
                data-wow-delay="{{ $delay }}s">
                <div
                    class="absolute -inset-1 bg-gradient-to-r from-neon-{{ $color }} to-primary-500 opacity-0 group-hover:opacity-20 blur-md transition-opacity duration-300">
                </div>

                <div class="relative z-10">
                    <!-- Icon/Image Container -->
                    <div
                        class="w-14 h-14 rounded-lg bg-dark-700 flex items-center justify-center mb-6 shadow-glow-{{ $color }}">
                        @if(!empty($post->icon))
                        <i class="{{ $post->icon }} text-2xl text-neon-{{ $color }}"></i>
                        @else
                        <img src="{{ asset($post->image?->url ?? $post->getRandomImage()) }}"
                            alt="{{ $post->title ?? '' }}" class="h-8 w-8 object-contain" />
                        @endif
                    </div>

                    <!-- Content -->
                    <h3 class="text-xl font-bold mb-3">{{ $post->title ?? '' }}</h3>

                    <div class="text-gray-400">
                        @if(!empty($post->excerpt))
                        {{ $post->excerpt }}
                        @else
                        <div class="prose prose-invert max-w-none">
                            @foreach ($post->blocks as $block)
                            @switch($block->type)
                            @case('markdown')
                            @markdom($block->data->content)
                            @break
                            @case('figure')
                            <crm::x-figure :image="$block->data->image" :alt="$block->data->alt"
                                :caption="$block->data->caption" />
                            @break
                            @default
                            @dump($block)
                            @endswitch
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <!-- Category Tag -->
                    @if($post->postCategory)
                    <div class="mt-6 pt-6 border-t border-dark-700">
                        <span
                            class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-dark-700 text-neon-{{ $color }}">
                            {{ $post->postCategory->name }}
                        </span>
                    </div>
                    @endif
                </div>

                <a class="absolute inset-0 z-10" href="#"></a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif