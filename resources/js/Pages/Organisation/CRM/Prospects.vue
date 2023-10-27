<!--
  - Author: Jonathan Lopez Sanchez <jonathan@ancientwisdom.biz>
  - Created: Tue, 28 Feb 2023 10:07:36 Central European Standard Time, Malaga, Spain
  - Copyright (c) 2023, Inikoo LTD
  -->

<script setup lang="ts">
import { Head } from "@inertiajs/vue3"
import PageHeading from "@/Components/Headings/PageHeading.vue"
import TableProspects from "@/Components/Tables/TableProspects.vue"
import { capitalize } from "@/Composables/capitalize"
import { reactive } from "vue"
import ButtonGroup from '@/Components/Elements/Buttons/ButtonGroup.vue'
import UploadExcel from '@/Components/Upload/UploadExcel.vue'

import { PageHeading as TSPageHeading } from '@/types/PageHeading'
import { routeType } from "@/types/route"

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

    <TableProspects :data="data" />

    <!-- Modal: after click 'upload' button -->
    <UploadExcel
        :routesModalUpload="{
            upload: props.pageHead.actions[0].buttons[0].route,
            download: uploads.templates.routes
        }"
        :dataModal="dataModal"
        :dataPusher="{
            channel: uploads.channel,
            event: uploads.event
        }"
    />
</template>

