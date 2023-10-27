<script setup lang="ts">
import { reactive } from 'vue'
import { Head } from '@inertiajs/vue3'
import PageHeading from '@/Components/Headings/PageHeading.vue'
import { capitalize } from "@/Composables/capitalize"
import TableEmployees from "@/Components/Tables/TableEmployees.vue"
import EmployeesUpload from '@/Components/Employee/EmployeesUpload.vue'
import { Link } from '@inertiajs/vue3'
import ButtonGroup from '@/Components/Elements/Buttons/ButtonGroup.vue'


const props = defineProps <{
    pageHead: any
    title: string
    data: object
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
    <EmployeesUpload
        :routesModalUpload="{
            upload: {
                name: props.pageHead.actions[0].buttons[0].route.name
            },
            download: {
                name: 'org.hr.employee.uploads.template.download'
            }
        }"
        :dataModal="dataModal"
    />
</template>
