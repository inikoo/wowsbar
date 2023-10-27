<script setup lang='ts'>
import Pusher from 'pusher-js'
import ModalUpload from '@/Components/Utils/ModalUpload.vue'
import { ref, computed, watch } from 'vue'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faUpload } from '@fas/'
import { faTimes } from '@fal/'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faUpload, faTimes)

const props = defineProps<{
    routesModalUpload: {
        upload: {
            name: string
        }
        download: {
            name: string
        }
    }
    dataModal: {
        isModalOpen: boolean
    }
}>()

const emits = defineEmits<{
    (e: 'onCloseModal', value: boolean): void
}>()

// Declare null data to avoid undefined
const dataPusher = ref({
    data: {
        number_rows: 0,
        number_success: 0,
        number_fails: 0
    }
})

const isShowProgress = ref(false)

// Pusher: subscribe
const pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
    cluster: 'ap1'
})
const channel = pusher.subscribe('uploads.org')
channel.bind('Employee', (data: any) => {
    // console.log(data)
    dataPusher.value = data
    // console.log(dataPusher.value)
})

// Progress bar for adding website
const compProgressBar = computed(() => {
    return dataPusher.value?.data.number_rows ? (dataPusher.value?.data.number_success+dataPusher.value?.data.number_fails)/dataPusher.value?.data.number_rows * 100 : 0
})

// Watch the progress, if 100% then close popup in 3 seconds
watch(compProgressBar, () => {
    compProgressBar.value > 0
        ? compProgressBar.value < 100
            ? isShowProgress.value = true
            : setTimeout(
                () => {
                    isShowProgress.value = false,
                    setTimeout(() => dataPusher.value.data = {number_rows: 0, number_success: 0, number_fails: 0}, 500)  // Clear data on finish, 3000)
                }, 3000)
        : isShowProgress.value = false
}, { immediate: true })


</script>

<template>
    <!-- Modal: Upload -->
    <ModalUpload
        v-model="dataModal.isModalOpen"
        :routes="routesModalUpload"
        @isShowProgress="isShowProgress = true"
    />

    <div :class="isShowProgress ? 'bottom-16' : '-bottom-16'" class="z-50 fixed right-1/2 translate-x-1/2 transition-all duration-200 ease-in-out flex gap-x-1 tabular-nums">
        <div class="flex justify-center items-center flex-col gap-y-1 text-gray-600">
            <div v-if="compProgressBar >= 100">Finished!!🥳</div>
            <div v-else>Adding Employees ({{ dataPusher.data.number_success+dataPusher.data.number_fails }}/<span class="font-semibold inline">{{ dataPusher.data.number_rows }}</span>)</div>
            <!-- <div class="flex rounded overflow-hidden border border-gray-400 tabular-nums">
                <div class="bg-gray-500 text-white px-2 py-1">
                    {{ dataPusher.data.number_rows }}
                </div>
                <div class="flex items-center bg-gray-100 px-3 gap-x-4 text-gray-500">
                    <div>Success: <span class="font-semibold inline text-lime-500">{{ dataPusher.data.number_success }}</span></div>
                    <div>Fails: <span class="font-semibold inline text-orange-500">{{ dataPusher.data.number_fails }}</span></div>
                </div>
            </div> -->
            <div class="overflow-hidden rounded-full bg-gray-200 w-64 flex justify-start">
                <div class="h-2 bg-lime-400 transition-all duration-100 ease-in-out" :style="`width: ${(dataPusher.data.number_success/dataPusher.data.number_rows)*100}%`" />
                <div class="h-2 bg-red-500 transition-all duration-100 ease-in-out" :style="`width: ${(dataPusher.data.number_fails/dataPusher.data.number_rows)*100}%`" />
            </div>
            <div class="flex w-full justify-around">
                <div class="text-lime-400">Success: {{ dataPusher.data.number_success }}</div>
                <div class="text-red-500">Fails: {{ dataPusher.data.number_fails }}</div>
            </div>
        </div>

        <div @click="isShowProgress = false" class="px-2 py-1 cursor-pointer text-gray-400 hover:text-gray-600">
            <FontAwesomeIcon icon='fal fa-times' class='text-xs' aria-hidden='true' />
        </div>

        <!-- <div class="flex justify-center items-center flex-col gap-y-1">
            <div>Finished!!🥳</div>
            <div class="flex rounded overflow-hidden border border-gray-400 tabular-nums">
                <div class="bg-gray-500 text-white px-2 py-1">
                    {{ dataPusher.data.number_rows }}
                </div>
                <div class="flex items-center bg-gray-100 px-3 gap-x-4 text-gray-500">
                    <div>Success: <span class="font-semibold inline text-lime-500">{{ dataPusher.data.number_success }}</span></div>
                    <div>Fails: <span class="font-semibold inline text-orange-500">{{ dataPusher.data.number_fails }}</span></div>
                </div>
            </div>
            
        </div> -->

        <!-- <div @click="isShowProgress = false" class="px-2 py-1 cursor-pointer text-gray-400 hover:text-gray-600">
            <FontAwesomeIcon icon='fal fa-times' class='text-xs' aria-hidden='true' />
        </div> -->
    </div>
</template>