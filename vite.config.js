import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite"; // Import this

export default defineConfig({
    plugins: [
        tailwindcss(), // Add this
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/css/filament/admin/theme.css", // Your Filament theme
                "resources/css/filament/platform/theme.css", // Platform panel theme
            ],
            refresh: true,
        }),
    ],
});
