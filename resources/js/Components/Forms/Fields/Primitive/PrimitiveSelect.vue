<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Wed, 10 May 2023 09:18:00 Malaysia Time, Pantai Lembeng, Bali, Id
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import Multiselect from "@vueform/multiselect"
import { set } from 'lodash'
import { ref, watch } from 'vue'
const props = defineProps<{
    data: any
    fieldName: any
    options: string[] | object
    fieldData: {
        placeholder: string
        searchable: boolean
    }
}>()

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

watch(value, (newValue) => {
    // Update the form field value when the value ref changes
    updateFormValue(newValue);
});



const updateFormValue = (newValue) => {
    let target = props.data
    if (Array.isArray(props.fieldName)) {
        set(target, props.fieldName, newValue);
    } else {
        target[props.fieldName] = newValue;
    }
    props.data = { ...target }
};
</script>

<template>
    <div class="">
        <div class="relative">
            <Multiselect v-model="value" 
                :options="props.fieldData.options" :placeholder="props.fieldData.placeholder ?? 'Select your option'"
                :canClear="!props.fieldData.required"
                :closeOnSelect="props.fieldData.mode == 'multiple' ? false : true" :canDeselect="!props.fieldData.required"
                :hideSelected="false" :searchable="!!props.fieldData.searchable" />
        </div>
    </div>
</template>

<style src="@vueform/multiselect/themes/default.css"></style>

<style>
/* Style for multiselect globally */
.multiselect-option.is-selected,
.multiselect-option.is-selected.is-pointed {
    background: var(--ms-option-bg-selected, #6366f1) !important;
    color: var(--ms-option-color-selected, #fff) !important;
}

.multiselect-option.is-selected.is-disabled {
    background: var(--ms-option-bg-selected-disabled, #c7d2fe);
    color: var(--ms-option-color-selected-disabled, #818cf8);
}

.multiselect.is-active {
    border: var(--ms-border-width-active, var(--ms-border-width, 1px)) solid var(--ms-border-color-active, var(--ms-border-color, #d1d5db));
    box-shadow: 0 0 0 var(--ms-ring-width, 3px) var(--ms-ring-color, rgba(99, 102, 241, 0.188));
}
</style>
