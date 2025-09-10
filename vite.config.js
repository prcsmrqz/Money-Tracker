import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    build: {
        outDir: "public/assets", // 👈 change build output folder
        manifest: true, // keep manifest.json
        rollupOptions: {
            input: "resources/js/app.js",
        },
    },
});
