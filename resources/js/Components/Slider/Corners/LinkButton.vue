<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 24 Jul 2023 12:11:04 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { trans } from "laravel-vue-i18n";

const props = defineProps<{
    data: {
        text?: string
        target: string
    }
    swiperRef?: Element 
}>()
console.log('ssss',props.data)
</script>

<template>
    <a :href="data.target" :target="data.target_window == 'In This Window' ? '_self' : '_blank'"  :style="`background : ${data.button_color}; color: ${data.text_color};`"
        class="border border-gray-50/50 rounded-md px-3 py-1 hover:bg-gray-900/60 whitespace-nowrap">
        {{ data.text?.length == 0 ? trans('Open') : data.text }}
    </a>
</template>
