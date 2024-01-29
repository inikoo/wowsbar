import { useLayoutStore } from '@/Stores/layout'
import { useLocaleStore } from '@/Stores/locale'
import { router, usePage } from '@inertiajs/vue3'
import { loadLanguageAsync } from 'laravel-vue-i18n'
import { watchEffect } from 'vue'
import { useCustOnlineUsers } from '@/Stores/cust-online-users'

export const initialiseCustomerApp = () => {
    const layout = useLayoutStore()
    const locale = useLocaleStore()

    if (usePage().props.auth.user) {
        // echoPersonal.subscribe(usePage().props.auth.user.id)
        useCustOnlineUsers().subscribe()  // Websockets: online users

        router.on('navigate', (event) => {
            const dataActiveUser = {
                ...usePage().props.auth.user,
                name: null,
                last_active: new Date(),
                action: 'navigate',
                current_page: {
                    label: event.detail.page.props.title,
                    url: event.detail.page.url,
                    icon_left: usePage().props.live_users?.icon_left || null,
                    icon_right: usePage().props.live_users?.icon_right || null,
                },
            }

            // To avoid emit on logout
            if (dataActiveUser.id) {
                // Set to self
                useCustOnlineUsers().onlineUsers[usePage().props.auth.user.id] = dataActiveUser
                
                // Websockets: broadcast to others
                window.Echo.join(`cust.online.users`).whisper('otherIsNavigating', dataActiveUser)
            }
        })
    }

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

        if (usePage().props.auth.user) {
            layout.user = usePage().props.auth.user ?? null
        }

        layout.systemName = 'customer'

        layout.currentRouteParameters = route().params
        layout.currentRoute = route().current()


        let moduleName = (layout.currentRoute || '').split(".")
        layout.currentModule = moduleName.length > 1 ? moduleName[1] : ''

        if (layout.currentModule === 'portfolio') {
            layout.currentModule = moduleName[2]

        }


        if (layout.currentModule === 'banners') {
            layout.currentParentModule = 'websites'
        } else {
            layout.currentParentModule = null
        }

        // console.log(layout.currentRoute)
        // console.log(moduleName)

        layout.booted = true

        if (usePage().props.auth.user.avatar_thumbnail) {
            layout.avatar_thumbnail = usePage().props.auth.user.avatar_thumbnail
        }
    })

    return layout
}
