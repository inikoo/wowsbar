<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sun, 19 Mar 2023 16:45:18 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">

const props = defineProps<{
    data: object,
    tab?: string
}>()
import {Link} from '@inertiajs/vue3';
import Table from '@/Components/Table/Table.vue';


function paymentAccountRoute(paymentAccount) {
    switch (route().current()) {
        case 'org.shops.show.accounting.payment-accounts.index':
            return route(
                'org.shops.show.accounting.payment-accounts.show',
                [paymentAccount.shop_slug, paymentAccount.slug]);
        case 'org.accounting.payment-service-providers.show':
        case 'org.accounting.payment-service-providers.show.payment-accounts.index':
            return route(
                'org.accounting.payment-service-providers.show.payment-accounts.show',
                [paymentAccount.payment_service_providers_slug, paymentAccount.slug]);
        case 'org.accounting.payment-accounts.index':
        default:
            return route(
                'org.accounting.payment-accounts.show',
                [paymentAccount.slug]);

    }

}

function paymentsRoute(paymentAccount: PaymentAccount) {
    switch (route().current()) {

        case 'org.accounting.payment-service-providers.show.payment-accounts.index':
            return route(
                'org.accounting.payment-service-providers.show.payment-accounts.show.payments.index',
                [paymentAccount.payment_service_providers_slug, paymentAccount.slug]);
        case 'org.accounting.payment-accounts.index':
            return route(
                'org.accounting.payment-accounts.show.payments.index',
                [paymentAccount.slug]);
        default:
            return route('org.accounting.payments.index');
    }

}
</script>


<template>
    <Table :resource="data" :name="tab" class="mt-5">
<!--        <template #cell(code)="{ item: paymentAccount }">
            <Link :href="paymentAccountRoute(paymentAccount)">
                {{ paymentAccount['code'] }}
            </Link>
        </template>

        <template #cell(number_payments)="{ item: paymentAccount }">
            <Link :href="paymentsRoute(paymentAccount)">
                {{ paymentAccount['number_payments'] }}
            </Link>
        </template>-->
    </Table>
</template>
