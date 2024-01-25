/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 08 Dec 2023 02:51:39 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */


import { usePage, router } from "@inertiajs/vue3"
import { loadLanguageAsync } from "laravel-vue-i18n"
import { watchEffect } from "vue"

import { useLayoutStore } from "@/Stores/layout"
import { useLocaleStore } from "@/Stores/locale"
import { liveOrganisationUsers } from '@/Stores/active-users'
import { useEchoOrgPersonal } from '@/Stores/echo-org-personal.js'
import { useEchoOrgGeneral } from '@/Stores/echo-org-general.js'


export const initialiseOrgApp = () => {
    const layout = useLayoutStore()
    const locale = useLocaleStore()
    const echoPersonal = useEchoOrgPersonal()
    const echoGeneral = useEchoOrgGeneral()

    // Subscribe, sees join, sees leaving, listen to Websockets
    liveOrganisationUsers().subscribe()  // Websockets: active users
    echoGeneral.subscribe()  // Websockets: notification

    if (usePage().props.auth.user) {
        echoPersonal.subscribe(usePage().props.auth.user.id)

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
            if(dataActiveUser.id){
                // Set to self
                liveOrganisationUsers().liveOrganisationUsers[usePage().props.auth.user.id ] = dataActiveUser
                
                // Websockets: broadcast to others
                window.Echo.join(`org.live.users`).whisper('otherIsNavigating', dataActiveUser)
            }
        })
    }

    loadLanguageAsync(usePage().props.localeData?.language?.code)

    watchEffect(() => {
        // Set data of Navigation
        if (usePage().props.layout) {
            layout.navigation = usePage().props.layout.navigation ?? null
            layout.secondaryNavigation = usePage().props.layout.secondaryNavigation ?? null
        }

        // Set data of Locale (Language)
        if (usePage().props.localeData) {
            locale.language = usePage().props.localeData.language
            locale.languageOptions = usePage().props.localeData.languageOptions
        }

        if (usePage().props.organisation) {
            layout.organisation = usePage().props.organisation
        }

        // Set data of User
        if (usePage().props.user) {
            layout.user = usePage().props.user
        }

        // Set avatar thumbnail
        if (usePage().props.auth?.user?.avatar_thumbnail) {
            layout.avatar_thumbnail = usePage().props.auth.user.avatar_thumbnail
        }

        // Set logo app
        if (usePage().props.app) {
            layout.app = usePage().props.app
        }

        layout.currentRouteParameters = route().params
        layout.currentRoute = route().current()

        layout.systemName = 'org'
        let moduleName = (layout.currentRoute || '').split(".")
        layout.currentModule = moduleName.length > 1 ? moduleName[1] : ''

        layout.booted = true
    })

    return layout
}
