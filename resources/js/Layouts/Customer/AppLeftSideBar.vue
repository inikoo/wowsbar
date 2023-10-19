<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 19 Sep 2023 14:02:24 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {trans} from 'laravel-vue-i18n'
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome"
import {Link} from "@inertiajs/vue3"
import {ref, onMounted, onUnmounted} from 'vue'
import {router} from '@inertiajs/vue3'

import {library} from "@fortawesome/fontawesome-svg-core"
import {
    faBrowser, faSign, faUserCog, faTachometerAlt,
    faTransporter, faAd, faThumbsUp, faEnvelope, faCreditCard
} from '@fal/'
import {faGoogle} from '@fortawesome/free-brands-svg-icons'

import {faChevronLeft} from '@far/'
import {useLayoutStore} from "@/Stores/layout.js"
import {computed} from "vue"

library.add(faBrowser, faSign, faUserCog, faChevronLeft, faTachometerAlt,
    faGoogle, faTransporter, faAd, faThumbsUp, faEnvelope, faCreditCard
)

const layout = useLayoutStore()
const isHover = ref(false)

const currentIndexModule = computed(() => {
    return Object.keys(layout.navigation).indexOf(layout.currentModule)
})


onMounted(() => {
    window.addEventListener('keydown', handleKey)
    if (localStorage.getItem('leftSideBar')) {
        // Read from local storage then store to Pinia
        layout.leftSidebar.show = JSON.parse(localStorage.getItem('leftSideBar'))
    }
})

onUnmounted(() => {
    window.removeEventListener('keydown', handleKey)
})

const handleKey = (event: any) => {
    // If Arrow Up key is pressed and the element is hovered and not the first index
    if (event.key === 'ArrowUp' && isHover.value && currentIndexModule.value != 0) {
        const prevTab = ref(layout.navigation[Object.keys(layout.navigation)[currentIndexModule.value - 1]])
        router.get(route(prevTab.value.route, prevTab.value.routeParameters))
    }
    // If Arrow Down key is pressed and the element is hovered and not the last index
    else if (event.key === 'ArrowDown' && isHover.value && currentIndexModule.value != Object.keys(layout.navigation).length - 1) {
        const nextTab = ref(layout.navigation[Object.keys(layout.navigation)[currentIndexModule.value + 1]])
        router.get(route(nextTab.value.route, nextTab.value.routeParameters))
    }
}

// Set LeftSidebar value to local storage
const handleToggleLeftbar = () => {
    localStorage.setItem('leftSideBar', (!layout.leftSidebar.show).toString())
    layout.leftSidebar.show = !layout.leftSidebar.show
}
</script>

<template>
    <div class="mt-11 fixed md:flex md:flex-col md:inset-y-0 lg:mt-10 bg-slate-600 dark:bg-gray-800 dark:text-gray-100 h-full text-white"
        :class="[layout.leftSidebar.show ? 'w-8/12 md:w-56' : 'w-8/12 md:w-10']"
        @mouseenter="isHover = true" @mouseleave="isHover = false"
    >
        <!-- Toggle: collapse-expand LeftSideBar -->
        <div @click="handleToggleLeftbar"
            class="hidden absolute z-10 right-0 top-2/4 -translate-y-full translate-x-1/2 w-7 aspect-square bg-slate-500 hover:bg-slate-400 border-2 border-gray-600 text-white rounded-full md:flex md:justify-center md:items-center cursor-pointer"
            :title="[layout.leftSidebar.show ? 'Collapse the bar' : 'Expand the bar']"
        >
            <div class="flex items-center justify-center" :class="{'rotate-180': !layout.leftSidebar.show}">
                <FontAwesomeIcon icon='far fa-chevron-left' class='-translate-x-[1px] h-[14px]' aria-hidden='true'/>
            </div>
        </div>

        <div class="isolate relative flex flex-grow flex-col h-full overflow-y-auto custom-hide-scrollbar dark:border-gray-500 pb-4">
            <div class="flex flex-grow flex-col pb-16">
                <nav class="flex-1 space-y-1" aria-label="Sidebar">
                    <!-- LeftSide Links -->
                    <div v-for="(item, itemKey) in layout.navigation"
                        :key="itemKey"
                    >
                        <!-- Navigation -->

                        <Link v-if="item.route"  :href="route(item.route)"
                            class="flex items-center group text-sm font-medium py-2"
                            :class="[
                                itemKey === layout.currentModule || Object.keys(item.subNav ?? {}).some(subNav => subNav === layout.currentModule)
                                    ? 'navigationActiveCustomer dark:border-gray-100 dark:bg-gray-600 px-0.5'
                                    : 'navigationCustomer dark:hover:bg-dark-700 px-1',
                                !layout.leftSidebar.show && Object.keys(item.subNav ?? {}).some(subNav => subNav === layout.currentModule) ? 'text-white border-l-1 border-transparent' : '',
                            ]"
                            :aria-current="itemKey === layout.currentModule ? 'page' : undefined"
                        >
                            <FontAwesomeIcon
                                aria-hidden="true"
                                class="dark:text-gray-200 ml-2 mr-3 flex-shrink-0 h-4 w-4"
                                :icon="item.icon" fixed-width />
                            <span class="capitalize leading-none whitespace-nowrap" :class="[layout.leftSidebar.show ? 'block md:block' : 'block md:hidden']">{{ trans(item.label) }}</span>
                            <!-- <span >{{ itemKey }} -- {{ item.scope }} -- {{ Object.values(layout.navigation)[0].scope }}</span> -->
                        </Link>

                        <!-- Sub Navigation -->
                        <template v-if="item.subNav">
                            <div class="flex flex-col"
                                :class="[layout.leftSidebar.show ? 'pl-2' : 'pl-0']"
                            >
                                <Link :href="route(subNav.route)" v-for="(subNav, subNavKey) in item.subNav"
                                    class="group flex items-center text-sm font-medium py-2"
                                    :class="[
                                        subNavKey === layout.currentModule
                                            ? 'navigationActiveCustomer border-l-[3px] dark:border-gray-100 dark:bg-gray-600 px-0.5'
                                            : 'navigationCustomer dark:hover:bg-dark-700 px-1',
                                        layout.leftSidebar.show ? 'px-3' : '',
                                    ]"
                                    :aria-current="subNavKey === layout.currentModule ? 'page' : undefined"
                                >
                                    <FontAwesomeIcon
                                    aria-hidden="true"
                                    class="dark:text-gray-200 ml-2 mr-3 flex-shrink-0 h-4 w-4"
                                    :icon="subNav.icon"/>
                                    <span class="whitespace-nowrap">{{ subNav.label }}</span>
                                </Link>
                            </div>
                        </template>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</template>

<style>
/* Hide scrollbar for Chrome, Safari and Opera */
.custom-hide-scrollbar::-webkit-scrollbar {
    display: none;
}

/* Hide scrollbar for IE, Edge and Firefox */
.custom-hide-scrollbar {
    -ms-overflow-style: none; /* IE and Edge */
    scrollbar-width: none; /* Firefox */
}</style>
