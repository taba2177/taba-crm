@props(['name'])

@if ($menu = \App\Models\Menu::whereName($name)->first())
<header class="sticky top-0 z-50 w-full bg-white/95 backdrop-blur-sm transition-all duration-300 shadow-sm dark:bg-gray-900/95"
    x-data="{
        isScrolled: false,
        mobileMenuOpen: false,
        updateScroll() {
            this.isScrolled = window.scrollY > 50;
        }
    }"
    x-init="
        updateScroll();
        window.addEventListener('scroll', updateScroll);
    "
    :class="{
        'py-3': !isScrolled,
        'py-2': isScrolled,
        'shadow-lg': isScrolled
    }">

    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="/" class="flex items-center" aria-label="Brand">
                    <img src="/images/icon2.png" class="h-10 w-auto" alt="Logo">
                    <span class="ml-2 text-xl font-bold text-gray-900 dark:text-white hidden md:block">مصادر الشمال</span>
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-8 rtl:space-x-reverse">
                @foreach ($menu->items as $item)
                    <a wire:navigate
                        href="{{ $item['url'] }}"
                        @if ($item['type'] === 'external') target="_blank" rel="noopener noreferrer" @endif
                        class="{{ request()->is('*'.$item['url'].'*') ? 'text-sky-600 dark:text-sky-400 font-medium' : 'text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }} transition-colors duration-200"
                        aria-current="{{ request()->is(parse_url($item['url'], PHP_URL_PATH)) ? 'page' : 'false' }}">
                        {{ $item['title'] }}
                    </a>
                @endforeach
            </div>

            <!-- Right side buttons -->
            <div class="hidden md:flex items-center space-x-4 rtl:space-x-reverse">
                <a href="tel:0566228884" class="flex items-center text-gray-700 hover:text-sky-600 dark:text-gray-300 dark:hover:text-sky-400 transition-colors">
                    <svg class="w-5 h-5 mr-1 rtl:ml-1 rtl:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <span>0566228884</span>
                </a>

                <a wire:navigate href="/login" class="px-4 py-2 rounded-md bg-sky-600 text-white hover:bg-sky-700 transition-colors duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2 rtl:ml-2 rtl:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    تسجيل الدخول
                </a>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-sky-500" aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="block h-6 w-6" :class="{ 'hidden': mobileMenuOpen, 'block': !mobileMenuOpen }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="hidden h-6 w-6" :class="{ 'block': mobileMenuOpen, 'hidden': !mobileMenuOpen }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="md:hidden" x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95">
            <div class="pt-2 pb-3 space-y-1">
                @foreach ($menu->items as $item)
                    <a wire:navigate
                        href="{{ $item['url'] }}"
                        @if ($item['type'] === 'external') target="_blank" rel="noopener noreferrer" @endif
                        class="{{ request()->is('*'.$item['url'].'*') ? 'bg-sky-50 text-sky-600 dark:bg-gray-800 dark:text-sky-400' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }} block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200"
                        aria-current="{{ request()->is(parse_url($item['url'], PHP_URL_PATH)) ? 'page' : 'false' }}">
                        {{ $item['title'] }}
                    </a>
                @endforeach

                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="tel:0566228884" class="flex items-center px-3 py-2 text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white transition-colors">
                        <svg class="w-5 h-5 mr-2 rtl:ml-2 rtl:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        0566228884
                    </a>

                    <a wire:navigate href="/login" class="mt-2 w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-sky-600 hover:bg-sky-700 transition-colors">
                        <svg class="w-4 h-4 mr-2 rtl:ml-2 rtl:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        تسجيل الدخول
                    </a>
                </div>
            </div>
        </div>
    </nav>
</header>
@endif
