@props(['posts'])
@if(!empty($posts))
<section>
  <div id="blogs" class="py-60px md:py-20 lg:py-100px xl:py-30">
    <div class="container">
      <!-- section heading -->
      <x-section-header title="{{ $posts->first()->postCategory->name ?? '' }}"
        description="{{ $posts->first()->postCategory->description ?? '' }}" />

      <!-- blogs -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-25px 2xl:gap-35px">
        @foreach($posts as $item)
        <div class="group relative flex flex-col items-center wow fadeInUp" data-wow-delay=".5s">
          <div class="rounded-10px relative overflow-hidden max-w-400 w-full">
            <a href="{{  $item->url  }}" class="rounded-10px overflow-hidden">

              <img src="{{ asset($item->image?->url  ?? $item->getRandomImage()) }}" alt="{{$item->title }}"
                class="group-hover:scale-110 transition-all duration-500" /></a>

            <a href="{{  $item->url  }}"
              class="text-size-13 uppercase px-10px py-7px rounded-50px leading-1 absolute top-[15px] ltr:left-[15px] rtl:right-[15px] rtl:left-auto text-white-color bg-gradient-secondary-2 bg-200 hover:bg-100">{{ $item->subtitle ?? '.' }}</a>

            <div class="absolute left-0 bottom-5 w-full px-10px lg:px-5 transition-all duration-500">
              <div
                class="relative z-0 p-15px pb-5 bg-white-color dark:bg-seondary-color rounded-15px w-full after:absolute after:top-0 after:left-0 after:w-full after:h-full after:opacity-0 group-hover:after:opacity-100 after:transition-all after:duration-500 after:z-1 after:bg-gradient-primary after:rounded-15px">
                <div class="relative z-10">
                  <ul class="flex gap-5 items-center mb-2">
                    <li
                      class="text-sm font-medium text-primary-color group-hover:text-white-color transition-all duration-500">
                      <i class="fa-light fa-calendar-days mr-0.5"></i> {{ date('d M, Y') }}
                    </li>
                    <li class="text-sm font-medium">
                      <i
                        class="fa-light fa-comments mr-0.5 text-primary-color group-hover:text-white-color transition-all duration-500"></i>
                      <a href="{{ $item->url }}"
                        class="text-primary-color group-hover:text-white-color transition-all duration-500">
                        {{$item->tags[0]->name ?? $item->title}}
                      </a>
                    </li>
                  </ul>
                  <a href="{{ $item->url }}"
                    class="text-primary-color-light dark:text-white-color group-hover:text-white-color w-full capitalize">
                    <span class="block text-lg md:text-size-22 font-bold">
                      {{$item->excerpt }}
                    </span>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>
@endif