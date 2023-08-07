<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sat, 05 Aug 2023 11:56:10 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="relative ">
        <div class="bg-gradient-to-b from-gray-100/50 to-gray-100/0 w-screen fixed top-3 z-10 px-8 py-1 flex justify-between items-center">
            <Link :href="route('landlord.welcome')">
                <img class="h-6 select-none" src="@/../art/logo/png/1.png" alt="Wowsbar" />
            </Link>
            <div class="flex justify-end gap-x-4 text-sm font-medium">
                <Link :href="route('login')" class="bg-gray-500 text-gray-100 py-0.5 flex place-items-center cursor-pointer px-4 rounded-md hover:bg-gray-600/90 active:bg-gray-600 select-none">
                    Login
                </Link>
                <Link :href="route('register')" class="bg-gray-100/30 border-2 border-gray-500 cursor-pointer text-gray-600 py-0.5 flex place-items-center px-4 rounded-md hover:bg-gray-200/70 active:bg-gray-300 select-none">
                    Register
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
</template>

