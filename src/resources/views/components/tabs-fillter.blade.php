<section id="portfolio">
  <div class="pt-60px pb-30px md:pt-20 md:pb-60px lg:pt-100px lg:pb-20">
    <div class="container">
      <!-- section heading -->
      <x-section-header title="{{ $collections['our_works'][0]->postCategory->name }}" description="{{ $collections['our_works'][0]->postCategory->description }}" />
      <!-- portfolio -->
      <div class="portfolio-filter">
        <!-- Filter Controls -->
        <div class="flex items-center" dir="ltr">
          <div class="button-group filter-button-group mx-auto inline-flex items-center justify-center bg-cream-light-color dark:bg-black rounded-full px-2 py-1 md:py-1.5 relative z-0 wow fadeInUp" data-wow-delay=".5s">
            {{-- <button data-filter="*" class="text-sm md:text-size-15 px-3  py-10px md:py-3 text-primary-color dark:text-white-color leading-1 active">
                                  {{ __('All') }}
            </button> --}}
            {{-- {{ dd($collections['our_works'][0]->metadata[0]['value']) }} --}}
            @foreach(collect($collections['our_works'])->pluck('metadata')->flatten(1)->unique('value') as $category)
            @if(!empty($category['value']))
            <button data-filter=".{{ Str::slug($category['value']) }}" class="text-sm md:text-size-15 px-3  py-10px md:py-3 text-primary-color dark:text-white-color leading-1">
              {{ $category['value'] }}
            </button>
            @endif
            @endforeach
            <button data-filter="*" class="text-sm md:text-size-15 px-3  py-10px md:py-3 text-primary-color dark:text-white-color leading-1 active">
              {{ __('All') }}
            </button>
            <div class="active-bg !-z-1"></div>
          </div>
        </div>
        <!-- Portfolio Items -->
        <div class="portfolio-box mt-10 md:mt-50px wow fadeInUp" data-wow-delay=".6s">
          <div class="portfolio-sizer"></div>
          <div class="gutter-sizer"></div>
          @foreach($collections['our_works'] as $post)
          @php
          $metadata = $post->metadata;
          $categories = collect($metadata)->pluck('value')->map(function ($value) {
          return Str::slug($value);
          })->implode(' ');
          $filterClass = $categories;
          @endphp
          <div class="portfolio-item {{ $filterClass }} bg-primary-color-light px-15px pt-25px pb-0 lg:p-5 lg:pb-0 rounded-10px group relative rtl:float-right ltr:float-left inline-flex">
            <img src="{{ asset($post->image?->url ?? $post->getRandomImage()) }}" alt="{{ $post->title }}" />
            <div class="absolute left-0 rtl:right-0 rtl:left-auto bottom-[15px] group-hover:bottom-5 translate-y-5 group-hover:translate-y-0 opacity-0 invisible group-hover:opacity-100 group-hover:visible w-full group-hover: px-15px lg:px-5 transition-all duration-300">
              <a href="{{ route('posts.show',[$post->postCategory->slug , $post->slug]) }}" class="text-white-color p-15px rtl:pl-30px lg:p-5 rtl:lg:pl-50px pr-30px lg:p-5 lg:pr-50px bg-gradient-primary rounded-15px w-full">
                <span class="block text-xl md:text-size-25 lg:text-3xl font-bold mb-2 lg:mb-15px">
                  {{ $post->title }}
                </span>
                <span class="block text-body-color">
                  {{ $post->excerpt ?? Str::limit($post->content, 100) }}
                </span>
                <i class="flaticon-up-right-arrow text-size-15 md:text-xl text-primary-color group-hover:text-white-color absolute top-[20%] lg:top-1/2 right-5 lg:right-[55px] rtl:right-auto rtl:left-5 lg:rtl:left-[55px] lg:rtl:right-auto transition-all duration-300"></i>
              </a>
            </div>
          </div>
          @endforeach
        </div>
      </div>
      {{-- @push('scripts')
                  <script>
                      document.addEventListener('DOMContentLoaded', function() {
                          // Initialize Isotope
                          const portfolioBox = document.querySelector('.portfolio-box');
                          if (portfolioBox) {
                              const iso = new Isotope(portfolioBox, {
                                  itemSelector: '.portfolio-item'
                                  , percentPosition: true
                                  , masonry: {
                                      columnWidth: '.portfolio-sizer'
                                      , gutter: '.gutter-sizer'
                                  }
                              });

                              // Filter items on button click
                              const filterButtons = document.querySelectorAll('.filter-button-group button');
                              filterButtons.forEach(button => {
                                  button.addEventListener('click', function() {
                                      // Remove active class from all buttons
                                      filterButtons.forEach(btn => btn.classList.remove('active'));
                                      // Add active class to clicked button
                                      this.classList.add('active');

                                      const filterValue = this.getAttribute('data-filter');
                                      iso.arrange({
                                          filter: filterValue
                                      });
                                  });
                              });
                          }
                      });

                  </script>
                  @endpush --}}
    </div>
  </div>
</section>
