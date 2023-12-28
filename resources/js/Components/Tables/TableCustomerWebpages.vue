<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Thu, 28 Dec 2023 00:06:08 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Link} from '@inertiajs/vue3';
import Table from '@/Components/Table/Table.vue';
import {Website} from "@/types/website";

const props = defineProps<{
    data: object,
    tab?: string
}>()


function websiteRoute(website: Website) {

    switch (route().current()) {
        case 'org.crm.shop.customers.show':
            return route(
                'org.crm.shop.customers.show.customer-websites.show',
                [route().params.shop, route().params.customer, website.slug])

        case 'org.crm.shop.customers.show.customer-websites.index':
            return route(
                'org.crm.shop.customers.show.customer-websites.show',
                [
                    route().params['shop'],
                    route().params['customer'],
                    website.slug
                ]);

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
        <template #cell(slugx)="{ item: webpage }">
            <Link :href="webpageRoute(webpage)" :id=" webpage['slug']" class="specialUnderlineOrg py-1 px-1">
                {{ webpage['slug'] }}
            </Link>
        </template>

    </Table>


</template>


