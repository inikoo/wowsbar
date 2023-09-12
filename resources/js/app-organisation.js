import "./bootstrap";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";
import { i18nVue } from "laravel-vue-i18n"
import { library } from "@fortawesome/fontawesome-svg-core";
import { faPlus } from "@/../private/pro-regular-svg-icons";
import Notifications from '@kyvg/vue3-notification'
library.add(faPlus);

import { createPinia } from "pinia";
const appName =
    window.document.getElementsByTagName("title")[0]?.innerText || "Wowsbar";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/Organisation/${name}.vue`,
            import.meta.glob("./Pages/Organisation/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(createPinia())
            .use(ZiggyVue, Ziggy)
            .use(Notifications)
            .use(i18nVue, {
				resolve: async (lang) => {
					const languages = import.meta.glob("../../lang/*.json")
					return await languages[`../../lang/${lang}.json`]()
				},
			})
            .mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
