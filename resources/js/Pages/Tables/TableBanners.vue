<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Thu, 13 Jul 2023 22:20:34 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Link} from '@inertiajs/vue3';
import { library } from "@fortawesome/fontawesome-svg-core";
import Table from '@/Components/Table/Table.vue';
import {Banner} from "@/types/banner";
import Icon from '@/Components/Icon.vue'
import { faSeedling, faBroadcastTower} from "@/../private/pro-light-svg-icons"
import Image from "@/Components/Image.vue";

library.add(faSeedling, faBroadcastTower);
const props = defineProps<{
    data: object,
    tab?:string
}>()


function bannerRoute(banner: Banner) {
    switch (route().current()) {
        case 'portfolio.banners.index':
            return route(
                'portfolio.banners.show',
                [banner.slug]);
        case 'portfolio.websites.show':
            return route(
                'portfolio.websites.show.banners.show',
                [route().params['portfolioWebsite'],banner.slug]);
        case 'portfolio.websites.show.banners.index':
            return route(
                'portfolio.websites.show.banners.show',
                [route().params['portfolioWebsite'],banner.slug]);
    }
}

</script>

<template>
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(slug)="{ item: banner }">
            <Link :href="bannerRoute(banner)" :id="banner['slug']">
                {{ banner['slug'] }}
            </Link>
        </template>

        <template #cell(state)="{ item: banner }">
            <Icon :data="banner['state_icon']"/>
        </template>
        <template #cell(image_thumbnail)="{ item: banner }">
            <Image  :class="'h-7  flex '"  :src="banner['image_thumbnail']"/>
        </template>
    </Table>


</template>


