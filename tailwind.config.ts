import type { Config } from 'tailwindcss';
import defaultTheme from 'tailwindcss/defaultTheme';
import preset from './tailwind.preset';

export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './resources/js/**/*.ts',
    ],
    presets: [preset],
} satisfies Config;
