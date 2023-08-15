<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 15 Aug 2023 16:06:13 Malaysia Time, Pantai Lembeng, Bali
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { capitalize } from "@/Composables/capitalize"


const props = defineProps<{
    title: string,
}>()


</script>

<template layout="OrgApp">
    <Head :title="capitalize(title)" />


</template>
