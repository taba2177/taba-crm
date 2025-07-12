@props(['posts'])
@if(!empty($posts))
<section id="{{$posts->first()->postCategory->slug}}">
  <div class="py-60px md:py-20 lg:py-30">
    <div class="container">
      <div class="mb-10 md:mb-50px xl:mb-60px flex flex-wrap justify-between items-end wow fadeInUp"
        data-wow-delay=".3s">
        <div>
          <div class="mb-25px">
            <span
              class="text-xs uppercase text-primary-color font-semibold relative inline-block tracking-0.2em wow fadeInRight"
              data-wow-delay=".3s">
              {{ $posts->first()->postCategory->description ?? '' }}
            </span>
          </div>
          <h2
            class="text-3xl md:text-size-35 lg:text-size-40 xl:text-size-45 uppercase font-semibold leading-1.2 -tracking-0.02em inline-block text-seondary-color dark:text-white-color max-w-580px w-full tj-text-invert">
            {{ $posts->first()->postCategory->name ?? '' }}
          </h2>
        </div>
        <div>
          <div class="testimonial-navigation hidden lg:flex flex-wrap gap-15px items-center">
            <div
              class="service-prev w-55px h-55px inline-flex justify-center items-center border border-border-color relative dark:border-bg-color-2 rounded-100% z-1 group before:absolute before:w-full before:h-full before:top-0 before:left-0 before:bg-gradient-primary before:rounded-100% before:opacity-0 before:invisible before:transition-all before:duration-[0.6s] before:-z-1 hover:before:opacity-100 hover:before:visible">
              <i
                class="fa-regular fa-arrow-left text-primary-color-light group-hover:text-white-color dark:text-white-color"></i>
            </div>
            <div
              class="service-next w-55px h-55px inline-flex justify-center items-center relative border border-border-color dark:border-bg-color-2 group text-primary-color-light hover:text-white-color dark:text-white-color rounded-100% z-1 before:absolute before:w-full before:h-full before:top-0 before:left-0 before:bg-gradient-primary before:rounded-100% before:opacity-0 before:invisible before:transition-all before:duration-[0.6s] before:-z-1 hover:before:opacity-100 hover:before:visible">
              <i
                class="fa-regular fa-arrow-right text-primary-color-light group-hover:text-white-color dark:text-white-color"></i>
            </div>
          </div>
        </div>
      </div>
      <!-- services carousel -->
      <div
        class="relative overflow-x-hidden z-0 after:absolute after:top-1/2 after:-translate-y-1/2 after:left-1/2 after:-translate-x-1/2 after:w-650px after:h-550px after:max-w-2/3 after:max-h-2/3 after:blur-[150px] after:rounded-50% after:bg-gradient-circle-2 after:-z-1 after:opacity-60">
        <div class="service-slider swiper">
          <div class="swiper-wrapper">
            @foreach($posts as $index => $item)
            <div class="swiper-slide wow fadeInUp" data-wow-delay="{{ 0.3 + ($index * 0.1) }}s">
              <div
                class="rounded-15px relative p-30px bg-white-color dark:bg-dark-color backdrop-blur-[40px] border border-border-color dark:border-transparent text-center z-0 group transition-all duration-500">
                <div class="mb-35px">
                  <span
                    class="w-16 h-16 bg-gradient-secondary bg-200 rounded-100% inline-flex justify-center items-center leading-1">
                    <i
                      class="flaticon-design text-size-34 text-white-color leading-1 inline-flex transition-all duration-500 group-hover:scale-x-[-1]"></i>
                  </span>
                </div>
                <h3
                  class="tj-service-5-accordion-list-title text-xl sm:text-size-22 md:text-3xl font-bold mb-15px leading-1.2 md:leading-1.2 tracking-0.02em transition-all duration-300 ease-in relative">
                  <a href="{{  $item->url  }}"
                    class="text-seondary-color dark:text-white-color hover:text-primary-color dark:hover:text-primary-color">
                    {{$item->title }}
                  </a>
                </h3>
                <div class="prose dark:prose-invert max-w-none text-primary-color-light dark:text-white-color mb-2">
                  @foreach ($item->blocks as $block)
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
                <div class="flex justify-center">
                  <a href="#"
                    class="text-2xl text-white-color leading-1 transition-all duration-300 inline-flex justify-center items-center hover:text-primary-color">
                    <span class="relative overflow-hidden -rotate-45">
                      <i
                        class="fa-regular fa-arrow-right group-hover:translate-x-150% transition-all duration-500 inline-block"></i>
                      <i
                        class="fa-regular fa-arrow-right absolute left-0 top-0 -translate-x-150% group-hover:-translate-x-0 transition-all duration-500"></i>
                    </span>
                  </a>
                </div>
              </div>
            </div>
            @endforeach
          </div>
          <div class="service-pagination mt-35px lg:mt-50px text-center"></div>
        </div>
      </div>
    </div>
  </div>
</section>
@endif
