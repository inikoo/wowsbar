<script setup lang='ts'>
import Pusher from 'pusher-js'
import ModalUpload from '@/Components/Utils/ModalUpload.vue'
import ProgressBar from '@/Components/Utils/ProgressBar.vue'
import { ref, computed } from 'vue'

const props = defineProps<{
    routesModalUpload: {
        upload: {
            name: string
        }
        download?: {
            name: string
        }
    }
    dataModal: {
        isModalOpen: boolean
    }
    dataPusher: {
        channel: string
        event: string
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
const channel = pusher.subscribe(props.dataPusher.channel)
channel.bind(props.dataPusher.event, (data: any) => {
    dataPusher.value = data
})

// Progress bar for adding website
const compProgressBar = computed(() => {
    return dataPusher.value?.data.number_rows ? (dataPusher.value?.data.number_success+dataPusher.value?.data.number_fails)/dataPusher.value?.data.number_rows * 100 : 0
})

</script>

<template>
    <!-- Modal: Upload -->
    <ModalUpload
        v-model="dataModal.isModalOpen"
        :routes="routesModalUpload"
        @isShowProgress="isShowProgress = true"
    />

    <ProgressBar
        :progressData="{
            progressName: 'employees',
            isShowProgress: isShowProgress,
            progressPercentage: compProgressBar,
            countSuccess: dataPusher.data.number_success+dataPusher.data.number_success,
            countFails: dataPusher.data.number_success+dataPusher.data.number_fails,
            countTotal: dataPusher.data.number_success+dataPusher.data.number_rows
        }"
        @updateShowProgress="(newValue: boolean) => isShowProgress = newValue"
        @resetData="dataPusher.data = { number_rows: 0, number_success: 0, number_fails: 0 }"
    />

</template>