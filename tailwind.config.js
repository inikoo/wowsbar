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
        "lg:flex-nowrap"
      ],

    plugins: [forms],
};
