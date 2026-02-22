import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                "primary": "#135bec",
                "primary-hover": "#2563eb",
                "background-light": "#f6f6f8",
                "background-dark": "#101622",
                "surface-dark": "#161e2c",
                "border-dark": "#232f48",
            },
        },
    },

    plugins: [forms],
};
