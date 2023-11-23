<script setup lang='ts'>
import Pusher from 'pusher-js'
import Echo from 'laravel-echo'
import ModalUpload from '@/Components/Utils/ModalUpload.vue'
import ProgressBar from '@/Components/Utils/ProgressBar.vue'
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';

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
    description?: string
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
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER
})
const channel = pusher.subscribe(props.dataPusher.channel)
channel.bind(props.dataPusher.event, (data: any) => {
    console.log('sssssssssssssss')
    dataPusher.value = data
})

// Progress bar for adding website
const compProgressBar = computed(() => {
    return dataPusher.value?.data.number_rows ? (dataPusher.value?.data.number_success+dataPusher.value?.data.number_fails)/dataPusher.value?.data.number_rows * 100 : 0
})

window.Echo.channel('uploads.org.1').listen('Prospect', () => {
    console.log("xxxxxxxxxxxx")
    console.log(e)
})

console.log(window.Echo.socketId())

// to connect the privatechannel
window.Echo.private('uploads.org.1').listen('Prospect', ()=>{
    console.log("aaaaaaaaaaaaaaaaa")
})

onBeforeUnmount(() => {
    window.Echo.channel('uploads.org.1')
    .stopListening('Prospect')
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
            countSuccess: dataPusher.data.number_success,
            countFails: dataPusher.data.number_fails,
            countTotal: dataPusher.data.number_rows
        }"
        :description="description"
        @updateShowProgress="(newValue: boolean) => isShowProgress = newValue"
        @resetData="dataPusher.data = { number_rows: 0, number_success: 0, number_fails: 0 }"
    />

</template>