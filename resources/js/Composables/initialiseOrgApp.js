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
    liveOrganisationUsers().subscribe();

    echoGeneral.subscribe()

    if (usePage().props.auth.user) {
        echoPersonal.subscribe(usePage().props.auth.user.id)

        router.on('navigate', (event) => {
            // console.log(usePage().props.auth.user?.id)
            if(usePage().props.auth.user?.id) {
                // console.log("===== ada auth id =====")
                axios.post(
                    route('org.models.live-organisation-users-current-page.store',
                        usePage().props.auth.user?.id  ),
                    {
                        'label': event.detail.page.props.title
                    }
                )
                .then((response) => {
                    // console.log("Broadcast sukses", response)
                })
                .catch(error => {
                    console.error('Error broadcasting.'+error);
                });
            }
        })
    }


    watchEffect(() => {
        // Set data of Navigation
        if (usePage().props.layout) {
            layout.navigation = usePage().props.layout.navigation ?? null
            layout.secondaryNavigation = usePage().props.layout.secondaryNavigation ?? null
        }

        // Set data of Locale (Language)
        if (usePage().props.localeData) {
            loadLanguageAsync(usePage().props.localeData.language.code)
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
