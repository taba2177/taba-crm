// packages/taba/crm/tailwind-preset.js

/** @type {import('tailwindcss').Config} */
const colors = require("tailwindcss/colors");

// Helper: define a color that reads from a CSS custom property
// with full Tailwind opacity modifier support (<alpha-value>).
function cssVar(name, fallback) {
    return ({ opacityValue }) => {
        if (opacityValue !== undefined) {
            return `rgba(var(${name}, ${fallback}), ${opacityValue})`;
        }
        return `rgb(var(${name}, ${fallback}))`;
    };
}

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
        "./vendor/diogogpinto/filament-auth-ui-enhancer/resources/**/*.blade.php",
    ],
    theme: {
        extend: {
            colors: {
                ...colors,
                // CRM theme colors: configurable from dashboard via CSS custom properties.
                // Fallback values are compiled into the CSS; runtime overrides come from
                // inline <style> injected in the layout by CrmServiceProvider.
                "primary-color":       cssVar("--crm-primary",       "51, 57, 73"),
                "primary-color-2":     cssVar("--crm-primary-2",     "59, 170, 197"),
                "primary-color-light": cssVar("--crm-primary-light", "26, 42, 51"),
                "seondary-color":      cssVar("--crm-secondary",     "59, 170, 197"),
                "seondary-color-2":    cssVar("--crm-secondary-2",   "45, 74, 92"),
                "seondary-color-3":    cssVar("--crm-secondary-3",   "29, 17, 41"),
            },
            fontFamily: {
                tajawal: ["Tajawal", "sans-serif"],
            },
        },
    },
    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
    ],
};
