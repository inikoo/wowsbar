<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Thu, 13 Jul 2023 22:20:34 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Link} from '@inertiajs/vue3';
import Table from '@/Components/Table/Table.vue';
import {Banner} from "@/types/banner";

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
        case 'portfolio.portfolio-websites.show':
            return route(
                'portfolio.portfolio-websites.show.banners.show',
                [route().params['portfolioWebsite'],banner.slug]);
        case 'portfolio.portfolio-websites.show.banners.index':
            return route(
                'portfolio.portfolio-websites.show.banners.show',
                [route().params['portfolioWebsite'],banner.slug]);
    }
}

</script>

<template>
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(slug)="{ item: banner }">
            <Link :href="bannerRoute(banner)">
                {{ banner['slug'] }}
            </Link>
        </template>
    </Table>


</template>


