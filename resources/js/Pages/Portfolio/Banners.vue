<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Thu, 13 Jul 2023 22:19:35 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Head} from '@inertiajs/vue3';
import PageHeading from '@/Components/Headings/PageHeading.vue';
import { capitalize } from "@/Composables/capitalize"
import {library} from "@fortawesome/fontawesome-svg-core";
import {faGlobe} from "@/../private/pro-light-svg-icons"
import TableBanners from "@/Pages/Tables/TableBanners.vue";
library.add(faGlobe)

const props = defineProps <{
    pageHead: object
    title: string
    data:object
}>()
</script>

<template layout="App">
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead"></PageHeading>
    <TableBanners :data="data" />
</template>

