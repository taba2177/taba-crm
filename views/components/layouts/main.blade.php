<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Place favicon.ico in the root directory -->
    <link rel="shortcut icon" href="{{ asset("/assets/img/favicon.png") }}" type="image/x-icon" />
    <!-- CSS here -->
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
            <button
                class="theme-controller w-90px h-10 bg-200 bg-gradient-secondary hover:bg-[-100%] rounded-l-full text-whiteColor px-10px flex items-center transition-all duration-300 font-sora">
                <span class="text-base block dark:hidden">Dark</span>


                <svg xmlns="http://www.w3.org/2000/svg" class="mr-10px w-5 block dark:hidden" viewBox="0 0 512 512">
                    <path
                        d="M160 136c0-30.62 4.51-61.61 16-88C99.57 81.27 48 159.32 48 248c0 119.29 96.71 216 216 216 88.68 0 166.73-51.57 200-128-26.39 11.49-57.38 16-88 16-119.29 0-216-96.71-216-216z"
                        fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="32"></path>
                </svg>
                <span class="text-base hidden dark:block">Light</span>


                <svg xmlns="http://www.w3.org/2000/svg" class="hidden mr-10px w-5 dark:block" viewBox="0 0 512 512">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10"
                        stroke-width="32"
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

        <x-main-header />

        {{ $slot }}


    </div>
    @livewireScripts


    </script>

</body>

</html>
