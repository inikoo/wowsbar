<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 14 Aug 2023 08:45:47 Malaysia Time, Sanur, Bali
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->


<script setup lang="ts">
import { ref } from "vue"
import { usePage } from "@inertiajs/vue3"

import Footer from "@/Layouts/Footer/Organisation/Footer.vue"

import AppLeftSideBar from "@/Layouts/Organisation/AppLeftSideBar.vue"
import AppRightSideBar from "@/Layouts/Customer/AppRightSideBar.vue"
import AppTopBar from "@/Layouts/Organisation/AppTopBar.vue"
import Breadcrumbs from "@/Components/Navigation/Breadcrumbs.vue"

import { library } from "@fortawesome/fontawesome-svg-core"
import { initialiseOrgApp } from "@/Composables/initialiseOrgApp"
import { useLayoutStore } from "@/Stores/layout"
import { useAuthFirebase } from "@/Composables/firebaseAuth"

import {
    faHome,
    faConveyorBeltAlt,
    faUserHardHat,
    faBars,
    faUsersCog,
    faTachometerAltFast,
    faStoreAlt,
    faUser,
    faAbacus,
    faChevronDown,
    faGlobe,
    faLanguage, faUsers,faNetworkWired,faCalendar,faStopwatch,faBuilding
} from "@/../private/pro-light-svg-icons"
import { faSearch, faBell} from "../../private/pro-regular-svg-icons"


library.add(
    faHome,
    faConveyorBeltAlt,
    faUserHardHat,
    faBars,
    faUsersCog,
    faTachometerAltFast,
    faStoreAlt,
    faUser,
    faUser,
    faAbacus,
    faChevronDown,
    faGlobe,
    faLanguage,
    faSearch,
    faBell,
    faUsers,
    faNetworkWired,
    faCalendar,
    faStopwatch,
    faBuilding
)

const sidebarOpen = ref(false)
const layout = initialiseOrgApp()

if (usePage().props.firebaseAuthToken) {
    useAuthFirebase(usePage().props.firebaseAuthToken)
}

const layoutState = useLayoutStore()

</script>

<template>
    <div class="fixed top-0 left-0 w-screen h-screen dark:bg-gray-700 bg-gray-50" />
    <div class="relative h-screen transition-all duration-200 ease-in-out text-gray-700"
        :class="[Object.values(layout.rightSidebar).some(value => value.show) ? 'mr-44' : 'mr-0']"
    >
        <!-- TopBar -->
        <AppTopBar @sidebarOpen="(value: boolean) => sidebarOpen = value" :sidebarOpen="sidebarOpen" :logoRoute="`org.dashboard.show`" urlPrefix="org.">
            <img v-if="layout.organisation.logo_id" class="h-7 md:h-5 shadow" :src="`/media/${layout.organisation.logo_id}`" :alt="layout.organisation.code" />
            <span class="hidden leading-none md:inline font-bold  xl:truncate text-gray-800 dark:text-gray-300">
                {{ layout.organisation.name}}
            </span>
        </AppTopBar>

        <!-- Breadcrumbs -->
        <Breadcrumbs class="fixed top-11 lg:top-10 z-[19] w-full transition-all duration-200 ease-in-out"
            :class="[layoutState.leftSidebar.show ? 'left-0 md:left-56' : 'left-0 md:left-10']"
            :breadcrumbs="usePage().props.breadcrumbs??[]"
            :navigation="usePage().props.navigation??[]"
        />

        <!-- Sidebar: Left -->
        <div>
            <div class="bg-gray-200/80 fixed top-0 w-screen h-screen z-10 md:hidden" v-if="sidebarOpen" @click="sidebarOpen = !sidebarOpen" />
            <!-- Mobile Helper: background to close hamburger -->
            <AppLeftSideBar class="-left-2/3 transition-all duration-100 ease-in-out z-20 block md:left-[0]" :class="{'left-[0]': sidebarOpen }" @click="sidebarOpen = !sidebarOpen" />
        </div>

        <!-- Main Content -->
        <main class="h-full relative flex flex-col pt-16 pb-5 bg-gray-50 text-gray-700 dark:bg-gray-700 dark:text-gray-400 transition-all duration-200 ease-in-out"
            :class="[layoutState.leftSidebar.show ? 'ml-56' : 'ml-10']"
        >
            <slot />
        </main>

        <!-- Sidebar: Right -->
        <AppRightSideBar class="fixed top-[76px] md:top-[68px] lg:top-16 w-44 transition-all duration-200 ease-in-out"
            :class="[Object.values(layout.rightSidebar).some(value => value === true) ? 'right-0' : '-right-44']"
        />

    </div>

    <Footer />

</template>
