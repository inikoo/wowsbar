<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Wed, 09 Aug 2023 11:25:44 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Head} from '@inertiajs/vue3';
import PageHeading from '@/Components/Headings/PageHeading.vue';
import { capitalize } from "@/Composables/capitalize"
import {faWindowMaximize} from "@/../private/pro-light-svg-icons"
import {library} from "@fortawesome/fontawesome-svg-core";
import TableImages from "@/Pages/Tables/TableImages.vue";
library.add(faWindowMaximize)

const props = defineProps <{
    pageHead: object
    title: string
    data:object
}>()
</script>

<template layout="App">
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead"></PageHeading>
    <TableImages :data="data" />
</template>

