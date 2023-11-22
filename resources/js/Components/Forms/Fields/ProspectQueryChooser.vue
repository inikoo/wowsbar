<script setup lang='ts'>
import { ref, watch, onMounted } from 'vue'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
import ProspectQueries from '@/Components/Forms/Fields/ProspectQueries.vue'
import ProspectQueryBuilder from '@/Components/Forms/Fields/ProspectQuery/ProspectQueryBuilder.vue'
import ProspectSelect from '@/Components/Forms/Fields/ProspectsSelect.vue'
import { notify } from "@kyvg/vue3-notification"
import axios from "axios"

const props = defineProps<{
    form: {
        [key: string]: {
            recipient_builder_type: string  // query | custom | prospects
            recipient_builder_data: {
                query: number
                custom: {}
                prospects: {}
            }
        }
    }
    fieldName: string  // "query"
    fieldData: {

    }
    options: {
        query: {}
        custom: {}
        prospects: {}
        [key: string]: {}
    }
}>()

const selectedIndex = ref(0)
const recipientsCount = ref(0)

const categories = [
    {
        name: 'query',
        fieldName: "recipients",
        label: 'Query',
        component: ProspectQueries,
        options : props.options["query"]
    },
    {
        name: 'custom',
        fieldName: ["recipients", 'recipient_builder_data', 'custom'],
        label: 'Custom',
        component: ProspectQueryBuilder,
        options : {
            use : ["tags","contact"],
            ...props.options["custom"]
            
        }
    },
    {
        name: 'prospects',
        fieldName: ["recipients", 'recipient_builder_data', 'prospects'],
        label: 'Prospects',
        component: ProspectSelect,
        options : props.options["prospects"]
    },
]


const getTagsOptions = async () => {
        try {
            const formData = props.form.data(); // Assuming props.form.data() retrieves the form data

            const response = await axios.post(
                route('org.crm.shop.prospects.mailshots.estimated-recipients', route().params),
                formData // Sending form data as part of the POST request
            );

            // Assuming recipientsCount is a variable or element to store the response data
            recipientsCount.value = response.data; // Set the received data to recipientsCount

        } catch (error) {
            console.error(error); // Log the error for debugging purposes

            // Notify user about the failure
            notify({
                title: "Failed",
                text: "Failed to count recipients",
                type: "error"
            });
        }
}

const changeTab=(tabIndex : number)=>{
    props.form[props.fieldName].recipient_builder_type = categories[tabIndex].name
    selectedIndex.value = tabIndex
}

watch(props.form.recipients,getTagsOptions, {deep: true})


onMounted(() => {
    const pathname = location.search
    if (pathname) {
        props.form[props.fieldName].recipient_builder_type = "custom"
        selectedIndex.value = 1
    }
})

</script>

<template>
    <div class="w-full px-2 sm:px-0">
        <TabGroup manual @change="changeTab"  :selectedIndex="selectedIndex">
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
                <div style="margin-left: auto;">
                    <button class="whitespace-nowrap border-b-2 py-1.5 px-1 text-sm focus:ring-0 focus:outline-none border-transparent text-org-500  font-semibold">
                      Total recipients :  {{ recipientsCount }}
                    </button>
                </div>
            </TabList>

            <TabPanels class="mt-2">
                <TabPanel v-for="(category, categoryIndex) in categories" :key="categoryIndex"
                    class="rounded bg-gray-50 p-3 ring-2 ring-gray-200 focus:outline-none">
                    <component :is="category.component"
                        :form="form"
                        :fieldName="category.fieldName"
                        :tabName="category.name"
                        :fieldData="fieldData"
                        :options="category.options"
                    />
                </TabPanel>
            </TabPanels>
        </TabGroup>
    </div>
    <!-- {{ options }} -->
    <!-- <pre>{{ form[fieldName] }}</pre> -->
 <!--    <pre>{{ form }}</pre> -->
</template>