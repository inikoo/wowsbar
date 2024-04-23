<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 14 Aug 2023 08:45:47 Malaysia Time, Sanur, Bali
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->


<script setup lang="ts">
import { ref, provide } from "vue"
import { usePage } from "@inertiajs/vue3"
import Footer from "@/Layouts/Footer/Organisation/Footer.vue"
import AppLeftSideBar from "@/Layouts/Organisation/AppLeftSideBar.vue"
import AppRightSideBar from "@/Layouts/Customer/AppRightSideBar.vue"
import OrgAppTopBar from "@/Layouts/Organisation/OrgAppTopBar.vue"
import Breadcrumbs from "@/Components/Navigation/Breadcrumbs.vue"
import { library } from "@fortawesome/fontawesome-svg-core"
import { initialiseOrgApp } from "@/Composables/initialiseOrgApp"
import { useLayoutStore } from "@/Stores/layout"
import Notification from '@/Components/Utils/Notification.vue'

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
} from '@fal'
import { faSearch, faBell } from "@far"


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


const layoutState = useLayoutStore()
provide('layout', useLayoutStore())

</script>

<template>
    <div class="fixed top-0 left-0 w-screen h-screen bg-gray-50"></div>
    <div class="relative transition-all text-gray-700"
        :class="[Object.values(layout.rightSidebar).some(value => value.show) ? 'mr-44' : 'mr-0']">

        <!-- Section: TopBar -->
        <OrgAppTopBar @sidebarOpen="(value: boolean) => sidebarOpen = value" :sidebarOpen="sidebarOpen"
            :logoRoute="`org.dashboard.show`" urlPrefix="org.">
        </OrgAppTopBar>

        <!-- Section: Breadcrumbs -->
        <Breadcrumbs class="fixed top-11 lg:top-10 z-[19] w-full transition-all"
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
            class="h-full relative flex flex-col pt-20 md:pt-16 pb-6 bg-gray-50 text-gray-700 transition-all"
            :class="[layoutState.leftSidebar.show ? 'ml-0 md:ml-48' : 'ml-0 md:ml-10']">
            <slot />
        </main>

        <!-- Sidebar: Right -->
        <AppRightSideBar class="fixed top-[76px] md:top-[68px] lg:top-16 w-44 transition-all"
            :class="[Object.values(layout.rightSidebar).some(value => value.show === true) ? 'right-0' : '-right-44']" />

    </div>

    <Footer />
    <notifications
        dangerously-set-inner-html
        :max="3"
        width="500"
        classes="custom-style-notification"
        :pauseOnHover="true"    
    >
        <template #body="props">
            <Notification :notification="props" />  
        </template>
    </notifications>
</template>

<style lang="scss">

/* Navigation */
.navigationActiveOrg {
    @apply bg-amber-200/20 sm:border-l-4 sm:border-amber-300 text-white transition-all;
    background-color: v-bind('layout?.app?.theme[2]');
    color: v-bind('layout?.app?.theme[3]')
}
.navigationOrg {
    @apply hover:bg-gray-300/30 text-fuchsia-200/60 transition-all;
    color: v-bind('layout?.app?.theme[1]')
}

// Navigation Second
.navigationSecondActiveOrg {
    @apply bg-gray-200 sm:border-l-4 sm:border-org-500 text-org-600 transition-all
}
.navigationSecondOrg {
    @apply hover:bg-gray-100 text-gray-400 hover:text-gray-500 transition-all
}

// Bottom Navigation
.bottomNavigationActiveOrg {
    @apply w-5/6 absolute h-0.5 rounded-full bottom-0 left-[50%] translate-x-[-50%] mx-auto transition-all;
    background-color: v-bind('layout.app.theme[4]');
    color: v-bind('layout.app.theme[5]')
}
.bottomNavigationOrg {
    @apply bg-gray-400 w-0 group-hover:w-3/6 absolute h-0.5 rounded-full bottom-0 left-[50%] translate-x-[-50%] mx-auto transition-all
}

.specialUnderlineOrg {
    background: v-bind('`linear-gradient(to top, ${layout.app.theme[6]}, ${layout.app.theme[6] + "77"})`');
    &:hover, &:focus {
        color: v-bind('`${layout.app.theme[7]}`');
    }
    
    @apply 
    focus:ring-0 focus:outline-none focus:border-none
    bg-no-repeat [background-position:0%_100%]
    [background-size:100%_0.2em]
    motion-safe:transition-all
    hover:[background-size:100%_100%]
    focus:[background-size:100%_100%] px-1;
}

</style>