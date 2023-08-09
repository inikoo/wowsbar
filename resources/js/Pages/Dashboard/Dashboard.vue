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

}>()

const lastEditedBanners = [
    {
        id: 1,
        name: 'Banner Exodus',
        imageUrl: 'https://tailwindui.com/img/logos/48x48/tuple.svg',
        lastEdited: 'December 13, 2022',
        // dateTime: '2022-12-13',
        // amount: '$2,000.00',
        // status: 'Overdue'
        
    },
    {
        id: 2,
        name: 'Banner Savycal',
        imageUrl: 'https://tailwindui.com/img/logos/48x48/savvycal.svg',
        lastEdited: 'January 22, 2023',
        // dateTime: '2023-01-22',
        // amount: '$14,000.00',
        // status: 'Paid'
        
    },
    {
        id: 3,
        name: 'Banner Reform',
        imageUrl: 'https://tailwindui.com/img/logos/48x48/reform.svg',
        lastEdited: 'January 23, 2023',
        // dateTime: '2023-01-23',
        // amount: '$7,600.00',
        // status: 'Paid'
        
    },
]


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
                <Link href='#' v-for="lastEditedBanner in lastEditedBanners" :key="lastEditedBanner.id" class="overflow-hidden rounded-xl ring-1 ring-gray-300 transition-all duration-200 ease-in-out hover:ring-2 hover:ring-orange-500">
                    <div class="flex items-center gap-x-4 border-b border-gray-700/5 bg-indigo-100">
                        <img src="@/../art/banner/Image 555.jpg" :alt="lastEditedBanner.name" class="aspect-[4/1]" />
                    </div>
                    <dl class="divide-y divide-gray-100 px-6 py-3">
                        <div class="flex justify-between items-center gap-x-4 py-3">
                            <dt class="text-gray-500 text-sm">{{ trans('Name') }}</dt>
                            <dd class="flex items-start gap-x-2">
                                <div class="font-semibold text-gray-500">{{ lastEditedBanner.name }}</div>
                            </dd>
                        </div>
                        <div class="flex justify-between items-center gap-x-4 py-3">
                            <dt class="text-gray-500 text-sm">{{ trans('Last edit') }}</dt>
                            <dd class="text-gray-500 font-semibold">
                                <time :datetime="lastEditedBanner.lastEdited">{{ lastEditedBanner.lastEdited }}</time>
                            </dd>
                        </div>
                    </dl>
                </Link>
            </ul>
        </div>
    </div>

</template>
