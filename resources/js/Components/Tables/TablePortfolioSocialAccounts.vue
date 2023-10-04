<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 20 Mar 2023 23:18:59 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import Table from '@/Components/Table/Table.vue'
import { Website } from "@/types/website"
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

const props = defineProps<{
    data: object
    tab?: string
}>()


function accountRoute(account) {
    switch (route().current()) {
        case 'customer.portfolio.social.account.index':
            return route(
                'customer.portfolio.social.account.show',
                [account.slug]);
    }
}

</script>

<template>

    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(username)="{ item: account }">
            <Link :href="accountRoute(account)" :id="account['slug']" class="py-2 px-1">
                {{ account['username'] }}
            </Link>
        </template>
    </Table>

</template>
