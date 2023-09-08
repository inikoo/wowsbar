<script setup  lang="ts">
import 'vue-color-kit/dist/vue-color-kit.css'
import { set, get } from 'lodash'
import { ref, watch } from 'vue'

const props = defineProps<{
    data: any,
    fieldName: any
}>()


const emit = defineEmits()

const setFormValue = (data: Object, fieldName: String) => {
    if (Array.isArray(fieldName)) {
        return parseInt(getNestedValue(data, fieldName).match(/\d+/)[0], 10)
    } else {
        return parseInt(data[fieldName].match(/\d+/)[0], 10)
    }
}

const getNestedValue = (obj: Object, keys: Array) => {
    return keys.reduce((acc, key) => {
        if (acc && typeof acc === 'object' && key in acc) return acc[key];
        return null;
    }, obj);
}


const value = ref(setFormValue(props.data,props.fieldName))

watch(value, (newValue) => {
    updateFormValue(`${newValue}px`);
});

console.log('fontsize',props.data,value)

const updateFormValue = (newValue: any) => {
    let target = { ...props.data };

    if (Array.isArray(props.fieldName)) {
        set(target, props.fieldName, newValue);
    } else {
        target[props.fieldName] = newValue;
    }
    emit('update:data', target);
};

</script>

<template>
  <div>
    <div
      class="flex rounded-md w-16 shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
      <input v-model="value" type="number"
        class="block flex-1 w-16 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm"
        placeholder="Size" />
    </div>
  </div>
</template>
