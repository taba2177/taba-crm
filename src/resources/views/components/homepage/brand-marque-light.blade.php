@props(['posts'])
@if(!empty($posts))
<div class="overflow-x-hidden dark:bg-primary-color-light">
  <div
    class="text-center mb-50px md:mb-60px after:w-full after:h-1px after:bg-body-color dark:after:bg-dark-color-2 after:absolute after:top-1/2 after:left-0 after:-translate-y-1/2 relative z-0 after:-z-1">
    <p
      class="text-size-13 sm:text-base font-semibold uppercase inline-block px-6 bg-white-color dark:bg-primary-color-light text-seondary-color dark:text-white-color">
      <span class="text-primary-color">
        100+
      </span>
      {{ $posts[0]->postCategory->name ?? 'trusted brands' }}
    </p>
  </div>
  <div class="tj-roll-section">
    <div class="relative z-0 mask-fade-horizontal-2">
      <div class="tj-marquee tj-marquee--3 swiper">
        <div class="swiper-wrapper">
          @foreach($posts as $index => $item)
          @foreach($item->images as $image)
          <div
            class="swiper-slide cursor-pointer max-w-full !w-[200px] h-[84px] rounded-10px shadow-sm dark:bg-black-color flex items-center justify-center relative z-0 overflow-hidden after:absolute after:left-0 after:right-0 after:w-full after:h-full after:-z-1 after:opacity-0 after:transition-all after:duration-300 after:bg-200 after:bg-gradient-secondary hover:after:opacity-100 group">
            <div class="max-w-100px md:max-w-full">
              <img
                class="invert-0 dark:invert-0 w-full transition-all duration-300 group-hover:brightness-100 group-hover:invert-0"
                src="{{ asset($image?->url ?? $item->getRandomImage()) }}" alt="Brand" />
            </div>
          </div>
          @endforeach
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
@endif