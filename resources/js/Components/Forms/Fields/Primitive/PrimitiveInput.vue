<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 14 Mar 2023 23:44:10 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->


  <script setup lang="ts">
  import { set } from 'lodash'
  import { ref, watch, defineProps, defineEmits } from 'vue'
  
  const props = defineProps<{
      fieldName: string | []
      fieldData?: {
          placeholder: string
          readonly: boolean
          copyButton: boolean
      }
      data: Object
      counter: boolean
  }>()
  
  const emits = defineEmits()
  
  const setFormValue = (data: Object, fieldName: string | []) => {
      if (Array.isArray(fieldName)) {
          return getNestedValue(data, fieldName);
      } else {
          return data[fieldName];
      }
  }
  
  const getNestedValue = (obj: Object, keys: string[]) => {
      return keys.reduce((acc, key) => {
          if (acc && typeof acc === 'object' && key in acc) return acc[key];
          return null;
      }, obj);
  }
  
  const value = ref(setFormValue(props.data, props.fieldName))
  
  watch(value, (newValue) => {
      // Update the local form value when the value ref changes
      updateLocalFormValue(newValue);
  });
  
  const updateLocalFormValue = (newValue) => {
      let localData = { ...props.data }
      if (Array.isArray(props.fieldName)) {
          set(localData, props.fieldName, newValue);
      } else {
          localData[props.fieldName] = newValue;
      }
      emits('update:data', localData); // Emit event to update parent component's data
  };


 
  </script>
<template>
    <div class="relative">
        <div class="relative">
            <input v-if="fieldData" v-model.trim="value" :readonly="fieldData?.readonly" :type="props.fieldData.type ?? 'text'"
                :placeholder="fieldData?.placeholder"
                class="block w-full shadow-sm rounded-md dark:bg-gray-600 dark:text-gray-400 focus:ring-gray-500 focus:border-gray-500 sm:text-sm border-gray-300 dark:border-gray-500 read-only:bg-gray-100 read-only:ring-0 read-only:ring-transparent read-only:text-gray-500" />
            <div v-else>No field data passed</div>
        </div>

        <!-- Counter: Letters and Words -->
        <div v-if="counter && fieldData?.[fieldName]"
            class="grid grid-flow-col text-xs italic text-gray-500 mt-2 space-x-12 justify-start">
            <p class="">
                Letters: {{ fieldData?.[fieldName].length }}
            </p>
            <p class="">
                Words: {{ fieldData?.[fieldName].trim().split(/\s+/).filter(Boolean).length }}
            </p>
        </div>
    </div>
</template>