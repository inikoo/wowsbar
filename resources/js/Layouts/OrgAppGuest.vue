<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 14 Aug 2023 08:45:47 Malaysia Time, Sanur, Bali
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup>
import { useLayoutStore } from '@/Stores/layout'
import { usePage } from '@inertiajs/vue3'
import { loadLanguageAsync } from 'laravel-vue-i18n'
import Image from '@/Components/Image.vue'

const layout = useLayoutStore()
if (usePage().props.language) {
    loadLanguageAsync(usePage().props.language)
}

const logoSrc=usePage().props.art.logo;
</script>

<template>
    <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8 ">
        <div class="sm:mx-auto sm:w-full sm:max-w-md flex justify-center">
            <Image :class="'mx-auto h-16 -mb-3 w-auto '" :src="logoSrc" alt="Wowsbar" />
        </div>
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="font-logo mt-2 mb-4 text-center text-3xl text-gray-600"> 💼 </h2>
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <slot />
            </div>
        </div>
    </div>
</template>
