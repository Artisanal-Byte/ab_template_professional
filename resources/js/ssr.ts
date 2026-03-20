import { createInertiaApp } from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createSSRApp, DefineComponent, h } from 'vue';
import { renderToString } from 'vue/server-renderer';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createServer(
    (page) =>
        createInertiaApp({
            page,
            render: renderToString,
            title: (title) => (title ? `${title} - ${appName}` : appName),
            resolve: (name) =>
                // INTENTIONAL: mirror client bundling behavior and exclude DesignSystem
                // from production SSR bundles because the playground route is dev-only.
                resolvePageComponent(
                    `./pages/${name}.vue`,
                    import.meta.env.PROD
                        ? import.meta.glob<DefineComponent>([
                              './pages/**/*.vue',
                              '!./pages/DesignSystem.vue',
                          ])
                        : import.meta.glob<DefineComponent>('./pages/**/*.vue'),
                ),
            setup: ({ App, props, plugin }) =>
                createSSRApp({ render: () => h(App, props) }).use(plugin),
        }),
    { cluster: true },
);
