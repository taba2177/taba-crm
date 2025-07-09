<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Place favicon.ico in the root directory -->
  <link rel="shortcut icon" href="{{ asset("/assets/img/favicon.png") }}" type="image/x-icon" />
  <!-- CSS here -->
  <link rel="stylesheet" href="{{ asset("/assets/css/animate.min.css") }}" />
  <link rel="stylesheet" href="{{ asset("/assets/css/font-awesome-pro.min.css") }}" />
  <link rel="stylesheet" href="{{ asset("/assets/css/flaticon_gerold.css") }}" />
  <link rel="stylesheet" href="{{ asset("/assets/css/nice-select.css") }}" />
  <link rel="stylesheet" href="{{ asset("/assets/css/backToTop.css") }}" />
  <link rel="stylesheet" href="{{ asset("/assets/css/owl.carousel.min.css") }}" />
  <link rel="stylesheet" href="{{ asset("/assets/css/swiper.min.css") }}" />
  {{-- <link rel="stylesheet" href="{{ asset("/assets/css/odometer-theme-default.css") }}" /> --}}
  <link rel="stylesheet" href="{{ asset("/assets/css/magnific-popup.css") }}" />
  <link rel="stylesheet" href="{{ asset("/assets/css/nice-select.css") }}" />
  <link rel="stylesheet" href="{{ asset("/assets/css/backToTop.css") }}" />
  <link rel="stylesheet" href="{{ asset("/assets/css/style.css") }}" />
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  @livewireStyles

  {{ seo()->render() }}

</head>

{{-- <body class="font-sans antialiased"> --}}
<body class="font-sora dark:bg-dark-color">
  <div class="main-wrapper">
    <!-- Preloader Area Start -->

    <div class="preloader">
      <svg viewBox="0 0 1000 1000" preserveAspectRatio="none">
        <path id="preloaderSvg" d="M0,1005S175,995,500,995s500,5,500,5V0H0Z"></path>
      </svg>

      <div class="preloader-heading">
        <div class="load-text">
          <span>L</span>
          <span>o</span>
          <span>a</span>
          <span>d</span>
          <span>i</span>
          <span>n</span>
          <span>g</span>
        </div>
      </div>
    </div>
    <!-- Preloader Area End -->
    <!-- theme controller -->
    <div class="fixed top-[200px] lg:top-[300px] transition-all duration-300 right-[-50px] hover:right-0 z-4xl">
      <button class="theme-controller w-90px h-10 bg-200 bg-gradient-secondary hover:bg-[-100%] rounded-l-full text-whiteColor px-10px flex items-center transition-all duration-300 font-sora">
        <span class="text-base block dark:hidden">Dark</span>


        <svg xmlns="http://www.w3.org/2000/svg" class="mr-10px w-5 block dark:hidden" viewBox="0 0 512 512">
          <path d="M160 136c0-30.62 4.51-61.61 16-88C99.57 81.27 48 159.32 48 248c0 119.29 96.71 216 216 216 88.68 0 166.73-51.57 200-128-26.39 11.49-57.38 16-88 16-119.29 0-216-96.71-216-216z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"></path>
        </svg>
        <span class="text-base hidden dark:block">Light</span>


        <svg xmlns="http://www.w3.org/2000/svg" class="hidden mr-10px w-5 dark:block" viewBox="0 0 512 512">
          <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M256 48v48M256 416v48M403.08 108.92l-33.94 33.94M142.86 369.14l-33.94 33.94M464 256h-48M96 256H48M403.08 403.08l-33.94-33.94M142.86 142.86l-33.94-33.94">
          </path>
          <circle cx="256" cy="256" r="80" fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32"></circle>
        </svg>
      </button>
    </div>
    <!-- start: Back To Top -->
    <div class="progress-wrap" id="scrollUp">
      <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
      </svg>
    </div>
    {{-- <!-- header area  -->
    <header class="header-area header-absolute">
      <div class="pt-15px xl:pt-5 pb-5 md:pb-30px xl:pb-5 relative">
        <div class="container">
          <div class="flex flex-wrap justify-between items-center">
            <!-- logo and contact email -->
            <div>
              <ul class="flex items-center gap-x-15px xl:gap-x-35px">
                <li class="leading-1">
                  <a href="index.html" class="logo">
                    <img class="w-15 h-15 inline-block" src="/assets/img/logo/logo.png" alt="" />
                  </a>
                </li>
                <li class="hidden md:block">
                  <a href="mailto:info@bat-tourmarketing.com" class="text-size-15 font-medium text-white-color">info@bat-tourmarketing.com</a>
                </li>
              </ul>
            </div>
            <!-- main menu -->
            <nav>
              <ul class="nav flex items-center gap-x-5 xl:gap-x-30px 2xl:gap-x-45px">
                <li class="nav_item group relative hidden lg:block">
                  <a href="index.html#services" class="text-size-15 font-medium text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Services
                  </a>
                </li>
                <li class="nav_item group relative hidden lg:block">
                  <a href="index.html#portfolio" class="text-size-15 font-medium text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Works
                  </a>
                </li>
                <li class="nav_item group relative hidden lg:block">
                  <a href="index.html#resume" class="text-size-15 font-medium text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Resume
                  </a>
                </li>
                <li class="nav_item group relative hidden lg:block">
                  <a href="index.html#skills" class="text-size-15 font-medium text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Skills
                  </a>
                </li>
                <li class="nav_item group relative hidden lg:block">
                  <a href="index.html#testimonials" class="text-size-15 font-medium text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Testimonials
                  </a>
                </li>

                <li class="nav_item group relative hidden lg:block">
                  <a href="index.html#contact" class="text-size-15 font-medium text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Contact
                  </a>
                </li>
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
                <a href="{{ url("about") }}" class="text-size-15 font-bold text-white-color capitalize py-17px px-35px ml-10px bg-200 bg-gradient-secondary hover:bg-[-100%] rounded-full leading-1 transition-all duration-300">تواصل معنا</a>
    </li>
    </ul>

  </div>
  </div>
  <!-- mobile menu -->
  <!-- scale-y-0 transition-all duration-500 hover:scale-y-100 -->
  <div class="mobile-menu absolute left-0 top-full min-h-screen-90 w-full bg-seondary-color block origin-top-left lg:hidden">
    <div class="container py-5">
      <ul class="ml-4">
        <li>
          <a class="text-size-25 text-white-color uppercase leading-1.2 py-15px" href="index.html#services">
            Services
          </a>
        </li>
        <li>
          <a class="text-size-25 text-white-color uppercase leading-1.2 py-15px" href="index.html#portfolio">
            Works
          </a>
        </li>
        <li>
          <a class="text-size-25 text-white-color uppercase leading-1.2 py-15px" href="index.html#resume">
            Resume
          </a>
        </li>
        <li>
          <a class="text-size-25 text-white-color uppercase leading-1.2 py-15px" href="index.html#skills">
            Skills
          </a>
        </li>
        <li>
          <a class="text-size-25 text-white-color uppercase leading-1.2 py-15px" href="index.html#testimonials">
            Testimonials
          </a>
        </li>

        <li>
          <a class="text-size-25 text-white-color uppercase leading-1.2 py-15px" href="index.html#contact">
            Contact
          </a>
        </li>
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
                <a href="{{ url("index.html") }}" class="logo">
                  <img class="w-15 h-15 hidden dark:inline-block" src="{{ asset("/assets/img/logo/logo.png") }}" alt="" />
                  <img class="w-15 h-15 inlin-block dark:hidden" src="{{ asset("/assets/img/logo/logo-dark.png") }}" alt="" />
                </a>
              </li>

            </ul>
          </div>
          <!-- main menu -->
          <nav>
            <ul class="nav flex items-center gap-x-5 xl:gap-x-30px 2xl:gap-x-45px">
              <li class="nav_item group relative hidden lg:block">
                <a href="{{ url("#services") }}" class="text-size-15 font-medium text-seondary-color dark:text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Services
                </a>
              </li>
              <li class="nav_item group relative hidden lg:block">
                <a href="{{ url("#portfolio") }}" class="text-size-15 font-medium text-seondary-color dark:text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Works
                </a>
              </li>
              <li class="nav_item group relative hidden lg:block">
                <a href="{{ url("#resume") }}" class="text-size-15 font-medium text-seondary-color dark:text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Resume
                </a>
              </li>
              <li class="nav_item group relative hidden lg:block">
                <a href="{{ url("#skills") }}" class="text-size-15 font-medium text-seondary-color dark:text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Skills
                </a>
              </li>
              <li class="nav_item group relative hidden lg:block">
                <a href="{{ url("#testimonials") }}" class="text-size-15 font-medium text-seondary-color dark:text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Testimonials
                </a>
              </li>

              <li class="nav_item group relative hidden lg:block">
                <a href="{{ url("#contact") }}" class="text-size-15 font-medium text-seondary-color dark:text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Contact
                </a>
              </li>
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
              <a href="{{ url("about") }}" class="text-size-15 font-bold text-white-color capitalize py-17px px-35px ml-10px bg-200 bg-gradient-secondary hover:bg-[-100%] rounded-full leading-1 transition-all duration-300">تواصل معنا</a>
            </li>
          </ul>

        </div>
      </div>
      <!-- mobile menu -->
      <div class="mobile-menu absolute left-0 top-full min-h-screen-90 w-full bg-seondary-color block origin-top-left lg:hidden">
        <div class="container py-5">
          <ul class="ml-4">
            <li>
              <a class="text-size-25 text-white-color uppercase leading-1.2 py-15px" href="{{ url("#services") }}">
                Services
              </a>
            </li>
            <li>
              <a class="text-size-25 text-white-color uppercase leading-1.2 py-15px" href="{{ url("#portfolio") }}">
                Works
              </a>
            </li>
            <li>
              <a class="text-size-25 text-white-color uppercase leading-1.2 py-15px" href="{{ url("#resume") }}">
                Resume
              </a>
            </li>
            <li>
              <a class="text-size-25 text-white-color uppercase leading-1.2 py-15px" href="{{ url("#skills") }}">
                Skills
              </a>
            </li>
            <li>
              <a class="text-size-25 text-white-color uppercase leading-1.2 py-15px" href="{{ url("#testimonials") }}">
                Testimonials
              </a>
            </li>

            <li>
              <a class="text-size-25 text-white-color uppercase leading-1.2 py-15px" href="{{ url("#contact") }}">
                Contact
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </header> --}}

  <x-main-header />

  {{ $slot }}

  <x-footer />

  {{-- <footer>
    <div class="footer-inner bg-seondary-color dark:bg-dark-color">
      <div class="container">
        <div class="flex flex-col items-center pt-50px pb-5 md:pt-60px">
          <div class="footer-logo w-75px h-75px mb-6">
            <a href="{{ url("#") }}"><img src="{{ asset("/assets/img/logo/logo.png") }}" alt="" /></a>
  </div>
  <!-- nav -->
  <div>
    <ul class="nav flex flex-wrap justify-center items-center gap-x-35px">

      <li class="nav_item group relative">
        <a href="{{ url("#services") }}" class="text-size-15 font-medium text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[10px] md:after:bottom-[15px] lg:after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Services
        </a>
      </li>
      <li class="nav_item group relative">
        <a href="{{ url("#portfolio") }}" class="text-size-15 font-medium text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[10px] md:after:bottom-[15px] lg:after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Portfolios
        </a>
      </li>

      <li class="nav_item group relative">
        <a href="{{ url("#resume") }}" class="text-size-15 font-medium text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[10px] md:after:bottom-[15px] lg:after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Experience
        </a>
      </li>
      <li class="nav_item group relative">
        <a href="{{ url("#skills") }}" class="text-size-15 font-medium text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[10px] md:after:bottom-[15px] lg:after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Skills
        </a>
      </li>

      <li class="nav_item group relative">
        <a href="{{ url("#blogs") }}" class="text-size-15 font-medium text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[10px] md:after:bottom-[15px] lg:after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Blog
        </a>
      </li>
    </ul>
  </div>
  <div class="copyright text-gray-color whitespace-nowrap text-sm md:text-base mt-5">
    © 2024 All rights reserved by
    <a href="{{ url("index.html") }}" class="text-white-color hover:text-primary-color">Battour marketting</a>
  </div>
  </div>
  </div>
  </div>
  </footer> --}}
  </div>
  @livewireScripts
  <!-- JSS here -->
  <script src="{{ asset("/assets/js/jquery.min.js") }}"></script>
  <script src="{{ asset("/assets/js/gsap.min.js") }}"></script>
  <script src="{{ asset("/assets/js/gsap-scroll-to-plugin.min.js") }}"></script>
  <script src="{{ asset("/assets/js/gsap-scroll-trigger.min.js") }}"></script>
  <script src="{{ asset("/assets/js/gsap-split-text.min.js") }}"></script>
  <script src="{{ asset("/assets/js/appear.min.js") }}"></script>
  <script src="{{ asset("/assets/js/wow.min.js") }}"></script>
  {{-- <script src="{{ asset("/assets/js/odometer.min.js") }}"></script> --}}
  <script src="{{ asset("/assets/js/imagesloaded-pkgd.js") }}"></script>
  <script src="{{ asset("/assets/js/isotope.pkgd.min.js") }}"></script>
  <script src="{{ asset("/assets/js/owl.carousel.min.js") }}"></script>
  <script src="{{ asset("/assets/js/nice-select.min.js") }}"></script>
  <script src="{{ asset("/assets/js/backToTop.js") }}"></script>
  <script src="{{ asset("/assets/js/lenis.min.js") }}"></script>
  <script src="{{ asset("/assets/js/swiper.min.js") }}"></script>
  <script src="{{ asset("/assets/js/theme-controller.js") }}"></script>
  <script src="{{ asset("/assets/js/vanilla-tilt.min.js") }}"></script>
  <script src="{{ asset("/assets/js/sticky.min.js") }}"></script>
  <script src="{{ asset("/assets/js/lightcase.js") }}"></script>
  <script src="{{ asset("/assets/js/validate.min.js") }}"></script>
  <script src="{{ asset("/assets/js/main.js") }}"></script>
  <script src="{{ asset("/assets/js/tj-cursor.js") }}">
  </script>

</body>
</html>
