<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Thu, 15 Sept 2022 20:33:56 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Head} from '@inertiajs/vue3';
import PageHeading from '@/Components/Headings/PageHeading.vue';
// import TableClockingMachines from "@/Pages/Tables/TableClockingMachines.vue";
import { capitalize } from "@/Composables/capitalize"

const props = defineProps <{
    pageHead: object
    title: string
    data: object
}>()
</script>

<template layout="OrgApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"></PageHeading>
    <!-- <TableClockingMachines :data="data" /> -->
</template>

