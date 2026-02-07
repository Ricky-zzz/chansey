import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Filament/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    corePlugins: {
        // Keep DaisyUI working
    },

    plugins: [
        forms,
        function({ addUtilities }) {
            addUtilities({
                '.badge-count': {
                    '@apply inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-cyan-100 text-cyan-700': {},
                },
            })
        },
    ],
};
