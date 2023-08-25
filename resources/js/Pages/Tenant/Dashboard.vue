<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 14 Aug 2023 08:45:47 Malaysia Time, Sanur, Bali
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { capitalize } from "@/Composables/capitalize"
import { trans } from 'laravel-vue-i18n'
import { useLayoutStore } from '@/Stores/layout'
import Image from '@/Components/Image.vue'

const props = defineProps<{
    title: string,
    banners?: any
}>()

const formatDate = (dateIso: Date) => {
    if(dateIso){
        const date = new Date(dateIso)
        // const year = date.getFullYear()
        // const month = (date.getMonth() + 1).toString().padStart(2, '0')
        // const day = date.getDate().toString().padStart(2, '0')

        // const hours = date.getHours().toString()
        // const minutes = date.getMinutes().toString()

        return date.toString()
    }
    return ''
}

console.log(props.banners.data)

</script>

<template layout="TenantApp">
    <Head :title="capitalize(title)" />
    <div class="max-w-7xl px-4 sm:px-6 lg:px-8 lg:py-6">
        <div class="pt-2 mt-4 lg:mt-0 lg:pt-0 text-2xl">
            {{ trans('Welcome') }}, <span class="font-bold capitalize">{{ useLayoutStore().tenant.name }}</span>!
        </div>
        <hr class="mt-3 mb-6">
        <div v-if="banners" class="max-w-2xl lg:mx-0 lg:max-w-none">
            <div class="flex items-center justify-between">
                <h2 class="text-base text-gray-700">{{ trans('Recently edited banner') }}</h2>
                <Link :href="route('portfolio.banners.index')" class="text-sm font-semibold text-gray-500 hover:text-gray-700">
                    View all<span class="sr-only">, banner</span>
                </Link>
            </div>

            <!-- Looping: Last Edited Banners -->
            <ul  role="list" class="mt-3 grid grid-cols-1 gap-x-6 gap-y-8 lg:grid-cols-3 xl:gap-x-8">
                <Link :href="route(lastEditedBanner.route?.name, lastEditedBanner.route?.parameters)" v-for="lastEditedBanner in banners.data" :key="lastEditedBanner.id" class="overflow-hidden rounded-md ring-1 ring-gray-300 hover:ring-2 hover:ring-gray-400">
                    <div class="h-auto aspect-[4/1] flex items-center justify-center gap-x-4 border-b border-gray-700/5 bg-gray-200">
                        <Image :src="lastEditedBanner.components[0].image.source" :alt="lastEditedBanner?.name" class="" />
                    </div>
                    <dl class="divide-y divide-transparent px-4 pt-1 pb-3 text-sm">
                        <div class="flex justify-between items-center gap-x-4">
                            <!-- <dt class="text-gray-500 text-sm">{{ trans('Name') }}</dt> -->
                            <dd class="flex items-start gap-x-2">
                                <div class="text-lg font-semibold text-gray-600">{{ lastEditedBanner?.name }}</div>
                            </dd>
                        </div>
                        <div class="flex justify-between items-center gap-x-4">
                            <!-- <dt class="text-gray-500 text-sm">{{ trans('Last edit') }}</dt> -->
                            <dd class="text-gray-600 text-xs italic tracking-wide space-x-1">
                                <span class="text-gray-500">{{ trans('Last edited on') }}</span>
                                <time :datetime="lastEditedBanner.updated_at">{{  formatDate(lastEditedBanner.updated_at) }}</time>
                            </dd>
                        </div>
                    </dl>
                </Link>
            </ul>
        </div>
    </div>


</template>
