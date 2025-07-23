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

    <body class="bg-gray-50 text-gray-800 font-tajawal">

    {{ $slot }}

</body>

</html>
