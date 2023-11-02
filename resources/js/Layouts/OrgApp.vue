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
import OrgAppTopBar from "@/Layouts/Organisation/OrgAppTopBar.vue"
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
    faLanguage, faUsers, faNetworkWired, faCalendar, faStopwatch, faBuilding, faHandshake
} from '@fal/'
import { faSearch, faBell } from "@far/"


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
    faBuilding,
    faHandshake
)

const sidebarOpen = ref(false)
const layout = initialiseOrgApp()

if (usePage().props.firebaseAuthToken) {
    useAuthFirebase(usePage().props.firebaseAuthToken)
}

const layoutState = useLayoutStore()

</script>

<template>
    <div class="fixed top-0 left-0 w-screen h-screen bg-gray-50"></div>
    <div class="relative transition-all duration-200 ease-in-out text-gray-700"
        :class="[Object.values(layout.rightSidebar).some(value => value.show) ? 'mr-44' : 'mr-0']">

        <!-- Section: TopBar -->
        <OrgAppTopBar @sidebarOpen="(value: boolean) => sidebarOpen = value" :sidebarOpen="sidebarOpen"
            :logoRoute="`org.dashboard.show`" urlPrefix="org.">
        </OrgAppTopBar>

        <!-- Section: Breadcrumbs -->
        <Breadcrumbs class="fixed top-11 lg:top-10 z-[19] w-full transition-all duration-200 ease-in-out"
            :class="[layoutState.leftSidebar.show ? 'left-0 md:left-48' : 'left-0 md:left-10']"
            :breadcrumbs="usePage().props.breadcrumbs ?? []" :navigation="usePage().props.navigation ?? []" />

        <!-- Sidebar: Left -->
        <div>
            <div class="bg-gray-200/80 fixed top-0 w-screen h-screen z-10 md:hidden" v-if="sidebarOpen"
                @click="sidebarOpen = !sidebarOpen" />
            <!-- Mobile Helper: background to close hamburger -->
            <AppLeftSideBar class="-left-2/3 transition-all duration-300 ease-in-out z-20 block md:left-[0]"
                :class="{ 'left-[0]': sidebarOpen }" @click="sidebarOpen = !sidebarOpen" />
        </div>

        <!-- Main Content -->
        <main
            class="h-full relative flex flex-col pt-20 md:pt-16 pb-8 bg-gray-50 text-gray-700 transition-all duration-200 ease-in-out"
            :class="[layoutState.leftSidebar.show ? 'ml-0 md:ml-48' : 'ml-0 md:ml-10']">
            <slot />
        </main>

        <!-- Sidebar: Right -->
        <AppRightSideBar class="fixed top-[76px] md:top-[68px] lg:top-16 w-44 transition-all duration-200 ease-in-out"
            :class="[Object.values(layout.rightSidebar).some(value => value.show === true) ? 'right-0' : '-right-44']" />

    </div>

    <Footer />
    <notifications dangerously-set-inner-html :max="3" :width="500" />
</template>
