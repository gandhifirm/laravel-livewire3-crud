import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: [
                "app/Http/Controllers/*",
                "app/Livewire/*",
                "resources/views/includes/**/*",
                "resources/views/layouts/**/*",
                "resources/views/livewire/**/*",
                "app/Models/*",
            ],
        }),
    ],
});
