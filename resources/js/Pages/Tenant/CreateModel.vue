<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 14 Aug 2023 10:04:57 Malaysia Time, Pantai Lembeng, Bali
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import PageHeading from '@/Components/Headings/PageHeading.vue'
import { capitalize } from "@/Composables/capitalize"
import Create from "@/Components/Create.vue";

const props = defineProps<{
    title: string,
    pageHead: object,
    formData: {
        blueprint: object;
        route: {
            name: string,
            arguments?: Array<string>
        };
    }
}>()


</script>

<template layout="App">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"></PageHeading>
    <Create :formData="formData"/>
</template>

