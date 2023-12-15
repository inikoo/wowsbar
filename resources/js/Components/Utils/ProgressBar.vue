<script setup lang='ts'>
// Used well in EmployeesUpload.vue
import { watch } from 'vue'
import { trans } from 'laravel-vue-i18n'
import { useEchoOrgPersonal } from '@/Stores/echo-org-personal'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faTimes, faFrown, faMeh } from '@fal'
import { faSpinnerThird } from '@fad'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faTimes, faFrown, faMeh, faSpinnerThird)

const props = defineProps<{
    progressData:{
        progressName: string
        // isShowProgress: boolean
    }
    description?: string
}>()

const emits = defineEmits<{
    (e: 'updateShowProgress', newValue: boolean): void
    (e: 'onFinish'): void
}>()

// Watch the progress, if 100% then close popup in 3 seconds
// watch(() => props.progressData.progressPercentage, () => {
//     props.progressData.progressPercentage > 0
//         ? (
//             emits('updateShowProgress', true),
//             props.progressData.progressPercentage == 100
//             ? ( setTimeout(  // If progress 100% (finished)
//                     () => { 
//                         emits('updateShowProgress', false)
//                     }, 4000),
//                 emits('onFinish') )  // Reset data on finish
//             : ''
//         )
//         : emits('updateShowProgress', false)  // If equal 0 (means progress is not running yet)
// }, { immediate: true })

</script>

<template>
    <div :class="useEchoOrgPersonal().isShowProgress ? 'bottom-16' : '-bottom-24'"
        class="backdrop-blur-sm bg-white/60 ring-1 ring-gray-300 rounded-md px-4 py-2 z-50 fixed right-1/2 translate-x-1/2 transition-all duration-200 ease-in-out flex gap-x-6 tabular-nums">
        <template v-if="Object.keys(useEchoOrgPersonal().progressBars.Upload ?? {}).length > 0">
            <TransitionGroup name="progressbar">
                <div v-for="(upload, index) in useEchoOrgPersonal().progressBars.Upload" :key="index" class="flex justify-center items-center flex-col gap-y-1 text-gray-600">
                    <template v-if="upload.total">
                        <div v-if="upload.done >= upload.total">
                            <!-- Label: All failed -->
                            <span v-if="upload.done == upload.data.number_fails" class="text-red-600">
                                Oops Award: No Bravos
                                <FontAwesomeIcon icon='fal fa-frown' class='' aria-hidden='true' />
                            </span>

                            <!-- Label: All success -->
                            <span v-else-if="upload.done == upload.data.number_success" class="text-gray-500">
                                Success Streak: Nailed it🥳
                            </span>

                            <!-- Label: Fails is bigger -->
                            <span v-else-if="upload.data.number_success < upload.data.number_fails" class="text-gray-500">
                                Oops, more fails than victories!
                                <FontAwesomeIcon icon='fal fa-meh' class='' aria-hidden='true' />
                            </span>

                            <!-- Label: Success is bigger -->
                            <span v-else class="text-lime-600">Yeah, success roars😎</span>
                        </div>
                        <div v-else>{{ description ?? trans('Adding')}} ({{ upload.data.number_success + upload.data.number_fails }}/<span class="font-semibold inline">{{ upload.total }}</span>)</div>
                
                        <!-- Progress Bar -->
                        <div class="overflow-hidden rounded-full bg-gray-100 ring-1 ring-gray-300 w-64 flex justify-start">
                            <div class="h-2 bg-lime-600 transition-all duration-100 ease-in-out" :style="`width: ${(upload.data.number_success/upload.total)*100}%`" />
                            <div class="h-2 bg-red-500 transition-all duration-100 ease-in-out" :style="`width: ${(upload.data.number_fails/upload.total)*100}%`" />
                        </div>
                        <!-- Result count -->
                        <div class="flex w-full justify-around">
                            <div class="text-lime-600">Success: {{ upload.data.number_success }}</div>
                            <div class="text-red-500">Fails: {{ upload.data.number_fails }}</div>
                        </div>
                    </template>
                </div>
            </TransitionGroup>
        </template>
        <div v-else class="w-64 flex justify-center flex-col items-center gap-y-2 py-1 text-gray-500">
            <FontAwesomeIcon icon='fad fa-spinner-third' class='animate-spin' aria-hidden='true' />
            <div class="text-sm">Calculating data..</div>
        </div>


        <div @click="useEchoOrgPersonal().isShowProgress = false" class="absolute top-0 right-1 px-2 py-1 cursor-pointer text-gray-500 hover:text-gray-600">
            <FontAwesomeIcon icon='fal fa-times' class='text-xs' aria-hidden='true' />
        </div>
    </div>
</template>

<style scoped>
.progressbar-move, /* apply transition to moving elements */
.progressbar-enter-active,
.progressbar-leave-active {
    transition: all 0.5s ease;
}

.progressbar-enter-from,
.progressbar-leave-to {
    opacity: 0;
    transform: scale(0.1);
}

/* ensure leaving items are taken out of layout flow so that moving
   animations can be calculated correctly. */
.progressbar-leave-active {
    position: absolute;
}
</style>