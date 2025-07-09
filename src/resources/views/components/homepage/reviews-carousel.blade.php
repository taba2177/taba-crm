@props(['posts'])
@if(!empty($posts))
<section id="testimonials">
  <div class="dark:bg-primary-color-light p-60px md:p-20 lg:p-30 relative overflow-hidden">
    <div class="container">
      <div class="flex flex-col lg:flex-row gap-35px">
        <div class="w-full max-w-420px lg:max-w-365px xl:max-w-420px items-center align-center align-self-center justify-center">

          <div>
            <div class="mb-25px">
              <span
                class="text-xs uppercase text-primary-color font-semibold relative inline-block tracking-0.2em wow fadeInRight">
                {{ $posts->first()->postCategory->description ?? 'Clients feedback' }}
              </span>
            </div>
            <h2
              class="text-3xl md:text-size-35 lg:text-size-40 xl:text-size-45 uppercase font-semibold leading-1.2 -tracking-0.02em inline-block text-seondary-color dark:text-white-color max-w-580px w-full tj-text-invert">
              {{ $posts->first()->postCategory->name ?? "Let’s Hear From Dear Clients." }}
            </h2>
            <div class="mt-30px md:mt-10 wow fadeInUp" data-wow-delay=".3s">
              <a href="{{ url('#contact') }}"
                class="text-size-15 font-bold text-white-color capitalize py-17px px-35px bg-200 bg-gradient-secondary hover:bg-[-100%] rounded-full inline-flex gap-10px items-center leading-1 transition-all duration-300 group">
                Contact Me
                <i class="fa-regular fa-arrow-right transition-all duration-300 -rotate-45 group-hover:rotate-0"></i>
              </a>
            </div>
          </div>
        </div>
        <div class="w-full max-w-785px ml-auto">
          <div>
            <div
              class="testimonial-slider-9 swiper swiper-container h-[800px] lg:h-[720px] xl:h-[620px] overflow-hidden mask-fade-vertical flex items-center">
              <div class="swiper-wrapper">
                @foreach($posts as $index => $item)
                <div class="swiper-slide h-auto">
                  <div
                    class="px-22px py-7 border-2 border-primary-color border-opacity-30 [.swiper-slide-active_&]:border-opacity-100 hover:border-primary-color dark:hover:border-primary-color transition-all duration-500 rounded-15px relative z-0 group">
                    <div class="text-primary-color-light dark:text-white-color relative z-10">
                      <div
                        class="flex gap-15px items-end flex-wrap justify-between pb-25px mb-25px border-b border-body-color dark:border-bg-color-2">
                        <div class="flex gap-15px items-center">
                          <div
                            class="flex-shrink-0 w-77px h-77px p-2 border-2 border-body-color dark:border-bg-color-2 rounded-full">
                            <img class="full h-full object-cover rounded-full"
                              src="{{ $item->author->avatar ?? asset('assets/img/testimonials/user/h5-test-1.png') }}"
                              alt="{{ $item->author->name ?? 'User' }}">
                          </div>
                          <div>
                            <h4 class="text-lg md:text-xl text-seondary-color dark:text-white-color mb-1.5">
                              {{ $item->author->name ?? $item->title }}
                            </h4>
                            <p class="text-primary-color-light dark:text-body-color text-sm">
                              {{ $item->author->position ?? '' }}
                            </p>
                          </div>
                        </div>
                        <div class="flex-shrink-0">
                          <div class="testimonial-rating pl-90px sm:pl-0">
                            <div
                              class="star-ratings relative text-base tracking-[5px] sm:tracking-[8px] lg:tracking-[5px] xl:tracking-[8px] leading-none text-transparent stroke-[1px] drop-shadow-[1px_1px_0_var(--tj-theme-primary)] stroke-primary-color mb-1.5">
                              {{-- <div class="fill-ratings absolute top-0 left-0 z-1 overflow-hidden text-primary-color"
                                style="width: {{ ($item->rating ?? 4.3) / 5 * 100 }}%">
                                <span class="inline-block">★★★★★</span>

                              </div> --}}
                              <div class="empty-ratings block z-0 text-black-color">

                                <span class="inline-block">★★★★★</span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <p
                        class="text-primary-color-light dark:text-body-color-3 text-base sm:text-lg lg:text-xl leading-1.5 sm:leading-1.5 lg:leading-1.5">
                        @foreach ($item->blocks as $block)
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
                      </p>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
              <!-- Add Swiper pagination/navigation if needed -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endif