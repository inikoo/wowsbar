<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 25 Sep 2023 12:16:11 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Link} from '@inertiajs/vue3';
import Table from '@/Components/Table/Table.vue';
import {Website} from "@/types/website";

const props = defineProps<{
    data: object,
    tab?:string
}>()


function websiteRoute(website: Website) {

    console.log(route().current())
    switch (route().current()) {
        case  'org.portfolios.shop.customer-websites.index':
            return route(
                'org.portfolios.shop.customer-websites.show',
                [
                    route().params['shop'],
                    website.slug
                ]);
        case 'org.crm.shop.customers.show.customer-websites.index':
            return route(
                'org.crm.shop.customers.show.customer-websites.show',
                [
                    route().params['shop'],
                    route().params['customer'],
                    website.slug
                ]);
        case 'org.portfolios.index':
            return route(
                'org.portfolios.show',
                [website.slug]);
    }
}

</script>

<template>
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(slug)="{ item: website }">
            <Link :href="websiteRoute(website)" :id=" website['slug']" class="py-2 px-1">
                {{ website['slug'] }}
            </Link>
        </template>
    </Table>


</template>


