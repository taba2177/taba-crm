<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'Default dption')">
    <title>@yield('title', '|| Battour || Battour digital marketing')</title>

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('title', 'بطور للتسويق الإلكتروني')">
    <meta property="og:description" content="@yield('meta_description', 'Buttour is a digital marketing, websites and businesses.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="@yield('site_name','صالون بطور للتسويق الإلكتروني')"/>
    <meta property="og:image" content="@yield('meta_image', url('/default-image.jpg'))">
    <meta property="og:image:width" content="1024" />
	<meta property="og:image:height" content="1024" />
	<meta property="og:image:type" content="image/jpeg" />
    <meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:title" content="الصفحة الرئيسية" />
	<meta name="twitter:site" content="@Bat_tour" />



  <!-- favicons Icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset("/assets/images/favicons/apple-touch-icon.png") }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset("/assets/images/favicons/favicon-32x32.png") }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset("/assets/images/favicons/favicon-16x16.png") }}" />
    <link rel="manifest" href="{{asset("/assets/images/favicons/site.webmanifest") }}" />

      <!-- fonts -->
      <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&amp;family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
      rel="stylesheet">

  <link rel="stylesheet" href="{{ asset("/assets/vendors/bootstrap/css/bootstrap.min.css") }}" />
  <link rel="stylesheet" href="{{ asset("/assets/vendors/animate/animate.min.css") }}" />
  <link rel="stylesheet" href="{{ asset("/assets/vendors/animate/custom-animate.css") }}" />
  <link rel="stylesheet" href="{{ asset("/assets/vendors/fontawesome/css/all.min.css") }}" />
  <link rel="stylesheet" href="{{ asset("/assets/vendors/jarallax/jarallax.css") }}" />
  <link rel="stylesheet" href="{{ asset("/assets/vendors/jquery-magnific-popup/jquery.magnific-popup.css") }}" />
  <link rel="stylesheet" href="{{ asset("/assets/vendors/nouislider/nouislider.min.css") }}" />
  <link rel="stylesheet" href="{{ asset("/assets/vendors/nouislider/nouislider.pips.css") }}" />
  <link rel="stylesheet" href="{{ asset("/assets/vendors/odometer/odometer.min.css") }}" />
  <link rel="stylesheet" href="{{ asset("/assets/vendors/swiper/swiper.min.css") }}" />
  <link rel="stylesheet" href="{{ asset("/assets/vendors/flaticon/style.css") }}">
  <link rel="stylesheet" href="{{ asset("/assets/vendors/tiny-slider/tiny-slider.min.css") }}" />
  <link rel="stylesheet" href="{{ asset("/assets/vendors/reey-font/stylesheet.css") }}" />
  <link rel="stylesheet" href="{{ asset("/assets/vendors/owl-carousel/owl.carousel.min.css") }}" />
  <link rel="stylesheet" href="{{ asset("/assets/vendors/owl-carousel/owl.theme.default.min.css") }}" />
  <link rel="stylesheet" href="{{ asset("/assets/vendors/bxslider/jquery.bxslider.css") }}" />
  <link rel="stylesheet" href="{{ asset("/assets/vendors/bootstrap-select/css/bootstrap-select.min.css") }}" />
  <link rel="stylesheet" href="{{ asset("/assets/vendors/vegas/vegas.min.css") }}" />
  <link rel="stylesheet" href="{{ asset("/assets/vendors/jquery-ui/jquery-ui.css") }}" />
  <link rel="stylesheet" href="{{ asset("/assets/vendors/timepicker/timePicker.css") }}" />
  <link rel="stylesheet" href="{{ asset("/assets/vendors/polyglot-language-switcher/polyglot-language-switcher.css") }}" />
  <link rel='dns-prefetch' href='http://translate.google.com/' />

  <!-- template styles -->
   @if (session()->get('locale') == 'en')
  <link rel="stylesheet" href="{{ asset("/assets/css/new_ontech_full.css") }}" />
   @else
  <link rel="stylesheet" href="{{ asset("/assets/css/Battour.css") }}" />
   @endif

</head>

   @if (session()->get('locale') == 'en')
  <body class="custom-cursor" >
   @else
  <body class="custom-cursor" dir="rtl">
   @endif


    @include('header')

    @yield('content')

    @include('footer')


    <script src="{{ asset("/assets/vendors/jquery/jquery-3.6.0.min.js") }}"></script>
    <script src="{{ asset("/assets/vendors/bootstrap/js/bootstrap.bundle.min.js") }}"></script>
    <script src="{{ asset("/assets/vendors/jarallax/jarallax.min.js") }}"></script>
    <script src="{{ asset("/assets/vendors/jquery-ajaxchimp/jquery.ajaxchimp.min.js") }}"></script>
    <script src="{{ asset("/assets/vendors/jquery-appear/jquery.appear.min.js") }}"></script>
    <script src="{{ asset("/assets/vendors/jquery-circle-progress/jquery.circle-progress.min.js") }}"></script>
    <script src="{{ asset("/assets/vendors/jquery-magnific-popup/jquery.magnific-popup.min.js") }}"></script>
    <script src="{{ asset("/assets/vendors/jquery-validate/jquery.validate.min.js") }}"></script>
    <script src="{{ asset("/assets/vendors/nouislider/nouislider.min.js") }}"></script>
    <script src="{{ asset("/assets/vendors/odometer/odometer.min.js") }}"></script>
    <script src="{{ asset("/assets/vendors/swiper/swiper.min.js") }}"></script>
    <script src="{{ asset("/assets/vendors/tiny-slider/tiny-slider.min.js") }}"></script>
    <script src="{{ asset("/assets/vendors/wnumb/wNumb.min.js") }}"></script>
    <script src="{{ asset("/assets/vendors/wow/wow.js") }}"></script>
    <script src="{{ asset("/assets/vendors/isotope/isotope.js") }}"></script>
    <script src="{{ asset("/assets/vendors/countdown/countdown.min.js") }}"></script>
    <script src="{{ asset("/assets/vendors/owl-carousel/owl.carousel.min.js") }}"></script>
    <script src="{{ asset("/assets/vendors/bxslider/jquery.bxslider.min.js") }}"></script>
    <script src="{{ asset("/assets/vendors/bootstrap-select/js/bootstrap-select.min.js") }}"></script>
    <script src="{{ asset("/assets/vendors/vegas/vegas.min.js") }}"></script>
    <script src="{{ asset("/assets/vendors/jquery-ui/jquery-ui.js") }}"></script>
    <script src="{{ asset("/assets/vendors/timepicker/timePicker.js") }}"></script>
    <script src="{{ asset("/assets/vendors/circleType/jquery.circleType.js") }}"></script>
    <script src="{{ asset("/assets/vendors/circleType/jquery.lettering.min.js") }}"></script>
    <script src="{{ asset("/assets/vendors/polyglot-language-switcher/jquery.polyglot.language.switcher.js") }}"></script>
    <script src="{{ asset("/assets/vendors/progress-bar/knob.js") }}"></script>

    <!-- template js -->
    <script src="{{ asset("/assets/js/Battour.js") }}"></script>
    <script>
            $('.changeLanguage').change(function(event){
                var url = "{{ route('google.translate.change') }}";
             window.location.href = url+"?lang="+$(this).val()
            })
        </script>
</body>
</html>
