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
import { orgActiveUsers } from '@/Stores/active-users'
import { useEchoOrgPersonal } from '@/Stores/echo-org-personal.js'
import { useEchoOrgGeneral } from '@/Stores/echo-org-general.js'


export const initialiseOrgApp = () => {
    const layout = useLayoutStore()
    const locale = useLocaleStore()
    const echoPersonal = useEchoOrgPersonal()
    const echoGeneral = useEchoOrgGeneral()

    // If user change tab then broadcast to others
    router.on('navigate', (event) => {
        axios.post(
            route('org.models.live-users.update'),
            {
                'active_page': event.detail.page.props.title
            }
        )
        .catch(error => {
            console.error('Error broadcasting.');
        });

    })

    orgActiveUsers().subscribe();



    echoGeneral.subscribe()
    if (usePage().props.auth.user) {
        echoPersonal.subscribe(usePage().props.auth.user.id)
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

        if (usePage().props.organisation) {
            layout.organisation = usePage().props.organisation ?? null
        }

        // Set data of User
        if (usePage().props.user) {
            layout.user = usePage().props.user ?? null
        }

        // Set avatar thumbnail
        if (usePage().props.auth.user.avatar_thumbnail) {
            layout.avatar_thumbnail = usePage().props.auth.user.avatar_thumbnail
        }

        // Set logo app
        if (usePage().props.app) {
            layout.app = usePage().props.app
        }

        layout.systemName = 'org'

        layout.currentRouteParameters = route().params
        layout.currentRoute = route().current()

        let moduleName = layout.currentRoute.split(".")
        layout.currentModule = moduleName[1]


        layout.booted = true


    })
    return layout
}
