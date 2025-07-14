<x-layouts.main :title="$post->title">
  <x-banner :title="$post->title" :post="$post" backgroundImage="{{ asset('/assets/img/breadcrumb/breadcrumb-bg.jpg') }}">
    <x-breadcrumbs :items="[
          ['label' => 'الرئيسية', 'url' => route('home')],
          ['label' => $post->postCategory->name, 'url' => route('dynamic.route', [$post->postCategory->slug])],
          ['label' => $post->title, 'url' => '']
          ]" />
  </x-banner>
  <section id="blogs">
    <div class="py-60px md:py-20 lg:py-100px xl:py-30 dark:bg-black-color">
      <x-container>
        <div class="group relative wow fadeInUp" data-wow-delay=".3s">
          <div class="relative overflow-hidden">
            @if ($post->image)
            <div class="overflow-hidden px-15px md:px-25px lg:px-10 pt-30px md:pt-10 lg:pt-50px bg-cream-light-color dark:bg-primary-color-light">
              <img src="{{ $post->image?->url ?? $post->getRandomImage() }}" alt="{{ $post->title }}" class="w-full h-[400px] rounded" />

            </div>
            @endif
            <div class="pt-30px md:pt-10 lg:pt-60px">
              <div class="transition-all duration-500">
                <div class="relative z-10">
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-5 gap-x-50px items-start px-15px md:px-25px lg:px-10 mb-10 md:mb-50px">
                    <div>
                      <h3 class="mb-10px">
                        <span class="text-primary-color-light dark:text-white-color capitalize relative z-0 text-size-35 md:text-size-40 lg:text-size-45 font-bold">
                          {{ $post->title }}
                        </span>
                      </h3>
                      <p class="text-primary-color-light dark:text-white-color mb-5 md:mb-7">
                        {{ $post->excerpt }}
                      </p>
                      @if (auth()->check())
                      <x-button href="{{ $post->editUrl }}" label="تعديل المقال" icon="fa-arrow-left" />
                      @endif
                    </div>
                    <x-portfolio.meta-grid>
                      <x-portfolio.meta-item label="التصنيف" value="{{ $post->tags->isNotEmpty() ? $post->tags->first()->name : 'مقالة عامة' }}" />
                      <x-portfolio.meta-item label="الناشر" value="{{ $post->user->name }}" />
                      <x-portfolio.meta-item label="تاريخ النشر" value="{{ $post->published_at->translatedFormat('j F، Y', 'ar') }}" />
                      <x-portfolio.meta-item label="الوسوم">
                        @foreach ($post->tags as $tag)
                        <span class="mr-2 rtl:ml-2">{{ $tag->name }}</span>
                        @endforeach
                      </x-portfolio.meta-item>
                    </x-portfolio.meta-grid>
                  </div>
                  <!-- slider -->
                  {{--
                            <div class="mb-10 md:mb-50px">
                               <div class="portfolio_gallery owl-carousel">
                                  <div class="gallery_item">
                                     <img src="/assets/img/portfolio-gallery/p-gallery-1.jpg" alt="" />
                                  </div>
                                  <div class="gallery_item">
                                     <img src="/assets/img/portfolio-gallery/p-gallery-2.jpg" alt="" />
                                  </div>
                                  <div class="gallery_item">
                                     <img src="/assets/img/portfolio-gallery/p-gallery-3.jpg" alt="" />
                                  </div>
                                  <div class="gallery_item">
                                     <img src="/assets/img/portfolio-gallery/p-gallery-4.jpg" alt="" />
                                  </div>
                               </div>
                            </div>
                            --}}
                  {{-- @if($relatedProducts)
                            <x-portfolio.gallery class="mb-10 md:mb-50px">
                               @foreach ($relatedProducts as $relatedProduct)
                               <x-portfolio.gallery-item
                                  src="{{ !empty($relatedProduct->image) && is_array($relatedProduct->image) ? asset('storage/' . $relatedProduct->image[0]) : (!empty($relatedProduct->image) ? asset('storage/' . $relatedProduct->image) : asset('/assets/images/floating_logo.png') }}" href="{{ route('curtains', ['category' => $relatedProduct->product_category->url, 'product' => $relatedProduct->url]) }}" alt="{{ $relatedProduct->name ?? 'Product image' }}" />
                  @endforeach
                  </x-portfolio.gallery>
                  @endif --}}
                  <div class="px-15px md:px-25px lg:px-10">
                    <x-portfolio.section>
                      <div class="prose dark:prose-invert max-w-none text-primary-color-light dark:text-white-color">
                        @foreach ($post->blocks as $block)
                        @switch($block->type)
                        @case('markdown')
                        @markdom($block->data->content)
                        @break
                        @case('figure')
                        <x-figure :image="$block->data->image" :alt="$block->data->alt" :caption="$block->data->caption" />
                        @break
                        @default
                        @dump($block)
                        @endswitch
                        @endforeach
                      </div>
                    </x-portfolio.section>
                  </div>
                  @if($post->homepage_section_component && $post->homepage_section_content)
                  <div class="px-15px md:px-25px lg:px-10 mb-10 md:mb-50px">
                    @include('components.homepage.' . $post->homepage_section_component, ['content' => $post->homepage_section_content])
                  </div>
                  @endif
                  @if($relatedPosts->isNotEmpty())
                  <div class="px-15px md:px-25px lg:px-10 mb-10 md:mb-50px">
                    <h3 class="mb-15px md:mb-5">
                      <span class="text-primary-color-light dark:text-white-color capitalize relative z-0 text-size-35 md:text-size-40 lg:text-size-45 font-bold">
                        مقالات ذات صلة
                      </span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                      @foreach ($relatedPosts as $post)
                      <div class="blog-warp">
                        <a href="{{$post->url}}" title="{{ $post->title }}">
                          <img src="{{ $post->image?->url ?? $post->getRandomImage() ?? '/assets/images/blog_img_1.jpg' }}" alt="{{ $post->image->alt ?? $post->title }}" class="img-fluid bordered-img w-full h-auto" loading="lazy" style="aspect-ratio: 1/1;" />
                        </a>
                        <div class="meta-box mt-4">
                          <a href="{{$post->url}}" title="{{ $post->title }}" class="text-primary-color-light dark:text-white-color">
                            {{ $post->tags->isNotEmpty() ? $post->tags->first()->name : 'مقالة عامة' }}
                          </a>
                          <span class="text-primary-color-light dark:text-white-color">/</span>
                          <span class="text-primary-color-light dark:text-white-color">{{ $post->published_at->translatedFormat('j F، Y', 'ar') }}</span>
                        </div>
                        <h4 class="h4-md mb-3 mt-2"><a href="{{$post->url}}" title="{{ $post->title }}" class="text-primary-color-light dark:text-white-color hover:text-secondary-color">{{$post->title}}</a></h4>
                        <p class="text-primary-color-light dark:text-white-color">{{ $post->excerpt }}</p>
                      </div>
                      @endforeach
                    </div>
                  </div>
                  @endif
                  <div class="widget-wrap text-center bg-light-theme rounded py-5 px-15px md:px-25px lg:px-10 mb-10 md:mb-50px">
                    <div class="mb-2"><i class="icofont-headphone-alt icofont-4x text-orange"></i></div>
                    <h3 class="h3-md fw-5 txt-orange mb-4">هل تحتاج مساعدة؟</h3>
                    <p class="text-primary-color-light dark:text-white-color">نحن هنا لمساعدتك في أي أسئلة لديك</p>
                    <p class="text-primary-color-light dark:text-white-color mb-4">لا تتردد في الاتصال بنا</p>
                    <x-button href="{{ url('tel:966501253111') }}" label="تواصل معنا" icon="fa-arrow-left" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </x-container>
    </div>
  </section>
</x-layouts.main>
