@php
$categories = \App\Models\PostCategory::RegisterInHeader();

@endphp

<footer>
  <div class="footer-inner bg-seondary-color dark:bg-dark-color">
    <div class="container">
      <div class="flex flex-col items-center pt-50px pb-5 md:pt-60px">
        <div class="footer-logo w-75px h-75px mb-6">
          <a href="{{ url("/") }}"><img src="{{ asset("/assets/img/logo/logo.png") }}" alt=" " class="w-full h-full object-cover" /></a>
        </div>
        <!-- nav -->
        <div>
          <ul class="nav flex flex-wrap justify-center items-center gap-x-35px">
            @foreach ($categories as $category)
            <li class="nav_item group relative">
              <a href="{{ route('dynamic.route', ['slug' => $category->slug]) }}" class="text-size-15 font-medium text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[10px] md:after:bottom-[15px] lg:after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">{{$category->name}}

              </a>
            </li>
            @endforeach
          </ul>
        </div>
        <div class="copyright text-gray-color whitespace-nowrap text-sm md:text-base mt-5">
          © 2024 All rights reserved by
          <a href="{{ url("/") }}" class="text-white-color hover:text-primary-color">{{ __('Battour marketting') }}</a>
        </div>
      </div>
    </div>
  </div>
</footer>
