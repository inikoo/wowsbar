<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 22 Aug 2023 19:44:06 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
-->

<script setup lang="ts">
import { ref, watch } from 'vue'
import { getDbRef, getDataFirebase, setDataFirebase } from '@/Composables/firebase'
const themeOptions = [
  { name: 'Full', value: 'full' },
  { name: 'Page Margin', value: 'margin' },
];

let selectedTheme = ref('full'); // Default selected theme

async function setToFirebase() {
  const column = 'org/websites/layout';
  try {
    await setDataFirebase(column, selectedTheme.value);
  } catch (error) {
    console.log(error)
  }
}

watch(selectedTheme, setToFirebase, { deep: true })

setToFirebase()


</script>

<template>
  <div class="flex justify-center items-center w-full mt-3">
    <div class="w-[80%] flex justify-end">
      <select v-model="selectedTheme" class="px-2 py-1 rounded-md border-stone-300 border w-[150px]">
        <option v-for="option in themeOptions" :key="option.value" :value="option.value">
          {{ option.name }}
        </option>
      </select>
    </div>
  </div>

  <div class="flex justify-center items-center w-full">
    <div class="w-[80%] h-screen flex justify-center items-center border-2 border-gray-400 rounded-md my-9 bg-gray-200">
      <div class="bg-white  h-full rounded-md" :class="{
        'w-full': selectedTheme === 'full',
        'w-[60%]': selectedTheme === 'margin',
      }">
        <div class="h-1/3 border-b-2 flex items-center">
          <div class="mx-auto text-3xl font-medium">Header</div>
        </div>
        <div class="h-1/3 border-b-2 flex items-center">
          <div class="mx-auto text-3xl font-medium">Content</div>
        </div>
        <div class="h-1/3 border-b-2 flex items-center">
          <div class="mx-auto text-3xl font-medium">Footer</div>
        </div>

      </div>
    </div>
  </div>
</template>
