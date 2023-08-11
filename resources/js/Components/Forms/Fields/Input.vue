<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 14 Mar 2023 23:44:10 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faExclamationCircle, faCheckCircle } from "@/../private/pro-solid-svg-icons";
import { faSpinnerThird } from "@/../private/pro-duotone-svg-icons";
import { library } from "@fortawesome/fontawesome-svg-core";
import { set } from "lodash";
library.add(faExclamationCircle, faCheckCircle, faSpinnerThird);
import { ref, watch, defineEmits } from "vue";

const props = defineProps<{
  form: any;
  fieldName: string;
  options?: any;
  fieldData?: {
    placeholder: string;
    readonly: boolean;
    copyButton: boolean;
  };
}>();

const emits = defineEmits();

const copyText = (text: string) => {
  const textarea = document.createElement("textarea");
  textarea.value = text;
  document.body.appendChild(textarea);
  textarea.select();
  document.execCommand("copy");
  textarea.remove();
};

const setFormValue = (data: Object, fieldName: String) => {
  if (Array.isArray(fieldName)) {
    return getNestedValue(data, fieldName);
  } else {
    return data[fieldName];
  }
};

const getNestedValue = (obj: Object, keys: Array) => {
  return keys.reduce((acc, key) => {
    if (acc && typeof acc === "object" && key in acc) return acc[key];
    return null;
  }, obj);
};

const value = ref(setFormValue(props.form, props.fieldName));

watch(value, (newValue) => {
  // Update the form field value when the value ref changes
  updateFormValue(newValue);
});

const updateFormValue = (newValue) => {
  let target = props.form;
  if (Array.isArray(props.fieldName)) {
    set(target, props.fieldName, newValue);
  } else {
    target[props.fieldName] = newValue;
  }
  emits("update:form", target);
};
</script>
<template>
  <div class="relative">
    <div class="relative">
      <input
        v-model.trim="value"
        :readonly="fieldData?.readonly"
        :type="props.options?.type ?? 'text'"
        @input="form.errors[fieldName] = ''"
        :placeholder="fieldData?.placeholder"
        class="block w-full shadow-sm rounded-md dark:bg-gray-600 dark:text-gray-400 focus:ring-gray-500 focus:border-gray-500 sm:text-sm border-gray-300 dark:border-gray-500 read-only:bg-gray-100 read-only:ring-0 read-only:ring-transparent read-only:text-gray-500"
      />
      <div
        v-if="fieldData?.copyButton"
        class="absolute inset-y-0 right-0 group cursor-pointer px-1.5 flex justify-center items-center text-gray-600"
        @click="copyText(form[fieldName])"
      >
        <FontAwesomeIcon
          icon="fal fa-copy"
          class="text-lg leading-none mr-1 opacity-20 group-hover:opacity-75 group-active:opacity-100"
          aria-hidden="true"
        />
      </div>

      <!-- Icon: Error, Success, Loading -->
      <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
        <FontAwesomeIcon
          v-if="form.errors[fieldName]"
          icon="fas fa-exclamation-circle"
          class="h-5 w-5 text-red-500"
          aria-hidden="true"
        />
        <FontAwesomeIcon
          v-if="form.recentlySuccessful"
          icon="fas fa-check-circle"
          class="h-5 w-5 text-green-500"
          aria-hidden="true"
        />
        <FontAwesomeIcon
          v-if="form.processing"
          icon="fad fa-spinner-third"
          class="h-5 w-5 animate-spin dark:text-gray-200"
        />
      </div>
    </div>

    <!-- Counter: Letters and Words -->
    <div
      v-if="props.options?.counter"
      class="grid grid-flow-col text-xs italic text-gray-500 mt-2 space-x-12 justify-start"
    >
      <p class="">Letters: {{ form[fieldName].length }}</p>
      <p class="">
        Words: {{ form[fieldName].trim().split(/\s+/).filter(Boolean).length }}
      </p>
    </div>
  </div>
  <p
    v-if="form.errors[fieldName]"
    class="mt-2 text-sm text-red-600"
    :id="`${fieldName}-error`"
  >
    {{ form.errors[fieldName] }}
  </p>
</template>
