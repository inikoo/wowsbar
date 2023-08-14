<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 14 Aug 2023 08:45:47 Malaysia Time, Sanur, Bali
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { ref, watchEffect } from "vue";
import {
    Menu,
    MenuButton,
    MenuItem,
    MenuItems
} from "@headlessui/vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue"
import Button from "@/Components/Elements/Buttons/Button.vue"
import SearchBar from "@/Components/SearchBar.vue"
import AppFooter from "@/Layouts/AppFooter.vue"
import { usePage, router } from "@inertiajs/vue3"

import { useOrgLayoutStore } from "@/Stores/org-layout"
import { useLocaleStore } from "@/Stores/locale"

import AppLeftSideBar from "@/Layouts/AppLeftSideBar.vue"
import AppRightSideBar from "@/Layouts/AppRightSideBar.vue"
import AppTopBar from "@/Layouts/TopBar/AppTopBar.vue"
import Breadcrumbs from "@/Components/Navigation/Breadcrumbs.vue"

import { loadLanguageAsync, trans } from "laravel-vue-i18n"
import { Link } from "@inertiajs/vue3"
import { library } from "@fortawesome/fontawesome-svg-core"
import { useAppearanceStore } from "@/Stores/appearance";

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
} from "../../private/pro-light-svg-icons"
import { faSearch, faBell} from "../../private/pro-regular-svg-icons"
import { onMounted } from "vue";
import {useFirestore} from "vuefire";
import {useFirebaseStore} from "@/Stores/firebase";


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
    const layout = useOrgLayoutStore();
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
            //layout.secondaryNavigation = usePage().props.layout.secondaryNavigation ?? null;
        }

        if (usePage().props.localeData) {
            locale.language = usePage().props.localeData.language;
            locale.languageOptions = usePage().props.localeData.languageOptions;
        }

        if (usePage().props.organisation) {
            layout.organisation = usePage().props.organisation ?? null;
        }

        layout.currentRouteParameters=route().params;
        layout.currentRoute=route().current();
        layout.currentModule = layout.currentRoute?.substring(0, layout.currentRoute?.indexOf("."));

        if (usePage().props.auth.user.avatar_id) {
            layout.avatar_id=usePage().props.auth.user.avatar_id;
        }
    })
    return layout
}




const layout = initialiseApp()
const sidebarOpen = ref(false)
const showSearchDialog = ref(false)
const user = ref(usePage().props.auth.user)

const changeColorMode = (mode: boolean | string) => {
    // If browsers not support matchMedia
    if (!window.matchMedia) {
        return
    }

    let query: boolean | string = false

    if (mode == "system") {
        // If browsers prefers dark-mode then true
        query = window.matchMedia('(prefers-color-scheme: dark)').matches
    } else {
        query = mode
    }

    if(query) {
        document.documentElement.classList.add('dark')
        localStorage.setItem('darkMode', `${query}`)
    } else {
        document.documentElement.classList.remove('dark')
        localStorage.setItem('darkMode', `${query}`)
    }
}

onMounted(() => {
    useAppearanceStore().darkMode ? document.documentElement.classList.add('dark') : ''
})

</script>

<template>
    <div class="fixed top-0 left-0 w-screen h-screen dark:bg-gray-700 bg-gray-50" />
    <div class="relative min-h-full transition-all duration-200 ease-in-out"
        :class="[Object.values(layout.rightSidebar).some(value => value) ? 'mr-44' : 'mr-0']"
    >

        <!-- TopBar -->
        <AppTopBar :sidebarOpen="sidebarOpen" :logoRoute="`org.dashboard.show`">
            <img v-if="layout.organisation.logo_id" class="h-7 md:h-5 shadow" :src="`/media/${layout.organisation.logo_id}`" :alt="layout.organisation.code" />
            <span class="hidden leading-none md:inline font-bold  xl:truncate text-gray-800 dark:text-gray-300">
                {{ layout.organisation.name}}
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
