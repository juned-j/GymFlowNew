export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
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
