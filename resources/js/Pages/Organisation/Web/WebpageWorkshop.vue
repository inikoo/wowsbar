<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Thu, 14 Sep 2023 10:16:43 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import PageHeading from '@/Components/Headings/PageHeading.vue'
import { capitalize } from "@/Composables/capitalize"

const props = defineProps<{
    title: string,
    pageHead: object
}>()


</script>


<template layout="OrgApp">
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead"></PageHeading>
</template>

