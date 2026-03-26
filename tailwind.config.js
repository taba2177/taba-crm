import defaultTheme from 'tailwindcss/defaultTheme';

 module.exports = {
    presets: [require('./vendor/taba/crm/tailwind-preset.js')],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
   './resources/views/**/*.blade.php', 
     './vendor/filament/**/*.blade.php',
        './vendor/taba/crm/resources/views/**/*.blade.php', // Add crm views
    ],
};
