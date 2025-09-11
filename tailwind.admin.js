/** @type {import('tailwindcss').Config} */
const colors = require("tailwindcss/colors");

module.exports = {
    presets: [
        require("../../../vendor/filament/filament/tailwind.config.preset"),
    ],
    content: [
        "./app/Filament/**/*.php",
        "./resources/views/components/logo.blade.php",
        "./resources/views/filament/**/*.blade.php",
        "./vendor/awcodes/filament-curator/resources/**/*.blade.php",
        "./vendor/bezhansalleh/filament-exceptions/resources/**/*.blade.php",
        "./vendor/taba/crm/**/*.php",
        "./vendor/filament/**/*.blade.php",
        "./vendor/jeffgreco13/filament-breezy/resources/**/*.blade.php",
        "./vendor/pboivin/filament-peek/resources/views/**/*.blade.php",
        "./vendor/jaocero/radio-deck/resources/views/**/*.blade.php",
        "./vendor/bezhansalleh/filament-google-analytics/resources/views/**/*",
        "./vendor/bezhansalleh/filament-google-analytics/src/{Widgets,Support}/*",
        './vendor/diogogpinto/filament-auth-ui-enhancer/resources/**/*.blade.php';
    ],
    theme: {
        extend: {
            colors: {
                ...colors,
            },
            fontFamily: {
                tajawal: ["Tajawal", "sans-serif"],
            },
        },
    },
};
