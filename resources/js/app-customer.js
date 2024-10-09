import './bootstrap';
import '../css/app.css';

import {createApp, h} from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {ZiggyVue} from '../../vendor/tightenco/ziggy/dist/vue.m';
import {i18nVue} from 'laravel-vue-i18n';
import Notifications from '@kyvg/vue3-notification';
import {createPinia} from 'pinia';
import * as Sentry from '@sentry/vue';
import FloatingVue from 'floating-vue';
import 'floating-vue/dist/style.css';
import CustomerApp from '@/Layouts/CustomerApp.vue';
import { capitalize } from '@/Composables/capitalize.ts';
import PrimeVue from 'primevue/config';
import Aura from '@primevue/themes/aura';
import { definePreset } from '@primevue/themes';

const appName =
          window.document.getElementsByTagName('title')[0]?.innerText ||
          'Wowsbar';

const MyPrimePreset = definePreset(Aura, {
  semantic: {
      primary: {
          50: '{gray.50}',
          100: '{gray.100}',
          200: '{gray.200}',
          300: '{gray.300}',
          400: '{gray.400}',
          500: '{gray.500}',
          600: '{gray.600}',
          700: '{gray.700}',
          800: '{gray.800}',
          900: '{gray.900}',
          950: '{gray.950}'
      }
  }
});

createInertiaApp(
    {
      title  : (title) => `${title} - ${appName}`,
      resolve: (name) => {
          // resolvePageComponent(
          //     `./Pages/Customer/${name}.vue`,
          //     import.meta.glob('./Pages/Customer/**/*.vue'),
          // )
          const pages = import.meta.glob('./Pages/Customer/**/*.vue', { eager: true })
          let page = pages[`./Pages/Customer/${name}.vue`]
          if(!page) console.error(`File './Pages/Customer/${name}.vue' is not exist`)
          page.default.layout = page.default.layout || CustomerApp
          return page
      },
      setup({el, App, props, plugin}) {
        const app = createApp({render: () => h(App, props)});

        if(import.meta.env.VITE_SENTRY_CUST_DSN) {
          Sentry.init({
                        app,
                        dsn                     : import.meta.env.VITE_SENTRY_CUST_DSN,
                        environment             : import.meta.env.VITE_APP_ENV,
                          release: import.meta.env.VITE_RELEASE,
                        replaysSessionSampleRate: 0.1,
                        replaysOnErrorSampleRate: 1.0,
                        integrations: [new Sentry.Replay()]
                      });
        }
        app.use(plugin)
            .use(createPinia())
            .use(ZiggyVue, Ziggy)
            .use(Notifications)
            .use(FloatingVue)
            .use(PrimeVue, {
              theme: {
                preset: MyPrimePreset,
                options: {
                  darkModeSelector: '.my-app-dark',  // dark mode of Primevue depends .my-add-dark in <html>
                }
              }
            })
            .use(i18nVue, {
              resolve: async (lang) => {
                const languages = import.meta.glob('../../lang/*.json');
                return await languages[`../../lang/${lang}.json`]();
              },
            })
            .mount(el);
        return app;
      },
      progress: {
        color: '#4B5563',
      },
    });
