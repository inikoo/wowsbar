<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 03 Jul 2023 16:55:06 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {get} from "lodash"
import {capitalize} from "@/Composables/capitalize"
import {trans} from "laravel-vue-i18n"
import {Link} from "@inertiajs/vue3"
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome"
import {library} from "@fortawesome/fontawesome-svg-core"
import {
    faTerminal, faUserAlien, faCog, faGlobe, faWindowMaximize, faBriefcase, faPhotoVideo, faBrowser,
    faFolderTree, faCube, faTransporter, faEnvelope, faCoins, faFileInvoice, faMoneyCheckAlt, faChartNetwork,
    faInboxOut,faThumbsUp

} from '@fal'
import {useLayoutStore} from "@/Stores/layout"

library.add(
    faTerminal, faUserAlien, faCog, faGlobe, faWindowMaximize, faBriefcase, faPhotoVideo,
    faBrowser, faFolderTree, faCube, faTransporter, faEnvelope, faCoins, faFileInvoice, faMoneyCheckAlt,
    faChartNetwork, faInboxOut,faThumbsUp
)

const layout = useLayoutStore()

</script>

<template>
    <div class="flex h-full">
        <Link
            v-for="menu in layout.navigation?.[layout.currentModule]?.topMenu.subSections"
            :href="route(menu.route.name,menu.route.parameters)"
            :id="get(menu,'label',menu.route.name)"
            class="group relative text-gray-700 group text-sm flex justify-end items-center cursor-pointer py-3 gap-x-2 px-4 md:px-4 lg:px-4"
            :title="capitalize(menu.tooltip??menu.label??'')">

            <div :class="[
                route(layout.currentRoute, route().v().params).includes(route(menu.route.name,menu.route.parameters))
                ? 'bottomNavigationActiveOrg'
                : 'bottomNavigationOrg'
            ]"/>

            <FontAwesomeIcon :icon="menu.icon"
                class="h-5 lg:h-3.5 w-auto group-hover:opacity-100 opacity-70 transition duration-100 ease-in-out"
                aria-hidden="true"/>
            <span v-if="menu.label" class="hidden lg:inline capitalize whitespace-nowrap">{{ trans(menu.label) }}</span>
        </Link>
    </div>
</template>

