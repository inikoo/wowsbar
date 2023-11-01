<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Thu, 11 Aug 2022 11:08:49 Malaysia Time, Kuala Lumpur, Malaysia
  -  Reformatted: Fri, 03 Mar 2023 12:40:58 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->
<script setup lang="ts">
import { usePage } from "@inertiajs/vue3"
import Footer from "@/Layouts/Customer/Footer.vue"
import AppLeftSideBar from "@/Layouts/Customer/AppLeftSideBar.vue"
import AppRightSideBar from "@/Layouts/Customer/AppRightSideBar.vue"
import CustomerAppTopBar from "@/Layouts/Customer/CustomerAppTopBar.vue"
import Breadcrumbs from "@/Components/Navigation/Breadcrumbs.vue"
import { library } from "@fortawesome/fontawesome-svg-core"
import { initialiseCustomerApp } from "@/Composables/initialiseCustomerApp"
import { useLayoutStore } from "@/Stores/layout"
import { useAuthFirebase } from "@/Composables/firebaseAuth"

import {
    faHome,
    faBars,
    faUsersCog,
    faTachometerAltFast,
    faUser,
    faLanguage
} from '@fal/'
import { faSearch, faBell } from '@far/'


library.add(
    faHome,
    faBars,
    faUsersCog,
    faTachometerAltFast,
    faUser,
    faLanguage,
    faSearch,
    faBell
)

const layout = initialiseCustomerApp()


if (usePage().props.firebaseAuthToken) {
    useAuthFirebase(usePage().props.firebaseAuthToken)
}

const layoutState = useLayoutStore()


</script>

<template>
    <div class="fixed top-0 left-0 w-screen h-screen dark:bg-gray-700 bg-gray-50" />
    <div class="relative min-h-full transition-all duration-200 ease-in-out"
        :class="[Object.values(layoutState.rightSidebar).some(value => value.show) ? 'mr-44' : 'mr-0']">

        <!-- Section: TopBar -->
        <CustomerAppTopBar v-model="layoutState.leftSidebar.show" :logoRoute="`customer.dashboard.show`"
            urlPrefix="customer.">
        </CustomerAppTopBar>

        <!-- Breadcrumbs -->
        <Breadcrumbs class="fixed top-11 z-[19] w-full md:top-11 lg:top-10 "
            :class="[layoutState.leftSidebar.show ? 'left-0 md:left-56' : 'left-0 md:left-10']"
            :breadcrumbs="usePage().props.breadcrumbs ?? []" :navigation="usePage().props.navigation ?? []" />

        <!-- Sidebar: Left -->
        <div>
            <Transition>
                <!-- Mobile Helper: background to close hamburger -->
                <div class="bg-gray-200/80 fixed top-0 w-screen h-screen z-20 md:hidden transition-all duration-500 ease-in-out"
                    v-if="layoutState.leftSidebar.show"
                    @click="layoutState.leftSidebar.show = !layoutState.leftSidebar.show" />
            </Transition>
            <AppLeftSideBar class="-left-2/3 transition-all duration-300 ease-in-out z-20 block md:left-0"
                :class="{ 'left-0': layoutState.leftSidebar.show }" />
        </div>

        <!-- Main Content -->
        <main
            class="relative flex flex-col pt-20 pb-5 md:pt-[68px] lg:pt-16 bg-gray-50 text-gray-700 dark:bg-gray-700 dark:text-gray-400 transition-all duration-200 ease-in-out"
            :class="[layoutState.leftSidebar.show ? 'ml-0 md:ml-56' : 'ml-0 md:ml-10']">
            <slot />
        </main>

        <!-- Sidebar: Right -->
        <AppRightSideBar class="fixed top-[76px] md:top-[68px] lg:top-16 w-44 transition-all duration-200 ease-in-out"
            :class="[Object.values(layoutState.rightSidebar).some(value => value.show) ? 'right-0' : '-right-44']" />

    </div>

    <Footer />
    <notifications dangerously-set-inner-html :max="3" :width="500" />
</template>
