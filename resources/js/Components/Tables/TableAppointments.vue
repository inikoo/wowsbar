<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sat, 07 Oct 2023 21:49:18 Malaysia Time, Office, Bali, Indonesia
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
        <template #cell(customer)="{ item: appointment }">
            <Link :href="customerRoute(appointment)">
                {{ appointment['customer'] }}
            </Link>
        </template>
    </Table>
</template>
