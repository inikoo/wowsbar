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
            } | number[]
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
        fieldName: ["query", 'dataTab', 'custom'],
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
        <TabGroup @change="(tabIndex) => form[fieldName].recipient_builder_type = categories[tabIndex].name">
            <TabList class="flex space-x-1 rounded-lg bg-org-500 p-1">
                <Tab v-for="(category, categoryIndex) in categories" as="template" :key="categoryIndex"
                    v-slot="{ selected }">
                    <button :class="[
                        'w-full rounded-lg py-2.5 text-sm font-medium leading-5',
                        'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2',
                        selected
                            ? 'bg-white text-blue-700 shadow'
                            : 'text-blue-100 hover:bg-white/[0.12] hover:text-white',
                    ]">
                        {{ category.label }}
                    </button>
                </Tab>
            </TabList>

            <TabPanels class="mt-2">
                <TabPanel v-for="(category, categoryIndex) in categories" :key="categoryIndex"
                    class="rounded bg-gray-100 p-3 ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none">
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