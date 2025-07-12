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
    <link rel="stylesheet" href="{{ asset("/assets/css/odometer-theme-default.css") }}" />
    <link rel="stylesheet" href="{{ asset("/assets/css/magnific-popup.css") }}" />
    <link rel="stylesheet" href="{{ asset("/assets/css/nice-select.css") }}" />
    <link rel="stylesheet" href="{{ asset("/assets/css/backToTop.css") }}" />
    <link rel="stylesheet" href="{{ asset("/assets/css/style.css") }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{ seo()->render() }}

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
        <button
            class="theme-controller w-90px h-10 bg-200 bg-gradient-secondary hover:bg-[-100%] rounded-l-full text-whiteColor px-10px flex items-center transition-all duration-300 font-sora">
            <span class="text-base block dark:hidden">Dark</span>


            <svg xmlns="http://www.w3.org/2000/svg" class="mr-10px w-5 block dark:hidden" viewBox="0 0 512 512">
                <path
                    d="M160 136c0-30.62 4.51-61.61 16-88C99.57 81.27 48 159.32 48 248c0 119.29 96.71 216 216 216 88.68 0 166.73-51.57 200-128-26.39 11.49-57.38 16-88 16-119.29 0-216-96.71-216-216z"
                    fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32">
                </path>
            </svg>
            <span class="text-base hidden dark:block">Light</span>


            <svg xmlns="http://www.w3.org/2000/svg" class="hidden mr-10px w-5 dark:block" viewBox="0 0 512 512">
                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32"
                    d="M256 48v48M256 416v48M403.08 108.92l-33.94 33.94M142.86 369.14l-33.94 33.94M464 256h-48M96 256H48M403.08 403.08l-33.94-33.94M142.86 142.86l-33.94-33.94">
                </path>
                <circle cx="256" cy="256" r="80" fill="none" stroke="currentColor" stroke-linecap="round"
                    stroke-miterlimit="10" stroke-width="32"></circle>
            </svg>
        </button>
    </div>
    <!-- start: Back To Top -->
    <div class="progress-wrap" id="scrollUp">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>

    <crm::x-header />

    {{ $slot }}


    <div class="fixed bottom-4 left-4 z-50 flex flex-col space-y-4">
        <a href="https://wa.me/966557459656" target="_blank"
            class="bg-primary-color hover:bg-primary-color-700 text-white p-2 border-1 border-white rounded-full shadow-lg transition-transform duration-300 transform hover:scale-110 animate-bounce-in">
            <svg fill="#ffffff" class="w-8 h-8" viewBox="-1.66 0 740.824 740.824" xmlns="http://www.w3.org/2000/svg"
                stroke="#ffffff">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M630.056 107.658C560.727 38.271 468.525.039 370.294 0 167.891 0 3.16 164.668 3.079 367.072c-.027 64.699 16.883 127.855 49.016 183.523L0 740.824l194.666-51.047c53.634 29.244 114.022 44.656 175.481 44.682h.151c202.382 0 367.128-164.689 367.21-367.094.039-98.088-38.121-190.32-107.452-259.707m-259.758 564.8h-.125c-54.766-.021-108.483-14.729-155.343-42.529l-11.146-6.613-115.516 30.293 30.834-112.592-7.258-11.543c-30.552-48.58-46.689-104.729-46.665-162.379C65.146 198.865 202.065 62 370.419 62c81.521.031 158.154 31.81 215.779 89.482s89.342 134.332 89.311 215.859c-.07 168.242-136.987 305.117-305.211 305.117m167.415-228.514c-9.176-4.591-54.286-26.782-62.697-29.843-8.41-3.061-14.526-4.591-20.644 4.592-6.116 9.182-23.7 29.843-29.054 35.964-5.351 6.122-10.703 6.888-19.879 2.296-9.175-4.591-38.739-14.276-73.786-45.526-27.275-24.32-45.691-54.36-51.043-63.542-5.352-9.183-.569-14.148 4.024-18.72 4.127-4.11 9.175-10.713 13.763-16.07 4.587-5.356 6.116-9.182 9.174-15.303 3.059-6.122 1.53-11.479-.764-16.07-2.294-4.591-20.643-49.739-28.29-68.104-7.447-17.886-15.012-15.466-20.644-15.746-5.346-.266-11.469-.323-17.585-.323-6.117 0-16.057 2.296-24.468 11.478-8.41 9.183-32.112 31.374-32.112 76.521s32.877 88.763 37.465 94.885c4.587 6.122 64.699 98.771 156.741 138.502 21.891 9.45 38.982 15.093 52.307 19.323 21.981 6.979 41.983 5.994 57.793 3.633 17.628-2.633 54.285-22.19 61.932-43.616 7.646-21.426 7.646-39.791 5.352-43.617-2.293-3.826-8.41-6.122-17.585-10.714">
                    </path>
                </g>
            </svg>

        </a>
        <a href="tel:+966557459656"
            class="bg-primary-color hover:bg-primary-color-700 border-1 border-white text-white p-2 rounded-full shadow-lg transition-transform duration-300 transform hover:scale-110 animate-shake">
            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1C10.74 21 3 13.26 3 4c0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.47.57 3.58.12.35.03.74-.25 1.02L6.62 10.79z" />
            </svg>
        </a>
    </div>


    <crm::x-footer />

    <!-- JSS here -->
    <script src="{{ asset("/assets/js/jquery.min.js") }}"></script>
    <script src="{{ asset("/assets/js/gsap.min.js") }}"></script>
    <script src="{{ asset("/assets/js/gsap-scroll-to-plugin.min.js") }}"></script>
    <script src="{{ asset("/assets/js/gsap-scroll-trigger.min.js") }}"></script>
    <script src="{{ asset("/assets/js/gsap-split-text.min.js") }}"></script>
    <script src="{{ asset("/assets/js/appear.min.js") }}"></script>
    <script src="{{ asset("/assets/js/wow.min.js") }}"></script>
    <script src="{{ asset("/assets/js/odometer.min.js") }}"></script>
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
