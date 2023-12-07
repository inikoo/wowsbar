<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Wed, 20 Sep 2023 09:06:45 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import PageHeading from '@/Components/Headings/PageHeading.vue'
import { capitalize } from "@/Composables/capitalize.ts"
import Tabs from '@/Components/Navigation/Tabs.vue';
import {computed, ref} from 'vue';
import {useTabChange} from '@/Composables/tab-change.js';
import TableCustomerHistories from '@/Components/Tables/TableCustomerHistories.vue';
import PortfolioDashboard from "@/Components/Dashboard/PortfolioDashboard.vue";
import {faTransporter2
} from '@fal'
import {library} from "@fortawesome/fontawesome-svg-core";
import TablePayments from "@/Components/Tables/TablePayments.vue";

library.add(faTransporter2)

const props = defineProps <{
    pageHead: object
    title: string
    data: object
}>()


</script>

<template layout="CustomerApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"></PageHeading>
    <TablePayments :data="data" tab="dashboard"></TablePayments>
</template>
