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
        case 'org.crm.shop.appointments.index':
        case 'org.crm.shop.customers.show':
            return route(
                'org.crm.shop.customers.show',
                [route().params['shop'], customer.customer_slug]);
        default:
            return route(
                'org.crm.customers.show',
                [customer.customer_slug]);
    }
}

function appointmentRoute(appointment) {
    switch (route().current()) {
        case 'org.crm.shop.customers.index':
        case 'org.crm.shop.appointments.index':
        case 'org.crm.shop.customers.show':
            return route(
                'org.crm.shop.appointments.index',
                [route().params['shop'], appointment.customer_slug]);
        default:
            return route(
                'org.crm.appointments.show',
                [appointment.customer_slug]);
    }
}

</script>

<template>
    <Table :resource="data" :name="'appointments'" class="mt-5">
        <template #cell(name)="{ item: appointment }">
            <Link :href="appointmentRoute(appointment)">
                {{ appointment['name'] }}
            </Link>
        </template>
        <template #cell(customer_name)="{ item: appointment }">
            <Link :href="customerRoute(appointment)">
                {{ appointment['customer_name'] }}
            </Link>
        </template>
    </Table>
</template>
