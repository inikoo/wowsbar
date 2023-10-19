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

const appName =
          window.document.getElementsByTagName('title')[0]?.innerText ||
          'Wowsbar';

createInertiaApp(
    {
      title  : (title) => `${title} - ${appName}`,
      resolve: (name) =>
          resolvePageComponent(
              `./Pages/Customer/${name}.vue`,
              import.meta.glob('./Pages/Customer/**/*.vue'),
          ),
      setup({el, App, props, plugin}) {
        const app = createApp({render: () => h(App, props)});

        if(import.meta.env.VITE_SENTRY_CUST_DSN) {
          Sentry.init({
                        app,
                        dsn                     : import.meta.env.VITE_SENTRY_CUST_DSN,
                        environment             : import.meta.env.VITE_APP_ENV,
                        integrations            : [
                          new Sentry.Replay(),
                        ],
                        replaysSessionSampleRate: import.meta.env.VITE_SENTRY_CUST_REPLAY_SESSION_SAMPLE_RATE,
                        replaysOnErrorSampleRate: import.meta.env.VITE_SENTRY_CUST_REPLAY_ON_ERRORSAMPLE_RATE,
                      });
        }
        app.use(plugin).
            use(createPinia()).
            use(ZiggyVue, Ziggy).
            use(Notifications).
            use(i18nVue, {
              resolve: async (lang) => {
                const languages = import.meta.glob('../../lang/*.json');
                return await languages[`../../lang/${lang}.json`]();
              },
            }).
            mount(el);
        return app;
      },
      progress: {
        color: '#4B5563',
      },
    });
