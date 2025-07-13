@props(['posts'])
@if(!empty($posts))
<section class="overflow-x-hidden" dir="ltr">
  <div class="tj-roll-section ">
    <div class="bg-primary-color relative z-0 after:absolute after:left-0 after:top-0 after:w-full after:h-full after:z-1 after:bg-primary-color after:mask-fade-horizontal">
      <div class="tj-marquee tj-marquee--2 swiper py-3 lg:py-5 xl:py-30px ">
        <div class="swiper-wrapper">
          @foreach($posts as $index => $item)
          @foreach($item->images as $image)
          <div class="swiper-slide max-w-125px sm:max-w-140px">
            <div class="max-w-100px md:max-w-full">
              <img class=" w-full  " src="{{ asset($image?->url ?? $item->getRandomImage()) }}" alt="Brand" />
            </div>
          </div>
          @endforeach
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>
@endif

