<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Thu, 13 Jul 2023 22:19:35 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Head} from '@inertiajs/vue3';
import PageHeading from '@/Components/Headings/PageHeading.vue';
import TableWebsites from "@/Pages/Tables/TableWebsites.vue";
import { capitalize } from "@/Composables/capitalize"

const props = defineProps <{
    pageHead: object
    title: string
    data:object
}>()
</script>

<template layout="App">
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead"></PageHeading>
    <TableWebsites :data="data" />
</template>

