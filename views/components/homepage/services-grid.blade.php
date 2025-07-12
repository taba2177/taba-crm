@props(['posts'])
@if(!empty($posts))
<section id="services">

  <div class="py-60px md:py-20 lg:py-30 relative">
    <div class="container">
      <div class="flex flex-wrap justify-between gap-10px lg:gap-10 xl:gap-35px">
        <!-- Left Column -->
        <div class="w-full max-w-[425px] md:max-w-full lg:max-w-[360px] xl:max-w-[420px]">
          <div class="md:flex gap-30px lg:block mb-10 md:mb-50px lg:mb-0">
            <div class="md:w-1/2 lg:w-auto">
              <!-- Section Header -->
              <div class="mb-25px wow fadeInUp" data-wow-delay=".3s">
                <span class="text-xs uppercase text-primary-color font-semibold relative inline-block tracking-0.2em">
                  {{ $posts->first()->postCategory->subtitle ?? '' }}

                </span>
              </div>
              <h2
                class="text-3xl md:text-size-35 lg:text-size-40 xl:text-size-45 uppercase font-semibold leading-1.2 -tracking-0.02em inline-block text-seondary-color dark:text-white-color max-w-580px w-full wow fadeInUp"
                data-wow-delay=".4s">
                {{ $posts->first()->postCategory->name ?? '' }}
              </h2>
              <div class="prose dark:prose-invert max-w-none text-primary-color-light dark:text-white-color my-2">
                {!! $posts->first()->postCategory->description !!}

              </div>
              <div class="mt-30px md:mt-35px wow fadeInUp" data-wow-delay=".6s">
                <a href="{{ route('dynamic.route', [$posts[0]->postCategory->slug]) }}"
                  class="text-size-15 font-bold text-white-color capitalize py-17px px-35px bg-200 bg-gradient-secondary hover:bg-[-100%] rounded-full inline-flex gap-10px items-center leading-1 transition-all duration-300 group wow fadeInRight"
                  data-wow-delay=".5s">
                  {{ __('Learn More') }} <i
                    class="fa-regular fa-arrow-right transition-all duration-400 -rotate-45 group-hover:rotate-0"></i>
                </a>
              </div>
            </div>
            <!-- Image Column -->
            <div
              class="w-full max-w-[405px] md:w-1/2 lg:w-full lg:mx-auto mt-60px rounded-10px relative z-0 after:absolute after:top-[-15px] after:right-[-15px] after:w-full after:h-full after:border after:rounded-10px after:border-body-color dark:after:border-bg-color-2 after:-z-1">
              <img class="rounded-10px" src="{{ asset($posts[0]->image?->url  ?? $posts[0]->getRandomImage()) }}"
                alt="Services Overview">

            </div>
          </div>
        </div>
        <!-- Services List -->
        <div
          class="services-widget services-widget--2 relative w-full max-w-[815px] lg:max-w-[530px] xl:max-w-[655px] 2xl:max-w-[770px] ml-auto">
          @foreach($posts as $index => $item)
          <div
            class="service-item {{ $index === 0 ? 'current' : '' }} py-5 md:py-30px px-15px xl:px-30px border border-body-color dark:border-bg-color-2 mb-30px [.service-item:last-child]:mb-0 rounded-15px transition-all duration-300 relative overflow-hidden group wow fadeInUp"
            data-wow-delay=".6s">
            <div class="flex flex-wrap md:flex-nowrap items-center gap-x-10px gap-y-15px lg:gap-x-5">
              <div class="flex flex-wrap md:flex-nowrap gap-x-10px gap-y-15px lg:gap-x-5">
                <div class="w-10 flex-shrink-0">
                  <img
                    class="w-full group-hover:brightness-0 group-hover:invert [.service-item.current_&]:brightness-0 [.service-item.current_&]:invert transition-all duration-300"
                    src="{{ asset($item->image?->url ?? $item->getRandomImage()) }}" alt="{{$item->title }}">
                </div>
                <div>
                  <h4
                    class="text-xl leading-1.2 text-primary-color [.service-item.current_&]:text-white-color dark:text-white-color mb-15px md:mb-6 lg:mb-15px xl:mb-6 uppercase font-medium transition-all duration-300">
                    {{$item->title }}
                  </h4>
                  <div class="prose dark:prose-invert max-w-none text-primary-color-light dark:text-white-color mb-2">
                    <div class="prose dark:prose-invert max-w-none text-primary-color-light dark:text-white-color mb-2">
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
                    </div>
                  </div>
                </div>
              </div>
              <div class="flex-shrink-0">
                <div
                  class="text-size-15 md:text-xl w-50px h-50px md:w-70px md:h-70px text-primary-color-light dark:text-white-color [.service-item.current_&]:text-white-color group-hover:text-white-color bg-transparent group-hover:bg-primary-color outline-1 outline outline-border-color dark:outline-bg-color-2 group-hover:outline-transparent dark:group-hover:outline-transparent rounded-100% leading-1 md:leading-1 transition-all duration-300 inline-flex justify-center items-center">
                  <i
                    class="flaticon-up-right-arrow rotate-0 group-hover:rotate-45 transition-all duration-500 inline-block"></i>
                </div>
              </div>
            </div>
            {{-- <a href="{{ route('dynamic.route', [$item->postCategory->slug]) }}" class="absolute top-0 left-0 w-full h-full bg-transparent"></a> --}}
          </div>
          @endforeach
          <div class="active-bg hidden sm:block rounded-15px !-z-1 wow fadeInUp" data-wow-delay=".3s"></div>
        </div>
      </div>
    </div>
  </div>
</section>
@endif