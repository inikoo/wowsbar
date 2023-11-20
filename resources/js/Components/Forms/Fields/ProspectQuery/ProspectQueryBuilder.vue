<script setup lang="ts">
import Multiselect from "@vueform/multiselect"
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { faInfoCircle } from '@far/'
import { library } from "@fortawesome/fontawesome-svg-core"
import { ref, onMounted, watch, reactive } from 'vue'
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import PureInput from '@/Components/Pure/PureInput.vue'
import descriptor from './descriptor'
import axios from "axios"
import Tag from "@/Components/Tag.vue"
import { notify } from "@kyvg/vue3-notification"
import { get, set, isArray, isNull } from 'lodash'
import { faExclamationCircle, faCheckCircle, faChevronCircleLeft } from '@fas/';

library.add(faChevronCircleLeft, faInfoCircle, faExclamationCircle, faCheckCircle)

const props = withDefaults(defineProps<{
    form?: any
    fieldName: string
    tabName: string
    options: string[] | object
    fieldData?: {
        placeholder?: string
        required?: boolean
        mode?: string
        searchable?: boolean
    }
}>(), {
    options : {
        use: ['filter', "tags", "contact"],
    }

})


const tagsOptions = ref([])

/* get tags option */
const getTagsOptions = async () => {
    try {
        const response = await axios.get(
            route('org.json.tags'),
        )
        tagsOptions.value = Object.values(response.data)
    } catch (error) {
        notify({
            title: "Failed",
            text: "Failed to get data, Tags, please reload you page",
            type: "error"
        });
    }
}

const emits = defineEmits();


const setFormValue = (data, fieldName) => {
    if (isArray(fieldName)) {  /* if fieldName array */
        if (get(data, fieldName)) return get(data, fieldName, descriptor.defaultValue);  /* Chek if data null or undefined or has a objecjt*/
        else return descriptor.defaultValue
    } else return get(data, fieldName, descriptor.defaultValue); /* if fieldName string */

};

const value = reactive(setFormValue(props.form, props.fieldName));/* get value from form */


/* set form when data changes */
const updateFormValue = (newValue) => {
    let target = props.form;
    set(target, props.fieldName, newValue);
    emits("update:form", target);
};

watch(value, (newValue) => {
    updateFormValue(newValue);
});

onMounted(() => {
    getTagsOptions()
})



</script>
  
<template>
    <div>
        <Disclosure v-if="options.use.includes('filter')" as="div" class="mt-2" v-slot="{ open }" :defaultOpen="true">
            <DisclosureButton
                class="flex w-full justify-between rounded-lg bg-purple-100 px-4 py-2 text-left text-sm font-medium text-purple-900 hover:bg-purple-200 focus:outline-none focus-visible:ring focus-visible:ring-purple-500/75">
                <span>Prospects filter</span>
                <div class="flex gap-2">
                    <VTooltip>
                        <font-awesome-icon :icon="['far', 'info-circle']" />

                        <template #popper>
                            filter deliveries based on the prospect
                        </template>
                    </VTooltip>
                    <!--      <font-awesome-icon :icon="['fas', 'chevron-circle-left']" :class="open ? '-rotate-90 transform' : ''" class="h-4 w-4 text-purple-500"/> -->
                </div>
            </DisclosureButton>
            <DisclosurePanel class="px-4 pt-2 pb-2 text-sm text-gray-500">
                    <div class="flex flex-wrap items-center">
                        <div v-for="(query, index) in descriptor.QueryLists" :key="query.value"
                            class="flex items-center mr-4 mb-2 py-[4px] px-2.5 border border-solid border-gray-300 rounded-lg">
                            <input type="checkbox" v-model="value.query" :id="'query_' + query.value"
                                :key="'query_' + query.value" :value="query.value"
                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                            <label :for="'query_' + query.value" class="ml-2">{{ query.label }}</label>
                        </div>
                        <div class="flex items-center mr-4 mb-2 py-[4px] px-2.5">
                            <FontAwesomeIcon v-if="get(form, ['errors', `${fieldName}.query`])"
                                icon="fas fa-exclamation-circle" class="h-5 w-5 text-red-500" aria-hidden="true" />
                            <FontAwesomeIcon v-if="form.recentlySuccessful" icon="fas fa-check-circle"
                                class="h-5 w-5 text-green-500" aria-hidden="true" />
                            <FontAwesomeIcon v-if="form.processing" icon="fad fa-spinner-third"
                                class="h-5 w-5 animate-spin" />
                        </div>
                    </div>
                    <p v-if="get(form, ['errors', `${fieldName}.query`])" class="mt-2 text-sm text-red-600"
                        :id="`${fieldName}-error`">
                        {{ form.errors[`${fieldName}.query`] }}
                    </p>
            </DisclosurePanel>
        </Disclosure>
            <Disclosure v-slot="{ open }" :defaultOpen="true" v-if="options.use.includes('tags')">
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
                <DisclosurePanel class="px-4 text-sm text-gray-500">
                    <div class="mt-2">
                        <Multiselect v-model="value.tag.tags" mode="tags" placeholder="Select the tag" valueProp="slug"
                            trackBy="name" label="name" :close-on-select="false" :searchable="true" :caret="false"
                            :options="tagsOptions" noResultsText="No one left. Type to add new one.">

                            <template
                                #tag="{ option, handleTagRemove, disabled }: { option: tag, handleTagRemove: Function, disabled: boolean }">
                                <div class="px-0.5 py-[3px]">
                                    <Tag :theme="option.id" :label="option.name" :closeButton="true" :stringToColor="true"
                                        size="sm" @onClose="(event) => handleTagRemove(option, event)" />
                                </div>
                            </template>
                        </Multiselect>
                        <p v-if="get(form, ['errors', `${fieldName}.tag.tags`])" class="mt-2 text-sm text-red-600"
                            :id="`${fieldName}-error`">
                            {{ form.errors[`${fieldName}.tag.tags`] }}
                        </p>
                    </div>
                    <div v-if="value.tag.tags.length > 1" class="mb-4">
                        <div class="mt-1">
                            <fieldset>
                                <div class="space-y-4 sm:flex sm:items-center sm:space-x-10 sm:space-y-0">
                                    <div v-for="(filter, filterIndex) in descriptor.FilterTags" :key="filter.value"
                                        class="flex items-center">
                                        <input :id="filter.value" name="notification-method" type="radio"
                                            :value="filter.value"
                                            class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600"
                                            v-model="value.tag.state" />
                                        <label :for="filter.value"
                                            class="ml-3 block text-xs font-medium leading-6 text-gray-900">{{ filter.label
                                            }}</label>
                                    </div>
                                    <div class="flex items-center mr-4 mb-2 py-[4px] px-2.5">
                                        <FontAwesomeIcon v-if="get(form, ['errors', `${fieldName}.tag.state`])"
                                            icon="fas fa-exclamation-circle" class="h-5 w-5 text-red-500"
                                            aria-hidden="true" />
                                        <FontAwesomeIcon v-if="form.recentlySuccessful" icon="fas fa-check-circle"
                                            class="h-5 w-5 text-green-500" aria-hidden="true" />
                                        <FontAwesomeIcon v-if="form.processing" icon="fad fa-spinner-third"
                                            class="h-5 w-5 animate-spin" />
                                    </div>
                                </div>
                                <p v-if="get(form, ['errors', `${fieldName}.tag.state`])" class="mt-2 text-sm text-red-600"
                                    :id="`${fieldName}-error`">
                                    {{ form.errors[`${fieldName}.tag.state`] }}
                                </p>
                            </fieldset>
                        </div>
                    </div>

                </DisclosurePanel>
            </Disclosure>
            <Disclosure as="div" class="mt-2" v-slot="{ open }" :defaultOpen="true" v-if="options.use.includes('contact')">
                <DisclosureButton
                    class="flex w-full justify-between rounded-lg bg-purple-100 px-4 py-2 text-left text-sm font-medium text-purple-900 hover:bg-purple-200 focus:outline-none focus-visible:ring focus-visible:ring-purple-500/75">
                    <span>Tags last contact</span>
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
                <DisclosurePanel class="px-4 pt-2 pb-2 text-sm text-gray-500">
                    <div>
                        <Multiselect placeholder="Select contact" :allowEmpty="false" :options="descriptor.contact"
                            valueProp="value" trackBy="label" label="label" v-model="value.last_contact.state"
                            :can-clear="false"></Multiselect>
                        <p v-if="get(form, ['errors', `${fieldName}.last_contact.state`])" class="mt-2 text-sm text-red-600"
                            :id="`${fieldName}-error`">
                            {{ form.errors[`${fieldName}.last_contact.state`] }}
                        </p>
                    </div>

                    <div v-if="value.last_contact.state" class="flex flex-col gap-y-2 mt-4">
                        <div class="flex gap-x-2">
                            <div class="w-20">
                                <PureInput type="number" :minValue="1" :caret="false" placeholder="7"
                                    v-model="value.last_contact.data.quantity" />
                                <p v-if="get(form, ['errors', `${fieldName}.last_contact.data.quantity`])"
                                    class="mt-2 text-sm text-red-600" :id="`${fieldName}-error`">
                                    {{ form.errors[`${fieldName}.last_contact.data.quantity`] }}
                                </p>
                            </div>
                            <div class="w-full">
                                <Multiselect :options="['day', 'week', 'month']" placeholder="Pick a range"
                                    v-model="value.last_contact.data.unit" :can-clear="false" />
                                <p v-if="get(form, ['errors', `${fieldName}.last_contact.data.unit`])"
                                    class="mt-2 text-sm text-red-600" :id="`${fieldName}-error`">
                                    {{ form.errors[`${fieldName}.last_contact.data.unit`] }}
                                </p>
                            </div>
                        </div>
                    </div>
                </DisclosurePanel>
            </Disclosure>
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
