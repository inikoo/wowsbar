<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Thu, 26 Oct 2023 23:03:08 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import PageHeading from '@/Components/Headings/PageHeading.vue'
import { capitalize } from "@/Composables/capitalize"
import TableTaskTypes from "@/Components/Tables/TableTaskTypes.vue";

const props = defineProps<{
    pageHead: any
    title: string
    data?: object

}>()


</script>

<template layout="OrgApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"></PageHeading>
    <TableTaskTypes :data="data" />

</template>

