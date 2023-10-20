<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 24 Jul 2023 12:11:04 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {trans} from "laravel-vue-i18n";

const props = defineProps<{
    data: {
        text?: string
        target: string
    }
    swiperRef?: Element
}>()

let url = props.data.target ?? ''

url = url.replace(/^https?:\/\//g, '');


</script>

<template>
    <a :href="`https://${url}`" target="_top" :style="`background : ${data.button_color}; color: ${data.text_color};`"
       class="border border-gray-50/50 rounded-md px-3 py-1 hover:bg-gray-900/60 whitespace-nowrap">
        {{ data.text?.length == 0 ? trans('Open') : data.text }}

    </a>
</template>
