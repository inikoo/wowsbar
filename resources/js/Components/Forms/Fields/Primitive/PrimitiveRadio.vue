<script setup lang="ts">

import { RadioGroup, RadioGroupLabel, RadioGroupOption, RadioGroupDescription } from '@headlessui/vue'
import { ref, watch, defineEmits } from 'vue'
import { set , isEqual } from 'lodash'

const props = defineProps(['data', 'fieldName', 'fieldData'])
const emit = defineEmits()

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
        return props.fieldData.defaultValue ? props.fieldData.defaultValue : null;
    }, obj);
}


const value = ref(setFormValue(props.data, props.fieldName))

watch(value, (newValue) => {
    // Update the form field value when the value ref changes
    updateFormValue(newValue);
});




const updateFormValue = (newValue) => {
    let target = { ...props.data };

    if (Array.isArray(props.fieldName)) {
        set(target, props.fieldName, newValue);
    } else {
        target[props.fieldName] = newValue;
    }

    // Emit an event to notify the parent component
    emit('input', target);
};

</script>

<template>
    <div>
        <!-- <label class="text-base font-semibold text-gray-800 capitalize">{{ fieldName }}</label> -->
        <!-- <p class="text-xs text-gray-500 capitalize italic">{{ data[fieldName] }}</p> -->
        <fieldset class="select-none">
            <legend class="sr-only"></legend>
            <div class="flex items-center gap-x-8 gap-y-1 flex-wrap ">

                <!-- Radio: Default -->
                <div v-for="(option, index) in fieldData.options"
                    :key="option.label + index" class="inline-flex gap-x-2.5 items-center">
                    <input v-model="value" :id="option.label + index" :key="option.label + index"
                        :name="option.value" type="radio" :value="option.value" :checked="isEqual(value,option.value)"
                        class="h-4 w-4 border-gray-300 text-orange-600 focus:ring-0 focus:outline-none focus:ring-transparent cursor-pointer" />
                    <label :for="option.label + index" class="flex items-center gap-x-1.5 cursor-pointer">
                        <span v-if="option.label" class="font-light text-sm text-gray-400 capitalize">
                            {{ option.label }}
                            <!-- d -->
                        </span>
                    </label>
                </div>
            </div>
        </fieldset>
    </div>
</template>