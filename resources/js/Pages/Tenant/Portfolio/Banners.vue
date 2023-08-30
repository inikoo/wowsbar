<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Thu, 13 Jul 2023 22:19:35 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import PageHeading from '@/Components/Headings/PageHeading.vue'
import { capitalize } from "@/Composables/capitalize"
import { library } from "@fortawesome/fontawesome-svg-core"
import { faGlobe,faImage } from "@/../private/pro-light-svg-icons"
import TableBanners from "@/Pages/Tables/TableBanners.vue"

library.add(faGlobe,faImage)

defineProps<{
    pageHead: object
    title: string
    data: object
}>()

const isOpen = ref(false)
</script>

<template layout="TenantApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"></PageHeading>
    <TableBanners :data="data" />
</template>

