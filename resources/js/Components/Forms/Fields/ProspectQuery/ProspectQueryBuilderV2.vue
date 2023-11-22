<script setup lang="ts">
import { faInfoCircle } from '@far/'
import { library } from "@fortawesome/fontawesome-svg-core"
import { ref, onMounted, watch, reactive } from 'vue'
import descriptor from './descriptor'
import axios from "axios"
import { notify } from "@kyvg/vue3-notification"
import { get, set, isArray, cloneDeep } from 'lodash'
import { faExclamationCircle, faCheckCircle, faChevronDown, faChevronRight } from '@fas/';
import { faBoxOpen } from '@fal/';
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import Multiselect from "@vueform/multiselect"
import Tag from "@/Components/Tag.vue"
import PureInput from '@/Components/Pure/PureInput.vue'
import {trans} from "laravel-vue-i18n";
import { Switch, SwitchGroup, SwitchLabel } from '@headlessui/vue'

library.add(faChevronDown, faInfoCircle, faExclamationCircle, faCheckCircle, faChevronRight, faBoxOpen)

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
    options: {
        use: ['propspect_by', "tag", "last_contact"],
    }

})
const emits = defineEmits();
const sectionValue = ref([])
const schemaForm = descriptor['schemaForm'].filter((item)=>props.options.use.includes(item.name))
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


const setFormValue = (data, fieldName) => {
    if (isArray(fieldName)) {  /* if fieldName array */
        if (get(data, fieldName)) return get(data, fieldName, {});  /* Chek if data null or undefined or has a objecjt*/
        else return {}
    } else return get(data, fieldName, {}); /* if fieldName string */

};

const value = reactive(setFormValue(props.form, props.fieldName));/* get value from form */


/* set form when data changes */
const updateFormValue = (newValue) => {
    let target = props.form;
    set(target, props.fieldName, newValue);
    emits("update:form", target);
};

const changeSection = (index: Number) => {
    if (sectionValue.value.includes(index)) set(value, schemaForm[index].name, schemaForm[index].value);
    else delete value[schemaForm[index].name]
}


watch(value, (newValue) => {
    updateFormValue(newValue);
});

onMounted(() => {
    getTagsOptions()
})

console.log(value)

</script>

<template>
    <div class="flex">
        <div class="w-[20%] px-2">
            <div v-for="(sectionData, sectionIdx ) in schemaForm" :key="sectionIdx" class="relative py-1">
                <div
                    class="flex w-full justify-between rounded-lg bg-purple-100 px-4 py-2 text-left text-sm font-medium text-purple-900 hover:bg-purple-200 focus:outline-none focus-visible:ring focus-visible:ring-purple-500/75">
                    <label :for="sectionData.name" class="ml-2">{{ sectionData.label }}</label>
                    <input type="checkbox" :id="sectionData.name" :key="sectionData.name" :value="sectionIdx"
                        v-model="sectionValue" @change="changeSection(sectionIdx)"
                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                </div>
            </div>
        </div>

        <div class="w-[80%] bg-gray-50 p-4 rounded-md border border-gray-300">

           <!--  if value is null -->
            <div v-if="Object.keys(value).length === 0 && value.constructor === Object" class="flex justify-center items-center">
                <div class="text-center">
                    <font-awesome-icon class="h-16" :icon="['fal', 'box-open']" />
                    <div class="mt-1 text-xs">You don't have any filter data</div>
                </div>
            </div>
              <!-- end  if value is null -->

            <!--   Prospect By -->
            <div v-if="get(value,'propspect_by')  && options.use.includes('propspect_by')">
                <Disclosure as="div" class="mt-2" v-slot="{ open }" :defaultOpen="true">
                    <DisclosureButton
                        class="flex w-full justify-between  bg-purple-100 px-4 py-2 text-left text-sm font-medium text-purple-900 hover:bg-purple-200 focus:outline-none focus-visible:ring focus-visible:ring-purple-500/75">
                        <div> 
                            <font-awesome-icon class="h-[10px] pr-2 py-[2px]" :icon="open ? ['fas', 'chevron-down'] : ['fas', 'chevron-right']" /> 
                            <span>{{trans("Prospects by")}}</span>
                        </div>
                        <div class="flex gap-2">
                            <VTooltip>
                                <font-awesome-icon :icon="['far', 'info-circle']" />
                                <template #popper>
                                    {{trans("filter deliveries based on the prospect")}}
                                </template>
                            </VTooltip>
                        </div>
                    </DisclosureButton>
                    <DisclosurePanel
                        class="px-4 pt-3 pb-2 text-sm text-gray-500 bg-white border-gray-300 border border-t-0">
                        <div class="flex flex-wrap items-center">
                            <div v-for="(query, index) in descriptor.QueryLists" :key="query.value"
                                class="flex items-center mr-4 mb-2 py-[4px] px-2.5 border border-solid border-gray-300 rounded-lg">
                                <input type="checkbox" v-model="value.propspect_by.by" :id="query.value"
                                    :key="query.value" :value="query.value"
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                <label :for="query.value" class="ml-2">{{ trans(query.label) }}</label>
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
                    </DisclosurePanel>
                </Disclosure>
            </div>
            <!--  end By -->
            <!--   tags By -->
            <div v-if="get(value,'tag') && options.use.includes('tag')">
                <Disclosure as="div" class="mt-2" v-slot="{ open }" :defaultOpen="true">
                    <DisclosureButton
                        class="flex w-full justify-between  bg-purple-100 px-4 py-2 text-left text-sm font-medium text-purple-900 hover:bg-purple-200 focus:outline-none focus-visible:ring focus-visible:ring-purple-500/75">
                        <div> <font-awesome-icon class="h-[10px] pr-2 py-[2px]" 
                                :icon="open ? ['fas', 'chevron-down'] : ['fas', 'chevron-right']" />  <span>{{trans('Tags')}}</span>
                        </div>
                        <div class="flex gap-2">
                            <VTooltip>
                                <font-awesome-icon :icon="['far', 'info-circle']" />
                                <template #popper>
                                    {{trans('Filter by SEO tags')}}
                                </template>
                            </VTooltip>
                        </div>
                    </DisclosureButton>
                    <DisclosurePanel
                        class="px-4 pt-3 pb-2 text-sm text-gray-500 bg-white border-gray-300 border border-t-0">
                        <div>
                            <Multiselect v-model="value.tag.tags" mode="tags" placeholder="Select the tag" valueProp="slug"
                                trackBy="name" label="name" :close-on-select="false" :searchable="true" :caret="false"
                                :options="tagsOptions" noResultsText="No one left. Type to add new one.">

                                <template
                                    #tag="{ option, handleTagRemove, disabled }: { option: tag, handleTagRemove: Function, disabled: boolean }">
                                    <div class="px-0.5 py-[3px]">
                                        <Tag :theme="option.id" :label="option.name" :closeButton="true"
                                            :stringToColor="true" size="sm"
                                            @onClose="(event) => handleTagRemove(option, event)" />
                                    </div>
                                </template>
                            </Multiselect>
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
                                                class="ml-3 block text-xs font-medium leading-6 text-gray-900">{{
                                                    filter.label
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
                                </fieldset>
                            </div>
                        </div>
                    </DisclosurePanel>
                </Disclosure>
            </div>
            <!--  tags By -->
            <!-- last_contacted -->
            <div v-if="get(value,'last_contact') && options.use.includes('last_contact')">
                <Disclosure as="div" class="mt-2" v-slot="{ open }" :defaultOpen="true">
                    <DisclosureButton
                        class="flex w-full justify-between  bg-purple-100 px-4 py-2 text-left text-sm font-medium text-purple-900 hover:bg-purple-200 focus:outline-none focus-visible:ring focus-visible:ring-purple-500/75">
                        <div> <font-awesome-icon class="h-[10px] pr-2 py-[2px]" 
                                :icon="open ? ['fas', 'chevron-down'] : ['fas', 'chevron-right']" />    <span>{{trans('Last contacted')}}</span></div>
                        <div class="flex gap-2">
                            <VTooltip>
                                <font-awesome-icon :icon="['far', 'info-circle']" />
                                <template #popper>
                                    {{trans('filter recipients based on the last mailshot sent to them')}}
                                </template>
                            </VTooltip>
                        </div>
                    </DisclosureButton>
                    <DisclosurePanel
                        class="px-4 pt-3 pb-2 text-sm text-gray-500 bg-white border-gray-300 border border-t-0"> 
                        <!-- <div>
                            <Multiselect placeholder="Select contact" :allowEmpty="false" :options="descriptor.contact"
                                valueProp="value" trackBy="label" label="label" v-model="value.last_contact.state"
                                :can-clear="false"></Multiselect>
                            <p v-if="get(form, ['errors', `${fieldName}.last_contact.state`])"
                                class="mt-2 text-sm text-red-600" :id="`${fieldName}-error`">
                                {{ form.errors[`${fieldName}.last_contact.state`] }}
                            </p>
                        </div> -->

                        <SwitchGroup as="div" class="flex items-center">
                            <Switch v-model="value.last_contact.state" :class="[value.last_contact.state ? 'bg-indigo-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2']">
                            <span aria-hidden="true" :class="[value.last_contact.state ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']" />
                            </Switch>
                            <SwitchLabel as="span" class="ml-3 text-sm">
                            <span class="font-medium text-gray-900">{{ value.last_contact.state ? 'Last contact' : 'Never'}}</span>
                            </SwitchLabel>
                        </SwitchGroup>
                        

                        <div v-if="value.last_contact.state" class="flex flex-col gap-y-2 mt-4">
                            <div class="flex gap-x-2">
                                <div class="w-20">
                                    <PureInput type="number" :minValue="1" :caret="false" placeholder="range"
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
            <!--  last_contacted -->
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

.multiselect-tags {
    @apply m-0.5
}

.multiselect-tag-remove-icon {
    @apply text-lime-800
}</style>
