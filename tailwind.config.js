import preset from './vendor/filament/support/tailwind.config.preset'
export default {
    // Filament ke preset ko use karna zaroori hai colors aur spacing ke liye
    presets: [preset], 

    content: [
        // Aapke custom files
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        
        // Filament ki core files (duplicate paths remove kar diye gaye hain)
        "./app/Filament/**/*.php",
        "./vendor/filament/**/*.blade.php",
    ],

    darkMode: "class",

    theme: {
        extend: {
            colors: {
                primary: {
                    50: "#eef2ff",
                    500: "#6366f1",
                    700: "#4338ca",
                },
            },

            borderRadius: {
                xl: "14px",
                "2xl": "18px",
            },
        },
    },

    plugins: [],
};
