<x-layouts.main :title="$post->title">
    <div class="bg-gray-50 dark:bg-gray-900">
        <div class="container py-8 md:py-12">
            <x-breadcrumbs2 :post="$post" :image="$post->image?->url ?? null" :title="$post->postCategory->description"
                :items="[
                    ['label' => __('home'), 'url' => route('home')],
                    ['label' => $post->postCategory->name, 'url' => route('dynamic.route', [$post->postCategory->slug])],
                ]" />

            <div class="grid grid-cols-1 lg:grid-cols-1 lg:gap-12">

                <main class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 sm:p-8 rounded-lg shadow-sm">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white leading-tight">
                            {{ $post->title }}</h1>
                        {{-- @if(auth()->check())

                        <div
                            class="mt-4 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-gray-500 dark:text-gray-400">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ $post->published_at->translatedFormat('j F Y', 'ar') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>{{ $post->author->name ?? 'admin' }}</span>
                    </div>
                    <a href="{{ $post->editUrl }}" title="edit"
                        class="flex items-center gap-2 text-primary-600 hover:text-primary-700 dark:text-primary-500 dark:hover:text-primary-400 transition-colors">
                        <x-heroicon-s-pencil class="w-4 h-4" />
                        <span>تعديل</span>
                    </a>
            </div>
            @endif --}}

        </div>

        @if ($post->image)
        <img src="{{ $post->image?->url ?? $post->getRandomImage() }}" alt="{{ $post->title }}"
            class="my-8 w-full h-auto max-h-[500px] object-cover rounded-lg shadow-md" />
        @endif

        @if($post->blocks)
        <article class="prose prose-lg dark:prose-invert max-w-none">
            @foreach ($post->blocks as $block)
            @switch($block->type)
            @case('markdown')
            @if($block->data->content) @markdom($block->data->content) @endif
            @break
            @case('figure')
            <figure>
                <img src="{{ $block->data->image }}" alt="{{ $block->data->alt }}" class="rounded-md">
                @if($block->data->caption) <figcaption>{{ $block->data->caption }}</figcaption> @endif
            </figure>
            @break
            @case('heading')
            <h2>{{ $block->data->content }}</h2>
            @break
            @case('quote')
            <blockquote>
                <p>{{ $block->data->content }}</p>
            </blockquote>
            @break
            @case('list')
            <ul>
                @foreach($block->data->items as $item) <li>{{ $item }}</li> @endforeach
            </ul>
            @break
            @endswitch
            @endforeach
        </article>
        @endif

        @if($post->tags->isNotEmpty())
        <div
            class="mt-10 pt-6 border-t border-gray-200 dark:border-gray-700 flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap gap-2">
                @foreach($post->tags as $tag)
                <a href="#"
                    class="inline-block bg-gray-100 dark:bg-gray-700 rounded-full px-3 py-1 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">{{ $tag->name }}</a>
                @endforeach
            </div>
            <div class="flex items-center gap-4">
                <a href="#" class="text-gray-500 hover:text-gray-800 dark:hover:text-gray-200 transition-colors"><span
                        class="sr-only">Instagram</span><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <use xlink:href="#instagram-icon" />
                    </svg></a>
                <a href="#" class="text-gray-500 hover:text-gray-800 dark:hover:text-gray-200 transition-colors"><span
                        class="sr-only">Facebook</span><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <use xlink:href="#facebook-icon" />
                    </svg></a>
                <a href="#" class="text-gray-500 hover:text-gray-800 dark:hover:text-gray-200 transition-colors"><span
                        class="sr-only">X/Twitter</span><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <use xlink:href="#x-twitter-icon" />
                    </svg></a>
            </div>
        </div>
        @endif
        </main>
    </div>
    </div>

    @if($relatedPosts->isNotEmpty())
    <section class="py-12 sm:py-16">
        <div class="container">
            <h3 class="text-2xl font-bold text-center mb-8 text-gray-900 dark:text-white">
                {{ $post->postCategory->name }} ذات الصلة</h3>
            <div class="swiper related-posts-swiper">
                <div class="swiper-wrapper">
                    @foreach ($relatedPosts as $relatedPost)
                    <div class="swiper-slide">
                        <div
                            class="group bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden h-full flex flex-col">
                            <a href="{{ $relatedPost->url }}" class="block">
                                <img src="{{ $relatedPosts->image?->url ?? $relatedPost->getRandomImage() }}"
                                    alt="{{ $relatedPost->title }}" class="w-full h-48 object-cover">
                            </a>
                            <div class="p-4 flex flex-col flex-grow">
                                {{-- <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                                        {{ $relatedPost->published_at->translatedFormat('j F Y', 'ar') }}</p> --}}
                                <a href="{{ $relatedPost->url }}">
                                    <h4
                                        class="font-bold text-lg leading-tight text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                        {{ $relatedPost->title }}</h4>
                                </a>
                                <div class="mt-2 text-sm text-gray-600 dark:text-gray-300 flex-grow">
                                    @markdom($relatedPost->excerpt)
                                </div>
                                {{-- <a href="{{ $relatedPost->url }}"
                                class="mt-4 text-sm font-semibold text-primary-600 dark:text-primary-500
                                hover:underline">
                                {{__('Read more')}} &rarr;
                                </a> --}}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                {{-- <div class="swiper-pagination mt-8 relative"></div> --}}
            </div>
        </div>
    </section>
    @endif

    <div class="py-12 sm:py-16 bg-white dark:bg-gray-800 mt-8">
        <div class="container">
            <div
                class="flex flex-col md:flex-row items-center justify-between gap-8 mb-8 text-center md:text-start gap-8 p-8 bg-gray-100 dark:bg-gray-700/50 rounded-lg">
                <div class="flex-shrink-0">
                    <svg class="w-16 h-16 text-primary-600 dark:text-primary-500" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Need help ?') }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 mt-2">{{ __('We Are Here Hor Hny Help') }}</p>
                </div>
                <div class="md:ml-auto flex-shrink-0 m-auto">
                    <a href="tel:966592090200"
                        class="inline-block px-6 py-3 text-base font-semibold text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors shadow">{{__('Contact')}}</a>
                </div>
            </div>
        </div>
    </div>
    </div>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const relatedSwiper = new Swiper('.related-posts-swiper', {
            // How many slides to show
            slidesPerView: 1,
            // Space between slides
            spaceBetween: 16,
            // Autoplay configuration
            autoplay: {
                delay: 4000, // 4 seconds
                disableOnInteraction: false, // Continue playing after user interacts
                pauseOnMouseEnter: true, // Pause when mouse is over the slider
            },
            // Pagination dots
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            // Responsive breakpoints
            breakpoints: {
                // when window width is >= 640px
                640: {
                    slidesPerView: 2,
                    spaceBetween: 24,
                },
                // when window width is >= 1024px
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 32,
                }
            }
        });
    });
    </script>
</x-layouts.main>
