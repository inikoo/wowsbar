
<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 26 Sep 2023 12:45:20 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PageHeading from '@/Components/Headings/PageHeading.vue';
import { capitalize } from "@/Composables/capitalize"
import TableProducts from "@/Components/Tables/TableProducts.vue";

const props = defineProps <{
    pageHead: object
    title: string
    data: object
}>()
</script>

<template layout="OrgApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"></PageHeading>
    <TableProducts :data="data"/>
</template>

