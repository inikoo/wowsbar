<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sat, 07 Oct 2023 21:48:07 Malaysia Time, Sanur, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup  lang="ts">
import {Head} from '@inertiajs/vue3';
import PageHeading from '@/Components/Headings/PageHeading.vue';
import TableCustomers from '@/Components/Tables/TableCustomers.vue';
import { capitalize } from "@/Composables/capitalize"

const props = defineProps<{
    data: object
    title: string
    pageHead: object
}>()

</script>

<template layout="OrgApp">
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead"></PageHeading>
    <TableCustomers :data="data" />
</template>
