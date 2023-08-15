// Used for OrgApp, PublicApp, TenantApp
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
        if (usePage().props.layout) {
            layout.navigation = usePage().props.layout.navigation ?? null
            layout.secondaryNavigation =
                usePage().props.layout.secondaryNavigation ?? null
            if (usePage().props.layout.shopsInDropDown) {
                layout.shopsInDropDown =
                    usePage().props.layout.shopsInDropDown.data ?? {}
            }
            if (usePage().props.layout.websitesInDropDown) {
                layout.websitesInDropDown =
                    usePage().props.layout.websitesInDropDown.data ?? {}
            }
            if (usePage().props.layout.warehousesInDropDown) {
                layout.warehousesInDropDown =
                    usePage().props.layout.warehousesInDropDown.data ?? {}
            }
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
        layout.currentModule = layout.currentRoute?.substring(
            0,
            layout.currentRoute?.indexOf(".")
        )

        if (usePage().props.layoutShopsList) {
            layout.shops = usePage().props.layoutShopsList
        }

        if (usePage().props.layoutWebsitesList) {
            layout.websites = usePage().props.layoutWebsitesList
        }

        if (usePage().props.layoutWarehousesList) {
            layout.warehouses = usePage().props.layoutWarehousesList
        }

        if (!layout.booted && layout.shops) {
            if (Object.keys(layout.shops).length === 1) {
                layout.currentShopData = {
                    slug: layout.shops[Object.keys(layout.shops)[0]].slug,
                    name: layout.shops[Object.keys(layout.shops)[0]].name,
                    code: layout.shops[Object.keys(layout.shops)[0]].code,
                }
            }
        }

        if (!layout.booted && layout.websites) {
            if (Object.keys(layout.websites).length === 1) {
                layout.currentWebsiteData = {
                    slug: layout.websites[Object.keys(layout.websites)[0]].slug,
                    name: layout.websites[Object.keys(layout.websites)[0]].name,
                    code: layout.websites[Object.keys(layout.websites)[0]].code,
                }
            }
        }

        if (!layout.booted && layout.warehouses) {
            if (Object.keys(layout.warehouses).length === 1) {
                layout.currentWarehouseData = {
                    slug: layout.warehouses[Object.keys(layout.warehouses)[0]].slug,
                    name: layout.warehouses[Object.keys(layout.warehouses)[0]].name,
                    code: layout.warehouses[Object.keys(layout.warehouses)[0]].code,
                }
            }
        }

        layout.booted = true

        if (usePage().props.auth.user.avatar_thumbnail) {
            layout.avatar_thumbnail = usePage().props.auth.user.avatar_thumbnail
        }
    })
    return layout
}
