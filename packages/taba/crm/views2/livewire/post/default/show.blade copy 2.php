<x-layouts.main :title="$post->title">

    <div class="container tw-mb-8 sm:tw-mb-24">
        <nav class="breadcrumbs tw-w-full tw-py-5">
            <ol class="tw-flex tw-items-center tw-space-x-2 tw-text-sm tw-text-gray-500 tw-rtl:tw-space-x-reverse">
                <li><a class="tw-hover:tw-text-primary" href="{{ url('/') }}">الرئيسية</a></li>
                <li><span class="separator">/</span></li>
                <li><a class="tw-hover:tw-text-primary" href="{{ url('/blog') }}">المدونة</a></li>
                <li><span class="separator">/</span></li>
                <li class="tw-font-semibold tw-text-gray-800" aria-current="page">{{ $post->title }}</li>
            </ol>
        </nav>
        <div class="tw-md:tw-flex tw-items-start tw-gap-8">

            <div class="main-content tw-flex-1 tw-bg-white lg:tw-p-8 tw-rounded-md">

                <h1 class="tw-font-bold tw-text-3xl tw-mb-5 tw-leading-10">{{ $post->title }}</h1>

                <div
                    class="tw-mb-10 tw-text-gray-500 tw-rounded-md tw-inline-flex tw-text-sm tw-rtl:tw-space-x-reverse tw-space-x-5">
                    <div class="tw-flex tw-items-center">
                        <i class="fa-regular fa-clock tw-rtl:tw-ml-1 tw-ltr:tw-mr-1"></i>
                        <span>{{ $post->published_at->translatedFormat('j F Y', 'ar') }}</span>
                    </div>
                    <div class="tw-flex tw-items-center">
                        <i class="fa-regular fa-user tw-rtl:tw-ml-1 tw-ltr:tw-mr-1"></i>
                        <span>{{ $post->author->name ?? 'admin' }}</span>
                    </div>
                    @if (auth()->check())
                    <div class="tw-flex tw-items-center">
                        <x-heroicon-s-pencil class="tw-inline-block tw-w-3 tw-h-3 tw-mr-2" />
                        <a class="tw-inline-flex tw-items-center tw-text-sm tw-text-primary-500 tw-hover:tw-text-primary-600"
                            href="{{ $post->editUrl }}" title="edit">
                            تعديل
                        </a>
                    </div>
                    @endif
                </div>

                @if ($post->image)
                <img src="{{ $post->image?->url ?? $post->getRandomImage() }}" alt="{{ $post->title }}"
                    class="tw-h-96 tw-mb-10 tw-w-full tw-object-cover tw-rounded-md" />
                @endif

                <article class="prose tw-max-w-none tw-leading-7 tw-text-sm tw-mb-10">
                    @foreach ($post->blocks as $block)
                    @switch($block->type)
                    @case('markdown')
                    @markdom($block->data->content)
                    @break

                    @case('figure')
                    <figure class="tw-my-4">
                        <img src="{{ $block->data->image }}" alt="{{ $block->data->alt }}" class="tw-rounded-md">
                        @if($block->data->caption)
                        <figcaption class="tw-text-center tw-text-gray-500 tw-text-sm tw-mt-2">
                            {{ $block->data->caption }}</figcaption>
                        @endif
                    </figure>
                    @break

                    @case('heading')
                    <h2 class="tw-font-bold tw-text-2xl tw-mt-6 tw-mb-3">{{ $block->data->content }}</h2>
                    @break

                    @case('quote')
                    <blockquote class="tw-border-r-4 tw-border-gray-200 tw-pr-4 tw-my-4 tw-text-gray-600">
                        <p>{{ $block->data->content }}</p>
                    </blockquote>
                    @break

                    @case('list')
                    <ul class="tw-list-disc tw-pr-5 tw-my-4 tw-space-y-2">
                        @foreach($block->data->items as $item)
                        <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                    @break
                    @endswitch
                    @endforeach
                </article>

                @if($post->tags->isNotEmpty())
                <div class="post-tag-links tw-mt-10 tw-pt-5 tw-border-t">
                    <div class="tw-flex tw-items-center tw-justify-between">
                        <div class="post-tags">
                            <span class="tag-links">
                                @foreach($post->tags as $tag)
                                <a href="#"
                                    class="tw-inline-block tw-bg-gray-100 tw-rounded-full tw-px-3 tw-py-1 tw-text-sm tw-font-semibold tw-text-gray-700 tw-mr-2 tw-mb-2 tw-hover:tw-bg-gray-200">{{ $tag->name }}</a>
                                @endforeach
                            </span>
                        </div>
                        <div class="post-social-sharing">
                            <ul class="tw-inline-flex tw-space-x-3 tw-rtl:tw-space-x-reverse">
                                <li><a href="#" class="tw-text-gray-500 tw-hover:tw-text-primary"><i
                                            class="fa-brands fa-instagram"></i></a></li>
                                <li><a href="#" class="tw-text-gray-500 tw-hover:tw-text-primary"><i
                                            class="fa-brands fa-facebook-f"></i></a></li>
                                <li><a href="#" class="tw-text-gray-500 tw-hover:tw-text-primary"><i
                                            class="fa-brands fa-x-twitter"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            @if($relatedPosts->isNotEmpty())
            <aside class="sidebar tw-md:tw-w-80 lg:tw-w-96 tw-flex-shrink-0 tw-mt-10 md:tw-mt-0">
                <h3 class="tw-font-bold tw-text-xl tw-mb-4">مقالات ذات صلة</h3>
                {{-- Note: Using $relatedPost to avoid conflicts with the main $post object --}}
                @foreach ($relatedPosts as $relatedPost)
                <div class="tw-space-y-6 tw-bg-white tw-mb-3 tw-py-3 tw-rounded-md">
                    <a href="{{ $relatedPost->url }}"
                        class="tw-flex tw-items-start tw-space-x-4 tw-rtl:tw-space-x-reverse group">
                        <div class="tw-flex-shrink-0">
                            <img src="{{ $relatedPost->image?->url ?? $relatedPost->getRandomImage() }}"
                                alt="{{ $relatedPost->title }}"
                                class="tw-w-28 tw-h-20 tw-object-cover tw-ml-2 tw-rtl:tw-mr-2 tw-rtl:tw-ml-auto tw-rounded-md">
                        </div>
                        <div>
                            <div class="tw-text-xs tw-text-gray-500 tw-mb-1 tw-flex tw-items-center">
                                <i class="fa-regular fa-clock tw-text-xs tw-rtl:tw-ml-1 tw-ltr:tw-mr-1"></i>
                                <span>{{ $relatedPost->published_at->translatedFormat('j F Y', 'ar') }}</span>
                            </div>
                            <h4
                                class="tw-font-semibold tw-text-base tw-leading-tight group-hover:tw-text-primary tw-transition-colors">
                                {{ $relatedPost->title }}
                            </h4>
                            <p class="tw-text-sm tw-my-1">{{ $relatedPost->excerpt }}</p>
                        </div>
                    </a>
                </div>
                @endforeach
            </aside>
            @endif
        </div>
    </div>

    <div class="contact-cta py-12 bg-gray-50 my-8 ">
        <div class="container">
            <div class="row space-y-3 rtl:space-y-reverse">
                <div class="col-lg-12 text-center border-1 rounded-md">
                    <i class="icofont-headphone-alt icofont-4x text-primary"></i>
                    <h3 class="text-2xl font-bold bg-white -mt-4">هل تحتاج مساعدة؟</h3>
                    <p class="text-gray-600 my-3">نحن هنا لمساعدتك في أي أسئلة لديك</p>
                    <x-button href="{{ url('tel:966501253111') }}" label="تواصل معنا" icon="fa-arrow-left" />
                </div>
            </div>
        </div>
    </div>
</x-layouts.main>
