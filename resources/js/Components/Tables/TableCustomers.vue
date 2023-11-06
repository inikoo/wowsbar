<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 20 Mar 2023 23:18:59 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Link} from '@inertiajs/vue3';
import Table from '@/Components/Table/Table.vue';

const props = defineProps<{
    data: object,
    tab?: string
}>()

function customerRoute(customer) {
    switch (route().current()) {
        case 'org.crm.shop.customers.index':
            return route(
                'org.crm.shop.customers.show',
                [route().params['shop'],customer.slug]);
        default:
            return route(
                'org.crm.customers.show',
                [customer.slug]);
    }
}

</script>

<template>
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(slug)="{ item: customer }">
            <Link :href="customerRoute(customer)" class="py-1 specialUnderlineOrg">
                {{ customer['slug'] }}
            </Link>
        </template>
    </Table>
</template>
