// Used for  PublicApp, CustomerApp
import { useLayoutStore } from "@/Stores/layout"
import { useLocaleStore } from "@/Stores/locale"
import { usePage } from "@inertiajs/vue3"
import { loadLanguageAsync } from "laravel-vue-i18n"
import { watchEffect } from "vue"


export const initialiseApp = () => {
    const layout = useLayoutStore()
    const locale = useLocaleStore()

    if (usePage().props.localeData) {
        loadLanguageAsync(usePage().props.localeData.language.code)
    }
    watchEffect(() => {
        // Set data of Navigation
        if (usePage().props.layout) {
            layout.navigation = usePage().props.layout.navigation ?? null
            layout.secondaryNavigation =
                usePage().props.layout.secondaryNavigation ?? null
        }

        // Set data of Locale (Language)
        if (usePage().props.localeData) {
            locale.language = usePage().props.localeData.language
            locale.languageOptions = usePage().props.localeData.languageOptions
        }

        if (usePage().props.app) {
            layout.app = usePage().props.app ?? null
        }

        // Set data of User
        if (usePage().props.auth.user) {
            layout.user = usePage().props.auth.user ?? null
        }



        layout.currentRouteParameters = route().params
        layout.currentRoute = route().current()
        let substring = layout.currentRoute?.substring(
            0,
            layout.currentRoute?.indexOf(".")
        )

        if (substring === "org" || substring === "public" || substring === 'customer') {
            let moduleName = layout.currentRoute.split(".")
            layout.currentModule = moduleName[1]
        } else {
            layout.currentModule = layout.currentRoute?.substring(
                0,
                layout.currentRoute?.indexOf(".")
            );
        }

        layout.booted = true

        if (usePage().props.auth.user.avatar_thumbnail) {
            layout.avatar_thumbnail = usePage().props.auth.user.avatar_thumbnail
        }
    })
    return layout
}
