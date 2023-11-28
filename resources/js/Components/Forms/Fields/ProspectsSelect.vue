<script setup lang="ts">
import Multiselect from "@vueform/multiselect"
import { faInfoCircle } from '@far/'
import { library } from "@fortawesome/fontawesome-svg-core"
import { ref, onMounted, watch, onUnmounted } from 'vue'
import axios from "axios"
import Tag from "@/Components/Tag.vue"
import { notify } from "@kyvg/vue3-notification"
import { get, set, isArray } from 'lodash'
import { faTrash } from '@fas/';
import {trans} from "laravel-vue-i18n";
import Button from "@/Components/Elements/Buttons/Button.vue"

library.add(faTrash)

const props = defineProps<{
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
}>()

const emits = defineEmits();
let timeoutId: any
const Options = ref([])
const q = ref('')
const page = ref(1)

const getOptions = async () => {
    try {
        const response = await axios.get(
            route('org.json.prospects', {
                q: q.value,
                page: page.value
            }),
        )
        onGetOptionsSuccess(response)
    } catch (error) {
        notify({
            title: "Failed",
            text: "Error while fetching prospects",
            type: "error"
        });
    }
}

const onGetOptionsSuccess=(response)=>{
    const data = Object.values(response.data)
    if( q.value.length)   Options.value = [...data]
    else Options.value = [...Options.value, ...data]
}


const setFormValue = (data, fieldName) => {
    if (isArray(fieldName)) return get(data, fieldName, []);  /* if fieldName array */
    else return get(data, fieldName, []); /* if fieldName string */
};

const value = ref(setFormValue(props.form, props.fieldName));

watch(value, (newValue) => {
    updateFormValue(newValue);
});

const updateFormValue = (newValue) => {
    let target = props.form;
    if (Array.isArray(props.fieldName)) {
        set(target, props.fieldName, newValue);
    } else {
        target[props.fieldName] = newValue;
    }
    emits("update:form", target);
};

const SearchChange = (value) => {
    q.value = value
    page.value = 1
    clearTimeout(timeoutId)
    timeoutId = setTimeout(() => {
        getOptions()
    }, 900)
}

const handleScroll = () => {
    if (isScrollAtBottom()) {
        page.value++;
        getOptions();
    }
};

const isScrollAtBottom = () => {
    const multiselectDropdown = document.querySelector('.multiselect-dropdown');
    if (!multiselectDropdown) return false;

    const { scrollTop, scrollHeight, clientHeight } = multiselectDropdown;

    return scrollTop + clientHeight >= scrollHeight;
}

const deleteValueFromTable=(index)=>{
    console.log(index)
    value.value.splice(index,1)
}


onMounted(() => {
    const multiselectDropdown = document.querySelector('.multiselect-dropdown');
    if (multiselectDropdown) {
        multiselectDropdown.addEventListener('scroll', handleScroll);
    }
});

onUnmounted(() => {
    const multiselectDropdown = document.querySelector('.multiselect-dropdown');
    if (multiselectDropdown) {
        multiselectDropdown.removeEventListener('scroll', handleScroll);
    }
});


console.log(props.form,value)
</script>

<template>
    <Multiselect v-model="value" mode="tags" placeholder="Select the prospects"  trackBy="name" label="name" :multiple="true" valueProp="id" :object="true"
        :close-on-select="false" :searchable="true" :caret="false" :options="Options" 
        noResultsText="No one left." @open="getOptions" @search-change="SearchChange">
        <template
            #tag="{ option, handleTagRemove, disabled }: { option: tag, handleTagRemove: Function, disabled: boolean }">
            <div class="px-0.5 py-[3px]">
                <Tag :theme="option.id" :label="option.name" :closeButton="true" :stringToColor="true" size="sm"
                    @onClose="(event) => handleTagRemove(option, event)" />
            </div>
        </template>
    </Multiselect>


    <div v-if="get(value,'length',0) > 0" class="mt-8 flow-root">
      <div class="-mx-4 -my-2  sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
          <div class="shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-300">
              <thead class="bg-purple-100 ">
                <tr>
                  <th scope="col" class="py-2 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">{{ trans('Name') }}</th>
                  <th scope="col" class="px-3 py-2 text-left text-sm font-semibold text-gray-900">{{ trans('Email') }}</th>
                  <th scope="col" class="px-3 py-2 text-left text-sm font-semibold text-gray-900">{{ trans('Phone') }}</th>
                  <th scope="col" class="px-3 py-2 text-left text-sm font-semibold text-gray-900">{{ trans('Websites') }}</th>
                  <th scope="col" class="px-3 py-2 text-left text-sm font-semibold text-gray-900"></th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 bg-white">
                <tr v-for="(option,index) in value" :key="option.id">
                  <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ option.name }}</td>
                  <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">{{ option.email }}</td>
                  <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">{{ get(option,"phone","-") }}</td>
                  <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">{{ get(option,"websites","-") }}</td>
                  <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500"><Button icon="fas fa-trash" size="xs" :style="'red'" @click="()=>deleteValueFromTable(index)"></Button></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
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
}
</style>
