import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: [
                    "Outfit",
                    "Plus Jakarta Sans",
                    ...defaultTheme.fontFamily.sans,
                ],
                serif: ["Playfair Display", ...defaultTheme.fontFamily.serif],
            },
            colors: {
                // ☕ Coffee-inspired color palette
                coffee: {
                    50: "#fdf8f6",
                    100: "#f8f1e5",
                    200: "#f2e3d0",
                    300: "#e8d0b3",
                    400: "#d9b38c",
                    500: "#c89968",
                    600: "#b8804d",
                    700: "#6f4e37", // Main coffee brown
                    800: "#5a3e2c",
                    900: "#3e2723", // Dark espresso
                },
                cream: {
                    50: "#fffcf5",
                    100: "#fff9ee", // Slightly warmer off-white
                    200: "#fef3d9",
                    300: "#fdecc4",
                    400: "#fce3a8",
                },
                forest: {
                    500: "#2d4a3e", // Deep Forest Green
                    600: "#233b31",
                },
                midnight: {
                    500: "#191970", // Midnight Blue
                    600: "#121250",
                },
                latte: "#f5deb3",
                mocha: "#8b4513",
                cappuccino: "#d2691e",
            },
            animation: {
                "fade-in": "fadeIn 0.5s ease-in",
                "fade-out": "fadeOut 0.5s ease-out",
                "slide-in-top": "slideInTop 0.5s ease-out",
                "slide-in-bottom": "slideInBottom 0.5s ease-out",
                "bounce-slow": "bounce 2s infinite",
                "pulse-slow": "pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite",
                steam: "steam 2s ease-in-out infinite",
                brewing: "brewing 2s ease-in-out infinite",
                confetti: "confetti 0.5s ease-out",
                wiggle: "wiggle 1s ease-in-out infinite",
                float: "float 6s ease-in-out infinite",
                blob: "blob 7s infinite",
            },
            keyframes: {
                fadeIn: {
                    "0%": { opacity: "0" },
                    "100%": { opacity: "1" },
                },
                fadeOut: {
                    "0%": { opacity: "1" },
                    "100%": { opacity: "0" },
                },
                slideInTop: {
                    "0%": { transform: "translateY(-100%)", opacity: "0" },
                    "100%": { transform: "translateY(0)", opacity: "1" },
                },
                slideInBottom: {
                    "0%": { transform: "translateY(100%)", opacity: "0" },
                    "100%": { transform: "translateY(0)", opacity: "1" },
                },
                steam: {
                    "0%": {
                        transform: "translateY(0) scale(1)",
                        opacity: "0.7",
                    },
                    "50%": {
                        transform: "translateY(-20px) scale(1.2)",
                        opacity: "0.3",
                    },
                    "100%": {
                        transform: "translateY(-40px) scale(1.5)",
                        opacity: "0",
                    },
                },
                brewing: {
                    "0%, 100%": { transform: "translateY(0)" },
                    "50%": { transform: "translateY(-10px)" },
                },
                wiggle: {
                    "0%, 100%": { transform: "rotate(-3deg)" },
                    "50%": { transform: "rotate(3deg)" },
                },
                float: {
                    "0%, 100%": { transform: "translateY(0)" },
                    "50%": { transform: "translateY(-20px)" },
                },
                blob: {
                    "0%": { transform: "translate(0px, 0px) scale(1)" },
                    "33%": { transform: "translate(30px, -50px) scale(1.1)" },
                    "66%": { transform: "translate(-20px, 20px) scale(0.9)" },
                    "100%": { transform: "translate(0px, 0px) scale(1)" },
                },
            },
            boxShadow: {
                coffee: "0 4px 14px 0 rgba(111, 78, 55, 0.15)",
                "coffee-lg": "0 10px 25px 0 rgba(111, 78, 55, 0.2)",
            },
        },
    },

    plugins: [forms],
};
