import {useLayoutStore} from '@/Stores/layout';
import {useLocaleStore} from '@/Stores/locale';
import {usePage} from '@inertiajs/vue3';
import {loadLanguageAsync} from 'laravel-vue-i18n';
import {watchEffect} from 'vue';

export const initialiseCustomerApp = () => {
    const layout = useLayoutStore();
    const locale = useLocaleStore();

    if (usePage().props.localeData) {
        loadLanguageAsync(usePage().props.localeData.language.code);
    }

    watchEffect(() => {
        // Set data of Navigation
        if (usePage().props.layout) {
            layout.navigation = usePage().props.layout.navigation ?? null;
            layout.secondaryNavigation =
                usePage().props.layout.secondaryNavigation ?? null;
        }


        // Set data of Locale (Language)
        if (usePage().props.localeData) {
            locale.language = usePage().props.localeData.language;
            locale.languageOptions = usePage().props.localeData.languageOptions;
        }

        if (usePage().props.app) {
            layout.app = usePage().props.app ?? null;
        }

        if (usePage().props.auth.user) {
            layout.user = usePage().props.auth.user ?? null;
        }

        layout.systemName = 'customer'

        layout.currentRouteParameters = route().params;
        layout.currentRoute = route().current();


        let moduleName = layout.currentRoute.split('.');
        layout.currentModule = moduleName[1];

        if(layout.currentModule==='portfolio'){
            layout.currentModule = moduleName[2];

        }


        if(layout.currentModule==='banners'){
            layout.currentParentModule='websites';
        }else{
            layout.currentParentModule=null;
        }

        // console.log(layout.currentRoute)
        // console.log(moduleName)

        layout.booted = true;

        if (usePage().props.auth.user.avatar_thumbnail) {
            layout.avatar_thumbnail = usePage().props.auth.user.avatar_thumbnail;
        }
    });

    return layout;
};
