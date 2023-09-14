<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 22 Aug 2023 19:44:06 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
-->

<script setup lang="ts">
import { ref } from 'vue'
const themeOptions = [
  { name: 'Full', value: 1 },
  { name: 'Page Margin', value: 2 },
];

let selectedTheme = ref(1); // Default selected theme
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
        'w-full': selectedTheme === 1,
        'w-[60%]': selectedTheme === 2,
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
