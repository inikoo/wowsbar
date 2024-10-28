<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 02 Oct 2023 03:20:36 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import PageHeading from '@/Components/Headings/PageHeading.vue'
import { capitalize } from "@/Composables/capitalize"
import TableBanners from "@/Components/Tables/TableBanners.vue";
import TableAnnouncements from "@/Components/Tables/TableAnnouncements.vue";
import {Banner} from "@/types/banner";

const props = defineProps<{
    pageHead: {}
    title: string
    data: {}
}>()

const isModalOpen = ref(false)
</script>

<template layout="CustomerApp">
    <!-- <pre>{{ props }}</pre> -->
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"></PageHeading>

    <TableAnnouncements :data="data" />
</template>
