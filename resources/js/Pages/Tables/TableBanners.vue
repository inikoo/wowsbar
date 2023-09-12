<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Thu, 13 Jul 2023 22:20:34 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { library } from "@fortawesome/fontawesome-svg-core"
import Table from '@/Components/Table/Table.vue'
import { Banner } from "@/types/banner"
import Icon from '@/Components/Icon.vue'
import { faSeedling, faBroadcastTower } from "@/../private/pro-light-svg-icons"
import Image from "@/Components/Image.vue"
import { useFormatTime } from '@/Composables/useFormatTime'
import { useLocaleStore } from '@/Stores/locale'

const locale = useLocaleStore()

library.add(faSeedling, faBroadcastTower)

const props = defineProps<{
    data: object,
    tab?:string
}>()


function bannerRoute(banner: Banner) {
    switch (route().current()) {
        case 'tenant.portfolio.banners.index':
            return route(
                'tenant.portfolio.banners.show',
                [banner.slug])
        case 'tenant.portfolio.websites.show':
            return route(
                'tenant.portfolio.websites.show.banners.show',
                [route().params['portfolioWebsite'],banner.slug])
        case 'tenant.portfolio.websites.show.banners.index':
            return route(
                'tenant.portfolio.websites.show.banners.show',
                [route().params['portfolioWebsite'],banner.slug])
    }
}

</script>

<template>
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(slug)="{ item: banner }">
            <Link :href="bannerRoute(banner)" :id="banner['slug']" class="py-4 px-2">
                {{ banner['slug'] }}
            </Link>
        </template>

        <template #cell(state)="{ item: banner }">
            <Icon :data="banner['state_icon']"/>
        </template>

        <template #cell(image_thumbnail)="{ item: banner }">
            <div class="h-11 overflow-hidden aspect-[4/1]">
                <Image :src="banner['image_thumbnail']"/>
            </div>
        </template>

        <template #cell(created_at)="{ item }">
            <div class="text-gray-500">
                {{ useFormatTime(item.created_at, locale.language.code) }}
            </div>
        </template>

        <template #cell(updated_at)="{ item }">
            <div class="text-gray-500">
                {{ useFormatTime(item.updated_at, locale.language.code) }}
            </div>
        </template>
    </Table>


</template>


