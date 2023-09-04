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

const { data, fieldName } = toRefs(props);
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

const value = ref(props.data ? setFormValue(props.data, props.fieldName) : get(props,'value',null));

watch(value, (newValue) => {
    // Update the local form value when the value ref changes
    emits('onChange', newValue);
    updateLocalFormValue(newValue);
});

watch(data, (newValue) => {
    value.value = setFormValue(newValue, props.fieldName);
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

console.log('aaaa',props)

</script>

<template>
    <div class="relative">
        <div class="relative">
            <div
                class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-gray-500">
                <span v-if="fieldData?.prefix" class="flex select-none items-center pl-3 text-gray-400 sm:text-sm">{{
                    fieldData?.prefix }}</span>
                <input v-model.trim="value" :readonly="fieldData?.readonly"
                    :type="props.fieldData?.type ?? 'text'" :placeholder="fieldData?.placeholder"
                    class="block flex-1 border-0 bg-transparent py-1.5 pl-3 text-gray-600 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
            </div>
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
