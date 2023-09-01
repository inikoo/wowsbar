<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Wed, 10 May 2023 09:18:00 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import Multiselect from "@vueform/multiselect"
import { set, lowerCase, snakeCase } from 'lodash'
import { ref, watch, defineEmits } from 'vue'
const props = defineProps<{
    data: any
    fieldName: any
    fieldData: {
        placeholder: string
        searchable: boolean
    }
}>()
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
        return null;
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
    <div class="">
        <div class="relative">
            <Multiselect v-model="value" :options="props.fieldData.options"
                :placeholder="props.fieldData.placeholder ?? 'Select your option'" :canClear="!props.fieldData.required"
                :closeOnSelect="props.fieldData.mode == 'multiple' ? false : true" :canDeselect="!props.fieldData.required"
                :hideSelected="false" :searchable="!!props.fieldData.searchable">
                <template #option="{ option }">
                    <span :style="`font-family : ${snakeCase(lowerCase(option.value))}`">
                        {{ option.label }}
                    </span>
                </template>

            </Multiselect>
        </div>
    </div>
</template>
  
<style src="@vueform/multiselect/themes/default.css"></style>
  