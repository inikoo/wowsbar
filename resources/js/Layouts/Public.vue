<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sat, 12 Aug 2023 14:01:37 Malaysia Time, Pantai Lembeng, Bali
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3'
import Cookies from '@/Components/Cookies.vue'
import Image from "@/Components/Image.vue"
import { ref, Ref } from 'vue'
import FooterTabLanguage from '@/Components/Footer/FooterLanguage.vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
const isTabActive: Ref<boolean | string> = ref(false)

const logo = usePage().props.art.logo
const footerLogo = usePage().props.art.footer_logo

</script>

<template>
    <div class="relative ">
        <!-- TopBar -->
        <div class="bg-gradient-to-b from-gray-100/50 to-gray-100/0 w-screen fixed z-10 px-6 pt-3 flex justify-between items-center">
            <Link :href="route('public.welcome')">
                <Image class="h-6 select-none" :src="logo" alt="Wowsbar" />
            </Link>
            <div class="flex justify-end gap-x-4 text-sm font-medium">
                <Link :href="route('public.register')" class="">
                    <Button :style="`tertiary`" size="xs">Register</Button>
                </Link>
                <Link :href="route('public.login')" class="">
                    <Button :style="`primary`" size="xs">Login</Button>
                </Link>
            </div>
        </div>

        <section class="relative isolate overflow-hidden bg-white">
            <!-- Background: Square line -->
            <svg class="absolute inset-0 -z-10 h-full w-full stroke-gray-200 [mask-image:radial-gradient(100%_100%_at_top_right,white,transparent)]" aria-hidden="true">
                <defs>
                    <pattern id="0787a7c5-978c-4f66-83c7-11c213f99cb7" width="200" height="200" x="50%" y="-1" patternUnits="userSpaceOnUse">
                        <path d="M.5 200V.5H200" fill="none" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" stroke-width="0" fill="url(#0787a7c5-978c-4f66-83c7-11c213f99cb7)" />
            </svg>

            <slot />
        </section>

        <!-- Footer -->
        <footer class="z-20 fixed w-screen bottom-0 right-0  text-white bg-black">
            <!-- Helper: Outer background (close popup purpose) -->
            <div class="fixed z-40 right-0 top-0 bg-transparent w-screen h-screen"
                @click="isTabActive = !isTabActive"
                :class="[isTabActive ? '' : 'hidden']" />

            <div class="flex justify-between">
                <!-- Left: Logo Section -->
                <div class="pl-4 flex items-center gap-x-1.5 py-1">
                    <Image class="h-4 select-none" :src="footerLogo" alt="Wowsbar" />
                </div>

                <!-- Right: Tab Section -->
                <div class="flex items-end flex-row-reverse text-sm">
                    <FooterTabLanguage :isTabActive="isTabActive" @isTabActive="(value: any) => isTabActive = value" />
                </div>
            </div>
        </footer>
    </div>

    <Cookies />
</template>

