const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            // Here, you can add your own breakpoints for screen sizes
            screens: {
                'sm': '480px',
                'md': '768px',
                'lg': '976px',
                'xl': '1440px',
            },
            colors: {
                brightRed: 'hsl(12, 88%, 59%)',
                brightRedLight: 'hsl(12, 88%, 69%)',
                brightRedSupLight: 'hsl(12, 88%, 95%)',
                darkBlue: 'hsl(228, 39%, 23%)',
                darkGrayishBlue: 'hsl(227, 12%, 61%)',
                veryDarkBlue: 'hsl(233, 12%, 13%)',
                veryPaleRed: 'hsl(13, 100%, 96%)',
                veryLightGray: 'hsl(0, 0%, 98%)',
                transparent: 'transparent',
                current: 'currentColor',
                appBackground: '#F3F2EF',
                redButtons: '#BF2525',
                greenButtons:'#09814A',
                blueButtons: '#7699D4',
                accentWhite: '#D9D9D9',
            },
            width: {
                '500': '500px',
                '400': '400px',
                '300': '300px',
                '250': '250px',
                '200': '200px',
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
