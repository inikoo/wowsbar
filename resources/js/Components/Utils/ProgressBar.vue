<script setup lang='ts'>
// Used well in EmployeesUpload.vue
import { watch } from 'vue'
import { trans } from 'laravel-vue-i18n'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faTimes } from '@fal'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faTimes)

const props = defineProps<{
    progressData:{
        progressName: string
        isShowProgress: boolean
        progressPercentage: number
        countSuccess: number
        countFails: number
        countTotal: number
    }
    description?: string
}>()

const emits = defineEmits<{
    (e: 'updateShowProgress', newValue: boolean): void
    (e: 'onFinish'): void
}>()

// Watch the progress, if 100% then close popup in 3 seconds
watch(() => props.progressData.progressPercentage, () => {
    props.progressData.progressPercentage > 0
        ? (
            emits('updateShowProgress', true),
            props.progressData.progressPercentage == 100
            ? ( setTimeout(  // If progress 100% (finished)
                    () => { 
                        emits('updateShowProgress', false)
                    }, 4000),
                emits('onFinish') )  // Reset data on finish
            : ''
        )
        : emits('updateShowProgress', false)  // If equal 0 (means progress is not running yet)
}, { immediate: true })

</script>

<template>
    <div :class="progressData.isShowProgress ? 'bottom-16' : '-bottom-20'" class="backdrop-blur-sm bg-white/60 ring-1 ring-gray-300 rounded-md px-4 py-2 z-50 fixed right-1/2 translate-x-1/2 transition-all duration-200 ease-in-out flex gap-x-1 tabular-nums">
        <div class="flex justify-center items-center flex-col gap-y-1 text-gray-600">
            <div v-if="progressData.progressPercentage >= 100">Finished!!🥳</div>
            <div v-else>{{ description ?? trans('Adding')}} ({{ progressData.countSuccess + progressData.countFails }}/<span class="font-semibold inline">{{ progressData.countTotal }}</span>)</div>
            
            <!-- Progress Bar -->
            <div class="overflow-hidden rounded-full bg-white w-64 flex justify-start">
                <div class="h-2 bg-lime-600 transition-all duration-100 ease-in-out" :style="`width: ${(progressData.countSuccess/progressData.countTotal)*100}%`" />
                <div class="h-2 bg-red-500 transition-all duration-100 ease-in-out" :style="`width: ${(progressData.countFails/progressData.countTotal)*100}%`" />
            </div>

            <!-- Result count -->
            <div class="flex w-full justify-around">
                <div class="text-lime-600">Success: {{ progressData.countSuccess }}</div>
                <div class="text-red-500">Fails: {{ progressData.countFails }}</div>
            </div>
        </div>

        <div @click="emits('updateShowProgress', false)" class="absolute top-0 right-1 px-2 py-1 cursor-pointer text-gray-500 hover:text-gray-600">
            <FontAwesomeIcon icon='fal fa-times' class='text-xs' aria-hidden='true' />
        </div>
    </div>
</template>