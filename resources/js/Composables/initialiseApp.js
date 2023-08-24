// Used for OrgApp, PublicApp, TenantApp
import { useLayoutStore } from "@/Stores/layout"
import { useLocaleStore } from "@/Stores/locale"
import { usePage } from "@inertiajs/vue3"
import { loadLanguageAsync } from "laravel-vue-i18n"
import { watchEffect } from "vue"
import { getAuth, signInWithCustomToken } from "firebase/auth";

const auth = getAuth();
export const authFirebase = (tokenBackend) => {
    signInWithCustomToken(auth, tokenBackend)
        .then((userCredential) => {
            console.log("Successfully login to Firebase")
            // console.log(userCredential)
        })
        .catch((error) => {
            const errorCode = error.code;
            const errorMessage = error.message;
            console.log("Error login to Firebase")
            console.error(error)

            // ...
    });
}

export const initialiseApp = () => {
    const layout = useLayoutStore()
    const locale = useLocaleStore()

    if (usePage().props.localeData) {
        loadLanguageAsync(usePage().props.localeData.language.code)
    }
    watchEffect(() => {
        if (usePage().props.layout) {
            layout.navigation = usePage().props.layout.navigation ?? null
            layout.secondaryNavigation =
                usePage().props.layout.secondaryNavigation ?? null
        }

        if (usePage().props.localeData) {
            locale.language = usePage().props.localeData.language
            locale.languageOptions = usePage().props.localeData.languageOptions
        }

        if (usePage().props.tenant) {
            layout.tenant = usePage().props.tenant ?? null
        }



        layout.currentRouteParameters = route().params
        layout.currentRoute = route().current()
        let substring = layout.currentRoute?.substring(
            0,
            layout.currentRoute?.indexOf(".")
        )

        if (substring == "org" || substring == "public") {
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
