<script setup lang="ts">
import { ref } from "vue"
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { faBold, faUnderline, faStrikethrough, faItalic } from '@fas';
import { library } from '@fortawesome/fontawesome-svg-core';
import { get, set, unset } from 'lodash'
library.add(faBold, faUnderline, faStrikethrough, faItalic)

const props = defineProps<{
  data: any,
  fieldName: any,
}>()

const setText = (value) => {
  const updatedFontDecorator = { ...props.fontDecorator };

  if (value === 'bold') {
    const fieldPath = [props.fieldName, 'fontWeight'];
    if (get(props.data, fieldPath)) {
      unset(props.data,fieldPath)
    } else {
      set(props.data, fieldPath, 'bold');
    }
  }

  if (value === 'underline') {
    const fieldPath = [props.fieldName, 'textDecoration'];
    if (get(props.data, fieldPath)) {
      unset(props.data,fieldPath)
    } else {
      set(props.data, fieldPath,'underline');
    }
  }

  if (value === 'strikeThrough') {
    const fieldPath = [props.fieldName, 'textDecoration'];
    if (get(props.data, fieldPath)) {
      unset(props.data, fieldPath)
    } else {
      set(props.data,fieldPath,'line-through');
    }
  }

  if (value === 'italic') {
    const fieldPath = [props.fieldName, 'fontStyle'];
    if (get(props.data, fieldPath)) {
      unset(props.data,fieldPath)
    } else {
      set(props.data, fieldPath, 'italic');
    }
  }
};

</script>


<template>
  <div class="flex h-full gap-1 justify-center align-middle">

    <button @click="(e) => { e.stopPropagation(), setText('bold') }" type="button"
      class=" p-1.5   hover:bg-indigo-500 hover:text-white focus:ring-4 focus:outline-none  font-medium rounded-lg text-sm p-1 text-center inline-flex items-center  dark:border-indigo-500 dark:text-blue-500 dark:hover:text-white  dark:hover:bg-indigo-500">
      <font-awesome-icon :icon="['fas', 'bold']" />
      <span class="sr-only">Bold</span>
    </button>

    <button @click="(e) => { e.stopPropagation(), setText('underline') }" type="button"
      class=" p-1.5   hover:bg-blue-700 hover:text-white focus:ring-4 focus:outline-none  font-medium rounded-lg text-sm p-1 text-center inline-flex items-center  dark:border-indigo-500 dark:text-blue-500 dark:hover:text-white  dark:hover:bg-indigo-500">
      <font-awesome-icon :icon="['fas', 'underline']" />
      <span class="sr-only">underline</span>
    </button>

    <button @click="(e) => { e.stopPropagation(), setText('strikeThrough') }" type="button"
      class=" p-1.5   hover:bg-indigo-500 hover:text-white focus:ring-4 focus:outline-none  font-medium rounded-lg text-sm p-1 text-center inline-flex items-center  dark:border-indigo-500 dark:text-blue-500 dark:hover:text-white  dark:hover:bg-indigo-500">
      <font-awesome-icon :icon="['fas', 'strikethrough']" />
      <span class="sr-only">strike through</span>
    </button>

    <button @click="(e) => { e.stopPropagation(), setText('italic') }" type="button"
      class=" p-1.5   hover:bg-indigo-500 hover:text-white focus:ring-4 focus:outline-none  font-medium rounded-lg text-sm p-1 text-center inline-flex items-center  dark:border-indigo-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-indigo-500">
      <font-awesome-icon :icon="['fas', 'italic']" />
      <span class="sr-only">Italic</span>
    </button>

  </div>
</template>
