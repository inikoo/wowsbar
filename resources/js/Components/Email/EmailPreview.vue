<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 17 Nov 2023 21:00:29 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang='ts'>
import { trans } from "laravel-vue-i18n"
import ScreenView from '@/Components/ScreenView.vue'
import { ref, computed } from 'vue'

const props = defineProps<{
    data: {
        sender: string
        subject: string,
        emailBody: string
    }
}>()

const screenHeight = window.innerHeight  // To set IFrame height on first load

// First load: get device name depend on window width 
const deviceName = ref(window.innerWidth >= 1024
    ? 'desktop'
    : window.innerWidth >= 768
        ? 'tablet'
        : 'mobile')

// Getter: IFrame width depend on device name
const compIframeWidth = computed(() => {
    return (
        deviceName.value == 'desktop' ? 1200
            : deviceName.value == 'tablet' ? 800
                : 500
    )
})

</script>

<template>
    <div class="m-2 text-gray-500">
        <div class="bg-white rounded p-2 mb-1">{{ trans('From') }}: <span class="font-bold text-gray-600">{{ data.sender }}</span></div>
        <div class="bg-white rounded p-2 mb-1">{{ trans('Subject') }}: <span class="font-bold text-gray-600">{{ data.subject }}</span></div>
        <div class="bg-white shadow-md border border-gray-300 rounded">
            <ScreenView :currentView="deviceName" @screenView="(deviceType) => deviceName = deviceType" class="justify-end" />
            <iframe :srcdoc="data.emailBody" :style="{ width: `${compIframeWidth}px` }" :height="screenHeight" />
        </div>
    </div>
</template>
