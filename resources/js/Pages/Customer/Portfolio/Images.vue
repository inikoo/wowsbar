<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 19 Sep 2023 13:37:29 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Head} from '@inertiajs/vue3';
import PageHeading from '@/Components/Headings/PageHeading.vue';
import { capitalize } from "@/Composables/capitalize"
import {faRectangleWide} from '@fal'
import {library} from "@fortawesome/fontawesome-svg-core";
import TableUploadedImages from "@/Components/Tables/TableUploadedImages.vue";
library.add(faRectangleWide)

const props = defineProps <{
    pageHead: object
    title: string
    data:object
}>()
</script>

<template layout="CustomerApp">
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead"></PageHeading>
    <TableUploadedImages :data="data"  :is-select-image="false" />
</template>

