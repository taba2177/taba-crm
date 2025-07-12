@props(['posts'])
@if(!empty($posts))
<section id="{{$posts->first()->postCategory->slug}}" class="bg-primary-color">
  <div class="py-60px md:py-20 lg:py-30 overflow-x-hidden">
    <div class="container">
      <!-- section heading -->
      <x-section-header-dark title="{{ $posts->first()->postCategory->name ?? '' }}"
        description="{{ $posts->first()->postCategory->description ?? '' }}" />
      <!-- services -->
      <div>
        <ul class="tj-service-5-accordion-list accordion-list">
          @foreach($posts as $index => $item)
          <li
            class="bg-white dark:bg-dark-color px-15px lg:px-25px xl:p-30px py-25px lg:py-30px border border-body-color dark:border-bg-color-2 cursor-pointer mb-30px last:mb-0 rounded-15px group/main wow fadeInUp"
            data-wow-delay=".1s">
            <h3
              class="tj-service-5-accordion-list-title text-xl sm:text-size-22 md:text-3xl font-bold leading-1.2 md:leading-1.2 tracking-0.02em text-seondary-color dark:text-white-color transition-all duration-300 ease-in relative before:content-['\f123'] before:font-flaticon before:absolute before:text-xl before:right-0 rtl:before:left-0 rtl:before:right-auto before:top-0 before:opacity-1 before:visible before:rotate-0 before:transition-all before:duration-300 before:ease-in before:font-normal group-hover/main:before:text-primary-color group-hover/main:before:rotate-90">
              <span class="text-primary-color mr-1">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}.</span>
              {{$item->title }}
            </h3>
            <div class="tj-service-5-accordion-wrap flex justify-between items-center flex-wrap gap-y-50px">
              <div
                class="tj-service-5-accordion-list-content w-full max-w-504px lg:max-w-400px xl:max-w-430px ml-8 md:ml-50px mt-30px md:mt-35px lg:mt-50px transition-all duration-300 ease-in">
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
                <div class="tj-service-5-accordion-list-button">
                  <a href="{{ $item->url }}"
                    class="text-size-15 font-bold text-white-color capitalize py-17px px-35px bg-200 bg-gradient-secondary hover:bg-[-100%] rounded-full inline-flex gap-10px items-center leading-1 transition-all duration-300 group">

                    {{ __('Learn More') }} <i
                      class="fa-regular fa-arrow-right transition-all duration-400 -rotate-45 group-hover:rotate-0"></i>
                  </a>
                </div>
              </div>
              <div class="tj-service-5-accordion-list-image w-full lg:w-auto">
                <div
                  class="tj-service-5-accordion-thumb w-full max-w-full lg:max-w-400px xl:max-w-540px 2xl:max-w-615px">
                  <img src="{{ asset($item->image?->url ?? $item->getRandomImage()) }}" alt="{{$item->title }}"
                    class="service-image w-full h-full object-cover rounded-15px">

                </div>
              </div>
            </div>
          </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</section>
@endif