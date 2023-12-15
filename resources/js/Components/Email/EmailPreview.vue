<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 17 Nov 2023 21:00:29 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang='ts'>
import { trans } from "laravel-vue-i18n";

const props = defineProps<{
    data: {
        sender: string
        subject: string,
        emailBody: string
    }
}>()

</script>

<template>
    <div class="m-2 text-gray-600">
        <div class="bg-white border-1 rounded p-2 mb-1">{{ trans('From') }}: <span class="font-bold">{{ data.sender }}</span></div>
        <div class="bg-white border-1 rounded p-2 mb-1">{{ trans('Subject') }}: <span class="font-bold">{{ data.subject }}</span></div>
        <div class="bg-white border-1 rounded p-2" v-html="data.emailBody"></div>
    </div>
</template>
