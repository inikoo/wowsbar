<script setup lang="ts">
import { set } from 'lodash'
import { ref, watch } from 'vue'

const props = defineProps<{
    fieldName: string
    fieldData?: {
        placeholder: string
        readonly: boolean
        copyButton: boolean
    }
    data : Object
    counter: boolean
}>()


const setFormValue = (data: Object, fieldName: String) => {
    if (Array.isArray(fieldName)) {
        return getNestedValue(data, fieldName) / 1000
    } else {
        return data[fieldName] / 1000
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
    updateFormValue(newValue);
});



const updateFormValue = (newValue) => {
    let target = props.data
    if (Array.isArray(props.fieldName)) {
        set(target, props.fieldName, newValue * 1000);
    } else {
        target[props.fieldName] = newValue * 1000;
    }
    props.data = { ...target }
};


</script>

<template>
    <div class="flex flex-col space-y-2 p-2 w-full">
        <p>Duration: {{ value }}</p>
        <input v-model="value" type="range" class="w-full range accent-orange-500" min="2.5" max="15" step="0.5" />
        <ul class="flex justify-between w-full px-[10px]">
            <li class="flex justify-center relative"><span class="absolute">2.5</span></li>
            <li class="flex justify-center relative"><span class="absolute">5</span></li>
            <li class="flex justify-center relative"><span class="absolute">7.5</span></li>
            <li class="flex justify-center relative"><span class="absolute">10</span></li>
            <li class="flex justify-center relative"><span class="absolute">12.5</span></li>
            <li class="flex justify-center relative"><span class="absolute">15</span></li>
        </ul>
    </div>
</template>



<style scoped></style>