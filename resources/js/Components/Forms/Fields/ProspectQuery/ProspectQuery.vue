<script setup lang="ts">
import Multiselect from "@vueform/multiselect"
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { faChevronCircleLeft } from '@fas/'
import { faInfoCircle } from '@far/'
import { library } from "@fortawesome/fontawesome-svg-core"
import { ref, onMounted } from 'vue'
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import { ChevronUpIcon } from '@heroicons/vue/20/solid'
import PureMultiselect from '@/Components/Pure/PureMultiselect.vue'
import PureInput from '@/Components/Pure/PureInput.vue'
import descriptor from './descriptor'
import axios from "axios"
import Tag from "@/Components/Tag.vue"
import { notify } from "@kyvg/vue3-notification"

library.add(faChevronCircleLeft,faInfoCircle)

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

const tagsOptions = ref([])

if(!props.form[props.fieldName]) props.form.query_builder = descriptor.defaultValue
const getTagsOptions = async () => {
    try {
        const response = await axios.get(
            route('org.json.tags'),
        )
        tagsOptions.value = Object.values(response.data)
    } catch (error) {
        notify({
            title: "Failed",
            text: "failed to get data, Tags, please reload you page",
            type: "error"
        });
    }
}

onMounted(() => {
    getTagsOptions()
})

console.log(props.form)

</script>
  
<template>
    <div>
        <div class="mb-4">
            <div class="flex flex-wrap items-center">
                <div v-for="(query, index) in descriptor.QueryLists" :key="query" class="flex items-center mr-4 mb-2 ">
                    <div class="py-[4px] px-2.5 border border-solid border-gray-300 rounded-lg">
                        <input type="checkbox" :id="'query_' + query" :value="query" v-model="form[fieldName].query"
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                        <label :for="'query_' + query" class="ml-2">{{ query }}</label>
                    </div>

                </div>
            </div>

        </div>
        <div v-if="form[fieldName].query.length">
            <Disclosure v-slot="{ open }" :defaultOpen="true">
                <DisclosureButton
                    class="flex w-full justify-between rounded-lg bg-purple-100 px-4 py-2 text-left text-sm font-medium text-purple-900 hover:bg-purple-200 focus:outline-none focus-visible:ring focus-visible:ring-purple-500/75">
                    <span>Tags</span>
                    <div class="flex gap-2">
                        <VTooltip>
                            <font-awesome-icon :icon="['far', 'info-circle']" />

                            <template #popper>
                                filter deliveries based on seo tags
                            </template>
                        </VTooltip>
                   <!--      <font-awesome-icon :icon="['fas', 'chevron-circle-left']" :class="open ? '-rotate-90 transform' : ''" class="h-4 w-4 text-purple-500"/> -->
                    </div>
                </DisclosureButton>
                <DisclosurePanel class="px-4  pb-2 text-sm text-gray-500">
                    <div class="mb-4">
                        <div>
                            <fieldset class="mt-4">
                                <div class="space-y-4 sm:flex sm:items-center sm:space-x-10 sm:space-y-0">
                                    <div v-for="filter in descriptor.FilterTags" :key="filter" class="flex items-center">
                                        <input :id="filter" name="notification-method" type="radio" :value="filter"
                                            class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600"
                                            v-model="form[fieldName].tag.state" />
                                        <label :for="filter"
                                            class="ml-3 block text-xs font-medium leading-6 text-gray-900">{{ filter
                                            }}</label>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div>
                        <Multiselect v-model="form[fieldName].tag.tags" mode="tags" placeholder="Select the tag"
                            valueProp="slug" trackBy="name" label="name" :close-on-select="false" :searchable="true"
                            :caret="false" :options="tagsOptions" noResultsText="No one left. Type to add new one.">

                            <template
                                #tag="{ option, handleTagRemove, disabled }: { option: tag, handleTagRemove: Function, disabled: boolean }">
                                <div class="px-0.5 py-[3px]">
                                    <Tag :theme="option.id" :label="option.name" :closeButton="true" :stringToColor="true"
                                        size="sm" @onClose="(event) => handleTagRemove(option, event)" />
                                </div>
                            </template>
                        </Multiselect>
                    </div>

                </DisclosurePanel>
            </Disclosure>
            <Disclosure as="div" class="mt-2" v-slot="{ open }" :defaultOpen="true">
                <DisclosureButton
                    class="flex w-full justify-between rounded-lg bg-purple-100 px-4 py-2 text-left text-sm font-medium text-purple-900 hover:bg-purple-200 focus:outline-none focus-visible:ring focus-visible:ring-purple-500/75">
                    <span>Last Contact</span>
                    <div class="flex gap-2">
                        <VTooltip>
                            <font-awesome-icon :icon="['far', 'info-circle']" />

                            <template #popper>
                                filter deliveries based on the last contact with the customer
                            </template>
                        </VTooltip>
                   <!--      <font-awesome-icon :icon="['fas', 'chevron-circle-left']" :class="open ? '-rotate-90 transform' : ''" class="h-4 w-4 text-purple-500"/> -->
                    </div>
                </DisclosureButton>
                <DisclosurePanel class="px-4 pt-4 pb-2 text-sm text-gray-500">
                    <div>
                        <Multiselect placeholder="Select contact" :allowEmpty="false" :options="descriptor.contact"  valueProp="value" trackBy="label" label="label"
                            v-model="form[fieldName].last_contact.state" :can-clear="false"></Multiselect>
                    </div>

                    <div v-if="form[fieldName].last_contact.state" class="flex flex-col gap-y-2 mt-4">
                        <div class="flex gap-x-2">
                            <div class="w-20">
                                <PureInput type="number" :minValue="1" :caret="false" placeholder="7"
                                    v-model="form[fieldName].last_contact.data.quantity" />
                            </div>
                            <div class="w-full">
                                <Multiselect :options="['day', 'week', 'month']" placeholder="Pick a range"
                                    v-model="form[fieldName].last_contact.data.unit" :can-clear="false" />
                            </div>
                        </div>
                    </div>
                </DisclosurePanel>
            </Disclosure>
        </div>
    </div>
</template>
  
<style lang="scss">
.multiselect-tags-search {
    @apply focus:outline-none focus:ring-0
}

.multiselect.is-active {
    @apply shadow-none
}

// .multiselect-tag {
//     @apply bg-gradient-to-r from-lime-300 to-lime-200 hover:bg-lime-400 ring-1 ring-lime-500 text-lime-600
// }

.multiselect-tags {
    @apply m-0.5
}

.multiselect-tag-remove-icon {
    @apply text-lime-800
}
</style>
