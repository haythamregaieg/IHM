import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                display: ['Outfit', 'sans-serif'],
                body: ['Inter', 'sans-serif'],
            },
            colors: {
                brand: {
                    50: '#f0f4ff',
                    100: '#dce6ff',
                    200: '#baccff',
                    300: '#8aadff',
                    400: '#5585ff',
                    500: '#2B5CE6',
                    600: '#1a44c9',
                    700: '#1535a0',
                    800: '#122d85',
                    900: '#0d1f5c',
                },
                gold: {
                    400: '#F6A623',
                    500: '#E8950E',
                },
            },
            keyframes: {
                up: {
                    from: { opacity: 0, transform: 'translateY(20px)' },
                    to: { opacity: 1, transform: 'translateY(0)' },
                },
                pop: {
                    from: { opacity: 0, transform: 'scale(.95)' },
                    to: { opacity: 1, transform: 'scale(1)' },
                },
            },
            animation: {
                'fade-up': 'up .5s ease both',
                'pop-in': 'pop .3s ease both',
            },
        },
    },

    plugins: [forms],
};
