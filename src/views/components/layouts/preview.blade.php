<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap"
        as="style">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap">

    <!-- Place favicon.ico in the root directory -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles

    {{ seo()->render() }}

</head>

<body class="bg-gray-50 text-gray-800 font-tajawal">

    {!! $slot ?? '' !!}

</body>

</html>