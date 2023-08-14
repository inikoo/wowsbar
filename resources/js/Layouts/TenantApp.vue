<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Thu, 11 Aug 2022 11:08:49 Malaysia Time, Kuala Lumpur, Malaysia
  -  Reformatted: Fri, 03 Mar 2023 12:40:58 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Inikoo
  -  Version 4.0
  -->


<script setup lang="ts">
import { ref, watchEffect } from "vue"
import AppFooter from "@/Layouts/Footer/AppFooter.vue"
import { usePage, router } from "@inertiajs/vue3"

import { useLayoutStore } from "@/Stores/layout"
import { useLocaleStore } from "@/Stores/locale"

import AppLeftSideBar from "@/Layouts/AppLeftSideBar.vue"
import AppRightSideBar from "@/Layouts/AppRightSideBar.vue"
import AppTopBar from "@/Layouts/TopBar/AppTopBar.vue"
import Breadcrumbs from "@/Components/Navigation/Breadcrumbs.vue"

import { loadLanguageAsync, trans } from "laravel-vue-i18n"
import { library } from "@fortawesome/fontawesome-svg-core"

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
import {useFirebaseStore} from "@/Stores/firebase"


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

const initialiseApp = () => {
    const layout = useLayoutStore();
    const locale = useLocaleStore();
    const firebase = useFirebaseStore();

    if (usePage().props.firebase) {
        firebase.credential=JSON.parse(usePage().props.firebase.credential);
        firebase.databaseURL=usePage().props.firebase.databaseURL;
    }

    if (usePage().props.localeData) {
        loadLanguageAsync(usePage().props.localeData.language.code);
    }
    watchEffect(() => {
        if (usePage().props.layout) {
            layout.navigation = usePage().props.layout.navigation ?? null;
            layout.secondaryNavigation = usePage().props.layout.secondaryNavigation ?? null;
            if (usePage().props.layout.shopsInDropDown) {
                layout.shopsInDropDown = usePage().props.layout.shopsInDropDown.data ??
                    {};
            }
            if (usePage().props.layout.websitesInDropDown) {
                layout.websitesInDropDown = usePage().props.layout.websitesInDropDown.data ??
                    {};
            }
            if (usePage().props.layout.warehousesInDropDown) {
                layout.warehousesInDropDown = usePage().props.layout.warehousesInDropDown.data ??
                    {};
            }
        }


        if (usePage().props.localeData) {
            locale.language = usePage().props.localeData.language;
            locale.languageOptions = usePage().props.localeData.languageOptions;
        }

        if (usePage().props.tenant) {
            layout.tenant = usePage().props.tenant ?? null;
        }

        layout.currentRouteParameters=route().params;
        layout.currentRoute=route().current();
        layout.currentModule = layout.currentRoute?.substring(0, layout.currentRoute?.indexOf("."));


        if (usePage().props.layoutShopsList) {
            layout.shops = usePage().props.layoutShopsList;
        }

        if (usePage().props.layoutWebsitesList) {
            layout.websites = usePage().props.layoutWebsitesList;
        }

        if (usePage().props.layoutWarehousesList) {
            layout.warehouses = usePage().props.layoutWarehousesList;
        }

        if(!layout.booted){
            if(Object.keys(layout.shops).length===1){
                layout.currentShopData={
                    slug: layout.shops[Object.keys(layout.shops)[0]].slug,
                    name: layout.shops[Object.keys(layout.shops)[0]].name,
                    code: layout.shops[Object.keys(layout.shops)[0]].code,
                };
            }
        }

        if(!layout.booted){
            if(Object.keys(layout.websites).length===1){
                layout.currentWebsiteData={
                    slug: layout.websites[Object.keys(layout.websites)[0]].slug,
                    name: layout.websites[Object.keys(layout.websites)[0]].name,
                    code: layout.websites[Object.keys(layout.websites)[0]].code,
                };
            }
        }

        if(!layout.booted){
            if(Object.keys(layout.warehouses).length===1){
                layout.currentWarehouseData={
                    slug: layout.warehouses[Object.keys(layout.warehouses)[0]].slug,
                    name: layout.warehouses[Object.keys(layout.warehouses)[0]].name,
                    code: layout.warehouses[Object.keys(layout.warehouses)[0]].code,
                };
            }
        }

        layout.booted=true;

        if (usePage().props.auth.user.avatar_id) {
            layout.avatar_id=usePage().props.auth.user.avatar_id;
        }
    })
    return layout
}


router.on('navigate', () => {
    if(route().params.hasOwnProperty('shop')){
        layout.currentShopData=layout.shops[route().params['shop']]
    }
    if(route().params.hasOwnProperty('website')){
        layout.currentWebsiteData=layout.websites[route().params['website']]
    }
    if(route().params.hasOwnProperty('warehouse')){
        layout.currentWarehouseData=layout.warehouses[route().params['warehouse']]
    }
})

const layout = initialiseApp()
const sidebarOpen = ref(false)

</script>

<template>
    <div class="fixed top-0 left-0 w-screen h-screen dark:bg-gray-700 bg-gray-50" />
    <div class="relative min-h-full transition-all duration-200 ease-in-out"
        :class="[Object.values(layout.rightSidebar).some(value => value === true) ? 'mr-44' : 'mr-0']"
    >
        <!-- TopBar -->
        <AppTopBar :sidebarOpen="sidebarOpen" :logoRoute="`dashboard.show`">
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
            <div class="bg-gray-100/80 fixed top-0 w-screen h-screen z-10" v-if="sidebarOpen" @click="sidebarOpen = !sidebarOpen" />
            <AppLeftSideBar v-if="!sidebarOpen" class="hidden md:block" />
            <AppLeftSideBar  class="-left-2/3 transition-all duration-100 ease-in-out z-20 block md:hidden" :class="{'left-[0]': sidebarOpen }" @click="sidebarOpen = !sidebarOpen" />
        </div>

        <!-- Main Content -->
        <main class="relative flex flex-col pt-16 pb-5 ml-0 md:ml-10 xl:ml-56 bg-gray-50 text-gray-700 dark:bg-gray-700 dark:text-gray-400">
            <slot />
        </main>

        <!-- Sidebar: Right -->
        <AppRightSideBar class="fixed top-16 w-44 transition-all duration-200 ease-in-out"
            :class="[Object.values(layout.rightSidebar).some(value => value === true) ? 'right-0' : '-right-44']"
        />

    </div>

    <!-- Footer -->
    <AppFooter />

</template>

<style lang="scss">
.tabNavigationActive {
    // Indicate current active state to have consistent style. Use for: AppLeftSideBar, CreateModel
    @apply bg-gray-200/80 border-orange-500 text-gray-700 dark:text-gray-300
}

.tabNavigation {
    @apply hover:bg-gray-200/30 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-400
}
</style>
