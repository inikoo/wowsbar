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
import {faBriefcase, faEnvelope, faPuzzlePiece} from "@/../private/pro-light-svg-icons"
import {faChevronLeft} from "@/../private/pro-regular-svg-icons"
import {useLayoutStore} from "@/Stores/layout.js"
import {computed} from "vue"

library.add(faBriefcase, faPuzzlePiece, faChevronLeft, faEnvelope)

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
    <div class="mt-11 fixed md:flex md:flex-col md:inset-y-0 lg:mt-10 bg-gray-50 dark:bg-gray-800 dark:text-gray-100 h-full text-gray-400"
         :class="[layout.leftSidebar.show ? 'w-8/12 md:w-56' : 'w-8/12 md:w-10']"
         @mouseenter="isHover = true" @mouseleave="isHover = false"
    >
        <!-- Toggle: collapse-expand LeftSideBar -->
        <div @click="handleToggleLeftbar"
             class="hidden absolute z-10 right-0 top-2/4 -translate-y-full translate-x-1/2 w-7 aspect-square bg-gray-200 hover:bg-gray-300 border border-gray-300 rounded-full md:flex md:justify-center md:items-center cursor-pointer"
             :title="[layout.leftSidebar.show ? 'Collapse the bar' : 'Expand the bar']"
        >
            <div class="flex items-center justify-center transition-all duration-200 ease-in-out" :class="{'rotate-180': !layout.leftSidebar.show}">
                <FontAwesomeIcon icon='far fa-chevron-left' class='-translate-x-[1px] h-[14px]' aria-hidden='true'/>
            </div>
        </div>

        <div class="isolate relative flex flex-grow flex-col h-full overflow-y-auto custom-hide-scrollbar border-r border-gray-200 dark:border-gray-500 pb-4">
            <div class="flex flex-grow flex-col pb-16">
                <nav class="flex-1 space-y-1" aria-label="Sidebar">
                    <!-- LeftSide Links -->
                    <Link v-for="(item, itemKey) in layout.navigation"
                          :key="itemKey"
                          :href="route(item.route.name,item.route.parameters)"
                          :class="[
							itemKey === layout.currentModule
								? 'tabNavigationActive dark:border-gray-100 dark:bg-gray-600 px-0.5'
								: 'tabNavigation dark:hover:bg-dark-700 px-1',
							layout.leftSidebar.show ? 'px-3' : '',
							'group flex items-center text-sm font-medium py-2',
						]"
                          :aria-current="itemKey === layout.currentModule ? 'page' : undefined"
                    >
                        <div class="flex items-center">
                            <img v-if="item.name == 'dashboard'" src="@/../images/logo-charcoal-transparent.png" alt="" class="h-4 aspect-square"
                                 :class="[ itemKey === layout.currentModule
											? 'text-orange-500'
											: 'text-gray-400 group-hover:text-gray-600',
										'ml-2 mr-3 flex-shrink-0 h-4 w-4'
							]">
                            <FontAwesomeIcon
                                v-else
                                aria-hidden="true"
                                class="text-gray-400 dark:text-gray-200 ml-2 mr-3 flex-shrink-0 h-4 w-4"
                                :icon="item.icon"/>
                        </div>
                        <span class="capitalize text-gray-600" :class="[layout.leftSidebar.show ? 'block md:block' : 'block md:hidden']">{{ trans(item.label) }}</span>
                    </Link>
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
