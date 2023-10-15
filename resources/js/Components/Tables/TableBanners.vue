<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Thu, 13 Jul 2023 22:20:34 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Link} from '@inertiajs/vue3'
import {library} from "@fortawesome/fontawesome-svg-core"
import Table from '@/Components/Table/Table.vue'
import {Banner} from "@/types/banner"
import Icon from '@/Components/Icon.vue'
import {faSeedling, faBroadcastTower, faImage, faSparkles, faRocket, faDoNotEnter} from "@/../private/pro-light-svg-icons"
import Image from "@/Components/Image.vue"
import {useFormatTime} from '@/Composables/useFormatTime'
import {useLocaleStore} from '@/Stores/locale'


const locale = useLocaleStore()

library.add(faSeedling, faBroadcastTower, faImage, faSparkles, faRocket, faDoNotEnter)

const props = defineProps<{
    data: object,
    tab?: string
}>()


function bannerRoute(banner: Banner) {
    return route(
        'customer.caas.banners.show',
        [banner.slug]);
    /*
    switch (route().current()) {
        case 'customer.portfolio.banners.index':
            return route(
                'customer.portfolio.banners.show',
                [banner.slug])
        case 'customer.portfolio.websites.show':
            return route(
                'customer.portfolio.websites.show.banners.show',
                [route().params['portfolioWebsite'], banner.slug])
        case 'customer.portfolio.websites.show.banners.index':
            return route(
                'customer.portfolio.websites.show.banners.show',
                [route().params['portfolioWebsite'], banner.slug])
        default:
            return route(
                'customer.caas.banners.show',
                [banner.slug])
                }
     */

}

function websiteRoute(banner: Banner, slug) {
    return route(
        'customer.caas.websites.show',
        [slug]);
    /*
    switch (route().current()) {
        case 'customer.portfolio.banners.index':
            return route(
                'customer.portfolio.banners.show',
                [banner.slug])
        case 'customer.portfolio.websites.show':
            return route(
                'customer.portfolio.websites.show.banners.show',
                [route().params['portfolioWebsite'], banner.slug])
        case 'customer.portfolio.websites.show.banners.index':
            return route(
                'customer.portfolio.websites.show.banners.show',
                [route().params['portfolioWebsite'], banner.slug])
        default:
            return route(
                'customer.caas.banners.show',
                [banner.slug])
                }
     */

}


</script>

<template>

    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(slug)="{ item: banner }">
            <Link :href="bannerRoute(banner)" :id="banner['slug']" class="special-underline py-4 px-2">
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

        <template #cell(websites)="{ item: banner }">
            <Link v-for="website in banner['websites']" :href="websiteRoute(banner,website.slug)"  class="special-underline py-4 px-2 mr-2" >{{website.name}}</Link>
        </template>

        <template #cell(date)="{ item:banner }">
            <div class="text-gray-500">
                {{ useFormatTime(banner['date'], locale.language.code) }}
                <Icon class="ml-1" :data="banner['date_icon']"/>
            </div>
        </template>
    </Table>


</template>


