{{-- get categories  --}}
@php
$categories = \App\Models\PostCategory::RegisterInHeader();


@endphp
<!-- header area  -->
<header class="header-area header-absolute rtl:right-0 rtl:left-none">
  <div class="pt-15px xl:pt-5 pb-5 md:pb-30px xl:pb-5 relative">
    <div class="container">
      <div class="flex flex-wrap justify-between items-center">
        <!-- logo and contact email -->
        <div>
          <ul class="flex items-center gap-x-15px xl:gap-x-35px">
            <li class="leading-1">
              <a href="{{ url("/") }}" class="logo">
                <img class="w-15 h-15 hidden dark:inline-block" src="{{ asset("/assets/img/logo/logo.png") }}" alt="" />
                <img class="w-15 h-15 inlin-block dark:hidden" src="{{ asset("/assets/img/logo/logo.png") }}" alt="" />
              </a>
            </li>
            <li class="hidden md:block">
              <a href="{{ url("mailto:info@bat-tourmarketing.com") }}"
                class="text-size-15 font-medium text-white-color dark:text-white-color">info@bat-tourmarketing.com</a>
            </li>
          </ul>
        </div>
        <!-- main menu -->
        <nav>
          <ul class="nav flex items-center gap-x-5 xl:gap-x-30px 2xl:gap-x-45px">
            <li class="nav_item group relative hidden lg:block">
              <a href="/"
                class="text-size-15 font-medium text-white-color dark:text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">{{__('home')}}
              </a>
            </li>
            @foreach ($categories as $category)
            <li class="nav_item group relative hidden lg:block">
              <a href="{{ route('dynamic.route', ['slug' => $category->slug]) }}"
                class="text-size-15 font-medium text-white-color dark:text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">{{$category->name}}
              </a>
            </li>
            @endforeach
            <!-- open mobile menu button -->
            <li class="menu-bar lg:hidden">
              <div class="menu-bar">
                <button>
                  <span></span>
                  <span></span>
                  <span></span>
                  <span></span>
                </button>
              </div>
            </li>
          </ul>
        </nav>
        <ul>
          <li>
            <a href="{{ route('lang.switch', ['lang' => app()->getlocale() == 'ar' ? 'en' : 'ar']) }}"
              class="text-size-15 font-medium text-seondary-color border-1 border-color-primary dark:text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">
              <i class="fas fa-globe mr-1"></i>
              {{ __('lang.arabic') }}
            </a>
          </li>
        </ul>
        <ul>
          <li>
            <a href="{{ url("contact") }}"
              class="text-size-15 font-bold text-white-color capitalize py-17px px-35px ml-10px bg-200 bg-gradient-secondary hover:bg-[-100%] rounded-full leading-1 transition-all duration-300">
              تواصل معنا</a>
          </li>
        </ul>
      </div>
    </div>
    <!-- mobile menu -->
    <!-- scale-y-0 transition-all duration-500 hover:scale-y-100 -->
    <div
      class="mobile-menu absolute left-0 top-full min-h-screen-90 w-full bg-seondary-color block origin-top-left lg:hidden">
      <div class="container py-5">
        <ul class="ml-4">
          <li class="nav_item group relative hidden lg:block">
            <a href="/"
              class="text-size-15 font-medium text-white-color dark:text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">{{__('home')}}
            </a>
          </li>

          @foreach ($categories as $category)
          <li>
            <a class="text-size-25 text-white-color uppercase leading-1.2 py-15px"
              href="{{ route('dynamic.route', ['slug' => $category->slug]) }}">
              {{$category->name}}
            </a>
          </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</header>

<!-- sticky -->
<header class="header-area header-2 header-sticky">
  <div class="py-10px relative">
    <div class="container">
      <div class="flex flex-wrap justify-between items-center">
        <!-- logo and contact email -->
        <div>
          <ul class="flex items-center gap-x-15px xl:gap-x-35px">
            <li class="leading-1">
              <a href="{{ url("/") }}" class="logo">
                <img class="w-15 h-15 hidden dark:inline-block" src="{{ asset("/assets/img/logo/logo.png") }}" alt="" />
                <img class="w-15 h-15 inlin-block dark:hidden" src="{{ asset("/assets/img/logo/logo-dark.png") }}"
                  alt="" />
              </a>
            </li>
            <li class="hidden md:block">
              <a href="{{ url("mailto:info@bat-tourmarketing.com") }}"
                class="text-size-15 font-medium text-seondary-color dark:text-white-color">info@bat-tourmarketing.com</a>
            </li>
          </ul>
        </div>
        <!-- main menu -->
        <nav>
          <ul class="nav flex items-center gap-x-5 xl:gap-x-30px 2xl:gap-x-45px">
            <li class="nav_item group relative hidden lg:block">
              <a href="/"
                class="text-size-15 font-medium text-seondary-color dark:text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">{{__('home')}}
              </a>
            </li>

            @foreach ($categories as $category)
            <li class="nav_item group relative hidden lg:block">
              <a href="{{ route('dynamic.route', ['slug' => $category->slug]) }}"
                class="text-size-15 font-medium text-seondary-color dark:text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">{{$category->name}}
              </a>
            </li>
            @endforeach
            <!-- open mobile menu button -->
            <li class="menu-bar lg:hidden">
              <div class="menu-bar">
                <button>
                  <span></span>
                  <span></span>
                  <span></span>
                  <span></span>
                </button>
              </div>
            </li>
          </ul>
        </nav>
        <ul>
          <li>
            <a href="{{ route('lang.switch', ['lang' => app()->getlocale() == 'ar' ? 'en' : 'ar']) }}"
              class="text-size-15 font-medium text-seondary-color border-1 border-color-primary dark:text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">
              <i class="fas fa-globe mr-1"></i>
              {{ __('lang.arabic') }}
            </a>
          </li>
        </ul>
      </div>
    </div>
    <!-- mobile menu -->
    <!-- scale-y-0 transition-all duration-500 hover:scale-y-100 -->
    <div
      class="mobile-menu absolute left-0 top-full min-h-screen-90 w-full bg-seondary-color block origin-top-left lg:hidden">
      <div class="container py-5">
        <ul class="ml-4">
          <li>
            <a class="text-size-25 text-white-color uppercase leading-1.2 py-15px" href="/">
              {{ __('home')}}
            </a>
          </li>
          @foreach ($categories as $category)
          <li>
            <a class="text-size-25 text-white-color uppercase leading-1.2 py-15px"
              href="{{ route('dynamic.route', ['slug' => $category->slug]) }}">
              {{$category->name}}
            </a>
          </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</header>