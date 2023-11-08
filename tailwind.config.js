import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            fontSize: {
                xxs: ['0.6rem', {
                    lineHeight: '0.8rem',
                }]
            },
            colors: {
                'regal-pink': '#EDBBBA',
                'org': {
                    30: "#faf9fb",
                    50: '#e7e0e9',
                    100: '#cfc2d4',
                    200: '#b8a4be',
                    300: '#a188aa',
                    400: '#8a6c95',
                    500: '#745181',
                    600: '#60446a',
                    700: '#4d3755',
                    800: '#3a2b40',
                    900: '#291f2c',
                    950: '#18131a',
                }
            },
            keyframes: {
                shimmer: {
                    '100%': {
                        transform: 'translateX(100%)',
                    },
                },
            },
            animation: {
                skeleton: 'shimmer 1.3s ease-in-out infinite',
            }
        },
    },

    safelist: [
        'space-y-8',
        'mx-auto',
        'text-center',
        'md:text-left',
        'xl:col-span-2',
        'xl:mt-0',
        "md:flex-row ",
        "md:flex-nowrap",
        "flex-wrap", 
        "lg:flex-nowrap",
        "md:grid-cols-3",
        "md:divide-x",
        "md:divide-y-0",
        "grid-cols-1 ",
        "gap-x-8",
        "gap-y-16",
        "text-center",
        "lg:grid-cols-3",
        "xl:grid-cols-4",
        "rounded-3xl"
      ],

    plugins: [forms],
};
