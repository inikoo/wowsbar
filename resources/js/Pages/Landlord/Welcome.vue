<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 31 Jul 2023 16:01:32 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->
<script setup lang="ts">
import { Head } from "@inertiajs/vue3"
import { ChevronRightIcon } from '@heroicons/vue/20/solid'
import { Link } from "@inertiajs/vue3"

const props = defineProps<{
    whatsNew: {
        label: string
        route: {
            name: string
            parameters: string
        }
    }
}>()
</script>

<template layout="Landlord">
    <Head title="Homepage" />
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

        <div class="mx-auto max-w-7xl px-6 pb-24 pt-10 sm:pb-32 lg:flex lg:px-8 lg:py-40">
            <div class="mx-auto max-w-2xl lg:mx-0 lg:max-w-xl lg:flex-shrink-0 lg:pt-0">
                <img class="h-24 select-none" src="@/../art/logo/png/1.png" alt="Wowsbar" />
                <!-- <img src="@/../art/logo/png/2.png" alt="Wowsbar" class="h-4"> -->
                <div v-if="whatsNew" class="mt-8">
                    <Link :href="route(whatsNew.route.name, whatsNew.route.parameters)" class="inline-flex space-x-6">
                        <span class="rounded-full bg-gray-600/10 px-3 py-1 text-sm font-semibold leading-6 text-gray-600 ring-1 ring-inset ring-gray-600/10">What's new</span>
                        <span class="inline-flex items-center space-x-2 text-sm font-medium leading-6 text-gray-600">
                            <span>{{ whatsNew.label }}</span>
                            <ChevronRightIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                        </span>
                    </Link>
                </div>
                <h1 class="mt-3 text-4xl font-bold tracking-tight text-gray-700 sm:text-6xl">Your One-Stop Platform Banners Creator</h1>
                <p class="mt-6 text-lg leading-8 text-gray-600">Create stunning website banners effortlessly! Easy to use, customizable templates, and no design experience required. Elevate your website's appeal today!</p>
                <div class="mt-8 flex items-center gap-x-6 select-none">
                    <a href="#" class="rounded-md bg-gray-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600">Get started</a>
                    <a href="#" class="text-sm font-semibold leading-6 text-gray-700">Learn more <span aria-hidden="true">→</span></a>
                </div>
            </div>
            <div class="mx-auto mt-16 flex max-w-2xl sm:mt-24 lg:ml-10 lg:mr-0 lg:mt-0 lg:flex-none ">
                <div class="max-w-3xl flex-none">
                    <div class="-m-2 rounded-xl bg-gray-700/5 p-2 ring-1 ring-inset ring-gray-700/10 lg:-m-4 lg:rounded-2xl lg:p-4">
                        <img src="@/../art/others/welcome.png" alt="App screenshot" width="2432" height="1442" class="w-[76rem] rounded-md shadow-2xl ring-1 ring-gray-700/10 select-none" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
