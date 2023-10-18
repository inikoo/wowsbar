<script setup lang="ts">
import { trans } from "laravel-vue-i18n"
import { ref, watch, computed } from 'vue'
import Input from '@/Components/Forms/Fields/Primitive/PrimitiveInput.vue'
// import InputForm from '@/Components/Forms/Fields/Input.vue'
import ColorPicker from "@/Components/Workshop/Fields/ColorPicker.vue"
import Radio from '@/Components/Forms/Fields/Primitive/PrimitiveRadio.vue'
import { get, cloneDeep, set } from 'lodash'
import  Select from '@/Components/Forms/Fields/Primitive/PrimitiveSelect.vue'
import { faLock } from '@fas/'
import { faTimes } from '@fal/'
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
library.add(faLock, faTimes)

const props = defineProps<{
    data: any
    fieldName: string | []
    options?: any
    fieldData?: {
        placeholder: string
        readonly: boolean
        copyButton: boolean
    }
    common?: any
}>()

const emits = defineEmits()

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

const optionType = [
    // {
    //     label: 'Footer',
    //     value: 'cornerFooter',
    //     fields: [
    //         {
    //             name: 'text',
    //             type: 'input',
    //             label: trans('Text'),
    //             value: null,
    //             placeholder: "Christmas Sales on 20-31 December!"
    //         },
    //         {
    //             name: "color",
    //             type: 'colorPicker',
    //             label: trans('color'),
    //             value: null
    //         },
    //         {
    //             name: "fontSize",
    //             type: "radio",
    //             label: trans("Font Size"),
    //             value: null,
    //             defaultValue: { fontTitle: "text-[25px] lg:text-[44px]", fontSubtitle: "text-[12px] lg:text-[20px]" },
    //             options: [
    //                 { label: "Extra Small", value: {
    //                         fontTitle: "text-[13px] lg:text-[21px]",
    //                         fontSubtitle: "text-[8px] lg:text-[12px]"
    //                     }
    //                 },
    //                 {
    //                     label: "Small",
    //                     value: {
    //                         fontTitle: "text-[18px] lg:text-[32px]",
    //                         fontSubtitle: "text-[10px] lg:text-[15px]"
    //                     }
    //                 },
    //                 {
    //                     label: "Normal",
    //                     value: {
    //                         fontTitle: "text-[25px] lg:text-[44px]",
    //                         fontSubtitle: "text-[12px] lg:text-[20px]"
    //                     }
    //                 },
    //                 {
    //                     label: "Large", value: {
    //                         fontTitle: "text-[30px] lg:text-[60px]",
    //                         fontSubtitle: "text-[15px] lg:text-[25px]"
    //                     }
    //                 },
    //                 {
    //                     label: "Extra Large",
    //                     value: {
    //                         fontTitle: "text-[40px] lg:text-[70px]",
    //                         fontSubtitle: "text-[20px] lg:text-[30px]"
    //                     },
    //                 },
    //             ],
    //         },
    //     ]
    // },
    {
        label: 'Corner text',
        value: 'cornerText',
        fields: [
            // List field on Slides - Corners - [Top Left] - Corner Text
            {
                name: 'title',
                type: 'input',
                label: trans('title'),
                value: null,
                placeholder: "Holiday Sales!"
            },
            {
                name: 'subtitle',
                type: 'input',
                label: trans('subtitle'),
                value: null,
                placeholder: "Holiday sales up to 80% all items."
            },
            {
                name: 'width',
                type: 'number',
                label: trans('width'),
                value: 100,
                placeholder: "100",
                suffix: "%",
            },
            {
                name: 'color',
                type: 'colorPicker',
                label: trans('text color'),
                icon: 'far fa-text',
                value: null
            },
            {
                name: "fontSize",
                type: "radio",
                label: trans("Font Size"),
                value: null,
                defaultValue: { fontTitle: "text-[25px] lg:text-[44px]", fontSubtitle: "text-[12px] lg:text-[20px]" },
                options: [
                    { label: "Extra Small", value: {
                            fontTitle: "text-[13px] lg:text-[21px]",
                            fontSubtitle: "text-[8px] lg:text-[12px]"
                        }
                    },
                    {
                        label: "Small",
                        value: {
                            fontTitle: "text-[18px] lg:text-[32px]",
                            fontSubtitle: "text-[10px] lg:text-[15px]"
                        }
                    },
                    {
                        label: "Normal",
                        value: {
                            fontTitle: "text-[25px] lg:text-[44px]",
                            fontSubtitle: "text-[12px] lg:text-[20px]"
                        }
                    },
                    {
                        label: "Large", value: {
                            fontTitle: "text-[30px] lg:text-[60px]",
                            fontSubtitle: "text-[15px] lg:text-[25px]"
                        }
                    },
                    {
                        label: "Extra Large",
                        value: {
                            fontTitle: "text-[40px] lg:text-[70px]",
                            fontSubtitle: "text-[20px] lg:text-[30px]"
                        },
                    },
                ],
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
                value: null,
                placeholder: "Buy Now!"
            },
            {
                name: 'target',
                type: 'input',
                label: trans('Link'),
                value: null,
                prefix: "https://",
            },
            {
                name: 'button_color',
                type: 'colorPicker',
                label: trans('Button color'),
                value: 'rgb(244, 63, 94)'
            },
            {
                name: 'text_color',
                type: 'colorPicker',
                label: trans('Text color'),
                icon: 'far fa-text',
                value: 'rgb(244, 63, 94)'
            },
            {
                name: 'target_window',
                type: 'select',
                label: trans('Target Window'),
                value:  'In This Window',
                option : [ 'In This Window', 'New Window']
            },
        ]
    },
    {
        label: 'Slide Controls',
        value: "slideControls",
        fields: [],
    },
    {
        label: 'Ribbon',
        value: "ribbon",
        fields: [
            {
                name: 'text',
                type: 'input',
                label: trans('Text'),
                value: null,
                placeholder: 'Holiday Sales!'
            },
            {
                name: 'ribbon_color',
                type: 'colorPicker',
                label: trans('Ribbon color'),
                value: 'rgb(244, 63, 94)'
            },
            {
                name: 'text_color',
                type: 'colorPicker',
                label: trans('Text color'),
                icon: 'far fa-text',
                value: 'rgb(0, 0, 0)'
            },
        ]
    },
]


const filterType = () => {
    if (props.fieldData?.optionType) {
        const data = optionType.filter((item) => {
            // Check if the item's value is present in the optionType array
            return props.fieldData?.optionType.includes(item.value);
        });
        return data;
    }
    return optionType
}

const Type = filterType()

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


const cornerClick = (corner) => {
    area.value = corner;
    current.value = defaultCurrent.value
    setUpData();
}

const setUpData = () => {
    const currentType = Type[current.value];
    let data = {};
    for (const s of currentTypeFields.value) {
        // set(data,s.name,get(s,'value',null))
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

    if(Type[current.value]){
        if(Type[current.value].value == 'slideControls'){
            for(const set in value.value){
                if(value.value[set].type == 'slideControls' && area.value.id != set){
                    delete value.value[set]
                }
            }
        }
        if(Type[current.value].value == 'clear'){
            for(const set in value.value){
                if(area.value.id == set){
                    delete value.value[set]
                }
            }
        }
    }

    // console.log('iniiiii',value.value)
    
    updateFormValue(value.value)
};

const typeClick = (key) => {
    console.log('key',key)
    current.value = key 
    setUpData();
}



const updateFormValue = (newValue) => {
    // Step 1: Log the newValue


    // Step 2: Iterate over properties of newValue
    for (const v in newValue) {
        if (newValue[v].type != 'slideControls') {
            for (const c in newValue[v].data) {

                if (newValue[v].data[c] === null) {
                    delete newValue[v].data[c];
                }
            }

            // Step 5: Check if newValue[v].data is empty and log if it is
            if (Object.keys(newValue[v].data).length === 0) {
                delete newValue[v];
            }
        }

    }

    // Step 6: Update props.data based on props.fieldName
    let target = { ...props.data }; // Make a shallow copy of props.data

    if (Array.isArray(props.fieldName)) {
        set(target, props.fieldName, newValue);
    } else {
        target[props.fieldName] = newValue;
    }

    // Step 7: Update props.data with the new object
    // console.log('target',target)
    emits("update:data", target);
};

const OnchangeFields=(field,value)=>{
    field.value = value
    setUpData()
}

defineExpose({
    setUpData,
});

</script>


<template>
    <div class="space-y-8">
        <!-- Choose: The Corners box -->
        <div class="grid grid-cols-2 gap-0.5 h-full bg-amber-400 border border-gray-300">
            <div v-for="(corner, index) in corners" :key="corner.id"
                class="relative overflow-hidden flex items-center justify-center flex-grow text-base font-semibold py-4"
                :class="[
                    common && common.corners?.hasOwnProperty(corner.id) ? 'cursor-not-allowed bg-gray-200 text-red-500' : get(area, 'id') == corner.id ? 'bg-amber-300 text-gray-600 cursor-pointer' : 'bg-gray-100 hover:bg-gray-200 text-gray-400 cursor-pointer'
                ]"
                @click="()=>{
                    common && common.corners?.hasOwnProperty(corner.id) ?  null : cornerClick(corner)
                    }"
            >
                <div v-if="common && common.corners?.hasOwnProperty(corner.id)" class="isolate text-sm italic">
                    <div class=""><font-awesome-icon :icon="['fas', 'lock']" class="mr-2"/> Already used in common</div>
                    <!-- <div class="-z-10 absolute left-0 top-1/2 -translate-x-10 bg-gray-400/50 h-0.5 rotate-[9deg] w-[120%]"></div>
                    <div class="-z-10 absolute left-0 top-1/2 -translate-x-10 bg-gray-400/50 h-0.5 -rotate-[9deg] w-[120%]"></div> -->
                </div>
                <span v-else class="capitalize">{{ corner.label }}</span>
            </div>
        </div>

        <!-- Choose: The type of component (after select Corners) -->
        <div v-if="area != null" class="h-full">
            <!-- Choose: Card -->
            <div class="w-full flex">
                <span class="isolate flex w-full rounded-md gap-x-2">
                    <!-- Select the corners -->
                    <button v-for="(item, key) in Type" :key="item.value" type="button" @click="typeClick(key)"
                    :class="[
                            'py-2', 'px-4', 'rounded',
                            current === key ? 'bg-gray-300 text-gray-600 ring-2 ring-gray-500' : 'hover:bg-gray-200/70 border border-gray-400'
                        ]"
                    >
                        {{ item.label }}
                    </button>

                    <!-- Button: clear -->
                    <div class="px-1.5 flex items-center gap-x-1 text-red-500 hover:text-red-600 cursor-pointer">
                        <FontAwesomeIcon icon='fal fa-times' class='text-sm' aria-hidden='true' />
                        <span>{{ trans('Clear') }}</span>
                    </div>
                </span>
            </div>

            <!-- Field -->
            <div class="mt-6 block">
                <div v-for="(field, index ) in currentTypeFields" :key="field.name + index">
                    <dl class="pb-4 flex flex-col max-w-lg gap-1">
                        <dt class="text-sm font-medium text-gray-500 capitalize">
                            <div class="inline-flex items-start leading-none">
                                <span>{{ field.label }}</span>
                            </div>
                        </dt>
                        <dd class="sm:col-span-2">
                            <div class="mt-1 flex text-sm text-gray-700 sm:mt-0">
                                <div class="relative flex-grow" v-if="field.type == 'input' || field.type == 'number' ">
                                    <Input :key="field.label + index" :value="field.value" @input="setUpData"  @onChange="(newValue)=>OnchangeFields(field,newValue)" :fieldData="field" />
                                </div>
                                <div class="relative flex-grow" v-if="field.type == 'colorPicker'">
                                    <ColorPicker  :key="field.label + index" :color=field.value @onChange="(newValue)=>OnchangeFields(field,newValue)" :fieldData="field"/>
                                </div>
                                <div class="relative flex-grow" v-if="field.type == 'radio'">
                                    <Radio :key="field.label + index" :radioValue="field.value" :fieldData="field" @onChange="(newValue)=>OnchangeFields(field,newValue)"/>
                                </div>
                                <div class="relative flex-grow" v-if="field.type == 'select'">
                                    <Select :value="field.value"  :key="field.label + index" :fieldData="{ options: field.option }" @onChange="(newValue) => OnchangeFields(field,newValue)" />
                                </div>
                            </div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</template>
