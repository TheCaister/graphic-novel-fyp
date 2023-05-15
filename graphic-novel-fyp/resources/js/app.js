import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { Link, createInertiaApp, Head } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import Layout from './Shared/Layout.vue';


const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
      // Setting title, accepts current title as a parameter
    title: (title) => `${title} - ${appName}`,
    // resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    // Dynamic import
    // resolve: name => {
    //     // Dynamic imports return a promise
    //     // import('./Pages/${}');

    //     // Grabbing the default component from the page
    //     let page = require(`./Pages/${name}`).default;

    //     page.layout = Layout;
    // },

    // By awaiting, you can dynamically import pages in the case of large applications where a 
    // single bundle is not ideal. This will allow you to code split your pages into separate bundles.
    // Some pages might have their own dependencies?
    resolve: async (name) => {
        // Using the glob function, code splitting/lazy loading is enabled. Just omit the eager: true option.
        const page = await resolvePageComponent(
          `./Pages/${name}.vue`,
          import.meta.glob("./Pages/**/*.vue")
        );
        // page.then((module) => {
        //   module.default.layout = module.default.layout || Layout;
        // });

          if(page.default.layout === undefined) {
            page.default.layout = Layout;
          }

        // page.default.layout ??= Layout;
        return page;
      },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            // Register the <Link> component as a global component
            // Now, you don't need to import it in every page
            // Be careful with this!! May not always be the best idea!!
            .component('Link', Link)
            .component('Head', Head)
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

