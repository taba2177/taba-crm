<div>
  <div class="main-wrapper">
    <!-- breadcrumb -->
    <section>
      <div
        class="hero-breadcurmb pt-150px md:pt-40 lg:pt-200px pb-50px md:pb-60px lg:b-100px bg-cover bg-center bg-no-repeat relative z-1 after:absolute after:top-0 after:left-0 after:rtl:left-auto rtl:after:right-0 after:w-full after:h-full after:bg-primary-color-light after:-z-1 after:opacity-70">
        <div class="container">
          <div class="flex flex-col items-center">
            <h1 class="text-size-35 md:text-size-40 lg:text-size-50 font-bold text-white-color mb-15px">
              {{ $post->title }}
            </h1>
            <!-- breadcrumbs -->
            <ul class="nav flex items-center gap-x-10px">
              <li class="nav_item group relative">
                <a href="{{ url('/') }}"
                  class="font-medium text-white-color capitalize relative z-0 after:w-0 after:h-1px after:bg-white-color after:absolute after:left-0 after:rtl:left-auto after:rtl:right-0 after:bottom-0 after:transition-all after:duration-500 group-hover:after:w-full">
                  الرئيسية
                </a>
              </li>
              <li class="nav_item group relative">
                <a href="{{ route('posts') }}"
                  class="font-medium text-white-color capitalize relative z-0 after:w-0 after:h-1px after:bg-white-color after:absolute after:left-0 after:rtl:left-auto after:rtl:right-0 after:bottom-0 after:transition-all after:duration-500 group-hover:after:w-full">
                  <i class="far fa-long-arrow-left"></i> المدونة
                </a>
              </li>
              <li class="nav_item group relative">
                <p class="font-medium text-white-color capitalize relative flex items-center gap-10px">
                  <i class="far fa-long-arrow-left"></i> {{ $post->title }}
                </p>
              </li>
            </ul>
            <div class="text-white mt-3">
              نشر بواسطة {{ $post->user->name }} بتاريخ
              <time datetime="{{ $post->published_at->format('Y-m-d H:i:s') }}">
                {{ $post->published_at->translatedFormat('j F، Y', 'ar') }}
              </time>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- portfolio details area -->
    <section id="blogs">
      <div class="py-60px md:py-20 lg:py-100px xl:py-30 dark:bg-black-color">
        <div class="container">
          <!-- portfolio -->
          <div class="group relative wow fadeInUp" data-wow-delay=".3s">
            <div class="relative overflow-hidden">
              @if ($post->image)
              <div
                class="overflow-hidden px-15px md:px-25px lg:px-10 pt-30px md:pt-10 lg:pt-50px bg-cream-light-color dark:bg-primary-color-light">
                <img src="{{ $post->image?->url ?? $post->getRandomImage() }}" alt="{{ $post->title }}"
                  class="w-full h-[400px] rounded" />

              </div>
              @endif

              <div class="pt-30px md:pt-10 lg:pt-60px">
                <div class="transition-all duration-500">
                  <div class="relative z-10">
                    <!-- heading -->
                    <div
                      class="grid grid-cols-1 md:grid-cols-2 gap-5 gap-x-50px items-start px-15px md:px-25px lg:px-10 mb-10 md:mb-50px">
                      <div>
                        <h3 class="mb-10px">
                          <span
                            class="text-primary-color-light dark:text-white-color capitalize relative z-0 text-size-35 md:text-size-40 lg:text-size-45 font-bold">
                            {{ $post->title }}
                          </span>
                        </h3>

                        <p class="text-primary-color-light dark:text-white-color mb-5 md:mb-7">
                          {{ $post->excerpt }}
                        </p>
                        @if (auth()->check())
                        <div>
                          <a href="{{ $post->editUrl }}" title="تعديل المقال"
                            class="text-size-15 font-bold text-white-color capitalize py-17px px-35px bg-200 bg-gradient-secondary hover:bg-[-100%] rounded-full leading-1 transition-all duration-300 whitespace-nowrap group/nested">
                            تعديل المقال
                            <i
                              class="fal fa-arrow-left ml-10px rtl:mr-10px -rotate-45 group-hover/nested:rotate-0 transition-all duration-300"></i>
                          </a>
                        </div>
                        @endif
                      </div>
                      <ul class="grid grid-cols-2 gap-x-15px lg:gap-x-5 gap-y-5 md:gap-y-30px">
                        <li>
                          <p class="text-primary-color-light dark:text-white-color mb-1.5">
                            التصنيف
                          </p>
                          <p class="text-primary-color-light dark:text-white-color font-medium mb-1.5">
                            {{ $post->tags->isNotEmpty() ? $post->tags->first()->name : 'مقالة عامة' }}
                          </p>
                        </li>
                        <li>
                          <p class="text-primary-color-light dark:text-white-color mb-1.5">
                            الناشر
                          </p>
                          <p class="text-primary-color-light dark:text-white-color font-medium mb-1.5">
                            {{ $post->user->name }}
                          </p>
                        </li>
                        <li>
                          <p class="text-primary-color-light dark:text-white-color mb-1.5">
                            تاريخ النشر
                          </p>
                          <p class="text-primary-color-light dark:text-white-color font-medium mb-1.5">
                            {{ $post->published_at->translatedFormat('j F، Y', 'ar') }}
                          </p>
                        </li>
                        <li>
                          <p class="text-primary-color-light dark:text-white-color mb-1.5">
                            الوسوم
                          </p>
                          <p class="text-primary-color-light dark:text-white-color font-medium mb-1.5">
                            @foreach ($post->tags as $tag)
                            <span class="mr-2 rtl:ml-2">{{ $tag->name }}</span>
                            @endforeach
                          </p>
                        </li>
                      </ul>
                    </div>

                    <!-- slider -->
                    @if($relatedProducts->isNotEmpty())
                    <div class="mb-10 md:mb-50px">
                      <div class="portfolio_gallery owl-carousel">
                        @foreach ($relatedProducts as $relatedProduct)
                        <div class="gallery_item">
                          <a href="{{ route('curtains', ['category' => $relatedProduct->product_category->url, 'product' => $relatedProduct->url]) }}"
                            title="{{$relatedProduct->name}} ستاير صن رول - ">
                            @if (!empty($relatedProduct->image) && is_array($relatedProduct->image))
                            <img loading="lazy" src="{{ asset('storage/' . $relatedProduct->image[0]) }}"
                              alt="{{ $relatedProduct->name ?? 'Product image' }}" style="aspect-ratio: 1/1;">
                            @elseif (!empty($relatedProduct->image))
                            <img loading="lazy" src="{{ asset('storage/' . $relatedProduct->image) }}"
                              alt="{{ $relatedProduct->name ?? 'Product image' }}" style="aspect-ratio: 1/1;">
                            @else
                            <img src="{{ asset('/assets/images/floating_logo.png') }}" alt="Default Image"
                              style="aspect-ratio: 1/1;">
                            @endif
                          </a>
                        </div>
                        @endforeach
                      </div>
                    </div>
                    @endif

                    <!-- description wrapper -->
                    <div class="px-15px md:px-25px lg:px-10">
                      <!-- content -->
                      <div class="mb-10 md:mb-50px">
                        <div class="prose dark:prose-invert max-w-none text-primary-color-light dark:text-white-color">
                          @foreach ($post->blocks as $block)
                          @switch($block->type)
                          @case('markdown')
                          @markdom($block->data->content)
                          @break

                          @case('figure')
                          <x-figure :image="$block->data->image" :alt="$block->data->alt"
                            :caption="$block->data->caption" />
                          @break

                          @default
                          @dump($block)
                          @endswitch
                          @endforeach
                        </div>
                      </div>
                    </div>

                    <!-- related posts -->
                    @if($relatedPosts->isNotEmpty())
                    <div class="px-15px md:px-25px lg:px-10 mb-10 md:mb-50px">
                      <h3 class="mb-15px md:mb-5">
                        <span
                          class="text-primary-color-light dark:text-white-color capitalize relative z-0 text-size-35 md:text-size-40 lg:text-size-45 font-bold">
                          مقالات ذات صلة
                        </span>
                      </h3>
                      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($relatedPosts as $post)
                        <div class="blog-warp">
                          <a href="{{$post->url}}" title="{{ $post->title }}">
                            <img
                              src="{{ $post->image?->url ?? $post->getRandomImage() ?? '/assets/images/blog_img_1.jpg' }}"
                              alt="{{ $post->image->alt ?? $post->title }}" class="img-fluid bordered-img w-full h-auto"
                              loading="lazy" style="aspect-ratio: 1/1;" />
                          </a>
                          <div class="meta-box mt-4">
                            <a href="{{$post->url}}" title="{{ $post->title }}"
                              class="text-primary-color-light dark:text-white-color">
                              {{ $post->tags->isNotEmpty() ? $post->tags->first()->name : 'مقالة عامة' }}
                            </a>
                            <span class="text-primary-color-light dark:text-white-color">/</span>
                            <span
                              class="text-primary-color-light dark:text-white-color">{{ $post->published_at->translatedFormat('j F، Y', 'ar') }}</span>
                          </div>
                          <h4 class="h4-md mb-3 mt-2"><a href="{{$post->url}}" title="{{ $post->title }}"
                              class="text-primary-color-light dark:text-white-color hover:text-secondary-color">{{$post->title}}</a>
                          </h4>
                          <p class="text-primary-color-light dark:text-white-color">{{ $post->excerpt }}</p>
                        </div>
                        @endforeach
                      </div>
                    </div>
                    @endif

                    <!-- Support Widget -->
                    <div
                      class="widget-wrap text-center bg-light-theme rounded py-5 px-15px md:px-25px lg:px-10 mb-10 md:mb-50px">
                      <div class="mb-2"><i class="icofont-headphone-alt icofont-4x text-orange"></i></div>
                      <h3 class="h3-md fw-5 txt-orange mb-4">هل تحتاج مساعدة؟</h3>
                      <p class="text-primary-color-light dark:text-white-color">نحن هنا لمساعدتك في أي أسئلة لديك</p>
                      <p class="text-primary-color-light dark:text-white-color mb-4">لا تتردد في الاتصال بنا</p>
                      <a href="{{ url("tel:966501253111") }}"
                        class="text-size-15 font-bold text-white-color capitalize py-17px px-35px bg-200 bg-gradient-secondary hover:bg-[-100%] rounded-full leading-1 transition-all duration-300 whitespace-nowrap inline-block"
                        title="اتصل على رقم التواصل 966501253111">
                        تواصل معنا
                        <i
                          class="fal fa-arrow-left ml-10px rtl:mr-10px -rotate-45 group-hover/nested:rotate-0 transition-all duration-300"></i>

                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>