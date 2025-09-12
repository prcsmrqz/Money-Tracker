import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: false,
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Poppins", ...defaultTheme.fontFamily.sans],
            },
            keyframes: {
                fadeIn: {
                    "0%": { opacity: 0, transform: "translateY(20px)" },
                    "100%": { opacity: 1, transform: "translateY(0)" },
                },
                slideInRight: {
                    "0%": { opacity: 0, transform: "translateX(80px)" },
                    "100%": { opacity: 1, transform: "translateX(0)" },
                },
                slideInLeft: {
                    "0%": { opacity: 0, transform: "translateX(-80px)" },
                    "100%": { opacity: 1, transform: "translateX(0)" },
                },
                slideUp: {
                    "0%": { opacity: 0, transform: "translateY(80px)" },
                    "100%": { opacity: 1, transform: "translateY(0)" },
                },
            },
            animation: {
                "fade-in": "fadeIn 1s ease-out",
                "fade-in-delay": "fadeIn 1.5s ease-out",
                "slide-in-right": "slideInRight 1s ease-out forwards",
                "slide-in-right-delay": "slideInRight 1.5s ease-out forwards",
                "slide-in-left": "slideInLeft 1s ease-out forwards",
                "slide-in-left-delay": "slideInLeft 1.5s ease-out forwards",
                "slide-up": "slideUp 1s ease-out forwards",
                "slide-up-delay": "slideUp 1.5s ease-out forwards",
            },
        },
    },

    plugins: [forms],
};
