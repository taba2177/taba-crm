<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Place favicon.ico in the root directory -->
    <link rel="shortcut icon"
        href={{ Taba\Crm\Models\CrmSetting::get('crm_business_favicon') ?? asset("/assets/img/favicon.png") }}
        type="image/x-icon" />

    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap"
        as="style">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap">
    <!-- CSS here -->

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles

    {!! crm_theme_css() !!}

    @if($fontUrl = crm_setting('crm_theme_font_url'))
        <link rel="preload" href="{{ $fontUrl }}" as="style">
        <link rel="stylesheet" href="{{ $fontUrl }}">
    @endif

    {{ seo()->render() }}

</head>


<body class="bg-gray-50 text-gray-800 font-tajawal">

    {{ $slot }}

</body>

</html>
