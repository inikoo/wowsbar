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

    switch (route().current()) {
        case  'org.subscriptions.shop.customer-websites.index':
            return route(
                'org.subscriptions.shop.customer-websites.show',
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
        case 'org.subscriptions.index':
            return route(
                'org.subscriptions.show',
                [website.slug]);
    }
}

function customerRoute(website: Website) {

    switch (route().current()) {
        case  'org.subscriptions.shop.customer-websites.index':
            return route(
                'org.crm.shop.customers.show',
                [
                    route().params['shop'],
                    website.customer_slug
                ]);

    }
}

</script>

<template>
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(slug)="{ item: website }">
            <Link :href="websiteRoute(website)" :id=" website['slug']" class="specialUnderlineOrg py-1 px-1">
                {{ website['slug'] }}
            </Link>
        </template>
        <template #cell(customer_name)="{ item: website }">
            <Link :href="customerRoute(website)" :id=" website['customer_slug']" class="specialUnderlineOrg py-1 px-1">
                {{ website['customer_name'] }}
            </Link>
        </template>
    </Table>


</template>


