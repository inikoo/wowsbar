<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sun, 13 Aug 2023 09:41:55 Malaysia Time, Sanur, Bali
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { capitalize } from "@/Composables/capitalize"


const props = defineProps<{
    title: string,
}>()


</script>

<template layout="OrgApp">
    <Head :title="capitalize(title)" />
    <div class="max-w-7xl px-4 sm:px-6 lg:px-8 lg:py-6">
    </div>

</template>
