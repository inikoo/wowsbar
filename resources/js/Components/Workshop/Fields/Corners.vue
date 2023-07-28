<script setup lang="ts">
import { trans } from "laravel-vue-i18n"
import { ref, watch, computed, defineExpose } from 'vue'
import Input from '@/Components/Forms/Fields/Input.vue'
import { get, cloneDeep, set } from 'lodash'

const props = defineProps<{
    data: any,
    fieldName: string,
    options?: any,
    fieldData?: {
        placeholder: string
        readonly: boolean
        copyButton: boolean
    }
}>()


const area = ref(null)

const setFormValue = (data: Object, fieldName: String) => {
    if (Array.isArray(fieldName)) {
        return getNestedValue(data, fieldName);
    } else {
        return data[fieldName];
    }
}

const getNestedValue = (obj: Object, keys: Array) => {
    return keys.reduce((acc, key) => {
        if (acc && typeof acc === 'object' && key in acc) return acc[key];
        return null;
    }, obj);
}


const value = ref(setFormValue(props.data, props.fieldName))
const corners = ref([
    { label: trans('top left'), valueForm: get(value.value, [`topLeft`], null), id: 'topLeft' },
    { label: trans('Top right'), valueForm: get(value.value, [`topRight`], null), id: 'topRight' },
    { label: trans('bottom left'), valueForm: get(value.value, [`bottomLeft`], null), id: 'bottomLeft' },
    { label: trans('Bottom right'), valueForm: get(value.value, [`bottomRight`], null), id: 'bottomRight' },
])

const Type = [
    {
        label: 'Footer',
        value: 'cornerFooter',
        fields: [
            {
                name: 'text',
                type: 'input',
                label: trans('Text'),
                value: null
            },
        ]
    },
    {
        label: 'Corner text',
        value: 'cornerText',
        fields: [
            {
                name: 'title',
                type: 'input',
                label: trans('title'),
                value: null
            },
            {
                name: 'subtitle',
                type: 'input',
                label: trans('subtitle'),
                value: null
            },
        ]
    },
    {
        label: 'Link button',
        value: 'linkButton',
        fields: [
            {
                name: 'text',
                type: 'input',
                label: trans('Title'),
                value: null
            },
            {
                name: 'target',
                type: 'input',
                label: trans('Link'),
                value: null
            },
        ]
    },
]

const defaultCurrent = computed(() => {
    if (area.value != null) {
        if(value.value){
        const areaType = value.value[area.value.id]?.type;
        const index = Type.findIndex(item => item.value === areaType);
        return index == -1 ? 0 : index
        }else return 0
    } else {
        return 0; // Return 0 if area.value is null
    }
});

// Set current to the default index
const current = ref(defaultCurrent.value)

const currentTypeFields = computed(() => {
    const currentType = Type[current.value]
    return currentType.fields.map((field) => {
        return {
            ...field,
            value: currentType.value === Type[current.value].value && get(area.value, ['valueForm', 'type']) == currentType.value ? get(area.value, ['valueForm', 'data', `${field.name}`]) : null,
        }
    })
})


const handleClick = (corner) => {
    area.value = corner;
    current.value = defaultCurrent.value
    setUpData();
}

const setUpData = () => {
    const currentType = Type[current.value];
    let data = {};
    for (const s of currentTypeFields.value) {
        data[s.name] = s.value;
    }

    if (!value.value) {
        value.value = {}; // Initialize corners as an empty object
    }

    let setData = cloneDeep(value.value[area.value.id]);
    setData = {
        type: currentType.value,
        data: { ...data },
    };

    value.value[area.value.id] = setData;

    // Check if corners is an object, and convert it to an array if needed
    let cornersArray = Array.isArray(corners) ? corners : Object.values(corners);

    // Find the index in the cornersArray
    const indexCorners = cornersArray.findIndex((item) => item.id == area.value.id);

    // Use optional chaining (?.) to check if valueForm is defined
    if (indexCorners !== -1 && cornersArray[indexCorners]?.valueForm != null) {
        cornersArray[indexCorners].valueForm = setData;
    }

   
    updateFormValue(value.value)
};




const updateFormValue = (newValue) => {
    let target = props.data
    if (Array.isArray(props.fieldName)) {
        set(target, props.fieldName, newValue);
    } else {
        target[props.fieldName] = newValue;
    }
    props.data = { ...target }
};


defineExpose({
    setUpData,
});

</script>


<template>
    <div class="h-24">
        <div class="grid grid-cols-2 gap-2 h-full">
            <div v-for="(corner, index) in corners" :key="corner.id"
                class="flex items-center justify-center capitalize rounded flex-grow cursor-pointer"
                :class="[get(area, 'id') == corner.id ? 'bg-gray-300 hover:bg-gray-300 text-gray-600 ring-2 ring-gray-500' : 'hover:bg-gray-200/70 border border-dashed border-gray-400']" @click="handleClick(corner)">
                {{ corner.label }}
            </div>
        </div>

        <div v-if="area != null">
            <div class="w-full flex mt-3">
                <span class="isolate flex w-full rounded-md shadow-sm gap-x-2">
                    <!-- Select the corners -->
                    <button v-for="(item, key) in Type" :key="item.value" type="button" @click="current = key"
                        class="py-2 px-4 rounded"
                        :class="[current === key ? 'bg-gray-300 text-gray-600 ring-2 ring-gray-500' : 'hover:bg-gray-200/70 border border-gray-400']">
                        {{ item.label }}
                    </button>
                </span>
            </div>

            <!-- Input -->
            <div v-for="(fieldData, index ) in currentTypeFields" :key="index" class="mt-2.5">
                <dl class="divide-y divide-green-200  ">
                    <div class="pb-4 sm:pb-5 sm:grid sm:grid-cols-3 sm:gap-4 max-w-2xl">
                        <dt class="text-sm font-medium text-gray-500 capitalize">
                            <div class="inline-flex items-start leading-none">
                                <span>{{ fieldData.label }}</span>
                            </div>
                        </dt>
                        <dd class="sm:col-span-2">
                            <div class="mt-1 flex text-sm text-gray-700 sm:mt-0">
                                <div class="relative flex-grow">
                                    <input v-model=fieldData.value @input="setUpData"
                                        class="block w-full shadow-sm rounded-md dark:bg-gray-600 dark:text-gray-400 focus:ring-gray-500 focus:border-gray-500 sm:text-sm border-gray-300 dark:border-gray-500 read-only:bg-gray-100 read-only:ring-0 read-only:ring-transparent read-only:text-gray-500" />
                                </div>
                            </div>
                        </dd>
                    </div>
                </dl>
            </div>

        </div>


    </div>
</template>
