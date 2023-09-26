<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 11 Sep 2023 14:50:05 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PageHeading from '@/Components/Headings/PageHeading.vue';
import { capitalize } from "@/Composables/capitalize"
import Stats from "@/Components/DataDisplay/Stats.vue";


const props = defineProps<{
    title: string,
    pageHead: any,
    stats: object,
}>()
</script>


<template layout="OrgApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"></PageHeading>
    <stats class="p-4" :stats="stats"/>
</template>
