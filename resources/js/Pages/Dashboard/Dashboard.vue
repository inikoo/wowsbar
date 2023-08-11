<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sat, 24 Jun 2023 11:02:25 Malaysia Time, Pantai Lembeng, Bali, Id
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { capitalize } from "@/Composables/capitalize"
import PageHeading from "@/Components/Headings/PageHeading.vue"
import { trans } from 'laravel-vue-i18n'

import { ref } from 'vue'
import { Dialog, DialogPanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import {
    ArrowDownCircleIcon,
    ArrowPathIcon,
    ArrowUpCircleIcon,
    Bars3Icon,
    EllipsisHorizontalIcon,
    PlusSmallIcon,
} from '@heroicons/vue/20/solid'
import { BellIcon, XMarkIcon } from '@heroicons/vue/24/outline'

const props = defineProps<{
    title: string,
    banners: any
}>()

const formatDate = (dateIso: Date) => {
    const date = new Date(dateIso)
    const year = date.getFullYear()
    const month = (date.getMonth() + 1).toString().padStart(2, '0')
    const day = date.getDate().toString().padStart(2, '0')

    const hours = date.getHours().toString()
    const minutes = date.getMinutes().toString()

    return `${year}-${month}-${day} ${hours}:${minutes}`
}

</script>

<template layout="App">
    <Head :title="capitalize(title)" />
    <div class="max-w-7xl px-4 sm:px-6 lg:px-8 lg:py-6">
        <div class="max-w-2xl lg:mx-0 lg:max-w-none">
            <div class="flex items-center justify-between">
                <h2 class="text-base text-gray-700">{{ trans('Recently edited banner') }}</h2>
                <!-- <a href="#" class="text-sm font-semibold leading-6 text-indigo-600 hover:text-indigo-500">
                    View all<span class="sr-only">, banner</span>
                </a> -->
            </div>

            <!-- Looping: Last Edited Banners -->
            <ul role="list" class="mt-3 grid grid-cols-1 gap-x-6 gap-y-8 lg:grid-cols-3 xl:gap-x-8">
                <Link :href="route(lastEditedBanner.route['name'], lastEditedBanner.route['parameters'])" v-for="lastEditedBanner in banners.data" :key="lastEditedBanner.id" class="overflow-hidden rounded-xl ring-1 ring-gray-300 transition-all duration-200 ease-in-out hover:ring-2 hover:ring-gray-400">
                    <div class="flex items-center gap-x-4 border-b border-gray-700/5 bg-indigo-100">
                        <img :src="lastEditedBanner.components[0].image_source" :alt="lastEditedBanner.name" class="aspect-[4/1]" />
                        <!-- <div class="bg-gray-400 aspect-[4/1] w-full" /> -->
                    </div>
                    <dl class="divide-y divide-transparent px-4 py-3 text-sm">
                        <div class="flex justify-between items-center gap-x-4">
                            <!-- <dt class="text-gray-500 text-sm">{{ trans('Name') }}</dt> -->
                            <dd class="flex items-start gap-x-2">
                                <div class="text-lg font-semibold text-gray-600">{{ lastEditedBanner.name }}</div>
                            </dd>
                        </div>
                        <div class="flex justify-between items-center gap-x-4">
                            <!-- <dt class="text-gray-500 text-sm">{{ trans('Last edit') }}</dt> -->
                            <dd class="text-gray-600 text-xs italic tracking-wide">
                                <span class="text-gray-500">Last edited on </span>
                                <time :datetime="lastEditedBanner.updated_at">{{  formatDate(lastEditedBanner.updated_at) }}</time>
                            </dd>
                        </div>
                    </dl>
                </Link>
            </ul>
        </div>
    </div>

</template>
