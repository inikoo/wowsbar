<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 03 Jul 2023 15:46:34 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Link, router } from "@inertiajs/vue3"
import { useLayoutStore } from "@/Stores/layout"
import AppTopBarNavs from "@/Layouts/TopBar/AppTopBarNavs.vue"
import { ref, onMounted } from "vue"
import { useSignoutFirebase } from "@/Composables/firebaseAuth"

import {
    Menu,
    MenuButton,
    MenuItem,
    MenuItems
} from "@headlessui/vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { Disclosure } from "@headlessui/vue"
import Button from "@/Components/Elements/Buttons/Button.vue"
import SearchBar from "@/Components/SearchBar.vue"
import { trans } from "laravel-vue-i18n"
import { useAppearanceStore } from "@/Stores/appearance"
import Image from "@/Components/Image.vue"

const props = defineProps<{
    sidebarOpen: boolean
    logoRoute: string
    urlPrefix: string
}>()

defineEmits<{
    (e: 'sidebarOpen'): void
}>()

const layout = useLayoutStore()

const showSearchDialog = ref(false)

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

const logoutAuth = () => {
    // Signout from app and Firebase
    router.post(route(props.urlPrefix + 'logout'))
    useSignoutFirebase()
}

</script>

<template>
    <Disclosure as="nav" class=" fixed top-0 z-20 w-full bg-gray-50 text-gray-700 dark:bg-gray-800 dark:text-gray-200" v-slot="{ open }">
        <div class="px-0">
            <div class="flex h-11 lg:h-10 flex-shrink-0 border-b border-gray-200 dark:border-gray-500 ">
                <div class="flex flex-1">
                    <div class="flex flex-1 lg:justify-between">
                        <!-- Hamburger -->
                        <button class="block md:hidden w-10 h-10 relative focus:outline-none" @click="sidebarOpen = !sidebarOpen, $emit('sidebarOpen', sidebarOpen)">
                            <span class="sr-only">Open sidebar</span>
                            <div class="block w-5 absolute left-1/2 top-1/2   transform  -translate-x-1/2 -translate-y-1/2">
                                <span aria-hidden="true" class="block absolute rounded-full h-0.5 w-5 bg-gray-900 transform transition duration-200 ease-in-out"
                                    :class="{'rotate-45': sidebarOpen,' -translate-y-1.5': !sidebarOpen }"></span>
                                <span aria-hidden="true" class="block absolute rounded-full h-0.5 w-5 bg-gray-900 transform transition duration-100 ease-in-out" :class="{'opacity-0': sidebarOpen } "></span>
                                <span aria-hidden="true" class="block absolute rounded-full h-0.5 w-5 bg-gray-900 transform transition duration-200 ease-in-out"
                                    :class="{'-rotate-45': sidebarOpen, ' translate-y-1.5': !sidebarOpen}"></span>
                            </div>
                        </button>

                        <!-- Menu -->
                        <div class="flex flex-1 items-center justify-between lg:justify-start">
                            <Link :href="route(logoRoute)"
                                class="md:pl-3 flex items-center h-full xl:overflow-hidden space-x-2 mr-6 xl:w-56 xl:pr-2 xl:border-r-2 xl:mr-0 xl:border-gray-200 dark:xl:border-gray-500"
                            >
                                <slot />
                            </Link>
                            <AppTopBarNavs />
                        </div>
                    </div>

                    <!-- Avatar Group -->
                    <div class="flex items-center mr-6 space-x-3">
                        <div class="flex">
                            <!-- <div class="cursor-pointer text-white bg-indigo-500 px-2 py-0.5 rounded-md select-none" @click="changeColorMode(true)">Dark mode: True</div>
                            <div class="cursor-pointer text-white bg-indigo-500 px-2 py-0.5 rounded-md select-none" @click="changeColorMode(false)">Dark mode: False</div>
                            <div class="cursor-pointer text-white bg-indigo-500 px-2 py-0.5 rounded-md select-none" @click="changeColorMode('system')">Dark mode: OS System</div> -->

                            <!-- Button: Search -->
                            <button @click="showSearchDialog = !showSearchDialog" id="search"
                                    class="h-8 w-8 grid items-center justify-center rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                <span class="sr-only">{{ trans("Search") }}</span>
                                <font-awesome-icon aria-hidden="true" icon="fa-regular fa-search" size="lg" />
                                <SearchBar v-if="showSearchDialog" v-on:close="showSearchDialog = false" />
                            </button>

                            <!-- Button: Notifications -->
                            <button type="button"
                                    class="h-8 w-8 grid items-center justify-center rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                <span class="sr-only">{{ trans("View notifications") }}</span>
                                <font-awesome-icon aria-hidden="true" icon="fa-regular fa-bell" size="lg" />
                            </button>
                        </div>

                        <!-- Avatar Button -->
                        <Menu as="div" class="relative">
                            <MenuButton id="avatar-thumbnail"
                                class="flex max-w-xs overflow-hidden items-center rounded-full bg-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-gray-500">
                                <span class="sr-only">{{ trans("Open user menu") }}</span>
                                <Image  class="h-8 w-8 rounded-full"
                                    :src="layout.avatar_thumbnail"
                                    alt="" />

                            </MenuButton>

                            <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100"
                                        leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                                <MenuItems class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-200 focus:outline-none">
                                    <div class="py-1">
                                        <MenuItem v-slot="{ active,close }">
                                            <Link as="ul" type="button" :href="route(urlPrefix+'profile.show')" @click="close"
                                                :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm cursor-pointer']">
                                                {{ trans("View profile") }}
                                            </Link>
                                        </MenuItem>

                                    </div>

                                    <div class="py-1">
                                        <MenuItem v-slot="{ active }">
                                            <div @click="logoutAuth()"
                                                :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm cursor-pointer']"
                                            >
                                                {{ trans('Logout') }}
                                            </div>
                                        </MenuItem>
                                    </div>
                                </MenuItems>
                            </transition>
                        </Menu>
                    </div>
                </div>
            </div>
        </div>
    </Disclosure>
</template>

