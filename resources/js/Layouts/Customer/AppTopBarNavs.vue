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
    faRectangleWide

} from "@/../private/pro-light-svg-icons"
import { useLayoutStore } from "@/Stores/layout"


library.add(
    faTerminal, faUserAlien, faCog, faGlobe, faWindowMaximize, faBriefcase, faPhotoVideo,
    faBrowser,faRectangleWide
)

const layout = useLayoutStore()

</script>

<template>
    <div class="flex">

        <!-- <div class="fixed top-10 bg-blue-300"><pre>{{ layout.navigation?.[layout.currentModule]?.topMenu.subSections }}</pre></div> -->
        <Link
            v-for="menu in layout.navigation?.[layout.currentModule]?.topMenu.subSections" :href="route(menu.route.name)"
            :id="get(menu,'label',menu.route.name)"
            class="group relative text-gray-700 dark:text-gray-400 group text-sm flex justify-end items-center cursor-pointer py-1 gap-x-2 px-4 md:px-4 lg:px-4"
            :title="capitalize(menu.tooltip??menu.label??'')">
            <div class="absolute h-0.5 rounded-full -bottom-2 xl:-bottom-1.5 left-[50%] translate-x-[-50%] mx-auto transition-all duration-200 ease-in-out"
                 :class="[route(layout.currentRoute, route().v().params).includes(route(menu.route.name)) ? 'bg-orange-500 dark:bg-gray-300 w-5/6' : 'bg-gray-400 w-0 group-hover:w-3/6']"
            />
            <FontAwesomeIcon :icon="menu.icon"
                             class="h-5 lg:h-3.5 w-auto group-hover:opacity-100 opacity-70 transition duration-100 ease-in-out"
                             aria-hidden="true"/>
            <span v-if="menu.label" class="hidden lg:inline capitalize whitespace-nowrap">{{ trans(menu.label) }}</span>
        </Link>

    </div>
</template>

