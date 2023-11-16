<script setup lang="ts">
import Multiselect from "@vueform/multiselect"
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { faExclamationCircle, faCheckCircle } from '@fas/'
import { library } from "@fortawesome/fontawesome-svg-core"
import { ref } from 'vue'
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import { ChevronUpIcon } from '@heroicons/vue/20/solid'
import PureMultiselect from '@/Components/Pure/PureMultiselect.vue'
import PureInput from '@/Components/Pure/PureInput.vue'
import descriptor from './descriptor'
library.add(faExclamationCircle, faCheckCircle)

const props = defineProps<{
    form?: any
    fieldName: any
    options: string[] | object
    fieldData?: {
        placeholder?: string
        required?: boolean
        mode?: string
		searchable?: boolean
    }
}>()

console.log(props)

props.form.query_builder = descriptor.defaultValue

</script>
  
<template>
    <div>
        <div class="mb-4">
            <div class="flex flex-wrap items-center">
                <div v-for="(query,index) in descriptor.QueryLists" :key="query" class="flex items-center mr-4 mb-2 ">
                    <div class="p-2 border border-solid border-blue-500 rounded-lg">
                        <input type="checkbox" :id="'query_' + query" :value="query" v-model="form[fieldName].query"
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                        <label :for="'query_' + query" class="ml-2">{{ query }}</label>
                    </div>

                </div>
            </div>

        </div>
        <div v-if="form[fieldName].query.length">
            <Disclosure v-slot="{ open }">
                <DisclosureButton
                    class="flex w-full justify-between rounded-lg bg-purple-100 px-4 py-2 text-left text-sm font-medium text-purple-900 hover:bg-purple-200 focus:outline-none focus-visible:ring focus-visible:ring-purple-500/75">
                    <span>Tag</span>
                    <ChevronUpIcon :class="open ? 'rotate-180 transform' : ''" class="h-5 w-5 text-purple-500" />
                </DisclosureButton>
                <DisclosurePanel class="px-4  pb-2 text-sm text-gray-500">
                    <div class="mb-4">
                        <div>
                            <fieldset class="mt-4">
                                <legend class="sr-only">Notification method</legend>
                                <div class="space-y-4 sm:flex sm:items-center sm:space-x-10 sm:space-y-0">
                                    <div v-for="filter in descriptor.FilterTags" :key="filter" class="flex items-center">
                                        <input :id="filter" name="notification-method" type="radio" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600" v-model="form[fieldName].tag.filter" />
                                        <label :for="filter" class="ml-3 block text-xs font-medium leading-6 text-gray-900">{{ filter }}</label>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div>
                        <Multiselect mode="tags" placeholder="Select the tag" valueProp="name" trackBy="name" label="name"
                            :close-on-select="false" :searchable="true" :create-option="true" :caret="false"
                            noResultsText="No one left. Type to add new one." appendNewTag v-model="form[fieldName].tag.tag">
                        </Multiselect>
                    </div>

                </DisclosurePanel>
            </Disclosure>
            <Disclosure as="div" class="mt-2" v-slot="{ open }">
                <DisclosureButton
                    class="flex w-full justify-between rounded-lg bg-purple-100 px-4 py-2 text-left text-sm font-medium text-purple-900 hover:bg-purple-200 focus:outline-none focus-visible:ring focus-visible:ring-purple-500/75">
                    <span>Last Contact</span>
                    <ChevronUpIcon :class="open ? 'rotate-180 transform' : ''" class="h-5 w-5 text-purple-500" />
                </DisclosureButton>
                <DisclosurePanel class="px-4 pt-4 pb-2 text-sm text-gray-500">
                    <div>
                        <Multiselect placeholder="Select contact" noResultsText="No one left. Type to add new one." :options="descriptor.contact" v-model="form[fieldName].last_contact.filter"></Multiselect>
                    </div>
                   
                    <div v-if="form[fieldName].last_contact.filter == 'Last Contact'" class="flex flex-col gap-y-2 mt-4">
                        <div class="flex gap-x-2">
                            <div class="w-20">
                                <PureInput type="number" :minValue="1" :caret="false" placeholder="7" v-model="form[fieldName].last_contact.data.count"/>
                            </div>
                            <div class="w-full">
                                <PureMultiselect :options="['day', 'week', 'month']" required v-model="form[fieldName].last_contact.data.range" />
                            </div>
                        </div>
                    </div>
                </DisclosurePanel>
            </Disclosure>
        </div>
    </div>
</template>
  
<style></style>
