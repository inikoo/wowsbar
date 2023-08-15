<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 31 Jul 2023 16:01:32 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->
<script setup lang="ts">
const props = defineProps<{

    data: Object

}>()

console.log('banner', props)
</script>
    
<template>
    <div class="relative bg-gray-900">
        <div aria-hidden="true" class="absolute inset-0 overflow-hidden">
            <img :src="data.img.src" :alt="data.img.alt" class="h-full w-full object-cover object-center" />
        </div>
        <div aria-hidden="true" class="absolute inset-0 bg-gray-900 opacity-50" />

        <div class="relative mx-auto flex max-w-3xl flex-col items-center px-6 py-32 text-center sm:py-64 lg:px-0">
            <h1 class="text-4xl font-bold tracking-tight text-white lg:text-6xl">{{ data.title }}</h1>
            <p class="mt-4 text-xl text-white">{{ data.subtitle }}</p>
            <a :href="data.button.link"
                class="mt-8 inline-block rounded-md border border-transparent bg-white px-8 py-3 text-base font-medium text-gray-900 hover:bg-gray-100">Shop
                {{ data.button.text }}</a>
        </div>
    </div>
</template>
    
    
    