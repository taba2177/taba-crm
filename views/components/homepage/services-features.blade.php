@props(['posts'])
@if(!empty($posts))
<section id="{{$posts->first()->postCategory->slug}}">

  <div class="py-60px md:py-20 lg:py-30 bg-cream-light-color dark:bg-black-color overflow-x-hidden">
    <div class="container">
      <div class="flex flex-wrap items-end justify-between gap-x-30px gap-y-50px">
        <!-- Left Column (Image) -->
        <div class="w-full max-w-[515px] lg:max-w-[390px] xl:max-w-[515px] mx-auto lg:mx-0 relative z-0 before:w-full before:h-[300px] sm:before:h-[405px] before:absolute before:bottom-0 before:left-0 before:rounded-[120px_14px_14px_14px] before:bg-gradient-13 before:animate-gratient before:bg-[length:600%_100%] before:shadow-shadow-inset before:-z-1 after:w-full after:h-[300px] sm:after:h-[405px] after:absolute after:left-[-10px] after:bottom-[10px] after:border-2 after:border-primary-color after:rounded-[120px_14px_14px_14px] after:shadow-shadow-inset-2 after:bg-transparent after:-z-[2] wow zoomIn" data-wow-delay=".3s">
          <img class="w-full rounded-[120px_14px_14px_14px]" src="{{ asset($posts[0]->image?->url ?? $posts[0]->getRandomImage()) }}" alt="Services Overview">
          <div class="absolute w-full max-w-50px right-[30px] top-[120px] z-1 animate-move-var">
            <img src="./assets/img/shapes/ab-8-shapes.png" class="w-full" alt="Shapes">
          </div>
        </div>

        <!-- Right Column (Content) -->
        <div class="w-full max-w-[630px] lg:max-w-[510px] xl:max-w-[560px] 2xl:max-w-[630px]">
          <div class="mb-25px">
            <span class="text-xs uppercase text-primary-color font-semibold relative inline-block tracking-0.2em wow fadeInRight" data-wow-delay=".3s">
              {{ $posts->first()->postCategory->description ?? '' }}

            </span>
          </div>

          <h2 class="text-3xl md:text-size-35 lg:text-size-40 xl:text-size-45 uppercase font-semibold leading-1.2 -tracking-0.02em inline-block text-seondary-color dark:text-white-color mb-35px lg:mb-5 xl:mb-30px max-w-580px w-full tj-text-invert">
            {{ $posts->first()->postCategory->name ?? '' }}
          </h2>
          <div class="w-full max-w-[510px]">
            <div>
              <div class="prose dark:prose-invert max-w-none text-primary-color-light dark:text-white-color mb-2">
                @foreach ($posts->first()->blocks as $block)
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

              <div class="grid grid-cols-2 max-w-420px wow fadeInUp" data-wow-delay=".3s">
                @foreach($posts as $index => $item)
                <span class="text-base leading-1.75 sm:leading-1.75 font-semibold text-seondary-color dark:text-white-color block relative pl-3 before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2 before:w-5px before:h-5px dark:before:bg-white-color">
                  {{$item->title }}
                </span>
                @endforeach
              </div>
              <div class="mt-35px xl:mt-35px wow fadeInUp" data-wow-delay=".3s">
                <a href="#" class="text-size-15 font-bold text-white-color capitalize py-17px px-35px bg-200 bg-gradient-secondary hover:bg-[-100%] rounded-full inline-flex gap-10px items-center leading-1 transition-all duration-300 group">

                  {{ __('Learn More') }} <i class="fa-regular fa-arrow-right transition-all duration-400 -rotate-45 group-hover:rotate-0"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endif