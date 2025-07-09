// const mix = require('laravel-mix');

// mix.js('resources/js/app.js', 'public/js')
//    .sass('resources/sass/app.scss', 'public/css')
//    .minify('public/js/app.js')
//    .minify('public/css/app.css');

const mix = require('laravel-mix');

// Combine and minify JS files
mix.js([
    // Core libraries (jQuery first, then dependencies)
    'resources/js/jquery.min.js',
    'resources/js/bootstrap.bundle.min.js',

    // GSAP animation suite
    'resources/js/gsap.min.js',
    'resources/js/gsap-scroll-to-plugin.min.js',
    'resources/js/gsap-scroll-trigger.min.js',
    'resources/js/gsap-split-text.min.js',

    // Utility libraries
    'resources/js/imagesloaded-plgd.js',
    'resources/js/isotope.plgd.min.js',
    'resources/js/masonry.min.js',
    'resources/js/vanilla-tilt.min.js',
    'resources/js/headroom.min.js',

    // UI components
    'resources/js/owl.carousel.min.js',
    'resources/js/swiper.min.js',
    'resources/js/magnific-popup.js',
    'resources/js/venobox.min.js',
    'resources/js/lightcase.js',
    'resources/js/nice-select.min.js',
    'resources/js/ti-cursor.js',

    // Animation libraries
    'resources/js/wow.min.js',
    'resources/js/appear.min.js',
    'resources/js/odometer.min.js',

    // Navigation
    'resources/js/one-page-nav.js',
    'resources/js/sticky.min.js',
    'resources/js/lenis.min.js',
    'resources/js/backToTop.js',

    // Forms
    'resources/js/validate.min.js',

    // Theme
    'resources/js/theme-controller.js',

    // Main application script (should be last)
    'resources/js/main.js'
], 'public/js/app.js').minify('public/js/app.js');

// Combine and minify CSS files
mix.styles([
    // Core CSS
    'resources/css/bootstrap.css', // Note: You might need to add this

    // Plugin CSS
    'resources/css/owl.carousel.min.css',
    'resources/css/swiper.min.css',
    'resources/css/magnific-popup.css',
    'resources/css/venobox.min.css',
    'resources/css/nice-select.css',

    // Animation/UI CSS
    'resources/css/odometer-theme-default.css',

    // Main styles (should be last to override others)
    'resources/css/style.css'
], 'public/css/app.css').minify('public/css/app.css');

// Optional: Copy any fonts or images that might be referenced
// mix.copy('resources/fonts', 'public/fonts');
// mix.copy('resources/images', 'public/images');
