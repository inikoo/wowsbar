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

const isProgress = ref(false)
const dataPusher = ref({
    data: {
        total_uploads: 0,
        total_complete: 0
    }
})

const isUploaded = ref(false)

// Pusher: subscribe
const pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
    cluster: 'ap1'
})
const channel = pusher.subscribe('uploads.org')
channel.bind('Employee', (data: any) => {
    console.log(data)
    dataPusher.value = data
    console.log(dataPusher.value)
})

// Progress bar for adding website
const compProgressBar = computed(() => {
    return dataPusher.value?.data.total_uploads ? dataPusher.value?.data.total_complete/dataPusher.value?.data.total_uploads * 100 : 100
})

// Watch the progress, if 100% then close popup in 3 seconds
watch(compProgressBar, () => {
    compProgressBar.value >= 100 ? setTimeout(() => {
        isProgress.value = true
    }, 3000) : ''
}, {immediate: true})


</script>

<template>
    <!-- Modal: Upload -->
    <ModalUpload
        v-model="dataModal.isModalOpen"
        :routes="routesModalUpload"
        :isUploaded="isUploaded"
        @isUploaded="(val: any) => isUploaded = val"
    />

    <div :class="isUploaded && !isProgress ? 'bottom-12' : '-bottom-12'" class="z-50 fixed right-1/2 translate-x-1/2 transition-all duration-200 ease-in-out flex gap-x-1">
        <div class="flex justify-center items-center flex-col gap-y-1">
            <div v-if="compProgressBar >= 100">Finished!!🥳</div>
            <div v-else>Adding Employees ({{ dataPusher.data.total_complete }}/<span class="font-semibold inline">{{ dataPusher.data.total_uploads }}</span>)</div>
            <div class="overflow-hidden rounded-full bg-gray-200 w-64">
                <div class="h-2 rounded-full bg-slate-600 transition-all duration-100 ease-in-out" :style="`width: ${compProgressBar}%`" />
            </div>
        </div>

        <div class="px-2 py-1 cursor-pointer text-gray-400 hover:text-gray-600">
            <FontAwesomeIcon icon='fal fa-times' class='text-xs' aria-hidden='true' />
        </div>
    </div>
</template>