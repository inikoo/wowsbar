<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 20 Jun 2023 20:46:53 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import PageHeading from "@/Components/Headings/PageHeading.vue"
import { capitalize } from "@/Composables/capitalize"
import TableUploads from "@/Components/Tables/TableUploads.vue"

import { faImages } from '@fal'
import { library } from '@fortawesome/fontawesome-svg-core'
import TableUploadRecords from "@/Components/Tables/TableUploadRecords.vue";
library.add(faImages)

const props = defineProps<{
    title: string
    pageHead: object
    data?: object


}>()

</script>

<template layout="OrgApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"></PageHeading>
    <TableUploadRecords :tab="'upload_histories'" :data="data"></TableUploadRecords>
</template>
