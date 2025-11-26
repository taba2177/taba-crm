@props(['posts'])

@if(!empty($posts) && $posts->count() > 0)
@php
$count = $posts->count();
$category = $posts->first()->postCategory;
@endphp

<section id="services" class="relative overflow-hidden bg-primary-color py-20 lg:py-32">
    <div class="container mx-auto px-4">

        <div class="mb-16 max-w-3xl {{ $count === 1 ? 'mx-auto text-center' : '' }}">
            <div class="mb-4">
                <span
                    class="inline-block relative text-xs font-bold uppercase tracking-widest text-primary-color-light/80"
                    data-wow-delay=".3s">
                    {{ $category->name }}
                </span>
            </div>
            <h2 class="mb-6 text-3xl font-bold text-white md:text-5xl" data-wow-delay=".4s">
                {{ $category->subtitle }}
            </h2>
            <p class="text-lg text-gray-300">{{ $category->description ?? '' }}</p>
        </div>

        {{-- CASE 1: SINGLE SPOTLIGHT DESIGN --}}
        @if($count === 1)
        @php $post = $posts->first(); @endphp
        <div class="mx-auto max-w-5xl rounded-3xl bg-white/5 p-8 backdrop-blur-sm transition-all duration-500 hover:bg-white/10 md:p-12"
            data-wow-delay="0.6s">
            <div class="flex flex-col gap-8 md:flex-row md:items-center">
                <div class="shrink-0">
                    <span
                        class="flex h-24 w-24 items-center justify-center rounded-2xl bg-primary-color shadow-lg md:h-32 md:w-32">
                        @if(!empty($post->icon))
                        <i class="{{ $post->icon }} text-5xl text-white"></i>
                        @else
                        <img src="{{ asset($post->image?->url ?? $post->getRandomImage()) }}" alt="{{ $post->title }}"
                            class="h-16 w-16 object-contain" />
                        @endif
                    </span>
                </div>
                <div class="flex-1">
                    <h3 class="mb-4 text-2xl font-bold text-white md:text-3xl">{{ $post->title }}</h3>
                    <div class="prose prose-invert max-w-none text-gray-300">
                        @include('partials.post-content', ['post' => $post])
                    </div>
                    <a href="{{ $post->url ?? '#' }}"
                        class="mt-6 inline-flex items-center text-sm font-bold uppercase tracking-wider text-white hover:text-primary-color-light">
                        {{ $post->metadata['read_more'] ?? 'Read More' }} <span class="ml-2">&rarr;</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- CASE 2: DUAL SPLIT DESIGN --}}
        @elseif($count === 2)
        <div class="grid gap-8 md:grid-cols-2 lg:gap-12">
            @foreach($posts as $index => $post)
            <div class="group relative overflow-hidden rounded-3xl bg-white/5 p-8 transition-all duration-500 hover:-translate-y-2 hover:bg-white/10"
                data-wow-delay="{{ 0.6 + ($index * 0.1) }}s">
                <div class="mb-8">
                    <span
                        class="inline-flex h-20 w-20 items-center justify-center rounded-2xl bg-primary-color transition-transform duration-500 group-hover:scale-110">
                        @if(!empty($post->icon))
                        <i class="{{ $post->icon }} text-4xl text-white"></i>
                        @else
                        <img src="{{ asset($post->image?->url ?? $post->getRandomImage()) }}"
                            class="h-10 w-10 object-contain" />
                        @endif
                    </span>
                </div>
                <h3 class="mb-4 text-xl font-bold text-white">{{ $post->title }}</h3>
                <div class="prose prose-invert mb-6 line-clamp-3 text-gray-400">
                    @include('partials.post-content', ['post' => $post])
                </div>
                <a href="{{ $post->url ?? '#' }}" class="absolute inset-0 z-10"></a>
            </div>
            @endforeach
        </div>

        {{-- CASE 3+: GRID & MASONRY DESIGN --}}
        @else
        <div class="grid gap-6 md:grid-cols-2 {{ $count === 3 ? 'lg:grid-cols-3' : 'lg:grid-cols-4' }}">
            @foreach($posts as $index => $post)
            <div class="group flex h-full flex-col rounded-2xl border border-white/5 bg-transparent p-6 transition-all duration-300 hover:border-white/20 hover:bg-white/5"
                data-wow-delay="{{ 0.6 + ($index * 0.1) }}s">

                <div class="mb-6 flex items-start justify-between">
                    <span
                        class="flex h-14 w-14 items-center justify-center rounded-xl bg-primary-color/20 text-primary-color-light transition-colors group-hover:bg-primary-color group-hover:text-white">
                        @if(!empty($post->icon))
                        <i
                            class="{{ $post->icon }} text-2xl transition-transform duration-500 group-hover:rotate-12"></i>
                        @else
                        <img src="{{ asset($post->image?->url ?? $post->getRandomImage()) }}"
                            class="h-8 w-8 object-contain" />
                        @endif
                    </span>
                    <a href="{{ $post->url ?? '#' }}"
                        class="flex h-8 w-8 items-center justify-center rounded-full border border-white/10 text-white opacity-0 transition-all group-hover:opacity-100">
                        &nearr;
                    </a>
                </div>

                <div class="flex flex-1 flex-col">
                    <h3 class="mb-3 text-lg font-bold text-white group-hover:text-primary-color-light">
                        {{ $post->title }}</h3>
                    <div class="mb-4 flex-1 text-sm leading-relaxed text-gray-400">
                        {{-- Truncate content for cleaner grid --}}
                        @if(!empty($post->excerpt))
                        {{ Str::limit($post->excerpt, 100) }}
                        @else
                        <div class="line-clamp-3">
                            @include('partials.post-content', ['post' => $post])
                        </div>
                        @endif
                    </div>
                </div>
                <a href="{{ $post->url ?? '#' }}" class="absolute inset-0 z-10"></a>
            </div>
            @endforeach
        </div>
        @endif

    </div>
</section>
@endif

{{--
    INLINE PARTIAL FOR CONTENT RENDERING
    (Paste this at the bottom of the file or extract to a separate file)
--}}
@once
@push('scripts')
<script type="text/template" id="post-content-logic">
    {{-- This logic was extracted to avoid repeating the big block switch 3 times --}}
    </script>
@endpush
@endonce