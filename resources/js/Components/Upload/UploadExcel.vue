<script setup lang='ts'>

import ModalUpload from '@/Components/Utils/ModalUpload.vue'
import ProgressBar from '@/Components/Utils/ProgressBar.vue'
import { ref, computed, watch } from 'vue';
import { routeType } from '@/types/route'
import { router } from '@inertiajs/vue3'
import { useEchoOrgPersonal } from '@/Stores/echo-org-personal'

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

const isShowProgress = ref(false)


// Progress bar for adding file
// const compProgressBar = computed(() => {
//     return useEchoOrgPersonal().progressBars.total ? useEchoOrgPersonal().progressBars.done/useEchoOrgPersonal().progressBars.total * 100 : 0
// })


// On finish uploading
const onFinish = () => {
    // setTimeout(() => {
    //     // Reset data from Pusher binding
    //     useEchoOrgPersonal().progressBars = { data: {
    //             number_success: 0,
    //             number_fails: 0
    //         },
    //         done: 0,
    //         total: 0
    //     }  // Reset value, Can lead to isShowProgress to false
    // }, 6000)
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
            :propName="propName"
        />
    </KeepAlive>

    <ProgressBar
        :progressData="{
            progressName: 'employees'
        }"
        :description="description"
        @updateShowProgress="(newValue: boolean) => isShowProgress = newValue"
        @onFinish="onFinish"
    />

</template>
