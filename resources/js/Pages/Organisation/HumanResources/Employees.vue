<script setup lang="ts">
import { reactive } from 'vue'
import { Head } from '@inertiajs/vue3'
import PageHeading from '@/Components/Headings/PageHeading.vue'
import { capitalize } from "@/Composables/capitalize"
import TableEmployees from "@/Components/Tables/TableEmployees.vue"
import EmployeesUpload from '@/Components/Employee/EmployeesUpload.vue'
import { Link } from '@inertiajs/vue3'
import Button from '@/Components/Elements/Buttons/Button.vue'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faUpload } from '@fas/'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faUpload)

const props = defineProps <{
    pageHead: any
    title: string
    data: object
}>()

const dataModal = reactive({
    isModalOpen: false
})
</script>

<template layout="OrgApp">
    <Head :title="capitalize(title)" />

    <PageHeading :data="pageHead">
        <template #button>
            <div v-if="pageHead.actions[0].type === 'buttonGroup'" class="first:rounded-l last:rounded-r overflow-hidden ring-1 ring-gray-300 flex">
                <template v-for="(button, index) in pageHead.actions[0].buttons">
                    <Button v-if="index == 0" @click="dataModal.isModalOpen = true" :style="button.style" :label="button.label" :icon="button.icon"
                        class="relative capitalize items-center rounded-none text-sm border-none font-medium shadow-sm focus:ring-transparent focus:ring-offset-transparent focus:ring-0">
                        <FontAwesomeIcon icon='fas fa-upload' class='' aria-hidden='true' />
                        <div class="absolute inset-0 w-full flex items-center justify-center" />
                    </Button>
                    
                    <Link v-else
                        :href="`${route(button.route.name, button.route.parameters)}`" class=""
                        :method="button.method ?? 'get'"
                    >
                        <Button :style="button.style" :label="button.label" :icon="button.icon"
                            class="capitalize inline-flex items-center h-full rounded-none text-sm border-none font-medium shadow-sm focus:ring-transparent focus:ring-offset-transparent focus:ring-0">
                        </Button>
                    </Link>
                </template>
            </div>
        </template>      
    </PageHeading>
    
    <TableEmployees :data="data" />

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
