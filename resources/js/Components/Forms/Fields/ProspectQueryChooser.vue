<script setup lang='ts'>
import { ref } from 'vue'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
import ProspectQueries from '@/Components/Forms/Fields/ProspectQueries.vue'
import ProspectQueryBuilder from '@/Components/Forms/Fields/ProspectQuery/ProspectQueryBuilder.vue'

const props = defineProps<{
    form: {
        [key: string]: {
            selectedTab: string
        }
    }
    fieldName: string
    fieldData: {
        
    }
    options: {
        list: {}
        custom: {}
        [key: string]: {}
    }
}>()



const categories = [
    {
        name: 'list',
        fieldName : "query",
        label: 'List',
        component: ProspectQueries
    },
    {
        name: 'custom',
        fieldName : ["query",'dataTab','custom'],
        label: 'Custom',
        component: ProspectQueryBuilder
    },
    {
        name: 'select',
        label: 'Select',
        fieldName : "query",
        component: 'div'
    },
]



</script>

<template>
    <div class="w-full max-w-md px-2 sm:px-0">
        <TabGroup @change="(tabIndex) => form[fieldName].selectedTab = categories[tabIndex].name">
            <TabList class="flex space-x-1 rounded bg-blue-900/20 p-1">
                <Tab v-for="(category, categoryIndex) in categories" as="template" :key="categoryIndex" v-slot="{ selected }">
                    <button :class="[
                        'w-full text-sm font-medium leading-5 ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2',
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
                    class="rounded bg-gray-300 p-3 ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none"
                >
                    <component :is="category.component"
                        :form="form"
                        :fieldName="category.fieldName"
                        :tabName="category.name"
                        :fieldData="fieldData"
                        :options="options[category.name]"
                    />
                </TabPanel>
            </TabPanels>
        </TabGroup>
    </div>
    <!-- {{ options }} -->
    <!-- <pre>{{ form[fieldName] }}</pre> -->
    <!-- <pre>{{ form }}</pre> -->
</template>