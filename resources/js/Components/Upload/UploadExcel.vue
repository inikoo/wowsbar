<script setup lang='ts'>

import ModalUpload from '@/Components/Utils/ModalUpload.vue'
import ProgressBar from '@/Components/Utils/ProgressBar.vue'
import { ref, computed, onMounted } from 'vue';
import { routeType } from '@/types/route'
import { router } from '@inertiajs/vue3'

const props = defineProps<{
    routes: {
        upload: routeType
        download?: routeType
        history?: routeType
    }
    dataModal: {
        isModalOpen: boolean
    }
    dataPusher: {
        channel: string
        event: string
    }
    description?: string
    propName?: string
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


// Progress bar for adding file
const compProgressBar = computed(() => {
    return dataPusher.value?.data.number_rows ? (dataPusher.value?.data.number_success+dataPusher.value?.data.number_fails)/dataPusher.value?.data.number_rows * 100 : 0
})

// To manipulation data history file upload
const recentlyUploaded = ref<{}[]>([])

// On finish uploading
const onFinish = () => {
    recentlyUploaded.value.push(dataPusher.value.data)
    setTimeout(() => {
    // Reset data from Pusher binding
        dataPusher.value.data = { number_rows: 0, number_success: 0, number_fails: 0 }  // Can lead to isShowProgress to false
    }, 6000)
    if(props.propName) {
        router.reload({
            only: [props.propName],  // only reload the props prospects so the table is updated
        })
    }
}
</script>

<template>
    <!-- Modal: Upload -->
    <KeepAlive>
        <ModalUpload
            v-model="dataModal.isModalOpen"
            :routes="routes"
            :recentlyUploaded="recentlyUploaded"
            @isShowProgress="isShowProgress = true"
            :propName="propName"
        />
    </KeepAlive>

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
        @onFinish="onFinish"
    />

</template>
