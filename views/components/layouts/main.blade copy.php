@props([
'title' => '',
'siteName' => config('app.name'),
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  dir="rtl">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>{{ $title ? "$title — " : '' }}{{ config('app.name') }}</title>
      @vite(['resources/css/app.css', 'resources/js/app.js'])
      <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
      <style>
         @keyframes scroll {
         0% {
         transform: translate(0%);
         }
         100% {
         transform: translate(calc(-100% - 2rem));
         }
         }
         .marquee__group {
         animation: scroll 30s linear infinite;
         }
      </style>
      @livewireStyles
   </head>
   {{--
   <body class="bg-white">
      --}}
      <body class="dark:bg-slate-700 flex flex-col min-h-screen transparent" >
         {{--
         <div class="flex flex-col min-h-screen">
            --}}
            {{-- @livewire('partials.navbar') --}}
            {{--
            <header class="bg-black text-white">
               <x-container>
                  <nav class="main-nav flex items-center">
                     @if ($siteName)
                     <div class="text-2xl">
                        <a href="/">{{ $siteName }}</a>
                     </div>
                     @endif --}}
                     <x-menu name="main" />
                     {{--
                  </nav>
               </x-container>
            </header>
            --}}
            <main class="flex-grow">
               {!! $slot ?? '' !!}

               <section class="py-10 lg:py-20">
                  <x-container>
                     <div class="grid lg:grid-cols-2 gap-6 items-center">
                        <div>
                           <div class="max-w-2xl animate-fade-up">
                              <span class="py-1 px-3 rounded-md text-xs font-medium uppercase bg-sky-600 tracking-wider border-b border-primary bg-primary/20 text-white">Smooth Onboarding</span>
                              <h2 class="text-4xl font-medium text-sky-950 mt-4">Effortless Integrations for a Quick Start</h2>
                              <p class="text-base mt-5">Link Vault with your current financial technology stack to simplify data comprehension and decision-making.</p>
                           </div>
                           <div class="grid md:grid-cols-3 sm:grid-cols-2 gap-6 mt-10">
                              <div class="p-10 rounded-xl border border-sky-200 transform transition duration-500 hover:scale-110 "><img src="/aeropage/assets/1-jg6ak9OC.png"></div>
                              <div class="p-10 rounded-xl border border-sky-200 transform transition duration-500 hover:scale-110"><img src="/aeropage/assets/2-Cwoqmxj3.png"></div>
                              <div class="p-10 rounded-xl border border-sky-200 transform transition duration-500 hover:scale-110"><img src="/aeropage/assets/3-azZlzWdS.png"></div>
                              <div class="p-10 rounded-xl border border-sky-200 transform transition duration-500 hover:scale-110"><img src="/aeropage/assets/4-CirBbC_M.png"></div>
                              <div class="p-10 rounded-xl border border-sky-200 transform transition duration-500 hover:scale-110"><img src="/aeropage/assets/5-CPOT8r6N.png"></div>
                              <div class="p-10 rounded-xl border border-sky-200 transform transition duration-500 hover:scale-110"><img src="/aeropage/assets/6-BqUhSAHC.png"></div>
                           </div>
                        </div>
                        <div class="relative lg:mb-0">
                           <div class="relative h-full"><img src="images/main.jpeg" class="mx-auto rounded-xl h-full"></div>
                           <div class="absolute inset-x-0 -bottom-14 hidden sm:block"><img src="/aeropage/assets/img-7-DCbG3-sz.png" class="h-full rounded-xl"></div>
                        </div>
                     </div>
                  </x-container>
               </section>

                <section class="py-16 px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-7xl">
                    <div class="flex flex-col items-center justify-between  lg:flex-row">
                    <!-- Image Section -->
                    <div class="relative w-full rounded-x-2xl lg:w-1/2">
                        <div class="relative overflow-hidden shadow-2xl">
                        <img src="/images/image (10).jpg" alt="فريق العمل المتخصص"
                            class="h-full w-full object-cover transition-transform duration-500 hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-sky-900/70 to-sky-900/20"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
                            <h3 class="text-2xl font-bold">خبرة تزيد عن 10 سنوات في السوق العقاري</h3>
                            <p class="mt-2 text-sky-100">
                            نقدم حلولاً عقارية متكاملة تلبي جميع احتياجات عملائنا الكرام
                            </p>
                            <button class="mt-4 rounded-lg bg-white px-6 py-2 font-medium text-sky-600 shadow-md transition hover:bg-sky-50">
                            تواصل معنا
                            </button>
                        </div>
                        </div>
                    </div>
                    <!-- Content Card -->
                    <div class="w-full lg:w-1/2">
                        <div class="relative h-full overflow-hidden rounded-2xl bg-white shadow-2xl">
                        <!-- Decorative elements -->
                        <div class="absolute top-0 left-0 h-full w-1 bg-gradient-to-b from-sky-400 to-sky-600"></div>
                        <div class="absolute bottom-0 left-0 w-full-xl opacity-5">
                            <img src="/images/bg-left-buttom.jpeg" alt="" class="h-auto w-full">
                        </div>

                        <!-- Card Content -->
                        <div class="relative p-8">
                            <div class="mb-8">
                            <span class="inline-block rounded-lg bg-sky-100 px-4 py-2 text-xl font-bold text-sky-600">
                                لماذا نحن
                            </span>
                            <div class="mt-4 h-1 w-12 bg-sky-500"></div>
                            </div>

                            <!-- Features List -->
                            <div class="space-y-6">
                            <!-- Feature 1 -->
                            <div class="relative flex items-start">
                                <div class="relative -right-4 flex h-8 w-8 items-center justify-center rounded-full bg-sky-500 text-white shadow-lg">
                                <span class="text-sm font-bold">1</span>
                                </div>
                                <div class="ml-10">
                                <h3 class="text-lg font-semibold text-sky-900">فريق عمل متخصص</h3>
                                <p class="mt-1 text-sm text-gray-600">
                                    فريقنا من الخبراء المؤهلين يضمن تقديم أفضل الحلول العقارية
                                </p>
                                </div>
                            </div>

                            <!-- Feature 2 -->
                            <div class="relative flex items-start">
                                <div class="relative -right-4 flex h-8 w-8 items-center justify-center rounded-full bg-sky-500 text-white shadow-lg">
                                <span class="text-sm font-bold">2</span>
                                </div>
                                <div class="ml-10">
                                <h3 class="text-lg font-semibold text-sky-900">حاصلين على التراخيص اللازمة</h3>
                                <p class="mt-1 text-sm text-gray-600">
                                    نعمل بتراخيص رسمية معتمدة من الهيئة العامة للعقار
                                </p>
                                </div>
                            </div>

                            <!-- Feature 3 -->
                            <div class="relative flex items-start">
                                <div class="relative -right-4 flex h-8 w-8 items-center justify-center rounded-full bg-sky-500 text-white shadow-lg">
                                <span class="text-sm font-bold">3</span>
                                </div>
                                <div class="ml-10">
                                <h3 class="text-lg font-semibold text-sky-900">أفضل التقنيات في مجالنا</h3>
                                <p class="mt-1 text-sm text-gray-600">
                                    نستخدم أحدث الأنظمة التقنية لإدارة العقارات والتسويق
                                </p>
                                </div>
                            </div>

                            <!-- Feature 4 -->
                            <div class="relative flex items-start">
                                <div class="relative -right-4 flex h-8 w-8 items-center justify-center rounded-full bg-sky-500 text-white shadow-lg">
                                <span class="text-sm font-bold">4</span>
                                </div>
                                <div class="ml-10">
                                <h3 class="text-lg font-semibold text-sky-900">نظام إدارة العملاء</h3>
                                <p class="mt-1 text-sm text-gray-600">
                                    نظام متكامل لمتابعة العملاء وتلبية احتياجاتهم باحترافية
                                </p>
                                </div>
                            </div>

                            <!-- Feature 5 -->
                            <div class="relative flex items-start">
                                <div class="relative -right-4 flex h-8 w-8 items-center justify-center rounded-full bg-sky-500 text-white shadow-lg">
                                <span class="text-sm font-bold">5</span>
                                </div>
                                <div class="ml-10">
                                <h3 class="text-lg font-semibold text-sky-900">الربط مع الجهات الحكومية المختلفة</h3>
                                <p class="mt-1 text-sm text-gray-600">
                                    نسهل إجراءات التوثيق والخدمات الحكومية لعملائنا
                                </p>
                                </div>
                            </div>

                            <!-- Feature 6 -->
                            <div class="relative flex items-start">
                                <div class="relative -right-4 flex h-8 w-8 items-center justify-center rounded-full bg-sky-500 text-white shadow-lg">
                                <span class="text-sm font-bold">6</span>
                                </div>
                                <div class="ml-10">
                                <h3 class="text-lg font-semibold text-sky-900">أفضل طرق التسويق</h3>
                                <p class="mt-1 text-sm text-gray-600">
                                    استراتيجيات تسويقية مبتكرة لعرض عقاراتك للجمهور المستهدف
                                </p>
                                </div>
                            </div>
                        </div>
                        </div>
                        </div>
                    </div>

                    </div>
                </div>
                </section>

               <section dir="ltr" id="solutions" class="py-10 lg:py-20">
                 <x-container>
                        <div class="mx-auto mb-14 max-w-xl text-center">
                        <span class="py-1 px-3 rounded-md text-xs font-medium uppercase bg-sky-600 tracking-wider border border-primary bg-primary/20 text-white animate-spin animate-once">Smooth Onboarding</span>
                        <h2 class="mt-4 text-4xl/tight font-medium text-default-950">Our Offered Services</h2>
                        <p class="mt-5 text-base">Adaptable layouts and immediate outcomes! Select a pre-designed header or craft a personalized layout that precisely aligns with your requirements.</p>
                    </div>
                    <div class="relative m-auto flex gap-8 overflow-hidden">
                        <div class="marquee__group flex min-w-full flex-shrink-0 items-center justify-around gap-8">
                            <div class="w-60 py-5">
                            <div class="rounded-xl bg-white p-6 text-center shadow-lg dark:bg-default-50 border-sky-200">
                                <div class="flex justify-center">
                                    <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="h-10 w-10 text-primary" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"></path>
                                        <path d="M18 14h-8"></path>
                                        <path d="M15 18h-5"></path>
                                        <path d="M10 6h8v4h-8V6Z"></path>
                                    </svg>
                                </div>
                                <h4 class="mt-5 text-lg font-medium text-default-950">Search Engine Optimization</h4>
                            </div>
                            </div>
                            <div class="w-60 py-5">
                            <div class="rounded-xl bg-white p-6 text-center shadow-lg dark:bg-default-50">
                                <div class="flex justify-center">
                                    <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="h-10 w-10 text-primary" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13c0 1.1.9 2 2 2Z"></path>
                                        <path d="M8 10v4"></path>
                                        <path d="M12 10v2"></path>
                                        <path d="M16 10v6"></path>
                                    </svg>
                                </div>
                                <h4 class="mt-5 text-lg font-medium text-default-950">التسويق العقاري </h4>
                            </div>
                            </div>
                            <div class="w-60 py-5">
                            <div class="rounded-xl bg-white p-6 text-center shadow-lg dark:bg-default-50">
                                <div class="flex justify-center">
                                    <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="h-10 w-10 text-primary" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="8" height="4" x="8" y="2" rx="1" ry="1"></rect>
                                        <path d="M8 4H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-.5"></path>
                                        <path d="M16 4h2a2 2 0 0 1 1.73 1"></path>
                                        <path d="M18.42 9.61a2.1 2.1 0 1 1 2.97 2.97L16.95 17 13 18l.99-3.95 4.43-4.44Z"></path>
                                        <path d="M8 18h1"></path>
                                    </svg>
                                </div>
                                <h4 class="mt-5 text-lg font-medium text-default-950">E-commerce Solutions</h4>
                            </div>
                            </div>
                            <div class="w-60 py-5">
                            <div class="rounded-xl bg-white p-6 text-center shadow-lg dark:bg-default-50">
                                <div class="flex justify-center">
                                    <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="h-10 w-10 text-primary" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"></path>
                                        <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path>
                                        <path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"></path>
                                        <path d="M2 7h20"></path>
                                        <path d="M22 7v3a2 2 0 0 1-2 2v0a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 16 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 12 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 8 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 4 12v0a2 2 0 0 1-2-2V7"></path>
                                    </svg>
                                </div>
                                <h4 class="mt-5 text-lg font-medium text-default-950">Pay-Per-Click Advertising</h4>
                            </div>
                            </div>
                            <div class="w-60 py-5">
                            <div class="rounded-xl bg-white p-6 text-center shadow-lg dark:bg-default-50">
                                <div class="flex justify-center">
                                    <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="h-10 w-10 text-primary" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M16 18a4 4 0 0 0-8 0"></path>
                                        <circle cx="12" cy="11" r="3"></circle>
                                        <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                                        <line x1="8" x2="8" y1="2" y2="4"></line>
                                        <line x1="16" x2="16" y1="2" y2="4"></line>
                                    </svg>
                                </div>
                                <h4 class="mt-5 text-lg font-medium text-default-950"> Branding  Strategy</h4>
                            </div>
                            </div>
                            <div class="w-60 py-5">
                            <div class="rounded-xl bg-white p-6 text-center shadow-lg dark:bg-default-50">
                                <div class="flex justify-center">
                                    <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="h-10 w-10 text-primary" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect>
                                        <circle cx="9" cy="9" r="2"></circle>
                                        <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                                    </svg>
                                </div>
                                <h4 class="mt-5 text-lg font-medium text-default-950">Marketing Copywriting</h4>
                            </div>
                            </div>
                        </div>
                        <div aria-hidden="true" class="marquee__group flex min-w-full flex-shrink-0 items-center justify-around gap-8">
                            <div class="w-60 py-5">
                            <div class="rounded-xl bg-white p-6 text-center shadow-lg dark:bg-default-50">
                                <div class="flex justify-center">
                                    <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="h-10 w-10 text-primary" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"></path>
                                        <path d="M18 14h-8"></path>
                                        <path d="M15 18h-5"></path>
                                        <path d="M10 6h8v4h-8V6Z"></path>
                                    </svg>
                                </div>
                                <h4 class="mt-5 text-lg font-medium text-default-950">Search Engine Optimization</h4>
                            </div>
                            </div>
                            <div class="w-60 py-5">
                            <div class="rounded-xl bg-white p-6 text-center shadow-lg dark:bg-default-50">
                                <div class="flex justify-center">
                                    <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="h-10 w-10 text-primary" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13c0 1.1.9 2 2 2Z"></path>
                                        <path d="M8 10v4"></path>
                                        <path d="M12 10v2"></path>
                                        <path d="M16 10v6"></path>
                                    </svg>
                                </div>
                                <h4 class="mt-5 text-lg font-medium text-default-950">التسويق العقاري </h4>
                            </div>
                            </div>
                            <div class="w-60 py-5">
                            <div class="rounded-xl bg-white p-6 text-center shadow-lg dark:bg-default-50">
                                <div class="flex justify-center">
                                    <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="h-10 w-10 text-primary" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="8" height="4" x="8" y="2" rx="1" ry="1"></rect>
                                        <path d="M8 4H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-.5"></path>
                                        <path d="M16 4h2a2 2 0 0 1 1.73 1"></path>
                                        <path d="M18.42 9.61a2.1 2.1 0 1 1 2.97 2.97L16.95 17 13 18l.99-3.95 4.43-4.44Z"></path>
                                        <path d="M8 18h1"></path>
                                    </svg>
                                </div>
                                <h4 class="mt-5 text-lg font-medium text-default-950">E-commerce Solutions</h4>
                            </div>
                            </div>
                            <div class="w-60 py-5">
                            <div class="rounded-xl bg-white p-6 text-center shadow-lg dark:bg-default-50">
                                <div class="flex justify-center">
                                    <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="h-10 w-10 text-primary" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"></path>
                                        <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path>
                                        <path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"></path>
                                        <path d="M2 7h20"></path>
                                        <path d="M22 7v3a2 2 0 0 1-2 2v0a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 16 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 12 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 8 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 4 12v0a2 2 0 0 1-2-2V7"></path>
                                    </svg>
                                </div>
                                <h4 class="mt-5 text-lg font-medium text-default-950">Pay-Per-Click Advertising</h4>
                            </div>
                            </div>
                            <div class="w-60 py-5">
                            <div class="rounded-xl bg-white p-6 text-center shadow-lg dark:bg-default-50">
                                <div class="flex justify-center">
                                    <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="h-10 w-10 text-primary" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M16 18a4 4 0 0 0-8 0"></path>
                                        <circle cx="12" cy="11" r="3"></circle>
                                        <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                                        <line x1="8" x2="8" y1="2" y2="4"></line>
                                        <line x1="16" x2="16" y1="2" y2="4"></line>
                                    </svg>
                                </div>
                                <h4 class="mt-5 text-lg font-medium text-default-950"> Branding  Strategy</h4>
                            </div>
                            </div>
                            <div class="w-60 py-5">
                            <div class="rounded-xl bg-white p-6 text-center shadow-lg dark:bg-default-50">
                                <div class="flex justify-center">
                                    <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="h-10 w-10 text-primary" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect>
                                        <circle cx="9" cy="9" r="2"></circle>
                                        <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                                    </svg>
                                </div>
                                <h4 class="mt-5 text-lg font-medium text-default-950">Marketing Copywriting</h4>
                            </div>
                            </div>
                        </div>
                    </div>
                </x-container>
            </section>

               <section id="home" class="relative overflow-hidden">
                  <x-container>
                     <div class="overflow-hidden rounded-2xl bg-cover bg-center bg-no-repeat" style="background-image: url(&quot;/aeropage/assets/img-1-Bbkznb6W.jpg&quot;);">
                        <div class="rounded-2xl inset-0 bg-gradient-to-t from-sky-900 to-sky-900/80 bg-hero">
                           <div class="container">
                              <div class="relative p-6">
                                 <div class="flex h-full items-center justify-center">
                                    <div class="relative mx-auto max-w-3xl text-center">
                                       <span class="rounded-md bg-white/10 px-3 py-1 text-sm font-medium uppercase tracking-wider text-white">AI knowledge hub</span>
                                       <h1 class="mt-10 text-3xl font-medium text-white md:text-5xl/snug">Build Quickly, Earn More</h1>
                                       <p class="mx-auto mt-5 w-3/4 text-base text-white/80">Leverage customer data to create exceptional and robust product experiences that drive conversions.</p>
                                       <div class="mt-10 flex justify-center">
                                          <a class="inline-flex items-center justify-center gap-2 rounded-md bg-primary px-8 py-2 text-base text-white transition-all duration-300 hover:bg-primary-700" href="/aeropage/landing/marketing-3">
                                          Read More
                                          <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                             <path d="M18 8L22 12L18 16"></path>
                                             <path d="M2 12H22"></path>
                                          </svg>
                                          </a>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </x-container>
               </section>

            </main>
            {{-- @livewire('partials.footer') --}}
            {{--
            <div class="mt-16"></div>
            <footer class="mt-auto text-center">
               <x-container class="text-gray-700">
                  <div class="flex flex-col lg:flex-row items-center justify-center space-x-4">
                     --}}
                     {{-- <span>Copyright © {{ date('Y') }} ACME inc.</span> --}}
                     <x-menu-footer name="footer" />
                     {{--
                  </div>
               </x-container>
            </footer>
            --}}
            {{--
         </div>
         --}}
         @stack('scripts')
         @livewireScripts
         <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
         <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
         {{--
         <x-livewire-alert::scripts />
         --}}
   </body>
</html>
