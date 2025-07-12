@props(['posts'])
@if(!empty($posts))
<section id="portfolio">
  <div class="bg-cream-light-color dark:bg-black-color pt-60px pb-30px md:pt-20 md:pb-60px lg:pt-100px lg:pb-20">
    <div class="container">
      <!-- section heading -->
      <div class="mb-10 md:mb-50px xl:mb-60px flex flex-wrap gap-5 lg:items-center lg:justify-between">
        <div class="max-w-560px">
          <div class="mb-25px">
            <span class="text-xs uppercase text-primary-color font-medium relative inline-block wow fadeInUp"
              data-wow-delay=".3s">{{ $posts->first()->postCategory->name}}</span>
          </div>
          <h2
            class="text-3xl md:text-size-35 lg:text-size-40 xl:text-size-45 font-semibold leading-1.2 -tracking-0.02em inline-block text-seondary-color dark:text-white-color wow fadeInUp"
            data-wow-delay=".4s">
            {{ $posts->first()->postCategory->description }}
          </h2>
        </div>
        <div>
          <a href="#"
            class="text-size-15 font-bold text-white-color capitalize py-17px px-35px bg-200 bg-gradient-secondary hover:bg-[-100%] rounded-full inline-flex gap-10px items-center leading-1 transition-all duration-300 group wow fadeInRight"
            data-wow-delay=".5s">
            {{ __('Explore More') }}
            <i class="fa-regular fa-arrow-right transition-all duration-400 -rotate-45 group-hover:rotate-0"></i>
          </a>
        </div>
      </div>
    </div>
    <div
      class="container w-auto md:!pr-0 !max-w-[inherit] ml-auto md:ml-[calc((100%-720px)/2)] lg:ml-[calc((100%-960px)/2)] xl:ml-[calc((100%-1140px)/2)] 2xl:ml-[calc((100%-1320px)/2)]">
      <div class="portfio-slider-wrapper wow fadeInUp" data-wow-delay=".3s">
        <div class="portfolio-slider-5 swiper">
          <div class="swiper-wrapper">
            @foreach($posts as $post)
            <div class="swiper-slide">
              <div
                class="portfolio-item branding p-5 md:p-25px lg:p-35px border-2 border-body-color dark:border-bg-color-2 rounded-15px group relative">
                <a href="{{ $post->url ?? '#' }}" class="mb-25px rounded-15px overflow-hidden">
                  <img src="{{ asset($post->image?->url ?? $post->getRandomImage()) }}"
                    class="transition-all duration-400 group-hover:scale-110" alt="{{ $post->title ?? '' }}" />
                </a>
                <div class="flex items-center gap-25px justify-between">
                  <div>
                    <h4
                      class="block text-xl text-seondary-color dark:text-white-color hover:text-primary-color dark:hover:text-primary-color font-bold mb-1.5">
                      <a href="{{ $post->url ?? '#' }}">{{ $post->title ?? '' }}</a>
                    </h4>
                    <p class="block text-primary-color-light dark:text-body-color-3">
                      @php
                      // Try to get a short description from the first markdown block, fallback to empty string
                      $desc = '';
                      foreach($post->blocks as $block) {
                      if($block->type === 'markdown') {
                      $desc = strip_tags($block->data->content);
                      break;
                      }
                      }
                      if(!$desc) $desc = '';
                      @endphp
                      {{ \Illuminate\Support\Str::limit($desc, 100) }}
                    </p>
                  </div>
                  <a href="{{ $post->url ?? '#' }}"
                    class="flex-shrink-0 text-size-25px w-60px h-60px text-primary-color-light dark:text-white-color group-hover:text-white-color bg-transparent group-hover:bg-gradient-primary-8 outline-1 outline outline-body-color dark:outline-bg-color-2 group-hover:border-transparent rounded-100% leading-1 transition-all duration-300 inline-flex justify-center items-center">
                    <span class="relative overflow-hidden -rotate-45">
                      <i
                        class="fa-regular fa-arrow-right text-lg group-hover:translate-x-150% transition-all duration-500 inline-block"></i>
                      <i
                        class="fa-regular fa-arrow-right absolute left-0 top-0 -translate-x-150% text-lg group-hover:-translate-x-0 transition-all duration-500"></i>
                    </span>
                  </a>
                </div>
              </div>
            </div>
            @endforeach
          </div>
          <div class="portfolio-pagination"></div>
        </div>
      </div>
    </div>
  </div>
</section>
@endif