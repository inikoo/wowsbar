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


function invoiceRoute(invoice) {
    switch (route().current()) {
        case 'org.shops.show.invoices.index':
            return route(
                'org.shops.show.invoices.show',
                [invoice.slug, invoice.slug]);
        default:
            return route(
                'org.accounting.invoices.show',
                [invoice.slug]);
    }
}

</script>

<template>
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(number)="{ item: invoice }">
            <Link :href="invoiceRoute(invoice)">
                {{ invoice["number"]}}
            </Link>
        </template>

    </Table>
</template>


