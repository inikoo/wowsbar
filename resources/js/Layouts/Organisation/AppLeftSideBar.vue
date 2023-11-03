<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 03 Mar 2023 13:49:56 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {trans} from 'laravel-vue-i18n'
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome"
import {Link} from "@inertiajs/vue3"
import {ref, onMounted, onUnmounted} from 'vue'
import {router} from '@inertiajs/vue3'

import {library} from "@fortawesome/fontawesome-svg-core"
import {faTasksAlt, faShapes, faBriefcase, faEnvelope, faPuzzlePiece, faThumbsUp, faAd, faAlbumCollection} from '@fal/'
import {faGoogle} from "@fortawesome/free-brands-svg-icons"
import {faChevronLeft} from '@far/'
import {useLayoutStore} from "@/Stores/layout.js"
import {computed} from "vue"

library.add(faTasksAlt, faShapes, faBriefcase, faPuzzlePiece, faChevronLeft, faEnvelope, faThumbsUp, faAd, faGoogle, faAlbumCollection)

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
    <div class="mt-11 fixed md:flex md:flex-col md:inset-y-0 lg:mt-10 bg-gradient-to-t from-org-700 to-org-600 h-full text-gray-400"
        :class="[layout.leftSidebar.show ? 'w-8/12 md:w-48' : 'w-8/12 md:w-10']"
        @mouseenter="isHover = true" @mouseleave="isHover = false"
    >
        <!-- Toggle: collapse-expand LeftSideBar -->
        <div @click="handleToggleLeftbar"
            class="hidden absolute z-10 right-0 top-2/4 -translate-y-full translate-x-1/2 w-5 aspect-square bg-org-500 hover:bg-org-600 text-org-100 border border-gray-300 rounded-full md:flex md:justify-center md:items-center cursor-pointer"
            :title="layout.leftSidebar.show ? 'Collapse the bar' : 'Expand the bar'"
        >
            <div class="flex items-center justify-center transition-all duration-300 ease-in-out" :class="{'rotate-180': !layout.leftSidebar.show}">
                <FontAwesomeIcon icon='far fa-chevron-left' class='h-[10px] leading-none' aria-hidden='true'
                    :class="layout.leftSidebar.show ? '-translate-x-[1px]' : ''"
                />
            </div>
        </div>

        <nav class="isolate relative flex flex-grow flex-col pb-4 h-full overflow-y-auto custom-hide-scrollbar flex-1 space-y-1" aria-label="Sidebar">
            <!-- LeftSide Links -->
            <Link v-for="(item, itemKey) in layout.navigation"
                :key="itemKey"
                :href="route(item.route.name,item.route.parameters)"
                class="group flex items-center text-sm font-medium py-2"
                :class="[
                    itemKey === layout.currentModule
                        ? 'navigationActiveOrg px-0.5'
                        : 'navigationOrg px-1',
                    layout.leftSidebar.show ? 'px-3' : '',
                ]"
                :aria-current="itemKey === layout.currentModule ? 'page' : undefined"
            >
                <div class="flex items-center px-2">
                    <FontAwesomeIcon
                        aria-hidden="true"
                        class="flex-shrink-0 h-4 w-4"
                        :icon="item.icon"/>
                </div>
                <Transition>
                    <span class="capitalize leading-none whitespace-nowrap" :class="[layout.leftSidebar.show ? 'block md:block' : 'block md:hidden']">
                        {{ trans(item.label) }}
                    </span>
                </Transition>
            </Link>
        </nav>
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
