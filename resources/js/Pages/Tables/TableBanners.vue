<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Thu, 13 Jul 2023 22:20:34 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Link} from '@inertiajs/vue3';
import Table from '@/Components/Table/Table.vue';
import {Banner} from "@/types/banner";
import Tag from '@/Components/Tag.vue'

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

function setColor(state: state) {
    switch (state) {
        case 'unpublished':
            return '#108ee9';
        case 'live':
            return '#87d068';
        case 'retired':
            return '#ff5500';
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
            <Tag :color="setColor(banner['state'])" :id="banner['state']">
                {{ banner['state'] }}
            </Tag>
        </template>
    </Table>


</template>


