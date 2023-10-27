<script setup lang="ts">
import { reactive } from 'vue'
import { Head } from '@inertiajs/vue3'
import PageHeading from '@/Components/Headings/PageHeading.vue'
import { capitalize } from "@/Composables/capitalize"
import TableEmployees from "@/Components/Tables/TableEmployees.vue"
import UploadExcel from '@/Components/Upload/UploadExcel.vue'
import ButtonGroup from '@/Components/Elements/Buttons/ButtonGroup.vue'
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
            <ButtonGroup
                :dataButton="pageHead.actions[0].buttons"
                :dataModal="dataModal"
            />
        </template>      
    </PageHeading>
    
    <TableEmployees :data="data" />

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
