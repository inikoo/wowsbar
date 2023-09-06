<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 14 Mar 2023 23:44:10 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { set, get } from "lodash";
import { ref, watch, toRefs } from "vue";

const props = defineProps<{
    fieldName?: string | [];
    fieldData?: {
        placeholder: string;
        readonly: boolean;
        copyButton: boolean;
        prefix?: string
        type?: string
    };
    data?: Object;
    counter?: boolean;
    value?: String;
}>();

const { data, fieldName, value } = toRefs(props);
const emits = defineEmits();

const setFormValue = (data: Object, fieldName: string | []) => {
    if (Array.isArray(fieldName)) {
        return getNestedValue(data, fieldName);
    } else {
        return data[fieldName];
    }
};

const getNestedValue = (obj: Object, keys: string[]) => {
    return keys.reduce((acc, key) => {
        if (acc && typeof acc === "object" && key in acc) return acc[key];
        return null;
    }, obj);
};

const valued = ref(props.data ? setFormValue(props.data, props.fieldName) : get(props,'value',null));

watch(valued, (newValue) => {
    // Update the local form value when the value ref changes
    emits('onChange', newValue);
    updateLocalFormValue(newValue);
});

watch(data, (newValue) => {
    valued.value = setFormValue(newValue, props.fieldName);
});

watch(props.value, (newValue) => {
    console.log('xxxxxx',newValue)
    valued.value = newValue
});

const updateLocalFormValue = (newValue) => {
    let localData = { ...props.data };
    if (Array.isArray(props.fieldName)) {
        set(localData, props.fieldName, newValue);
    } else {
        localData[props.fieldName] = newValue;
    }
    emits("update:data", localData); // Emit event to update parent component's data
};


</script>

<template>
    <div class="relative">
        <div class="flex rounded-md shadow-sm ring-2 ring-gray-200 focus-within:ring-gray-500 bg-transparent">
            <span v-if="fieldData?.prefix" class="flex select-none items-center pl-3 text-gray-400 sm:text-sm">
                {{ fieldData?.prefix }}
            </span>
            <input
                v-model.trim="valued"
                :readonly="fieldData?.readonly"
                :type="props.fieldData?.type ?? 'text'"
                :placeholder="fieldData?.placeholder"
                style="border: 2px solid transparent; outline: none;" 
                class="block flex-1 py-1.5 pl-3 rounded-md focus:outline-none focus:border-none focus:ring-0 placeholder:text-gray-400 placeholder:text-sm"
            />
        </div>


        <!-- Counter: Letters and Words -->
        <div v-if="counter && fieldData?.[fieldName]"
            class="grid grid-flow-col text-xs italic text-gray-500 mt-2 space-x-12 justify-start">
            <p class="">Letters: {{ fieldData?.[fieldName].length }}</p>
            <p class="">
                Words: {{ fieldData?.[fieldName].trim().split(/\s+/).filter(Boolean).length }}
            </p>
        </div>
    </div>
</template>


<style scoped>
/* Add this style to remove the focus styles */
.focus:outline-none:focus,
.focus:border-none:focus,
.focus:ring-0:focus {
  outline: none;
  border: none;
  ring: none;
}
</style>






