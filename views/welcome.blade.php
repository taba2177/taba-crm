<!DOCTYPE html>
<html lang="ar" class="dark overflow-x-hidden" dir="rtl">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gerold - Personal Portfolio Tailwind CSS Template</title>
  <!-- Place favicon.ico in the root directory -->
  <link rel="shortcut icon" href="./assets/img/favicon.png" type="image/x-icon" />
  <!-- CSS here -->
  <link rel="stylesheet" href="./assets/css/animate.min.css" />
  <link rel="stylesheet" href="./assets/css/font-awesome-pro.min.css" />
  <link rel="stylesheet" href="./assets/css/flaticon_gerold.css" />
  <link rel="stylesheet" href="./assets/css/nice-select.css" />
  <link rel="stylesheet" href="./assets/css/backToTop.css" />
  <link rel="stylesheet" href="./assets/css/owl.carousel.min.css" />
  <link rel="stylesheet" href="./assets/css/swiper.min.css" />
  <link rel="stylesheet" href="./assets/css/odometer-theme-default.css" />
  <link rel="stylesheet" href="./assets/css/magnific-popup.css" />
  <link rel="stylesheet" href="./assets/css/nice-select.css" />
  <link rel="stylesheet" href="./assets/css/backToTop.css" />
  <link rel="stylesheet" href="./assets/css/style.css" />
  @vite(['resources/css/app.css', 'resources/js/app.js'])


</head>

<body class="font-sora dark:bg-dark-color overflow-x-hidden relative">
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
  <!-- header area  -->
  <header class="header-area header-absolute">
    <div class="pt-15px xl:pt-5 pb-5 md:pb-30px xl:pb-5 relative">
      <div class="container">
        <div class="flex flex-wrap justify-between items-center">
          <!-- logo and contact email -->
          <div>
            <ul class="flex items-center gap-x-15px xl:gap-x-35px">
              <li class="leading-1">
                <a href="index.html" class="logo">
                  <img class="w-15 h-15 hidden dark:inline-block" src="./assets/img/logo/logo.png" alt="" />
                  <img class="w-15 h-15 inlin-block dark:hidden" src="./assets/img/logo/logo-dark.png" alt="" />
                </a>
              </li>
              {{-- <li class="hidden md:block">
                                <a href="mailto:info@bat-tourmarketing.com" class="text-size-15 font-medium text-seondary-color dark:text-white-color">info@bat-tourmarketing.com</a>
                            </li> --}}
            </ul>
          </div>
          <!-- main menu -->
          <nav>
            <ul class="nav flex items-center gap-x-5 xl:gap-x-30px 2xl:gap-x-45px">
              <li class="nav_item group relative hidden lg:block">
                <a href="#services" class="text-size-15 font-medium text-seondary-color dark:text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Services
                </a>
              </li>
              <li class="nav_item group relative hidden lg:block">
                <a href="#portfolio" class="text-size-15 font-medium text-seondary-color dark:text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Works
                </a>
              </li>
              <li class="nav_item group relative hidden lg:block">
                <a href="#resume" class="text-size-15 font-medium text-seondary-color dark:text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Resume
                </a>
              </li>
              <li class="nav_item group relative hidden lg:block">
                <a href="#skills" class="text-size-15 font-medium text-seondary-color dark:text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Skills
                </a>
              </li>
              <li class="nav_item group relative hidden lg:block">
                <a href="#testimonials" class="text-size-15 font-medium text-seondary-color dark:text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Testimonials
                </a>
              </li>

              <li class="nav_item group relative hidden lg:block">
                <a href="#contact" class="text-size-15 font-medium text-seondary-color dark:text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Contact
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
        </div>
      </div>
      <!-- mobile menu -->
      <!-- scale-y-0 transition-all duration-500 hover:scale-y-100 -->
      <div class="mobile-menu absolute left-0 top-full min-h-screen-90 w-full bg-seondary-color block origin-top-left lg:hidden">
        <div class="container py-5">
          <ul class="ml-4">
            <li>
              <a class="text-size-25 text-white-color uppercase leading-1.2 py-15px" href="#services">
                Services
              </a>
            </li>
            <li>
              <a class="text-size-25 text-white-color uppercase leading-1.2 py-15px" href="#portfolio">
                Works
              </a>
            </li>
            <li>
              <a class="text-size-25 text-white-color uppercase leading-1.2 py-15px" href="#resume">
                Resume
              </a>
            </li>
            <li>
              <a class="text-size-25 text-white-color uppercase leading-1.2 py-15px" href="#skills">
                Skills
              </a>
            </li>
            <li>
              <a class="text-size-25 text-white-color uppercase leading-1.2 py-15px" href="#testimonials">
                Testimonials
              </a>
            </li>

            <li>
              <a class="text-size-25 text-white-color uppercase leading-1.2 py-15px" href="#contact">
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
                <a href="index.html" class="logo">
                  <img class="w-15 h-15 hidden dark:inline-block" src="./assets/img/logo/logo.png" alt="" />
                  <img class="w-15 h-15 inlin-block dark:hidden" src="./assets/img/logo/logo-dark.png" alt="" />
                </a>
              </li>
              {{-- <li class="hidden md:block">
                                <a href="mailto:info@bat-tourmarketing.com" class="text-size-15 font-medium text-seondary-color dark:text-white-color">info@bat-tourmarketing.com</a>
                            </li> --}}
            </ul>
          </div>
          <!-- main menu -->
          <nav>
            <ul class="nav flex items-center gap-x-5 xl:gap-x-30px 2xl:gap-x-45px">
              <li class="nav_item group relative hidden lg:block">
                <a href="#services" class="text-size-15 font-medium text-seondary-color dark:text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Services
                </a>
              </li>
              <li class="nav_item group relative hidden lg:block">
                <a href="#portfolio" class="text-size-15 font-medium text-seondary-color dark:text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Works
                </a>
              </li>
              <li class="nav_item group relative hidden lg:block">
                <a href="#resume" class="text-size-15 font-medium text-seondary-color dark:text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Resume
                </a>
              </li>
              <li class="nav_item group relative hidden lg:block">
                <a href="#skills" class="text-size-15 font-medium text-seondary-color dark:text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Skills
                </a>
              </li>
              <li class="nav_item group relative hidden lg:block">
                <a href="#testimonials" class="text-size-15 font-medium text-seondary-color dark:text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Testimonials
                </a>
              </li>

              <li class="nav_item group relative hidden lg:block">
                <a href="#contact" class="text-size-15 font-medium text-seondary-color dark:text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Contact
                </a>
              </li>
              <!-- action button -->
              <li>
                <a href="{{ url('about')) }}" class="text-size-15 font-bold text-white-color capitalize py-17px px-35px ml-10px bg-200 bg-gradient-secondary hover:bg-[-100%] rounded-full leading-1 transition-all duration-300">Hire

                  Me!</a>
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
        </div>
      </div>
      <!-- mobile menu -->
      <!-- scale-y-0 transition-all duration-500 hover:scale-y-100 -->
      <div class="mobile-menu absolute left-0 top-full min-h-screen-90 w-full bg-seondary-color block origin-top-left lg:hidden">
        <div class="container py-5">
          <ul class="ml-4">
            <li>
              <a class="text-size-25 text-white-color uppercase leading-1.2 py-15px" href="#services">
                Services
              </a>
            </li>
            <li>
              <a class="text-size-25 text-white-color uppercase leading-1.2 py-15px" href="#portfolio">
                Works
              </a>
            </li>
            <li>
              <a class="text-size-25 text-white-color uppercase leading-1.2 py-15px" href="#resume">
                Resume
              </a>
            </li>
            <li>
              <a class="text-size-25 text-white-color uppercase leading-1.2 py-15px" href="#skills">
                Skills
              </a>
            </li>
            <li>
              <a class="text-size-25 text-white-color uppercase leading-1.2 py-15px" href="#testimonials">
                Testimonials
              </a>
            </li>

            <li>
              <a class="text-size-25 text-white-color uppercase leading-1.2 py-15px" href="#contact">
                Contact
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </header>
  <div class="main-wrapper">
    <!-- hero banner  -->
    <section class="hero-section relative pt-130px lg:pt-40 xl:pt-200px pb-10 md:pb-30px lg:pb-50px after:absolute after:top-0 after:right-0 after:w-322px after:h-308px after:blur-[150px] after:rounded-50% after:bg-gradient-circle after:-z-1 after:-mt-5% after:-mr-5% overflow-hidden">
      <!-- intro tex -->
      <div class="intro_text">
        <svg viewBox="0 0 1320 300" class="overflow-hidden">
          <text x="50%" y="50%" text-anchor="middle" class="animate-stroke">
            HI
          </text>
        </svg>
      </div>
      <div class="container">
        <div class="hidded md:grid md:grid-cols-2 md:items-center gap-30px">
          <div>
            <h4 class="text-seondary-color dark:text-body-color text-size-22 md:text-size-25 lg:text-4xl lg:leading-1.5 font-bold mb-1.5 xl:mb-10px">
              I am Gerold
            </h4>
            <h1 class="text-size-35 md:text-size-38 lg:text-size-50 xl:text-6xl 2xl:text-size-65 bg-gradient-text-light dark:bg-gradient-text bg-clip-text xl:leading-1.2 text-transparent mb-15px">
              Next-Level Web <br />
              Developer.
            </h1>
            <div class="flex md:hidden justify-center items-center my-30px">
              <img src="./assets/img/hero/me.png" alt="banner image" class="rounded-38px border-2 border-seondary-color hover:border-primary-color rotate-[4.29deg] hover:rotate-0 transition-all duration-300 max-w-[80%]" />
            </div>
            <p class="text-xl leading-1.5 text-primary-color-light dark:text-body-color max-w-540px">
              I break down complex user experinece problems to create
              integritiy focussed solutions that connect billions of people
            </p>
            <!-- action and social -->
            <div class="flex items-center gap-30px lg:gap-25px mt-5 flex-wrap lg:flex-nowrap md:mt-30px lg:mt-50px">
              <div>
                <a href="#" class="text-size-15 font-medium text-primary-color hover:text-body-color capitalize py-17px px-35px bg-200 bg-transparent hover:bg-primary-color rounded-full leading-1 border border-primary-color text-nowrap tracking-1px">Download
                  CV
                  <i class="flaticon-download ml-0.5 text-size-17"></i></a>
              </div>
              <div>
                <ul class="flex gap-x-5">
                  <li>
                    <a href="#" class="text-primary-color hover:text-body-color border border-primary-color w-35px h-35px rounded-full flex items-center justify-center overflow-hidden relative z-0 after:absolute after:top-1/2 after:left-1/2 after:-translate-x-1/2 after:-translate-y-1/2 after:w-full after:h-full after:scale-0 after:bg-primary-color hover:after:scale-105 after:transition-all after:duration-300 after:z-[-1] after:rounded-full"><i class="fa-brands fa-twitter"></i></a>
                  </li>
                  <li>
                    <a href="#" class="text-primary-color hover:text-body-color border border-primary-color w-35px h-35px rounded-full flex items-center justify-center overflow-hidden relative z-0 after:absolute after:top-1/2 after:left-1/2 after:-translate-x-1/2 after:-translate-y-1/2 after:w-full after:h-full after:scale-0 after:bg-primary-color hover:after:scale-105 after:transition-all after:duration-300 after:z-[-1] after:rounded-full"><i class="fa-light fa-basketball"></i></a>
                  </li>
                  <li>
                    <a href="#" class="text-primary-color hover:text-body-color border border-primary-color w-35px h-35px rounded-full flex items-center justify-center overflow-hidden relative z-0 after:absolute after:top-1/2 after:left-1/2 after:-translate-x-1/2 after:-translate-y-1/2 after:w-full after:h-full after:scale-0 after:bg-primary-color hover:after:scale-105 after:transition-all after:duration-300 after:z-[-1] after:rounded-full"><i class="fa-brands fa-linkedin-in"></i></a>
                  </li>
                  <li>
                    <a href="#" class="text-primary-color hover:text-body-color border border-primary-color w-35px h-35px rounded-full flex items-center justify-center overflow-hidden relative z-0 after:absolute after:top-1/2 after:left-1/2 after:-translate-x-1/2 after:-translate-y-1/2 after:w-full after:h-full after:scale-0 after:bg-primary-color hover:after:scale-105 after:transition-all after:duration-300 after:z-[-1] after:rounded-full"><i class="fa-brands fa-github"></i></a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="hidden md:flex md:justify-center md:items-center relative after:absolute after:bottom-0 after:left-0 after:w-220px after:h-220px after:blur-[150px] after:rounded-50% after:bg-gradient-circle after:-z-1 after:-mt-5% after:-mr-5%">
            <img src="./assets/img/hero/me.png" alt="banner image" class="rounded-38px border-2 border-seondary-color hover:border-primary-color rotate-[4.29deg] hover:rotate-0 transition-all duration-300" />
          </div>
        </div>
      </div>
      <!-- fanfact -->
      <div class="funfact-area mt-60px xl:mt-70px 2xl:mt-30">
        <div class="container">
          <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-30px text-primary-color dark:text-body-color">
            <div class="funfact-item flex flex-wrap sm:flex-nowrap flex-col sm:flex-row justify-center lg:justify-start items-center gap-15px">
              <div class="number text-size-45 md:text-size-55 xl:text-size-64 font-medium">
                <span class="odometer !font-sora tracking-[0.04em]" data-count="14">0</span>
              </div>
              <div class="text">Years of <br />Experience</div>
            </div>

            <div class="funfact-item flex flex-wrap sm:flex-nowrap flex-col sm:flex-row justify-center lg:justify-start items-center gap-15px">
              <div class="number text-size-45 md:text-size-55 xl:text-size-64 font-medium">
                <span class="odometer !font-sora tracking-[0.04em]" data-count="50">0</span>+
              </div>
              <div class="text">Project <br />Completed</div>
            </div>

            <div class="funfact-item flex flex-wrap sm:flex-nowrap flex-col sm:flex-row justify-center lg:justify-start items-center gap-15px">
              <div class="number text-size-45 md:text-size-55 xl:text-size-64 font-medium">
                <span class="odometer !font-sora tracking-[0.04em]" data-count="1.5">0</span>K
              </div>
              <div class="text">Happy <br />Clients</div>
            </div>

            <div class="funfact-item flex flex-wrap sm:flex-nowrap flex-col sm:flex-row justify-center lg:justify-start items-center gap-15px">
              <div class="number text-size-45 md:text-size-55 xl:text-size-64 font-medium">
                <span class="odometer !font-sora tracking-[0.04em]" data-count="14">0</span>
              </div>
              <div class="text">Years of <br />Experience</div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- services area -->
    <section id="{{$posts->first()->postCategory->slug}}">

      <div class="bg-cream-light-color dark:bg-black-color py-60px md:py-20 lg:py-30 overflow-x-hidden">
        <div class="container">
          <!-- section heading -->
          <div class="text-center flex flex-col items-center mb-10 md:mb-50px">
            <h2 class="text-3xl md:text-size-35 lg:text-size-40 xl:text-size-45 bg-gradient-text-light dark:bg-gradient-text bg-clip-text xl:leading-1.2 text-transparent mb-15px wow fadeInUp" data-wow-delay=".3s">
              My Quality Services
            </h2>
            <p class="text-primary-color-light dark:text-body-color max-w-700px wow fadeInUp" data-wow-delay=".4s">
              We put your ideas and thus your wishes in the form of a unique
              web project that inspires you and you customers.
            </p>
          </div>
          <!-- services -->
          <div class="services-widget relative">
            <div class="service-item current px-15px lg:px-30px border dark:border-0 dark:border-b border-body-color dark:border-seondary-color relative z-10 group wow fadeInUp" data-wow-delay=".5s">
              <a href="service-details.html" class="text-primary-color dark:text-white-color flex items-center gap-15px md:gap-5 flex-wrap md:flex-nowrap py-5 lg:py-30px group-hover:text-white">
                <span class="text-xl w-full md:w-[calc(40%-10px)] flex flex-wrap lg:flex-nowrap items-center gap-10px md:gap-5">
                  <b class="service-sl-num text-primary-color group-hover:text-white transition-all duration-300">01</b>
                  <b class="text-xl md:text-size-25 lg:text-3xl">Responsive Design</b>
                </span>

                <span class="text-primary-color-light dark:text-body-color w-full md:w-[calc(45%-10px)] group-hover:text-white">
                  Ensure your website looks great on any device, with layouts
                  that adapt to different screen sizes seamlessly.
                </span>
                <i class="flaticon-up-right-arrow text-size-15 md:text-xl text-primary-color group-hover:text-white-color absolute top-[20%] md:top-1/2 right-5 lg:right-[55px] rtl:left-5 rtl:lg:left-[55px] transition-all duration-300"></i>
              </a>
            </div>
            <div class="service-item px-15px lg:px-30px border dark:border-0 dark:border-b border-body-color dark:border-seondary-color relative z-10 group wow fadeInUp" data-wow-delay=".6s">
              <a href="service-details.html" class="text-primary-color dark:text-white-color flex items-center gap-15px md:gap-5 flex-wrap md:flex-nowrap py-5 lg:py-30px group-hover:text-white">
                <span class="text-xl w-full md:w-[calc(40%-10px)] flex flex-wrap lg:flex-nowrap items-center gap-10px md:gap-5">
                  <b class="service-sl-num text-primary-color group-hover:text-white transition-all duration-300">02</b>
                  <b class="text-xl md:text-size-25 lg:text-3xl">CMS Development</b>
                </span>

                <span class="text-primary-color-light dark:text-body-color w-full md:w-[calc(45%-10px)] group-hover:text-white">
                  Set up user-friendly CMS solutions like WordPress or
                  custom-built options so clients can manage content easily.
                </span>
                <i class="flaticon-up-right-arrow text-size-15 md:text-xl text-primary-color group-hover:text-white-color absolute top-[20%] lg:top-1/2 right-5 lg:right-[55px] rtl:left-5 rtl:lg:left-[55px] transition-all duration-300"></i>
              </a>
            </div>
            <div class="service-item px-15px lg:px-30px border dark:border-0 dark:border-b border-body-color dark:border-seondary-color relative z-10 group wow fadeInUp" data-wow-delay=".7s">
              <a href="service-details.html" class="text-primary-color dark:text-white-color flex items-center gap-15px md:gap-5 flex-wrap md:flex-nowrap py-5 lg:py-30px group-hover:text-white">
                <span class="text-xl w-full md:w-[calc(40%-10px)] flex flex-wrap lg:flex-nowrap items-center gap-10px md:gap-5">
                  <b class="service-sl-num text-primary-color group-hover:text-white transition-all duration-300">03</b>
                  <b class="text-xl md:text-size-25 lg:text-3xl">API Integrations</b>
                </span>

                <span class="text-primary-color-light dark:text-body-color w-full md:w-[calc(45%-10px)] group-hover:text-white">
                  Build and integrate APIs to connect websites with
                  third-party applications, enhancing functionality and
                  performance.
                </span>
                <i class="flaticon-up-right-arrow text-size-15 md:text-xl text-primary-color group-hover:text-white-color absolute top-[20%] lg:top-1/2 right-5 lg:right-[55px] rtl:left-5 rtl:lg:left-[55px] transition-all duration-300"></i>
              </a>
            </div>
            <div class="service-item px-15px lg:px-30px border dark:border-0 dark:border-b border-body-color dark:border-seondary-color relative z-10 group wow fadeInUp" data-wow-delay=".8s">
              <a href="service-details.html" class="text-primary-color dark:text-white-color flex items-center gap-15px md:gap-5 flex-wrap md:flex-nowrap py-5 lg:py-30px group-hover:text-white">
                <span class="text-xl w-full md:w-[calc(40%-10px)] flex flex-wrap lg:flex-nowrap items-center gap-10px md:gap-5">
                  <b class="service-sl-num text-primary-color group-hover:text-white transition-all duration-300">04</b>
                  <b class="text-xl md:text-size-25 lg:text-3xl">Website Redesign</b>
                </span>

                <span class="text-primary-color-light dark:text-body-color w-full md:w-[calc(45%-10px)] group-hover:text-white">
                  Refresh outdated websites with modern, appealing designs
                  that align with current brand goals and user expectations.
                </span>
                <i class="flaticon-up-right-arrow text-size-15 md:text-xl text-primary-color group-hover:text-white-color absolute top-[20%] lg:top-1/2 right-5 lg:right-[55px] rtl:left-5 rtl:lg:left-[55px] transition-all duration-300"></i>
              </a>
            </div>
            <!-- bg animation -->
            <div class="active-bg wow fadeInUp hidden sm:block" data-wow-delay=".6s"></div>
          </div>
        </div>
      </div>
    </section>

    <!-- portfolio area -->
    <section id="portfolio">
      <div class="pt-60px pb-30px md:pt-20 md:pb-60px lg:pt-100px lg:pb-20">
        <div class="container">
          <!-- section heading -->
          <div class="text-center flex flex-col items-center mb-10 md:mb-50px">
            <h2 class="text-3xl md:text-size-35 lg:text-size-40 xl:text-size-45 bg-gradient-text-light dark:bg-gradient-text bg-clip-text xl:leading-1.2 text-transparent mb-15px wow fadeInUp" data-wow-delay=".3s">
              My Recent Works
            </h2>
            <p class="text-primary-color-light dark:text-body-color max-w-700px wow fadeInUp" data-wow-delay=".4s">
              We put your ideas and thus your wishes in the form of a unique
              web project that inspires you and you customers.
            </p>
          </div>
          <!-- portfolio -->
          <div class="portfolio-filter">
            <!-- controllers -->
            <div class="flex items-center">
              <div class="button-group filter-button-group max-w-400 mx-auto inline-flex items-center justify-center bg-cream-light-color dark:bg-black rounded-full px-2 py-1 md:py-1.5 relative z-0 wow fadeInUp" data-wow-delay=".5s">
                <button data-filter="*" class="text-sm md:text-size-15 px-3  py-10px md:py-3 text-primary-color dark:text-white-color leading-1 active">
                  All2
                </button>
                <button data-filter=".uxui" class="text-sm md:text-size-15 px-3  py-10px md:py-3 text-primary-color dark:text-white-color leading-1">
                  UX/UI
                </button>
                <button data-filter=".branding" class="text-sm md:text-size-15 px-3  py-10px md:py-3 text-primary-color dark:text-white-color leading-1">
                  Branding
                </button>
                <button data-filter=".mobile-app" class="text-sm md:text-size-15 px-3  py-10px md:py-3 text-primary-color dark:text-white-color leading-1">
                  Apps
                </button>
                <div class="active-bg !-z-1"></div>
              </div>
            </div>

            <!-- contents -->
            <div class="portfolio-box mt-10 md:mt-50px wow fadeInUp" data-wow-delay=".6s">
              <div class="portfolio-sizer"></div>
              <div class="gutter-sizer"></div>
              <div class="portfolio-item branding bg-primary-color-light px-15px pt-25px pb-0 lg:p-9 lg:pb-0 rounded-10px group relative float-left inline-flex">
                <img src="./assets/img/portfolio/2.jpg" alt="" />
                <div class="absolute left-0 bottom-[15px] group-hover:bottom-5 translate-y-5 group-hover:translate-y-0 opacity-0 invisible group-hover:opacity-100 group-hover:visible w-full group-hover: px-15px lg:px-5 transition-all duration-300">
                  <a href="portfolio-details.html" class="text-white-color p-15px rtl:pl-30px lg:p-5 rtl:lg:pl-50px pr-30px lg:p-5 lg:pr-50px bg-gradient-primary rounded-15px w-full">
                    <span class="block text-xl md:text-size-25 lg:text-3xl font-bold mb-2 lg:mb-15px">
                      Deloitte
                    </span>

                    <span class="block text-body-color">
                      Project was about precision and information.
                    </span>
                    <i class="flaticon-up-right-arrow text-size-15 md:text-xl text-primary-color group-hover:text-white-color absolute top-1/2 right-8 lg:right-[55px] -translate-y-1/2 rotate-[-360deg] group-hover:rotate-0 transition-all duration-300"></i>
                  </a>
                </div>
              </div>
              <div class="portfolio-item uxui bg-primary-color-light px-15px pt-25px pb-0 lg:p-9 lg:pb-0 rounded-10px group relative float-left inline-flex">
                <img src="./assets/img/portfolio/3.jpg" alt="" />
                <div class="absolute left-0 bottom-[15px] group-hover:bottom-5 translate-y-5 group-hover:translate-y-0 opacity-0 invisible group-hover:opacity-100 group-hover:visible w-full group-hover: px-15px lg:px-5 transition-all duration-300">
                  <a href="portfolio-details.html" class="text-white-color p-15px rtl:pl-30px lg:p-5 rtl:lg:pl-50px pr-30px lg:p-5 lg:pr-50px bg-gradient-primary rounded-15px w-full">
                    <span class="block text-xl md:text-size-25 lg:text-3xl font-bold mb-2 lg:mb-15px">
                      New Age
                    </span>

                    <span class="block text-body-color">
                      Project was about precision and information.
                    </span>
                    <i class="flaticon-up-right-arrow text-size-15 md:text-xl text-primary-color group-hover:text-white-color absolute top-1/2 right-8 lg:right-[55px] -translate-y-1/2 rotate-[-360deg] group-hover:rotate-0 transition-all duration-300"></i>
                  </a>
                </div>
              </div>
              <div class="portfolio-item mobile-app bg-primary-color-light px-15px pt-25px pb-0 lg:p-9 lg:pb-0 rounded-10px group relative float-left inline-flex">
                <img src="./assets/img/portfolio/4.jpg" alt="" />
                <div class="absolute left-0 bottom-[15px] group-hover:bottom-5 translate-y-5 group-hover:translate-y-0 opacity-0 invisible group-hover:opacity-100 group-hover:visible w-full group-hover: px-15px lg:px-5 transition-all duration-300">
                  <a href="portfolio-details.html" class="text-white-color p-15px rtl:pl-30px lg:p-5 rtl:lg:pl-50px pr-30px lg:p-5 lg:pr-50px bg-gradient-primary rounded-15px w-full">
                    <span class="block text-xl md:text-size-25 lg:text-3xl font-bold mb-2 lg:mb-15px">
                      Sebastian
                    </span>

                    <span class="block text-body-color">
                      Project was about precision and information.
                    </span>
                    <i class="flaticon-up-right-arrow text-size-15 md:text-xl text-primary-color group-hover:text-white-color absolute top-1/2 right-8 lg:right-[55px] -translate-y-1/2 rotate-[-360deg] group-hover:rotate-0 transition-all duration-300"></i>
                  </a>
                </div>
              </div>
              <div class="portfolio-item branding bg-primary-color-light px-15px pt-25px pb-0 lg:p-9 lg:pb-0 rounded-10px group relative float-left inline-flex">
                <img src="./assets/img/portfolio/1.jpg" alt="" />
                <div class="absolute left-0 bottom-[15px] group-hover:bottom-5 translate-y-5 group-hover:translate-y-0 opacity-0 invisible group-hover:opacity-100 group-hover:visible w-full group-hover: px-15px lg:px-5 transition-all duration-300">
                  <a href="portfolio-details.html" class="text-white-color p-15px rtl:pl-30px lg:p-5 rtl:lg:pl-50px pr-30px lg:p-5 lg:pr-50px bg-gradient-primary rounded-15px w-full">
                    <span class="block text-xl md:text-size-25 lg:text-3xl font-bold mb-2 lg:mb-15px">
                      Mochnix
                    </span>

                    <span class="block text-body-color">
                      Project was about precision and information.
                    </span>
                    <i class="flaticon-up-right-arrow text-size-15 md:text-xl text-primary-color group-hover:text-white-color absolute top-1/2 right-8 lg:right-[55px] -translate-y-1/2 rotate-[-360deg] group-hover:rotate-0 transition-all duration-300"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- experience area -->
    <section id="resume">
      <div class="bg-cream-light-color dark:bg-black-color py-60px md:py-20 lg:py-30">
        <div class="container">
          <div class="experience grid grid-cols-1 md:grid-cols-2 gap-30px gap-y-50px">
            <!-- experience single area -->
            <div>
              <div class="mb-10 md:mb-50px wow fadeInUp" data-wow-delay=".3s">
                <h2 class="text-3xl md:text-size-35 lg:text-size-40 xl:text-size-45 bg-gradient-text-light dark:bg-gradient-text bg-clip-text xl:leading-1.2 text-transparent mb-15px">
                  <i class="flaticon-recommendation mr-2"></i> My Experience
                </h2>
              </div>
              <!-- services -->
              <div class="experience-wrapper flex flex-col gap-30px">
                <div class="px-15px md:px-30px py-5 bg-white-color dark:bg-primary-color-light rounded-20px relative z-0 group max-w-536px transition-all duration-500 after:absolute after:top-0 after:left-0 after:w-full after:h-full after:opacity-0 hover:after:opacity-100 after:transition-all after:duration-500 after:z-1 after:bg-gradient-primary after:rounded-20px wow fadeInLeft" data-wow-delay=".4s">
                  <div class="text-primary-color-light group-hover:text-white-color dark:text-white-color relative z-10 transition-all duration-300">
                    <p class="text-primary-color group-hover:text-white transition-all duration-300 text-size-15 lg:text-xl font-extrabold mb-2">
                      2022 - Present
                    </p>
                    <h4 class="text-lg md:text-xl lg:text-size-23 2xl:text-size-25 mb-2 uppercase">
                      Programming course
                    </h4>
                    <p class="text-primary-color-light dark:text-body-color group-hover:text-white-color transition-all duration-300">
                      Blockdots, London
                    </p>
                  </div>
                </div>
                <div class="px-15px md:px-30px py-5 bg-white-color dark:bg-primary-color-light rounded-20px relative z-0 group max-w-536px transition-all duration-500 after:absolute after:top-0 after:left-0 after:w-full after:h-full after:opacity-0 hover:after:opacity-100 after:transition-all after:duration-500 after:z-1 after:bg-gradient-primary after:rounded-20px wow fadeInLeft" data-wow-delay=".5s">
                  <div class="text-primary-color-light group-hover:text-white-color dark:text-white-color relative z-10 transition-all duration-300">
                    <p class="text-primary-color group-hover:text-white transition-all duration-300 text-size-15 lg:text-xl font-extrabold mb-2">
                      2021 - 2022
                    </p>
                    <h4 class="text-lg md:text-xl lg:text-size-23 2xl:text-size-25 mb-2 uppercase">
                      CMS course
                    </h4>
                    <p class="text-primary-color-light dark:text-body-color group-hover:text-white-color transition-all duration-300">
                      Parsons, The New School
                    </p>
                  </div>
                </div>
                <div class="px-15px md:px-30px py-5 bg-white-color dark:bg-primary-color-light rounded-20px relative z-0 group max-w-536px transition-all duration-500 after:absolute after:top-0 after:left-0 after:w-full after:h-full after:opacity-0 hover:after:opacity-100 after:transition-all after:duration-500 after:z-1 after:bg-gradient-primary after:rounded-20px wow fadeInLeft" data-wow-delay=".6s">
                  <div class="text-primary-color-light group-hover:text-white-color dark:text-white-color relative z-10 transition-all duration-300">
                    <p class="text-primary-color group-hover:text-white transition-all duration-300 text-size-15 lg:text-xl font-extrabold mb-2">
                      2020 - 2021
                    </p>
                    <h4 class="text-lg md:text-xl lg:text-size-23 2xl:text-size-25 mb-2 uppercase">
                      Web design course
                    </h4>
                    <p class="text-primary-color-light dark:text-body-color group-hover:text-white-color transition-all duration-300">
                      House of Life, Leeds
                    </p>
                  </div>
                </div>
                <div class="px-15px md:px-30px py-5 bg-white-color dark:bg-primary-color-light rounded-20px relative z-0 group max-w-536px transition-all duration-500 after:absolute after:top-0 after:left-0 after:w-full after:h-full after:opacity-0 hover:after:opacity-100 after:transition-all after:duration-500 after:z-1 after:bg-gradient-primary after:rounded-20px wow fadeInLeft" data-wow-delay=".7s">
                  <div class="text-primary-color-light group-hover:text-white-color dark:text-white-color relative z-10 transition-all duration-300">
                    <p class="text-primary-color group-hover:text-white transition-all duration-300 text-size-15 lg:text-xl font-extrabold mb-2">
                      2018 - 2020
                    </p>
                    <h4 class="text-lg md:text-xl lg:text-size-23 2xl:text-size-25 mb-2 uppercase">
                      Parsons, The New School
                    </h4>
                    <p class="text-primary-color-light dark:text-body-color group-hover:text-white-color transition-all duration-300">
                      Theme Junction, Bursa
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <!-- experience single area -->
            <div>
              <div class="mb-10 md:mb-50px wow fadeInUp" data-wow-delay=".4s">
                <h2 class="text-3xl md:text-size-35 lg:text-size-40 xl:text-size-45 bg-gradient-text-light dark:bg-gradient-text bg-clip-text xl:leading-1.2 text-transparent mb-15px">
                  <i class="flaticon-graduation-cap mr-2"></i> My Education
                </h2>
              </div>
              <!-- services -->
              <div class="experience-wrapper flex flex-col gap-30px">
                <div class="px-15px md:px-30px py-5 bg-white-color dark:bg-primary-color-light rounded-20px relative z-0 group max-w-536px transition-all duration-500 after:absolute after:top-0 after:left-0 after:w-full after:h-full after:opacity-0 hover:after:opacity-100 after:transition-all after:duration-500 after:z-1 after:bg-gradient-primary after:rounded-20px wow fadeInRight" data-wow-delay=".5s">
                  <div class="text-primary-color-light group-hover:text-white-color dark:text-white-color relative z-10 transition-all duration-300">
                    <p class="text-primary-color group-hover:text-white transition-all duration-300 text-size-15 lg:text-xl font-extrabold mb-2">
                      2020 - 2023
                    </p>
                    <h4 class="text-lg md:text-xl lg:text-size-23 2xl:text-size-25 mb-2 uppercase">
                      BLOCKDOTS
                    </h4>
                    <p class="text-primary-color-light dark:text-body-color group-hover:text-white-color transition-all duration-300">
                      Harverd University
                    </p>
                  </div>
                </div>
                <div class="px-15px md:px-30px py-5 bg-white-color dark:bg-primary-color-light rounded-20px relative z-0 group max-w-536px transition-all duration-500 after:absolute after:top-0 after:left-0 after:w-full after:h-full after:opacity-0 hover:after:opacity-100 after:transition-all after:duration-500 after:z-1 after:bg-gradient-primary after:rounded-20px wow fadeInRight" data-wow-delay=".6s">
                  <div class="text-primary-color-light group-hover:text-white-color dark:text-white-color relative z-10 transition-all duration-300">
                    <p class="text-primary-color group-hover:text-white transition-all duration-300 text-size-15 lg:text-xl font-extrabold mb-2">
                      2016 - 2020
                    </p>
                    <h4 class="text-lg md:text-xl lg:text-size-23 2xl:text-size-25 mb-2 uppercase">
                      Parsons, The New School
                    </h4>
                    <p class="text-primary-color-light dark:text-body-color group-hover:text-white-color transition-all duration-300">
                      University of Denmark
                    </p>
                  </div>
                </div>
                <div class="px-15px md:px-30px py-5 bg-white-color dark:bg-primary-color-light rounded-20px relative z-0 group max-w-536px transition-all duration-500 after:absolute after:top-0 after:left-0 after:w-full after:h-full after:opacity-0 hover:after:opacity-100 after:transition-all after:duration-500 after:z-1 after:bg-gradient-primary after:rounded-20px wow fadeInRight" data-wow-delay=".7s">
                  <div class="text-primary-color-light group-hover:text-white-color dark:text-white-color relative z-10 transition-all duration-300">
                    <p class="text-primary-color group-hover:text-white transition-all duration-300 text-size-15 lg:text-xl font-extrabold mb-2">
                      2012 - 2015
                    </p>
                    <h4 class="text-lg md:text-xl lg:text-size-23 2xl:text-size-25 mb-2 uppercase">
                      IDEO
                    </h4>
                    <p class="text-primary-color-light dark:text-body-color group-hover:text-white-color transition-all duration-300">
                      University of California
                    </p>
                  </div>
                </div>
                <div class="px-15px md:px-30px py-5 bg-white-color dark:bg-primary-color-light rounded-20px relative z-0 group max-w-536px transition-all duration-500 after:absolute after:top-0 after:left-0 after:w-full after:h-full after:opacity-0 hover:after:opacity-100 after:transition-all after:duration-500 after:z-1 after:bg-gradient-primary after:rounded-20px wow fadeInRight" data-wow-delay=".8s">
                  <div class="text-primary-color-light group-hover:text-white-color dark:text-white-color relative z-10 transition-all duration-300">
                    <p class="text-primary-color group-hover:text-white transition-all duration-300 text-size-15 lg:text-xl font-extrabold mb-2">
                      2010 - 2011
                    </p>
                    <h4 class="text-lg md:text-xl lg:text-size-23 2xl:text-size-25 mb-2 uppercase">
                      Parsons, The New School
                    </h4>
                    <p class="text-primary-color-light dark:text-body-color group-hover:text-white-color transition-all duration-300">
                      Parsons, The New School
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- skills area -->
    <section id="skills">
      <div class="pt-60px pb-60px md:pt-20 md:pb-60px lg:pt-100px lg:pb-20">
        <div class="container">
          <!-- section heading -->
          <div class="text-center flex flex-col items-center mb-10 md:mb-50px">
            <h2 class="text-3xl md:text-size-35 lg:text-size-40 xl:text-size-45 bg-gradient-text-light dark:bg-gradient-text bg-clip-text xl:leading-1.2 text-transparent mb-15px">
              My Skills
            </h2>
            <p class="text-primary-color-light dark:text-body-color max-w-700px">
              We put your ideas and thus your wishes in the form of a unique
              web project that inspires you and you customers.
            </p>
          </div>
          <!-- skills -->
          <div class="skills">
            <div class="flex flex-wrap items-center justify-center gap-5">
              <!-- skills single -->
              <div class="max-w-180px w-full group wow fadeInUp" data-wow-delay=".3s">
                <!-- contents -->
                <div class="flex flex-col items-center py-25px px-15px md:pt-30px 2xl:pt-10 2xl:mb-30px rounded-25px bg-cream-light-color dark:bg-primary-color-light border border-transparent group-hover:border-primary-color group-hover:bg-seondary-color transition-all duration-500 mb-15px">
                  <div class="mb-5 md:mb-30px mx-9 2xl:mx-44px w-60px flex flex-col justify-center items-center">
                    <img class="grayscale-[90%] group-hover:grayscale-0 transition-all duration-500 group-hover:scale-110 opacity-50 group-hover:opacity-100 w-60px" src="./assets/img/icons/figma.png" alt="" />
                  </div>
                  <div class="text-xl text-gray-color-2 group-hover:text-primary-color transition-none duration-300 font-extrabold">
                    92%
                  </div>
                </div>
                <p class="text-primary-color text-center">Figma</p>
              </div>
              <!-- skills single -->
              <div class="max-w-180px w-full group wow fadeInUp" data-wow-delay=".4s">
                <!-- contents -->
                <div class="flex flex-col items-center py-25px px-15px md:pt-30px 2xl:pt-10 2xl:mb-30px rounded-25px bg-cream-light-color dark:bg-primary-color-light border border-transparent group-hover:border-primary-color group-hover:bg-seondary-color transition-all duration-500 mb-15px">
                  <div class="mb-5 md:mb-30px mx-9 2xl:mx-44px w-60px flex flex-col justify-center items-center">
                    <img class="grayscale-[90%] group-hover:grayscale-0 transition-all duration-500 group-hover:scale-110 opacity-50 group-hover:opacity-100 w-60px" src="./assets/img/icons/sketch.png" alt="" />
                  </div>
                  <div class="text-xl text-gray-color-2 group-hover:text-primary-color transition-none duration-300 font-extrabold">
                    80%
                  </div>
                </div>
                <p class="text-primary-color text-center">Sketch</p>
              </div>
              <!-- skills single -->
              <div class="max-w-180px w-full group wow fadeInUp" data-wow-delay=".5s">
                <!-- contents -->
                <div class="flex flex-col items-center py-25px px-15px md:pt-30px 2xl:pt-10 2xl:mb-30px rounded-25px bg-cream-light-color dark:bg-primary-color-light border border-transparent group-hover:border-primary-color group-hover:bg-seondary-color transition-all duration-500 mb-15px">
                  <div class="mb-5 md:mb-30px mx-9 2xl:mx-44px w-60px flex flex-col justify-center items-center">
                    <img class="grayscale-[90%] group-hover:grayscale-0 transition-all duration-500 group-hover:scale-110 opacity-50 group-hover:opacity-100 w-60px" src="./assets/img/icons/xd.png" alt="" />
                  </div>
                  <div class="text-xl text-gray-color-2 group-hover:text-primary-color transition-none duration-300 font-extrabold">
                    85%
                  </div>
                </div>
                <p class="text-primary-color text-center">XD</p>
              </div>
              <!-- skills single -->
              <div class="max-w-180px w-full group wow fadeInUp" data-wow-delay=".6s">
                <!-- contents -->
                <div class="flex flex-col items-center py-25px px-15px md:pt-30px 2xl:pt-10 2xl:mb-30px rounded-25px bg-cream-light-color dark:bg-primary-color-light border border-transparent group-hover:border-primary-color group-hover:bg-seondary-color transition-all duration-500 mb-15px">
                  <div class="mb-5 md:mb-30px mx-9 2xl:mx-44px w-60px flex flex-col justify-center items-center">
                    <img class="grayscale-[90%] group-hover:grayscale-0 transition-all duration-500 group-hover:scale-110 opacity-50 group-hover:opacity-100 w-60px" src="./assets/img/icons/wp.png" alt="" />
                  </div>
                  <div class="text-xl text-gray-color-2 group-hover:text-primary-color transition-none duration-300 font-extrabold">
                    99%
                  </div>
                </div>
                <p class="text-primary-color text-center">WordPress</p>
              </div>
              <!-- skills single -->
              <div class="max-w-180px w-full group wow fadeInUp" data-wow-delay=".7s">
                <!-- contents -->
                <div class="flex flex-col items-center py-25px px-15px md:pt-30px 2xl:pt-10 2xl:mb-30px rounded-25px bg-cream-light-color dark:bg-primary-color-light border border-transparent group-hover:border-primary-color group-hover:bg-seondary-color transition-all duration-500 mb-15px">
                  <div class="mb-5 md:mb-30px mx-9 2xl:mx-44px w-60px flex flex-col justify-center items-center">
                    <img class="grayscale-[90%] group-hover:grayscale-0 transition-all duration-500 group-hover:scale-110 opacity-50 group-hover:opacity-100 w-60px" src="./assets/img/icons/react.png" alt="" />
                  </div>
                  <div class="text-xl text-gray-color-2 group-hover:text-primary-color transition-none duration-300 font-extrabold">
                    89%
                  </div>
                </div>
                <p class="text-primary-color text-center">React</p>
              </div>
              <!-- skills single -->
              <div class="max-w-180px w-full group wow fadeInUp" data-wow-delay=".8s">
                <!-- contents -->
                <div class="flex flex-col items-center py-25px px-15px md:pt-30px 2xl:pt-10 2xl:mb-30px rounded-25px bg-cream-light-color dark:bg-primary-color-light border border-transparent group-hover:border-primary-color group-hover:bg-seondary-color transition-all duration-500 mb-15px">
                  <div class="mb-5 md:mb-30px mx-9 2xl:mx-44px w-60px flex flex-col justify-center items-center">
                    <img class="grayscale-[90%] group-hover:grayscale-0 transition-all duration-500 group-hover:scale-110 opacity-50 group-hover:opacity-100 w-60px" src="./assets/img/icons/js.png" alt="" />
                  </div>
                  <div class="text-xl text-gray-color-2 group-hover:text-primary-color transition-none duration-300 font-extrabold">
                    93%
                  </div>
                </div>
                <p class="text-primary-color text-center">JavaScript</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- testimonials area -->
    <section id="testimonials">
      <div class="bg-cream-light-color dark:bg-black-color py-60px md:py-20 lg:py-30">
        <div class="container">
          <div class="testimonials grid lg:grid-cols-12 gap-6">
            <!-- section heading -->
            <div class="lg:col-start-1 lg:col-span-5">
              <div class="mb-5 md:mb-50px text-center lg:text-start wow fadeInUp" data-wow-delay=".3s">
                <h2 class="text-3xl md:text-size-35 lg:text-size-40 xl:text-size-45 bg-gradient-text-light dark:bg-gradient-text bg-clip-text xl:leading-1.2 text-transparent mb-15px wow fadeInLeft" data-wow-delay=".3s">
                  My Client's Stories
                </h2>
                <p class="text-primary-color-light dark:text-body-color wow fadeInLeft" data-wow-delay=".4s">
                  Empowering people in new a digital journey with my super
                  services
                </p>
              </div>
            </div>
            <!-- testimonial single -->
            <div class="lg:col-start-6 xl:col-start-7 lg:col-span-7 xl:col-span-6 wow fadeInRight overflow-hidden" data-wow-delay=".5s">
              <div class="owl-carousel testimonial-carousel">
                <div class="p-25px bg-white-color dark:bg-primary-color-light rounded-15px relative z-0 group">
                  <div class="flex justify-between gap-30px mb-5">
                    <div class="max-w-100px w-full">
                      <img src="./assets/img/testimonials/logo/1.png" alt="" class="!hidden dark:!block" />
                      <img src="./assets/img/testimonials/logo/1-dark.png" alt="" class="!block dark:!hidden" />
                    </div>
                    <div class="max-w-120px w-2/5">
                      <img class="rounded-5px rounded-bl-125px w-full" src="./assets/img/testimonials/user/1.jpg" alt="" />
                    </div>
                  </div>
                  <div class="text-primary-color-light dark:text-white-color relative z-10">
                    <div class="icon-box mb-25px flex gap-1">
                      <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" class="transition-all duration-300">
                        <path d="M0.105431 2.18998C0.0301532 0.988687 1.02531 -0.00647222 2.2266 0.0688056L19.4961 1.15097C21.2148 1.25867 22.0029 3.34358 20.7852 4.56127L4.5979 20.7486C3.3802 21.9663 1.2953 21.1781 1.1876 19.4594L0.105431 2.18998Z" fill="url(#paint6_linear_263_514)" class="fill-primary-color"></path>
                        <defs>
                          <linearGradient x1="-0.0363755" y1="-0.0729998" x2="35.3333" y2="-0.0729991" gradientUnits="userSpaceOnUse">
                            <stop offset="1" stop-color="var(--tj-theme-primary)"></stop>
                            <stop offset="1" stop-color="#140C1C" stop-opacity="0"></stop>
                          </linearGradient>
                        </defs>
                      </svg>
                      <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" class="transition-all duration-300 ml-1">
                        <path d="M0.105431 2.18998C0.0301532 0.988687 1.02531 -0.00647222 2.2266 0.0688056L19.4961 1.15097C21.2148 1.25867 22.0029 3.34358 20.7852 4.56127L4.5979 20.7486C3.3802 21.9663 1.2953 21.1781 1.1876 19.4594L0.105431 2.18998Z" fill="url(#paint7_linear_263_515)" class="fill-primary-color"></path>
                        <defs>
                          <linearGradient x1="-0.0363755" y1="-0.0729998" x2="35.3333" y2="-0.0729991" gradientUnits="userSpaceOnUse">
                            <stop offset="1" stop-color="var(--tj-theme-primary)"></stop>
                            <stop offset="1" stop-color="#140C1C" stop-opacity="0"></stop>
                          </linearGradient>
                        </defs>
                      </svg>
                    </div>
                    <p class="text-primary-color-light dark:text-body-color mb-30px md:mb-50px">
                      “Taylor is a professional Designer he really helps my
                      business by providing value to my business.
                    </p>
                    <h4 class="text-lg mb-2">Tim Bailey</h4>

                    <p class="text-primary-color-light dark:text-body-color text-sm">
                      SEO Specialist, Theme Junction
                    </p>
                  </div>
                </div>
                <div class="p-25px bg-white-color dark:bg-primary-color-light rounded-15px relative z-0 group">
                  <div class="flex justify-between gap-30px mb-5">
                    <div class="max-w-100px w-full">
                      <img src="./assets/img/testimonials/logo/2.png" alt="" class="!hidden dark:!block" />
                      <img src="./assets/img/testimonials/logo/2-dark.png" alt="" class="!block dark:!hidden" />
                    </div>
                    <div class="max-w-120px w-2/5">
                      <img class="rounded-5px rounded-bl-125px w-full" src="./assets/img/testimonials/user/2.jpg" alt="" />
                    </div>
                  </div>
                  <div class="text-primary-color-light dark:text-white-color relative z-10">
                    <div class="icon-box mb-25px flex gap-1">
                      <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" class="transition-all duration-300">
                        <path d="M0.105431 2.18998C0.0301532 0.988687 1.02531 -0.00647222 2.2266 0.0688056L19.4961 1.15097C21.2148 1.25867 22.0029 3.34358 20.7852 4.56127L4.5979 20.7486C3.3802 21.9663 1.2953 21.1781 1.1876 19.4594L0.105431 2.18998Z" fill="url(#paint6_linear_263_514)" class="fill-primary-color"></path>
                        <defs>
                          <linearGradient x1="-0.0363755" y1="-0.0729998" x2="35.3333" y2="-0.0729991" gradientUnits="userSpaceOnUse">
                            <stop offset="1" stop-color="var(--tj-theme-primary)"></stop>
                            <stop offset="1" stop-color="#140C1C" stop-opacity="0"></stop>
                          </linearGradient>
                        </defs>
                      </svg>
                      <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" class="transition-all duration-300 ml-1">
                        <path d="M0.105431 2.18998C0.0301532 0.988687 1.02531 -0.00647222 2.2266 0.0688056L19.4961 1.15097C21.2148 1.25867 22.0029 3.34358 20.7852 4.56127L4.5979 20.7486C3.3802 21.9663 1.2953 21.1781 1.1876 19.4594L0.105431 2.18998Z" fill="url(#paint7_linear_263_515)" class="fill-primary-color"></path>
                        <defs>
                          <linearGradient x1="-0.0363755" y1="-0.0729998" x2="35.3333" y2="-0.0729991" gradientUnits="userSpaceOnUse">
                            <stop offset="1" stop-color="var(--tj-theme-primary)"></stop>
                            <stop offset="1" stop-color="#140C1C" stop-opacity="0"></stop>
                          </linearGradient>
                        </defs>
                      </svg>
                    </div>
                    <p class="text-primary-color-light dark:text-body-color mb-30px md:mb-50px">
                      “Taylor is a professional Designer he really helps my
                      business by providing value to my business.
                    </p>
                    <h4 class="text-lg mb-2">Brandon Fraser</h4>

                    <p class="text-primary-color-light dark:text-body-color text-sm">
                      Senior Software Dev, Cosmic Sport
                    </p>
                  </div>
                </div>
                <div class="p-25px bg-white-color dark:bg-primary-color-light rounded-15px relative z-0 group">
                  <div class="flex justify-between gap-30px mb-5">
                    <div class="max-w-100px w-full">
                      <img src="./assets/img/testimonials/logo/1.png" alt="" class="!hidden dark:!block" />
                      <img src="./assets/img/testimonials/logo/1-dark.png" alt="" class="!block dark:!hidden" />
                    </div>
                    <div class="max-w-120px w-2/5">
                      <img class="rounded-5px rounded-bl-125px w-full" src="./assets/img/testimonials/user/1.jpg" alt="" />
                    </div>
                  </div>
                  <div class="text-primary-color-light dark:text-white-color relative z-10">
                    <div class="icon-box mb-25px flex gap-1">
                      <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" class="transition-all duration-300">
                        <path d="M0.105431 2.18998C0.0301532 0.988687 1.02531 -0.00647222 2.2266 0.0688056L19.4961 1.15097C21.2148 1.25867 22.0029 3.34358 20.7852 4.56127L4.5979 20.7486C3.3802 21.9663 1.2953 21.1781 1.1876 19.4594L0.105431 2.18998Z" fill="url(#paint6_linear_263_514)" class="fill-primary-color"></path>
                        <defs>
                          <linearGradient x1="-0.0363755" y1="-0.0729998" x2="35.3333" y2="-0.0729991" gradientUnits="userSpaceOnUse">
                            <stop offset="1" stop-color="var(--tj-theme-primary)"></stop>
                            <stop offset="1" stop-color="#140C1C" stop-opacity="0"></stop>
                          </linearGradient>
                        </defs>
                      </svg>
                      <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" class="transition-all duration-300 ml-1">
                        <path d="M0.105431 2.18998C0.0301532 0.988687 1.02531 -0.00647222 2.2266 0.0688056L19.4961 1.15097C21.2148 1.25867 22.0029 3.34358 20.7852 4.56127L4.5979 20.7486C3.3802 21.9663 1.2953 21.1781 1.1876 19.4594L0.105431 2.18998Z" fill="url(#paint7_linear_263_515)" class="fill-primary-color"></path>
                        <defs>
                          <linearGradient x1="-0.0363755" y1="-0.0729998" x2="35.3333" y2="-0.0729991" gradientUnits="userSpaceOnUse">
                            <stop offset="1" stop-color="var(--tj-theme-primary)"></stop>
                            <stop offset="1" stop-color="#140C1C" stop-opacity="0"></stop>
                          </linearGradient>
                        </defs>
                      </svg>
                    </div>
                    <p class="text-primary-color-light dark:text-body-color mb-30px md:mb-50px">
                      “Taylor is a professional Designer he really helps my
                      business by providing value to my business.
                    </p>
                    <h4 class="text-lg mb-2">Tim Bailey</h4>

                    <p class="text-primary-color-light dark:text-body-color text-sm">
                      SEO Specialist, Theme Junction
                    </p>
                  </div>
                </div>
                <div class="p-25px bg-white-color dark:bg-primary-color-light rounded-15px relative z-0 group">
                  <div class="flex justify-between gap-30px mb-5">
                    <div class="max-w-100px w-full">
                      <img src="./assets/img/testimonials/logo/2.png" alt="" class="!hidden dark:!block" />
                      <img src="./assets/img/testimonials/logo/2-dark.png" alt="" class="!block dark:!hidden" />
                    </div>
                    <div class="max-w-120px w-2/5">
                      <img class="rounded-5px rounded-bl-125px w-full" src="./assets/img/testimonials/user/2.jpg" alt="" />
                    </div>
                  </div>
                  <div class="text-primary-color-light dark:text-white-color relative z-10">
                    <div class="icon-box mb-25px flex gap-1">
                      <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" class="transition-all duration-300">
                        <path d="M0.105431 2.18998C0.0301532 0.988687 1.02531 -0.00647222 2.2266 0.0688056L19.4961 1.15097C21.2148 1.25867 22.0029 3.34358 20.7852 4.56127L4.5979 20.7486C3.3802 21.9663 1.2953 21.1781 1.1876 19.4594L0.105431 2.18998Z" fill="url(#paint6_linear_263_514)" class="fill-primary-color"></path>
                        <defs>
                          <linearGradient x1="-0.0363755" y1="-0.0729998" x2="35.3333" y2="-0.0729991" gradientUnits="userSpaceOnUse">
                            <stop offset="1" stop-color="var(--tj-theme-primary)"></stop>
                            <stop offset="1" stop-color="#140C1C" stop-opacity="0"></stop>
                          </linearGradient>
                        </defs>
                      </svg>
                      <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" class="transition-all duration-300 ml-1">
                        <path d="M0.105431 2.18998C0.0301532 0.988687 1.02531 -0.00647222 2.2266 0.0688056L19.4961 1.15097C21.2148 1.25867 22.0029 3.34358 20.7852 4.56127L4.5979 20.7486C3.3802 21.9663 1.2953 21.1781 1.1876 19.4594L0.105431 2.18998Z" fill="url(#paint7_linear_263_515)" class="fill-primary-color"></path>
                        <defs>
                          <linearGradient x1="-0.0363755" y1="-0.0729998" x2="35.3333" y2="-0.0729991" gradientUnits="userSpaceOnUse">
                            <stop offset="1" stop-color="var(--tj-theme-primary)"></stop>
                            <stop offset="1" stop-color="#140C1C" stop-opacity="0"></stop>
                          </linearGradient>
                        </defs>
                      </svg>
                    </div>
                    <p class="text-primary-color-light dark:text-body-color mb-30px md:mb-50px">
                      “Taylor is a professional Designer he really helps my
                      business by providing value to my business.
                    </p>
                    <h4 class="text-lg mb-2">Brandon Fraser</h4>

                    <p class="text-primary-color-light dark:text-body-color text-sm">
                      Senior Software Dev, Cosmic Sport
                    </p>
                  </div>
                </div>
                <div class="p-25px bg-white-color dark:bg-primary-color-light rounded-15px relative z-0 group">
                  <div class="flex justify-between gap-30px mb-5">
                    <div class="max-w-100px w-full">
                      <img src="./assets/img/testimonials/logo/1.png" alt="" class="!hidden dark:!block" />
                      <img src="./assets/img/testimonials/logo/1-dark.png" alt="" class="!block dark:!hidden" />
                    </div>
                    <div class="max-w-120px w-2/5">
                      <img class="rounded-5px rounded-bl-125px w-full" src="./assets/img/testimonials/user/1.jpg" alt="" />
                    </div>
                  </div>
                  <div class="text-primary-color-light dark:text-white-color relative z-10">
                    <div class="icon-box mb-25px flex gap-1">
                      <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" class="transition-all duration-300">
                        <path d="M0.105431 2.18998C0.0301532 0.988687 1.02531 -0.00647222 2.2266 0.0688056L19.4961 1.15097C21.2148 1.25867 22.0029 3.34358 20.7852 4.56127L4.5979 20.7486C3.3802 21.9663 1.2953 21.1781 1.1876 19.4594L0.105431 2.18998Z" fill="url(#paint6_linear_263_514)" class="fill-primary-color"></path>
                        <defs>
                          <linearGradient x1="-0.0363755" y1="-0.0729998" x2="35.3333" y2="-0.0729991" gradientUnits="userSpaceOnUse">
                            <stop offset="1" stop-color="var(--tj-theme-primary)"></stop>
                            <stop offset="1" stop-color="#140C1C" stop-opacity="0"></stop>
                          </linearGradient>
                        </defs>
                      </svg>
                      <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" class="transition-all duration-300 ml-1">
                        <path d="M0.105431 2.18998C0.0301532 0.988687 1.02531 -0.00647222 2.2266 0.0688056L19.4961 1.15097C21.2148 1.25867 22.0029 3.34358 20.7852 4.56127L4.5979 20.7486C3.3802 21.9663 1.2953 21.1781 1.1876 19.4594L0.105431 2.18998Z" fill="url(#paint7_linear_263_515)" class="fill-primary-color"></path>
                        <defs>
                          <linearGradient x1="-0.0363755" y1="-0.0729998" x2="35.3333" y2="-0.0729991" gradientUnits="userSpaceOnUse">
                            <stop offset="1" stop-color="var(--tj-theme-primary)"></stop>
                            <stop offset="1" stop-color="#140C1C" stop-opacity="0"></stop>
                          </linearGradient>
                        </defs>
                      </svg>
                    </div>
                    <p class="text-primary-color-light dark:text-body-color mb-30px md:mb-50px">
                      “Taylor is a professional Designer he really helps my
                      business by providing value to my business.
                    </p>
                    <h4 class="text-lg mb-2">Tim Bailey</h4>

                    <p class="text-primary-color-light dark:text-body-color text-sm">
                      SEO Specialist, Theme Junction
                    </p>
                  </div>
                </div>
                <div class="p-25px bg-white-color dark:bg-primary-color-light rounded-15px relative z-0 group">
                  <div class="flex justify-between gap-30px mb-5">
                    <div class="max-w-100px w-full">
                      <img src="./assets/img/testimonials/logo/2.png" alt="" class="!hidden dark:!block" />
                      <img src="./assets/img/testimonials/logo/2-dark.png" alt="" class="!block dark:!hidden" />
                    </div>
                    <div class="max-w-120px w-2/5">
                      <img class="rounded-5px rounded-bl-125px w-full" src="./assets/img/testimonials/user/2.jpg" alt="" />
                    </div>
                  </div>
                  <div class="text-primary-color-light dark:text-white-color relative z-10">
                    <div class="icon-box mb-25px flex gap-1">
                      <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" class="transition-all duration-300">
                        <path d="M0.105431 2.18998C0.0301532 0.988687 1.02531 -0.00647222 2.2266 0.0688056L19.4961 1.15097C21.2148 1.25867 22.0029 3.34358 20.7852 4.56127L4.5979 20.7486C3.3802 21.9663 1.2953 21.1781 1.1876 19.4594L0.105431 2.18998Z" fill="url(#paint6_linear_263_514)" class="fill-primary-color"></path>
                        <defs>
                          <linearGradient x1="-0.0363755" y1="-0.0729998" x2="35.3333" y2="-0.0729991" gradientUnits="userSpaceOnUse">
                            <stop offset="1" stop-color="var(--tj-theme-primary)"></stop>
                            <stop offset="1" stop-color="#140C1C" stop-opacity="0"></stop>
                          </linearGradient>
                        </defs>
                      </svg>
                      <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" class="transition-all duration-300 ml-1">
                        <path d="M0.105431 2.18998C0.0301532 0.988687 1.02531 -0.00647222 2.2266 0.0688056L19.4961 1.15097C21.2148 1.25867 22.0029 3.34358 20.7852 4.56127L4.5979 20.7486C3.3802 21.9663 1.2953 21.1781 1.1876 19.4594L0.105431 2.18998Z" fill="url(#paint7_linear_263_515)" class="fill-primary-color"></path>
                        <defs>
                          <linearGradient x1="-0.0363755" y1="-0.0729998" x2="35.3333" y2="-0.0729991" gradientUnits="userSpaceOnUse">
                            <stop offset="1" stop-color="var(--tj-theme-primary)"></stop>
                            <stop offset="1" stop-color="#140C1C" stop-opacity="0"></stop>
                          </linearGradient>
                        </defs>
                      </svg>
                    </div>
                    <p class="text-primary-color-light dark:text-body-color mb-30px md:mb-50px">
                      “Taylor is a professional Designer he really helps my
                      business by providing value to my business.
                    </p>
                    <h4 class="text-lg mb-2">Brandon Fraser</h4>

                    <p class="text-primary-color-light dark:text-body-color text-sm">
                      Senior Software Dev, Cosmic Sport
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- blogs area -->
    <section>
      <div id="blogs" class="py-60px md:py-20 lg:py-100px xl:py-30">
        <div class="container">
          <!-- section heading -->
          <div class="text-center flex flex-col items-center mb-10 md:mb-50px">
            <h2 class="text-3xl md:text-size-35 lg:text-size-40 xl:text-size-45 bg-gradient-text-light dark:bg-gradient-text bg-clip-text xl:leading-1.2 text-transparent mb-15px wow fadeInUp" data-wow-delay=".3s">
              Recent Blogs
            </h2>
            <p class="text-primary-color-light dark:text-body-color max-w-700px wow fadeInUp" data-wow-delay=".4s">
              We put your ideas and thus your wishes in the form of a unique
              web project that inspires you and you customers.
            </p>
          </div>
          <!-- blogs -->

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-25px 2xl:gap-35px">
            <div class="group relative flex flex-col items-center wow fadeInUp" data-wow-delay=".5s">
              <div class="rounded-10px relative overflow-hidden max-w-400 w-full">
                <a href="blog-details.html" class="rounded-10px overflow-hidden">
                  <img src="./assets/img/blog/1.jpg" alt="" class="group-hover:scale-110 transition-all duration-500" /></a>
                <a href="#" class="text-size-13 uppercase px-10px py-7px rounded-50px leading-1 absolute top-[15px] left-[15px] rtl:left-auto rtl:right-[15px] text-white-color bg-gradient-secondary-2 bg-200 hover:bg-100">Tutorial</a>
                <div class="absolute left-0 bottom-5 w-full px-10px lg:px-5 transition-all duration-500">
                  <div class="relative z-0 p-15px pb-5 bg-white-color dark:bg-seondary-color rounded-15px w-full after:absolute after:top-0 after:left-0 after:w-full after:h-full after:opacity-0 group-hover:after:opacity-100 after:transition-all after:duration-500 after:z-1 after:bg-gradient-primary after:rounded-15px">
                    <div class="relative z-10">
                      <ul class="flex gap-5 items-center mb-2">
                        <li class="text-sm font-medium text-primary-color group-hover:text-white-color transition-all duration-500">
                          <i class="fa-light fa-calendar-days mr-0.5"></i> Oct
                          01, 2022
                        </li>
                        <li class="text-sm font-medium">
                          <i class="fa-light fa-comments mr-0.5 text-primary-color group-hover:text-white-color transition-all duration-500"></i>
                          <a href="#" class="text-primary-color group-hover:text-white-color transition-all duration-500">Comment
                            (0)</a>
                        </li>
                      </ul>
                      <a href="blog-details.html" class="text-primary-color-light dark:text-white-color group-hover:text-white-color w-full capitalize">
                        <span class="block text-lg md:text-size-22 font-bold">
                          top 10 ui ux designers
                        </span>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="group relative flex flex-col items-center wow fadeInUp" data-wow-delay=".6s">
              <div class="rounded-10px relative overflow-hidden max-w-400 w-full">
                <a href="blog-details.html" class="rounded-10px overflow-hidden">
                  <img src="./assets/img/blog/2.jpg" alt="" class="group-hover:scale-110 transition-all duration-500" /></a>
                <a href="#" class="text-size-13 uppercase px-10px py-7px rounded-50px leading-1 absolute top-[15px] left-[15px] rtl:left-auto rtl:right-[15px] text-white-color bg-gradient-secondary-2 bg-200 hover:bg-100">TIPS</a>
                <div class="absolute left-0 bottom-5 w-full px-10px lg:px-5 transition-all duration-500">
                  <div class="relative z-0 p-15px pb-5 bg-white-color dark:bg-seondary-color rounded-15px w-full after:absolute after:top-0 after:left-0 after:w-full after:h-full after:opacity-0 group-hover:after:opacity-100 after:transition-all after:duration-500 after:z-1 after:bg-gradient-primary after:rounded-15px">
                    <div class="relative z-10">
                      <ul class="flex gap-5 items-center mb-2">
                        <li class="text-sm font-medium text-primary-color group-hover:text-white-color transition-all duration-500">
                          <i class="fa-light fa-calendar-days mr-0.5"></i> Nov
                          01, 2022
                        </li>
                        <li class="text-sm font-medium">
                          <i class="fa-light fa-comments mr-0.5 text-primary-color group-hover:text-white-color transition-all duration-500"></i>
                          <a href="#" class="text-primary-color group-hover:text-white-color transition-all duration-500">Comment
                            (0)</a>
                        </li>
                      </ul>
                      <a href="blog-details.html" class="text-primary-color-light dark:text-white-color group-hover:text-white-color w-full capitalize">
                        <span class="block text-lg md:text-size-22 font-bold">
                          App Development Guides
                        </span>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="group relative flex flex-col items-center wow fadeInUp" data-wow-delay=".7s">
              <div class="rounded-10px relative overflow-hidden max-w-400 w-full">
                <a href="blog-details.html" class="rounded-10px overflow-hidden">
                  <img src="./assets/img/blog/3.jpg" alt="" class="group-hover:scale-110 transition-all duration-500" /></a>
                <a href="#" class="text-size-13 uppercase px-10px py-7px rounded-50px leading-1 absolute top-[15px] left-[15px] rtl:left-auto rtl:right-[15px] text-white-color bg-gradient-secondary-2 bg-200 hover:bg-100">FREEBIES</a>
                <div class="absolute left-0 bottom-5 w-full px-10px lg:px-5 transition-all duration-500">
                  <div class="relative z-0 p-15px pb-5 bg-white-color dark:bg-seondary-color rounded-15px w-full after:absolute after:top-0 after:left-0 after:w-full after:h-full after:opacity-0 group-hover:after:opacity-100 after:transition-all after:duration-500 after:z-1 after:bg-gradient-primary after:rounded-15px">
                    <div class="relative z-10">
                      <ul class="flex gap-5 items-center mb-2">
                        <li class="text-sm font-medium text-primary-color group-hover:text-white-color transition-all duration-500">
                          <i class="fa-light fa-calendar-days mr-0.5"></i> Dec
                          01, 2022
                        </li>
                        <li class="text-sm font-medium">
                          <i class="fa-light fa-comments mr-0.5 text-primary-color group-hover:text-white-color transition-all duration-500"></i>
                          <a href="#" class="text-primary-color group-hover:text-white-color transition-all duration-500">Comment
                            (0)</a>
                        </li>
                      </ul>
                      <a href="blog-details.html" class="text-primary-color-light dark:text-white-color group-hover:text-white-color w-full capitalize">
                        <span class="block text-lg md:text-size-22 font-bold">
                          learn graphic design free
                        </span>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- contact area -->
    <section id="contact">
      <div class="bg-cream-light-color dark:bg-black-color py-60px md:py-20 lg:py-100px xl:py-30">
        <div class="container">
          <div class="flex flex-col-reverse md:grid md:grid-cols-12 md:items-center gap-x-6 gap-y-10">
            <!-- section heading -->
            <div class="md:col-start-1 md:col-span-7 lg:col-span-6">
              <div class="wow fadeInLeft" data-wow-delay=".3s">
                <form id="contact-form" class="contact px-15px py-30px md:px-5 lg:px-30px lg:py-10 xl:px-10 bg-white-color dark:bg-primary-color-light rounded-15px">
                  <div class="text-center mb-25px">
                    <h2 class="text-3xl md:text-size-35 lg:text-size-40 xl:text-size-45 bg-gradient-text-light dark:bg-gradient-text bg-clip-text xl:leading-1.2 text-transparent mb-15px">
                      Let’s work together!
                    </h2>
                    <p class="text-primary-color-light dark:text-body-color wow fadeInLeft" data-wow-delay=".4s">
                      I design and code beautifully simple things and i love
                      what i do. Just simple like that!
                    </p>
                  </div>
                  <!-- inputs -->
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-15px">
                    <!-- first name -->
                    <div>
                      <input name="conName" id="conName" type="text" placeholder="First name" class="text-white-color w-full px-5 py-14px border bg-cream-light-color dark:bg-black-color focus:border-primary-color rounded-lg outline-none focus:outline-none transition-all duration-300 placeholder:text-gray-color leading-1" />
                    </div>
                    <!-- Last name -->
                    <div>
                      <input name="conLName" id="conLName" type="text" placeholder="Last name" class="text-white-color w-full px-5 py-14px border bg-cream-light-color dark:bg-black-color focus:border-primary-color rounded-lg outline-none focus:outline-none transition-all duration-300 placeholder:text-gray-color leading-1" />
                    </div>
                    <!-- Email address -->
                    <div>
                      <input name="conEmail" id="conEmail" type="email" placeholder="Email address" class="text-white-color w-full px-5 py-14px border bg-cream-light-color dark:bg-black-color focus:border-primary-color rounded-lg outline-none focus:outline-none transition-all duration-300 placeholder:text-gray-color leading-1" />
                    </div>
                    <!-- Phone number -->
                    <div>
                      <input name="conPhone" id="conPhone" type="text" placeholder="Phone number" class="text-white-color w-full px-5 py-14px border bg-cream-light-color dark:bg-black-color focus:border-primary-color rounded-lg outline-none focus:outline-none transition-all duration-300 placeholder:text-gray-color leading-1" />
                    </div>
                    <div class="form_group sm:col-start-1 sm:col-span-2">
                      <select name="conService" id="conService" class="tj-nice-select">
                        <option value="" selected="" disabled="">
                          Choose Service
                        </option>
                        <option value="braning">Branding Design</option>
                        <option value="web">Web Design</option>
                        <option value="uxui">UI/UX Design</option>
                        <option value="app">App Design</option>
                      </select>
                    </div>
                    <div class="sm:col-start-1 sm:col-span-2">
                      <textarea name="conMessage" id="conMessage" cols="1" rows="10" placeholder="Message" class="text-white-color w-full px-5 py-14px border bg-cream-light-color dark:bg-black-color focus:border-primary-color rounded-lg outline-none focus:outline-none transition-all duration-300 placeholder:text-gray-color leading-1"></textarea>
                    </div>
                    <div class="sm:col-start-1 sm:col-span-2">
                      <button type="submit" class="text-size-15 font-bold text-white-color capitalize py-17px px-35px bg-200 bg-gradient-secondary hover:bg-[-100%] rounded-full leading-1 transition-all duration-300">
                        Send Message
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <!-- experience single area -->
            <div class="md:col-start-8 md:col-span-5">
              <div class="contact-info-list">
                <ul class="flex flex-col gap-y-10">
                  <li class="flex flex-wrap items-center gap-25px position-relative wow fadeInRight" data-wow-delay=".4s">
                    <div class="icon-box text-xl w-50px h-50px text-white-color flex justify-center items-center flex-col bg-gradient-primary-2 rounded-full leading-1">
                      <i class="flaticon-phone-call leading-1 mt-1"></i>
                    </div>
                    <div class="text-box">
                      <p class="text-primary-color-light dark:text-white-color mb-1">
                        Phone
                      </p>
                      <a href="tel:0123456789" class="text-primary-color-light dark:text-white-color text-lg lg:text-xl font-medium hover:text-primary-color">+01
                        123 654 8096</a>
                    </div>
                  </li>
                  <li class="flex flex-wrap items-center gap-25px position-relative wow fadeInRight" data-wow-delay=".5s">
                    <div class="icon-box text-xl w-50px h-50px text-white-color flex justify-center items-center flex-col bg-gradient-primary-2 rounded-full leading-1">
                      <i class="flaticon-mail-inbox-app leading-1 mt-1"></i>
                    </div>
                    <div class="text-box">
                      <p class="text-primary-color-light dark:text-white-color mb-1">
                        Email
                      </p>
                      <a href="mailto:mail@mail.com" class="text-primary-color-light dark:text-white-color text-lg lg:text-xl font-medium hover:text-primary-color">gerolddesign@mail.com</a>
                    </div>
                  </li>
                  <li class="flex flex-wrap items-center gap-25px position-relative wow fadeInRight" data-wow-delay=".6s">
                    <div class="icon-box text-xl w-50px h-50px text-white-color flex justify-center items-center flex-col bg-gradient-primary-2 rounded-full leading-1">
                      <i class="flaticon-location leading-1 mt-1"></i>
                    </div>
                    <div class="text-box">
                      <p class="text-primary-color-light dark:text-white-color mb-1">
                        Address
                      </p>
                      <a href="#" class="text-primary-color-light dark:text-white-color text-lg lg:text-xl font-medium hover:text-primary-color">Warne
                        Park Street Pine, <br />
                        FL 33157, New York</a>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- footer area -->
  <footer>
    <div class="footer-inner bg-seondary-color dark:bg-dark-color">
      <div class="container">
        <div class="flex flex-col items-center pt-50px pb-5 md:pt-60px">
          <div class="footer-logo w-75px h-75px mb-6">
            <a href="#"><img src="./assets/img/logo/logo.png" alt="" /></a>
          </div>
          <!-- nav -->
          <div>
            <ul class="nav flex flex-wrap justify-center items-center gap-x-35px">

              <li class="nav_item group relative">
                <a href="#services" class="text-size-15 font-medium text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[10px] md:after:bottom-[15px] lg:after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Services
                </a>
              </li>
              <li class="nav_item group relative">
                <a href="#portfolio" class="text-size-15 font-medium text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[10px] md:after:bottom-[15px] lg:after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Portfolios
                </a>
              </li>

              <li class="nav_item group relative">
                <a href="#resume" class="text-size-15 font-medium text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[10px] md:after:bottom-[15px] lg:after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Experience
                </a>
              </li>
              <li class="nav_item group relative">
                <a href="#skills" class="text-size-15 font-medium text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[10px] md:after:bottom-[15px] lg:after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Skills
                </a>
              </li>

              <li class="nav_item group relative">
                <a href="#blogs" class="text-size-15 font-medium text-white-color capitalize py-10px md:py-15px lg:py-25px 2xl:py-30px relative z-0 after:w-0 after:h-0.5 after:bg-gradient-primary after:absolute after:right-0 hover:after:left-0 after:bottom-[10px] md:after:bottom-[15px] lg:after:bottom-[25px] after:transition-all after:duration-500 group-hover:after:w-full">Blog
                </a>
              </li>
            </ul>
          </div>
          <div class="copyright text-gray-color whitespace-nowrap text-sm md:text-base mt-5">
            © 2024 All rights reserved by
            <a href="index.html" class="text-white-color hover:text-primary-color">ThemeJunction</a>
          </div>
        </div>
      </div>
    </div>
  </footer>
  <!-- JSS here -->
  <script src="./assets/js/jquery.min.js"></script>
  <script src="./assets/js/gsap.min.js"></script>
  <script src="./assets/js/gsap-scroll-to-plugin.min.js"></script>
  <script src="./assets/js/gsap-scroll-trigger.min.js"></script>
  <script src="./assets/js/gsap-split-text.min.js"></script>
  <script src="./assets/js/appear.min.js"></script>
  <script src="./assets/js/wow.min.js"></script>
  <script src="./assets/js/odometer.min.js"></script>
  <script src="./assets/js/imagesloaded-pkgd.js"></script>
  <script src="./assets/js/isotope.pkgd.min.js"></script>
  <script src="./assets/js/owl.carousel.min.js"></script>
  <script src="./assets/js/nice-select.min.js"></script>
  <script src="./assets/js/backToTop.js"></script>
  <script src="./assets/js/lenis.min.js"></script>
  <script src="./assets/js/swiper.min.js"></script>
  <script src="./assets/js/theme-controller.js"></script>
  <script src="./assets/js/vanilla-tilt.min.js"></script>
  <script src="./assets/js/sticky.min.js"></script>
  <script src="./assets/js/lightcase.js"></script>
  <script src="./assets/js/validate.min.js"></script>
  <script src="./assets/js/main.js"></script>
  <script src="./assets/js/tj-cursor.js">
  </script>
</body>

</html>
