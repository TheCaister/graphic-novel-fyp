import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { Link, createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { InertiaProgress } from '@inertiajs/progress';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            // Register the <Link> component as a global component
            // Now, you don't need to import it in every page
            // Be careful with this!! May not always be the best idea!!
            .component('Link', Link)
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
    // This will now include a progress bar at the top of the page
    // You can customize it in several ways
    progress: {
        color: '#4B5563',
        showSpinner: true
    },
});

