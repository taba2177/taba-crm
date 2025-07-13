<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
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

</head>

<body class="bg-white">
  <div class="flex flex-col min-h-screen">
    <main>
      {!! $slot ?? '' !!}
    </main>
  </div>
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
  <script src="{{ asset("/assets/js/tj-cursor.js") }}"></script>

</body>

</html>