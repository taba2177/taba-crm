<x-layouts.main :title="$post->title">

    <div class="container mb-8 sm:mb-24">
        <nav class="breadcrumbs w-full py-5">
            <ol class="flex items-center space-x-2 text-sm text-gray-500 rtl:space-x-reverse">
                <li><a class="hover:text-primary" href="{{ url('/') }}">الرئيسية</a></li>
                <li><span class="separator">/</span></li>
                <li><a class="hover:text-primary" href="{{ url('/blog') }}">المدونة</a></li>
                <li><span class="separator">/</span></li>
                <li class="font-semibold text-gray-800" aria-current="page">{{ $post->title }}</li>
            </ol>
        </nav>
        <div class="md:flex items-start gap-8">

            <div class="main-content flex-1 bg-white lg:p-8 rounded-md">

                <h1 class="font-bold text-3xl mb-2 leading-10">{{ $post->title }}</h1>

                <div class="mb-10 text-gray-500 rounded-md inline-flex text-sm rtl:space-x-reverse space-x-5">
                    <div class="flex items-center">
                        <i class="fa-regular fa-clock rtl:ml-1 ltr:mr-1"></i>
                        <span>{{ $post->published_at->translatedFormat('j F Y', 'ar') }}</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fa-regular fa-user rtl:ml-1 ltr:mr-1"></i>
                        <span>{{ $post->author->name ?? 'admin' }}</span>
                    </div>
                    @if(auth()->check())
                    <div class="flex items-center">
                        <x-heroicon-s-pencil class="inline-block w-3 h-3 mr-2" />
                        <a class="inline-flex items-center text-sm text-primary-500 hover:text-primary-600" href="{{ $post->editUrl }}" title="edit">
                        تعديل <p>Welcome, {{ auth()->user()->name }}</p>
                        </a>
                    </div>
                    @endif
                </div>

                @if ($post->image)
                <img src="{{ $post->image?->url ?? $post->getRandomImage() }}" alt="{{ $post->title }}" class="h-96 mb-10 w-full object-cover rounded-md" />
                @endif

                <article class="prose max-w-none leading-7 text-sm mb-10">
                    @foreach ($post->blocks as $block)
                    @switch($block->type)
                    @case('markdown')
                    @markdom($block->data->content)
                    @break

                    @case('figure')
                    <figure class="my-4">
                        <img src="{{ $block->data->image }}" alt="{{ $block->data->alt }}" class="rounded-md">
                        @if($block->data->caption)
                        <figcaption class="text-center text-gray-500 text-sm mt-2">{{ $block->data->caption }}
                        </figcaption>
                        @endif
                    </figure>
                    @break

                    @case('heading')
                    <h2 class="font-bold text-2xl mt-6 mb-3">{{ $block->data->content }}</h2>
                    @break

                    @case('quote')
                    <blockquote class="border-r-4 border-gray-200 pr-4 my-4 text-gray-600">
                        <p>{{ $block->data->content }}</p>
                    </blockquote>
                    @break

                    @case('list')
                    <ul class="list-disc pr-5 my-4 space-y-2">
                        @foreach($block->data->items as $item)
                        <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                    @break
                    @endswitch
                    @endforeach
                </article>

                @if($post->tags->isNotEmpty())
                <div class="post-tag-links mt-10 pt-3 border-t">
                    <div class="flex items-center justify-between">
                        <div class="post-tags">
                            <span class="tag-links">
                                @foreach($post->tags as $tag)
                                <a href="#" class="inline-block bg-gray-100 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2 hover:bg-gray-200">{{ $tag->name }}</a>
                                @endforeach
                            </span>
                        </div>
                        <div class="post-social-sharing">
                            <ul class="inline-flex space-x-3 rtl:space-x-reverse">
                                <li><a href="#" class="text-gray-500 hover:text-primary"><i class="fa-brands fa-instagram"></i></a></li>
                                <li><a href="#" class="text-gray-500 hover:text-primary"><i class="fa-brands fa-facebook-f"></i></a></li>
                                <li><a href="#" class="text-gray-500 hover:text-primary"><i class="fa-brands fa-x-twitter"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            @if($relatedPosts->isNotEmpty())
            <aside class="sidebar md:w-80 lg:w-96 flex-shrink-0 mt-10 md:mt-0">
                <h3 class="font-bold text-xl mb-4">مقالات ذات صلة</h3>
                {{-- Note: Using $relatedPost to avoid conflicts with the main $post object --}}
                @foreach ($relatedPosts as $relatedPost)
                <div class="space-y-6 bg-white mb-3 py-3 rounded-md">
                    <a href="{{ $relatedPost->url }}" class="flex items-start space-x-4 rtl:space-x-reverse group">
                        <div class="flex-shrink-0">
                            <img src="{{ $relatedPost->image?->url ?? $relatedPost->getRandomImage() }}" alt="{{ $relatedPost->title }}" class="w-28 h-20 object-cover ml-2 rtl:mr-2 rtl:ml-auto rounded-md">
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-1 flex items-center">
                                <i class="fa-regular fa-clock text-xs rtl:ml-1 ltr:mr-1"></i>
                                <span>{{ $relatedPost->published_at->translatedFormat('j F Y', 'ar') }}</span>
                            </div>
                            <h4 class="font-semibold text-base leading-tight group-hover:text-primary transition-colors">
                                {{ $relatedPost->title }}
                            </h4>
                            <div class="text-sm my-1 pros text-sm ">
                                @markdom($relatedPost->excerpt)
                            </div>
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
