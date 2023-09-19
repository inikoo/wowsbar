<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 19 Sep 2023 13:37:29 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import PageHeading from '@/Components/Headings/PageHeading.vue'
import { capitalize } from "@/Composables/capitalize"
import { library } from "@fortawesome/fontawesome-svg-core"
import { faGlobe,faImage } from "@/../private/pro-light-svg-icons"
import TableBanners from "@/Components/Tables/TableBanners.vue"

library.add(faGlobe,faImage)

defineProps<{
    pageHead: object
    title: string
    data: object
}>()

const isOpen = ref(false)
</script>

<template layout="CustomerApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"></PageHeading>
    <TableBanners :data="data" />
</template>

