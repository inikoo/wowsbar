<script setup lang='ts'>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faExclamationTriangle } from '@fal/'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faExclamationTriangle)
    
const props = defineProps<{
    message: string
}>()

const onClickSetting = () => {

}
</script>

<template>
    <div class="px-5 pt-1.5 pb-1 w-full border-b border-yellow-500/20 bg-yellow-500/10 flex items-center gap-x-1.5 text-gray-500 text-sm">
        <FontAwesomeIcon icon='fal fa-exclamation-triangle' class='h-4 text-gray-500' aria-hidden='true' />
        <div class="">{{ message }}</div>
        <!-- <div class="">Your email's sender mailshot is failed to verified.</div> -->
        <!-- <div class="">Please verify your email's sender mailshot.</div> -->
        <!-- <div @click="onClickSetting" class="underline hover:text-gray-600 cursor-pointer font-semibold">Setting</div> -->
    </div>
</template>