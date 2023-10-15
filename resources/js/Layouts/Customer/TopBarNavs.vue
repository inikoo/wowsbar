<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 03 Jul 2023 16:55:06 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {get} from "lodash";
import {capitalize} from "@/Composables/capitalize";
import { trans } from 'laravel-vue-i18n'
import { Link } from "@inertiajs/vue3"
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { library } from "@fortawesome/fontawesome-svg-core"
import {
    faTerminal, faUserAlien, faCog, faGlobe, faWindowMaximize, faBriefcase, faPhotoVideo, faBrowser,
    faSign,faChartNetwork,faThumbsUp

} from "@/../private/pro-light-svg-icons"
import { useLayoutStore } from "@/Stores/layout"


library.add(
    faTerminal, faUserAlien, faCog, faGlobe, faWindowMaximize, faBriefcase, faPhotoVideo,
    faBrowser,faSign,faChartNetwork,faThumbsUp
)

const layout = useLayoutStore()

</script>

<template>
    <div class="flex text-gray-400">
<!-- aa{{ layout.navigation?.[layout.currentModule]?.subNav }}ddddddd -->
        <template v-if="layout.navigation?.[layout.currentModule]?.subNav">
            <Link
                v-for="menu in layout.navigation?.[layout.currentModule]?.subNav?.topMenu.subSections" :href="route(menu.route.name)"
                :id="get(menu,'label',menu.route.name)"
                class="group relative text-gray-600 dark:text-gray-400 group text-sm flex justify-end items-center cursor-pointer py-3 gap-x-2 px-4 md:px-4 lg:px-4"
                :title="capitalize(menu.tooltip??menu.label??'')"
            >
                <FontAwesomeIcon :icon="menu.icon"
                    class="h-5 lg:h-3.5 w-auto group-hover:opacity-100 opacity-70 transition duration-100 ease-in-out"
                    aria-hidden="true"/>
                <span v-if="menu.label" class="hidden lg:inline capitalize whitespace-nowrap">{{ trans(menu.label) }}</span>

                <!-- The line appear on hover and active state -->
                <div :class="[route(layout.currentRoute, route().v().params).includes(route(menu.route.name)) ? 'bottomNavigationActiveCustomer' : 'bottomNavigationCustomer']" />
            </Link>
        </template>

        <template v-else>
            <Link
                v-for="menu in layout.navigation?.[layout.currentModule]?.topMenu.subSections" :href="route(menu.route.name)"
                :id="get(menu,'label',menu.route.name)"
                class="group relative text-gray-600 dark:text-gray-400 group text-sm flex justify-end items-center cursor-pointer py-3 gap-x-2 px-4 md:px-4 lg:px-4"
                :title="capitalize(menu.tooltip??menu.label??'')"
            >
                <FontAwesomeIcon :icon="menu.icon"
                    class="h-5 lg:h-3.5 w-auto group-hover:opacity-100 opacity-70 transition duration-100 ease-in-out"
                    aria-hidden="true"/>
                <span v-if="menu.label" class="hidden lg:inline capitalize whitespace-nowrap">{{ trans(menu.label) }}</span>
                <!-- The line appear on hover and active state -->
                <div :class="[route(layout.currentRoute, route().v().params).includes(route(menu.route.name)) ? 'bottomNavigationActiveCustomer' : 'bottomNavigationCustomer']" />
            </Link>
        </template>

    </div>
</template>

