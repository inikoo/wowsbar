<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Wed, 14 Sept 2022 02:00:03 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Raul A Perusquia Flores
  -->
<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { reactive } from 'vue'
import PageHeading from '@/Components/Headings/PageHeading.vue'
import { capitalize } from "@/Composables/capitalize"
import TableGuests from "@/Components/Tables/TableGuests.vue"
import ButtonGroup from '@/Components/Elements/Buttons/ButtonGroup.vue'
import UploadExcel from '@/Components/Upload/UploadExcel.vue'

import { PageHeading as TSPageHeading } from '@/types/PageHeading'
import { routeType } from '@/types/route'

const props = defineProps <{
    pageHead: TSPageHeading
    title: string
    data: object
    uploads: {
        templates: {
            routes: routeType
        }
        channel: string
        event: string
    }
}>()

// To handle Modal on click 'upload' button
const dataModal = reactive({
    isModalOpen: false
})
</script>

<template layout="OrgApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead">
        <template v-if="pageHead.actions[0].type === 'buttonGroup'" #button>
            <ButtonGroup :dataButton="pageHead.actions[0].buttons" :dataModal="dataModal" />
        </template>
    </PageHeading>

    <TableGuests :data="data" />

    <!-- Modal: after click 'upload' button -->
    <UploadExcel
        :propName="title"
        :routesModalUpload="{
            upload: props.pageHead.actions[0].buttons[0].route,
            download: uploads?.templates?.routes
        }"
        :dataModal="dataModal"
        :dataPusher="{
            channel: uploads.channel,
            event: uploads.event
        }"
    />
</template>


