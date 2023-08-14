<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sat, 12 Aug 2023 14:01:37 Malaysia Time, Pantai Lembeng, Bali
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Link, usePage} from '@inertiajs/vue3'
import Cookies from '@/Components/Cookies.vue'
import Image from "@/Components/Image.vue";

const logo=usePage().props.art.logo;

</script>

<template>
    <div class="relative ">
        <div class="bg-gradient-to-b from-gray-100/50 to-gray-100/0 w-screen fixed top-3 z-10 px-8 py-1 flex justify-between items-center">
            <Link :href="route('public.welcome')">
                <Image class="h-6 select-none" :src="logo" alt="Wowsbar" />
            </Link>
            <div class="flex justify-end gap-x-4 text-sm font-medium">
                <Link :href="route('public.register')" class="bg-gray-100/30 border-2 border-gray-600 cursor-pointer text-gray-600 py-0.5 flex place-items-center px-4 rounded-md hover:bg-gray-200/70 active:bg-gray-300 select-none">
                    Register
                </Link>
                <Link :href="route('public.login')" class="bg-gray-600 text-gray-100 py-0.5 flex place-items-center cursor-pointer px-4 rounded-md hover:bg-gray-700 active:bg-gray-800 select-none">
                    Login
                </Link>
            </div>
        </div>

        <div class="relative isolate overflow-hidden bg-white">
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

        </div>
    </div>

    <Cookies />
</template>

