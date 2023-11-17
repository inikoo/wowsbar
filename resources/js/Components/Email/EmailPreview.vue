<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 17 Nov 2023 21:00:29 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang='ts'>
import { useRangeFromNow } from '@/Composables/useFormatTime'
import SliderLandscape from "@/Components/Slider/SliderLandscape.vue"
import SliderSquare from "@/Components/Slider/SliderSquare.vue"
import Image from '@/Components/Image.vue'
import {trans} from "laravel-vue-i18n";

const props = defineProps<{
    data: {
        sender:string
        subject:string,
        emailBody:string
    }
}>()

</script>

<template>
<div class="m-2">
    <div class="bg-white border-1 rounded p-2 mb-1" >{{trans('From')}}: {{data.sender}}</div>
    <div class="bg-white border-1 rounded p-2 mb-1" >{{data.subject}}</div>
    <div class="bg-white border-1 rounded p-2" v-html="data.emailBody"></div>
</div>
</template>
