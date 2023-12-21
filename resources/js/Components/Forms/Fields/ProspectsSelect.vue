<script setup lang="ts">
import Multiselect from "@vueform/multiselect"
import { faInfoCircle } from '@far'
import { library } from "@fortawesome/fontawesome-svg-core"
import { ref, onMounted, watch, onUnmounted } from 'vue'
import axios from "axios"
import Tag from "@/Components/Tag.vue"
import { notify } from "@kyvg/vue3-notification"
import { get, set, isArray, isNull} from 'lodash'
import { faTimes } from '@far';
import {trans} from "laravel-vue-i18n";
import Button from "@/Components/Elements/Buttons/Button.vue"
import { useFormatTime } from '@/Composables/useFormatTime';

library.add(faTimes)

const props = defineProps<{
    form?: any
    fieldName: string | Array
    tabName: string
    dataTabProspect?: object | Array
    options?: string[] | object
    changeWeeksValue? : Function
    fieldData?: {
        placeholder?: string
        required?: boolean
        mode?: string
        searchable?: boolean
    }
}>()

console.log(props)

const emits = defineEmits();
let timeoutId: any
const Options = ref([])
const q = ref('')
const page = ref(1)
const modelValue = ref([])
const loading = ref(false)

const getOptions = async () => {
    loading.value = true
    try {
        const response = await axios.get(
            route('org.json.prospects.search', {
                q: q.value,
            }),
            
        )
        onGetOptionsSuccess(response)
        loading.value = false
    } catch (error) {
        loading.value = false
        notify({
            title: "Failed",
            text: "Error while fetching prospects",
            type: "error"
        });
    }
}

const onGetOptionsSuccess = (response) => {
    const data = Object.values(response.data);
    Options.value = [ ...data];
    if (isNull(value.value)) Options.value = [ ...data];
    else{
        const result = [...data].filter(item => !value.value.includes(item.id));
        Options.value =  result
    }
}

const getValueTable = async () => {
    try {
        const response = await axios.get(
            route('org.json.mailshot.recipe-prospects', { mailshot : props.options.mailshot.id}),
        )
        const data = Object.values(response.data);
        emits("changeValueDataTabProspect", data);
    } catch (error) {
        console.log('error',error)
        notify({
            title: "Failed",
            text: "Error while fetching prospects",
            type: "error"
        });
    }
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
    }, 500)
}

 
const deleteValueFromTable=(data)=>{
    const index = value.value.findIndex((item)=>item == data.id)
    const indexTable = props.dataTabProspect.findIndex((item)=>item.id == data.id)
    if(index != -1) value.value.splice(index,1)
    if(indexTable != -1)props.dataTabProspect.splice(indexTable,1)
}

const onMultiselectChange=(data)=>{
        if(isNull(value.value)) value.value = [data.id]
        else { 
            if(!(value.value.find((item)=>item.id == data.id))) value.value.push(data.id)
        }
    modelValue.value = {}
    props.dataTabProspect.splice(data.length,0,data)
}


 
onMounted(() => {
    if(get(props,['options','mailshot'])) getValueTable() 
});

console.log('ssss',props.dataTabProspect)

</script>

<template> 
    <Multiselect 
        :value="modelValue" 
        placeholder="Search prospect"  
        trackBy="name" 
        label="name" 
        valueProp="id" 
        :object="true" 
        :clearOnSearch="false"
        :close-on-select="true" 
        :searchable="true" 
        :caret="false" 
        :options="Options" 
        :noResultsText="loading ? 'loading...' : 'No Result'" 
        @open="getOptions()" 
        @search-change="SearchChange"
        @input="onMultiselectChange"
        >
    </Multiselect>


    <div v-if="get(value,'length',0) > 0" class="mt-4 flow-root">
      <div class="-mx-4 -my-2  sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
          <div class="shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-300">
              <thead class="bg-purple-100 ">
                <tr>
                  <th scope="col" class="py-2 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">{{ trans('Name') }}</th>
                  <th scope="col" class="px-3 py-2 text-left text-sm font-semibold text-gray-900">{{ trans('Email') }}</th>
                  <th scope="col" class="px-3 py-2 text-left text-sm font-semibold text-gray-900">{{ trans('State') }}</th>
                  <th scope="col" class="px-3 py-2 text-left text-sm font-semibold text-gray-900">{{ trans('Last Contacted At') }}</th>
                  <th scope="col" class="px-3 py-2 text-left text-sm font-semibold text-gray-900"></th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 bg-white">
                <tr v-for="(option,index) in props.dataTabProspect" :key="option.id">
                  <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ option.name }}</td>
                  <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">{{ option.email }}</td>
                  <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">{{ get(option,"state","-") }}</td>
                  <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500">{{ useFormatTime(option.last_contacted_at) }}</td>
                  <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500"><Button icon="far fa-times" size="xs" :style="'red'" @click="()=>deleteValueFromTable(option)"></Button></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      </div>

</template>

<style lang="scss">
.multiselect-search {
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
