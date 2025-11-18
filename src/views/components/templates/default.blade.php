<x-layouts.main :title="$post->title ?? 'Blog Details'">

    <!-- Breadcrumb Section -->
    <section>
        <div
            class="hero-breadcurmb pt-150px md:pt-40 lg:pt-200px pb-50px md:pb-60px lg:b-100px bg-[url('../img/breadcrumb/breadcrumb-bg.jpg')] bg-cover bg-center bg-no-repeat relative z-1 after:absolute after:top-0 after:left-0 after:w-full after:h-full after:bg-primary-color-light after:-z-1 after:opacity-70">
            <div class="container">
                <div class="flex flex-col items-center">
                    <h1 class="text-size-35 md:text-size-40 lg:text-size-50 font-bold text-white-color mb-15px">
                        {{ $post->title }}
                    </h1>
                    <!-- breadcrumbs -->
                    <ul class="nav flex items-center gap-x-10px">
                        <li class="nav_item group relative">
                            <a href="{{ route('home') }}"
                                class="font-medium text-white-color capitalize relative z-0 after:w-0 after:h-1px after:bg-white-color after:absolute after:left-0 after:bottom-0 after:transition-all after:duration-500 group-hover:after:w-full">
                                {{ __('home') }}
                            </a>
                        </li>
                        <li class="nav_item group relative">
                            <p class="font-medium text-white-color capitalize relative flex items-center gap-10px">
                                <i class="far fa-long-arrow-right"></i> {{ $post->postCategory->name }}
                            </p>
                        </li>
                        <li class="nav_item group relative">
                            <p class="font-medium text-white-color capitalize relative flex items-center gap-10px">
                                <i class="far fa-long-arrow-right"></i> {{ $post->title }}
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Details Area -->
    <section id="blogs" dir="ltr">
        <div class="py-60px md:py-20 lg:py-100px xl:py-30 dark:bg-black-color">
            <div class="container">
                <div class="lg:grid lg:gap-6 lg:grid-cols-12">
                    <!-- Main Content -->
                    <div dir="rtl" class="lg:col-start-1 lg:col-span-8">
                        <div class="group relative flex flex-col items-center">
                            <div class="rounded-lg relative overflow-hidden w-full">
                                @if ($post->image)
                                <div class="rounded-t-lg overflow-hidden">
                                    <img src="{{ $post->image?->url ?? $post->getRandomImage() }}"
                                        alt="{{ $post->title }}" class="h-96 w-full object-cover rounded-md" />
                                </div>
                                @endif

                                <div class="pt-30px md:pt-10 w-full">
                                    <div class="transition-all duration-500">
                                        <div class="relative z-0">
                                            <div class="relative z-10">
                                                <h3 class="mb-15px md:mb-5">
                                                    <span
                                                        class="text-primary-color-light dark:text-white-color capitalize relative z-0 text-size-35 md:text-size-40 lg:text-size-45 font-bold">
                                                        {{ $post->title }}
                                                    </span>
                                                </h3>
                                                <div
                                                    class="mb-10 text-gray-400 rounded-md inline-flex text-sm rtl:space-x-reverse space-x-5">
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
                                                        <a class="inline-flex items-center text-sm text-primary-500 hover:text-primary-600"
                                                            href="{{ $post->editUrl }}" title="edit">
                                                            تعديل <p>Welcome, {{ auth()->user()->name }}</p>
                                                        </a>
                                                    </div>
                                                    @endif
                                                </div>
                                                @if($post->blocks)
                                                <article
                                                    class="prose max-w-none dark:text-white-color leading-7 text-sm mb-10">
                                                    @foreach ($post->blocks as $block)
                                                    @switch($block->type)
                                                    @case('markdown')

                                                    @if($block->data->content)
                                                    @markdom($block->data->content)
                                                    @endif

                                                    @break

                                                    @case('figure')
                                                    <figure class="my-4">
                                                        <img src="{{ $block->data->image }}"
                                                            alt="{{ $block->data->alt }}" class="rounded-md">
                                                        @if($block->data->caption)
                                                        <figcaption class="text-center text-gray-400 text-sm mt-2">
                                                            {{ $block->data->caption }}</figcaption>
                                                        @endif
                                                    </figure>
                                                    @break
                                                    @case('heading')
                                                    <h2 class="font-bold text-2xl mt-6 mb-3">{{ $block->data->content }}
                                                    </h2>
                                                    @break
                                                    @case('quote')
                                                    <blockquote
                                                        class="border-r-4 border-gray-200 pr-4 my-4 text-gray-600">
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
                                                @endif
                                                @if($post->tags->isNotEmpty())
                                                <div class="post-tag-links mt-10 pt-3 border-t">
                                                    <div class="flex items-center justify-between">
                                                        <div class="post-tags">
                                                            <span class="tag-links">
                                                                @foreach($post->tags as $tag)
                                                                <a href="#"
                                                                    class="inline-block bg-gray-100 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2 hover:bg-gray-200">{{ $tag->name }}</a>
                                                                @endforeach
                                                            </span>
                                                        </div>
                                                        <div class="post-social-sharing">
                                                            <ul class="inline-flex space-x-3 rtl:space-x-reverse">
                                                                <li><a href="#"
                                                                        class="text-gray-500 hover:text-primary-color"><i
                                                                            class="fa-brands fa-instagram"></i></a></li>
                                                                <li><a href="#"
                                                                        class="text-gray-500 hover:text-primary-color"><i
                                                                            class="fa-brands fa-facebook-f"></i></a>
                                                                </li>
                                                                <li><a href="#"
                                                                        class="text-gray-500 hover:text-primary-color"><i
                                                                            class="fa-brands fa-x-twitter"></i></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Sidebar -->
                    <div dir="rtl"
                        class="sidebar lg:col-start-9 lg:col-span-4 pt-50px lg:pt-0 mt-10 lg:mt-0 border-t border-border-color dark:border-gray-color-3 lg:border-none">
                        <div class="flex flex-col gap-30px">
                            <!-- Categories -->
                            <div
                                class="px-15px md:px-25px py-30px bg-cream-light-color dark:bg-primary-color-light rounded-lg">
                                <h3
                                    class="mb-25px text-primary-color dark:text-white-color uppercase relative z-0 text-size-lg md:text-xl font-bold">
                                    {{ __('categories') }}
                                </h3>
                                <ul class="sidebar-categories">
                                    <li
                                        class="mb-2 {{ $post->postCategory->id == $post->postCategory->id ? 'active' : '' }}">
                                        <button
                                            class="px-5 pt-15px pb-3 rounded-lg hover:bg-seondary-color text-primary-color-light dark:text-white-color hover:text-white-color transition-all duration-500 flex items-center justify-between gap-x-5 w-full group">
                                            <span class="inline-flex gap-1 items-start">
                                                <i class="flaticon-design mr-10px text-size-25 leading-1"></i>
                                                {{ $post->postCategory->name }}
                                            </span>
                                            <span
                                                class="text-primary-color-light dark:text-white-color group-hover:text-white-color leading-1 transition-none duration-500">
                                                <i class="fas fa-angle-right"></i>
                                            </span>
                                        </button>
                                    </li>
                                    {{-- Add more categories here if needed --}}
                                </ul>
                            </div>
                            <!-- Get in Touch -->
                            <div
                                class="px-15px md:px-25px py-30px bg-cream-light-color dark:bg-primary-color-light rounded-lg">
                                <form>
                                    <h3
                                        class="mb-25px text-primary-color dark:text-white-color uppercase relative z-0 text-size-lg md:text-xl font-bold">
                                        {{ __('conttact us') }}
                                    </h3>
                                    <div class="flex flex-col gap-10px">
                                        <div>
                                            <input type="text" placeholder="الاسم"
                                                class="text-white-color w-full px-5 py-14px border border-gray-color-3 bg-cream-light-color dark:bg-black-color focus:border-primary-color rounded-lg outline-none focus:outline-none transition-all duration-300 placeholder:text-gray-color leading-1" />
                                        </div>
                                        <div>
                                            <input type="email" placeholder="البريد الإلكتروني"
                                                class="text-white-color w-full px-5 py-14px border border-gray-color-3 bg-cream-light-color dark:bg-black-color focus:border-primary-color rounded-lg outline-none focus:outline-none transition-all duration-300 placeholder:text-gray-color leading-1" />
                                        </div>
                                        <div>
                                            <textarea cols="1" rows="10" placeholder="رسالتك"
                                                class="text-white-color w-full px-5 py-14px border border-gray-color-3 bg-cream-light-color dark:bg-black-color focus:border-primary-color rounded-lg outline-none focus:outline-none transition-all duration-300 placeholder:text-gray-color leading-1"></textarea>
                                        </div>
                                        <div class="sm:col-start-1 sm:col-span-2">
                                            <div class="flex items-center justify-between mb-10px">
                                                <span
                                                    class="text-primary-color dark:text-white-color font-bold">السعر:</span>
                                                <span
                                                    class="text-primary-color dark:text-white-color font-bold">$99.99</span>
                                            </div>
                                            <button type="submit"
                                                class="text-size-15 font-bold text-white-color capitalize py-17px px-35px bg-200 bg-primary-color hover:bg-[-100%] rounded-full leading-1 transition-all duration-300 w-full text-center mb-10px">
                                                <i class="fas fa-credit-card mr-2"></i> الدفع الآن
                                            </button>
                                            <button type="button"
                                                class="text-size-15 hidden font-bold text-black-color capitalize py-17px px-35px bg-white-color hover:bg-gray-100 rounded-full leading-1 transition-all duration-300 w-full text-center border border-gray-300  items-center justify-center">
                                                <img src="./assets/img/apple-pay-logo.svg" alt="Apple Pay"
                                                    class="h-5 mr-2" /> الدفع بواسطة Apple Pay
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- Related Posts -->
                            @if($relatedPosts->isNotEmpty())
                            <div
                                class="px-15px md:px-25px py-30px bg-cream-light-color dark:bg-primary-color-light rounded-lg">
                                <h3 class="font-bold text-xl mb-4">{{ $post->postCategory->name }} {{ __('related') }}
                                </h3>
                                @foreach ($relatedPosts as $relatedPost)
                                <div class="space-y-6 mb-3 py-3 px-3 rounded-md">
                                    <a href="{{ $relatedPost->url }}"
                                        class="flex items-start space-x-4 rtl:space-x-reverse group">
                                        <div class="flex-shrink-0">
                                            <img src="{{ $relatedPost->image?->url ?? $relatedPost->getRandomImage() }}"
                                                alt="{{ $relatedPost->title }}"
                                                class="w-28 h-20 object-cover ml-2 rtl:mr-2 rtl:ml-auto rounded-md">
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-400 mb-1 flex items-center">
                                                <i class="fa-regular fa-clock text-xs rtl:ml-1 ltr:mr-1"></i>
                                                <span>{{ $relatedPost->published_at->translatedFormat('j F Y', 'ar') }}</span>
                                            </div>
                                            <h4
                                                class="font-semibold text-base leading-tight group-hover:text-primary-color transition-colors">
                                                {{ $relatedPost->title }}
                                            </h4>
                                            <div class="text-sm my-1 text-gray-300 pros text-sm ">
                                                @markdom($relatedPost->excerpt)
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.main>
