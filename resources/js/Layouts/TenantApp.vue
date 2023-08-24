<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Thu, 11 Aug 2022 11:08:49 Malaysia Time, Kuala Lumpur, Malaysia
  -  Reformatted: Fri, 03 Mar 2023 12:40:58 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->


<script setup lang="ts">
import { ref } from "vue"
import AppFooter from "@/Layouts/Footer/AppFooter.vue"
import { usePage, router } from "@inertiajs/vue3"

import AppLeftSideBar from "@/Layouts/AppLeftSideBar.vue"
import AppRightSideBar from "@/Layouts/AppRightSideBar.vue"
import AppTopBar from "@/Layouts/TopBar/AppTopBar.vue"
import Breadcrumbs from "@/Components/Navigation/Breadcrumbs.vue"

import { library } from "@fortawesome/fontawesome-svg-core"
import { useFirebaseStore } from "@/Stores/firebase"

import { initialiseApp, authFirebase } from "@/Composables/initialiseApp"

import {
    faHome,
    faConveyorBeltAlt,
    faUserHardHat,
    faBars,
    faUsersCog,
    faTachometerAltFast,
    faInventory,
    faStoreAlt,
    faUser,
    faIndustry,
    faParachuteBox,
    faDollyEmpty,
    faShoppingCart,
    faAbacus,
    faChevronDown,
    faGlobe,
    faLanguage
} from "@/../private/pro-light-svg-icons"
import { faSearch, faBell} from "@/../private/pro-regular-svg-icons"


library.add(
    faHome,
    faConveyorBeltAlt,
    faUserHardHat,
    faBars,
    faUsersCog,
    faTachometerAltFast,
    faInventory,
    faStoreAlt,
    faUser,
    faUser,
    faIndustry,
    faParachuteBox,
    faDollyEmpty,
    faShoppingCart,
    faAbacus,
    faChevronDown,
    faGlobe,
    faLanguage,
    faSearch,
    faBell
);



const layout = initialiseApp()


if (usePage().props.firebaseAuthToken) {
    authFirebase(usePage().props.firebaseAuthToken)
}


const sidebarOpen = ref(false)

</script>

<template>
    <div class="fixed top-0 left-0 w-screen h-screen dark:bg-gray-700 bg-gray-50" />
    <div class="relative min-h-full transition-all duration-200 ease-in-out"
        :class="[Object.values(layout.rightSidebar).some(value => value) ? 'mr-44' : 'mr-0']"
    >
        <!-- TopBar -->
        <AppTopBar @sidebarOpen="(value: boolean) => sidebarOpen = value" :sidebarOpen="sidebarOpen" :logoRoute="`dashboard.show`" urlPrefix="">
            <img v-if="layout.tenant.logo_id" class="h-7 md:h-5 shadow" :src="`/media/${layout.tenant.logo_id}`" :alt="layout.tenant.code" />
            <span class="hidden leading-none md:inline font-bold  xl:truncate text-gray-800 dark:text-gray-300">
                {{ layout.tenant.name}}
            </span>
        </AppTopBar>

        <!-- Breedcrumbs -->
        <Breadcrumbs class="fixed md:left-10 xl:left-56 top-11 lg:top-10 z-[19] w-full"
            :breadcrumbs="usePage().props.breadcrumbs??[]"
            :navigation="usePage().props.navigation??[]"
        />

        <!-- Sidebar: Left -->
        <div>
            <div class="bg-gray-200/80 fixed top-0 w-screen h-screen z-10 md:hidden" v-if="sidebarOpen" @click="sidebarOpen = !sidebarOpen" />
            <AppLeftSideBar class="-left-2/3 transition-all duration-100 ease-in-out z-20 block md:left-[0]" :class="{'left-[0]': sidebarOpen }" @click="sidebarOpen = !sidebarOpen" />
        </div>

        <!-- Main Content -->
        <main class="relative flex flex-col pt-20 pb-5 ml-0 md:pt-16 md:ml-10 xl:ml-56 bg-gray-50 text-gray-700 dark:bg-gray-700 dark:text-gray-400">
            <slot />
        </main>

        <!-- Sidebar: Right -->
        <AppRightSideBar class="fixed top-16 w-44 transition-all duration-200 ease-in-out"
            :class="[Object.values(layout.rightSidebar).some(value => value === true) ? 'right-0' : '-right-44']"
        />

    </div>

    <!-- Footer -->
    <AppFooter appScope="tenants" />

</template>

<style lang="scss">
.tabNavigationActive {
    // Indicate current active state to have consistent style. Use for: AppLeftSideBar, CreateModel
    @apply bg-gray-200/80 border-l-4 border-orange-500 text-gray-700 dark:text-gray-300 transition-all duration-100 ease-in-out
}

.tabNavigation {
    @apply hover:bg-gray-200/30 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-400 transition-all duration-100 ease-in-out
}
</style>
