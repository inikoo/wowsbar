<script setup lang='ts'>
import { ref } from 'vue'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
import ProspectQueries from '@/Components/Forms/Fields/ProspectQueries.vue'
import ProspectQueryBuilder from '@/Components/Forms/Fields/ProspectQuery/ProspectQueryBuilder.vue'

const props = defineProps<{
    form: {
        [key: string]: {
            recipient_builder_type: string  // query | custom | prospects
            recipient_builder_data: {
                query_id: number
            } | number[] | null
        }
    }
    fieldName: string
    fieldData: {

    }
    options: {
        query: {}
        custom: {}
        prospects: {}
        [key: string]: {}
    }
}>()



const categories = [
    {
        name: 'query',
        fieldName: "query",
        label: 'Query',
        component: ProspectQueries
    },
    {
        name: 'custom',
        fieldName: ["query", 'recipient_builder_data'],
        label: 'Custom',
        component: ProspectQueryBuilder
    },
    {
        name: 'prospects',
        label: 'Prospects',
        fieldName: "query",
        component: 'div'
    },
]

// Store to variable to keep data while change tab
const valueTab: { [key: string]: any } = {
    query: 0,
    custom: '',
    prospects: []
}

</script>

<template>
    <div class="w-full max-w-md px-2 sm:px-0">
        <TabGroup @change="(tabIndex) => {
                form[fieldName].recipient_builder_type = categories[tabIndex].name,
                form[fieldName].recipient_builder_data = null
        }">
            <TabList class="flex space-x-8 ">
                <Tab v-for="(category, categoryIndex) in categories" as="template" :key="categoryIndex"
                    v-slot="{ selected }">
                    <button :class="[
                        'whitespace-nowrap border-b-2 py-1.5 px-1 text-sm font-medium focus:ring-0 focus:outline-none',
                        selected
                            ? 'border-org-5s00 text-org-500'
                            : 'border-transparent text-gray-400 hover:border-gray-300',
                    ]">
                        {{ category.label }}
                    </button>
                </Tab>
            </TabList>

            <TabPanels class="mt-2">
                <TabPanel v-for="(category, categoryIndex) in categories" :key="categoryIndex"
                    class="rounded bg-gray-50 p-3 ring-2 ring-gray-200 focus:outline-none">
                    <component :is="category.component"
                        :form="form"
                        :fieldName="category.fieldName"
                        :tabName="category.name"
                        :fieldData="fieldData"
                        :options="options[category.name]"
                        :valueTab="valueTab[category.name]"
                    />
                </TabPanel>
            </TabPanels>
        </TabGroup>
    </div>
    <!-- {{ options }} -->
    <!-- <pre>{{ form[fieldName] }}</pre> -->
    <pre>{{ form }}</pre>
</template>