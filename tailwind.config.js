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
            colors: {
                'regal-pink': '#EDBBBA',
                'dark1': '#111827',
                'dark2': '#1f2937',
                'dark3': '#374151',
                'dark4': '#4b5563',
                'darklight': '#6b7280',
                'light1': '#f9fafb',
                'light2': '#f3f4f6',
                'light3': '#e5e7eb',
                'light4': '#9ca3af',
            },
        },
    },

    plugins: [forms],
};
