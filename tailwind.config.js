/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./includes/**/*.html",
        "./pages/**/*.php",
    ],
    theme: {
        extend: {
            backgroundColor: {
            'custom-overlay': 'rgba(0, 0, 0, 0.2)', // Define a custom background color
            },
            screens: {
            'xs': '320px',     // Extra small phone screens
            'sm': '375px',     // Small phone screens (iPhone SE, etc)
            'md': '430px',     // Medium phone screens (iPhone Pro Max, etc)
            'lg': '640px',     // Large phone screens / Small tablets
            'xl': '768px',     // Tablets
            '2xl': '1024px',   // Small laptops
            '3xl': '1280px',   // Desktops
            '4xl': '1536px',   // Large desktops         
            },
            fontFamily: {
                'Krylon' : ['Krylon', 'sans-serif'],
                'Grotesk': ['Grotesk', 'serif'],
            }
        },
    },
    plugins: [],
};
