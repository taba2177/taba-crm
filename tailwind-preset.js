// packages/taba/crm/tailwind-preset.js

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        // Add all the paths your package and its dependencies need
        "./vendor/taba/crm/src/**/*.php",
        "./vendor/filament/**/*.blade.php",
        "./vendor/awcodes/filament-curator/resources/**/*.blade.php",
        "./vendor/jeffgreco13/filament-breezy/resources/**/*.blade.php",
        "./vendor/pboivin/filament-peek/resources/views/**/*.blade.php",
        "./app/Filament/**/*.php",
        "./resources/views/components/logo.blade.php",
        "./resources/views/filament/**/*.blade.php",
        "./vendor/awcodes/filament-curator/resources/**/*.blade.php",
        "./vendor/bezhansalleh/filament-exceptions/resources/**/*.blade.php",
        "./vendor/filament/**/*.blade.php",
        "./vendor/jeffgreco13/filament-breezy/resources/**/*.blade.php",
        "./vendor/pboivin/filament-peek/resources/views/**/*.blade.php",
    ],
    theme: {
        extend: {},
    },
    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
    ],
};
