import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { initializeTheme } from './composables/useAppearance';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        // INTENTIONAL: keep DesignSystem playground code out of production bundles.
        // The route is development-only in `routes/web.php`; excluding it here prevents
        // shipping playground/demo UI to production clients.
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.env.PROD
                ? import.meta.glob<DefineComponent>([
                      './pages/**/*.vue',
                      '!./pages/DesignSystem.vue',
                  ])
                : import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();
