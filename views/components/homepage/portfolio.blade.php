@props(['posts'])
@if(!empty($posts))
<section id="{{$posts->first()->postCategory->slug}}">

  <div class="px-15px py-60px md:py-20 lg:py-30">
    <div class="grid grid-cols-2 md:grid-cols-1 lg:grid-cols-2 xl:grid-cols-2 mx-auto gap-9 md:gap-3">
      <div class="mx-auto lg:order-2 max-w-[991px] w-full">
        @foreach($posts as $index => $item)
        <div class="sticky top-[150px] overflow-hidden rounded-20px mb-30px md:mb-50px last:mb-0 wow fadeInUp" data-wow-delay=".1s">
          <div class="w-full overflow-hidden max-h-[640px] object-cover object-[center_top] before:absolute before:bottom-0 before:left-0 before:w-full before:h-[165px] md:before:h-[250px] lg:before:h-[350px] xl:before:h-[470px] before:bg-gradient-primary-9 before:z-1">
            <img src="{{ asset($item->image?->url ?? $item->getRandomImage()) }}" class="w-full" alt="{{$item->title }}" />
          </div>
          <div class="absolute top-[15px] right-[15px] sm:top-[30px] sm:right-[30px] z-[2]">
            <a href="{{ $item->url }}" class="text-size-25px w-50px h-50px sm:w-65px sm:h-65px md:w-20 md:h-20 lg:w-100px lg:h-100px text-white-color group-hover:text-white-color bg-transparent hover:bg-primary-color border border-body-color dark:border-white-color hover:border-primary-color dark:hover:border-primary-color rounded-100% leading-1 transition-all duration-300 inline-flex justify-center items-center group">
              <span class="relative overflow-hidden -rotate-45">
                <i class="fa-regular fa-arrow-right text-xl group-hover:translate-x-150% transition-all duration-500 inline-block"></i>
                <i class="fa-regular fa-arrow-right absolute left-0 top-0 -translate-x-150% text-xl group-hover:-translate-x-0 transition-all duration-500"></i>
              </span>
            </a>
          </div>
          <div class="absolute bottom-0 left-0 w-full p-15px md:p-30px flex flex-wrap lg:flex-nowrap items-end gap-15px md:gap-5 justify-between z-[2]">
            <div>
              <h6 class="text-primary-color mb-10px md:mb-5">{{$posts[0]->subtitle ?? 'serv'}}</h6>
              <h4 class="text-size-25 sm:text-3xl md:text-size-35 lg:text-size-40 xl:text-size-44 font-semibold leading-1.2 sm:leading-1.2 -tracking-0.02em inline-block max-w-580px w-full">
                <a href="{{ $item->url }}" class="text-white-color hover:text-primary-color dark:hover:text-primary-color transition-all duration-[.8s] inline relative z-0 bg-[0_100%] [background-size:0_2px] bg-no-repeat bg-gradient-primary-3 hover:[background-size:100%_2px]">
                  {{$item->title }}
                </a>
              </h4>
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
            </div>
          </div>
        </div>
        @endforeach
      </div>

      <div class="lg:sticky lg:top-[50%] mx-auto lg:h-[calc(100vh-400px)] lg:overflow-y-auto">
        <div class="lg:pl-30px rtl:lg:pr-30px">

          <!-- Section Header -->
          <div class="mb-25px wow fadeInUp" data-wow-delay=".3s">
            <span class="text-xs uppercase text-primary-color font-semibold relative inline-block tracking-0.2em">
              {{ $posts->first()->postCategory->description ?? '' }}
            </span>
          </div>
          <h2 class="text-3xl md:text-size-35 lg:text-size-40 xl:text-size-45 uppercase font-semibold leading-1.2 -tracking-0.02em inline-block text-seondary-color dark:text-white-color max-w-580px w-full wow fadeInUp" data-wow-delay=".4s">
            {{ $posts->first()->postCategory->name ?? '' }}
          </h2>
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

          <div class="mt-30px md:mt-35px wow fadeInUp" data-wow-delay=".6s">
            <a href="{{ $item->url }}" class="text-size-15 font-bold text-white-color capitalize py-17px px-35px bg-200 bg-gradient-secondary hover:bg-[-100%] rounded-full inline-flex gap-10px items-center leading-1 transition-all duration-300 group wow fadeInRight" data-wow-delay=".5s">
              {{ __('Browse More') }} <i class="fa-regular fa-arrow-right transition-all duration-400 -rotate-45 group-hover:rotate-0"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="pt-20 justify-center text-center">
      <a href="{{ route('dynamic.route', [$item->postCategory->slug]) }}" class="text-size-15 font-bold text-white-color capitalize py-17px px-35px ml-10px bg-200 bg-gradient-secondary hover:bg-[-100%] rounded-full leading-1 transition-all duration-300">
        {{ __('View All') }}</a></div>
  </div>
</section>
@endif
