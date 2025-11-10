/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", "ui-sans-serif", "system-ui"],
            },
        },
    },

    plugins: [require("@tailwindcss/forms"), require("daisyui")],

    daisyui: {
        themes: ["light", "dark", "corporate"],
    },
};
