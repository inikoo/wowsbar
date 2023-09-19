<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { trans } from 'laravel-vue-i18n'

import TablePortfolioWebsites from "@/Components/Tables/TablePortfolioWebsites.vue"
import Modal from '@/Components/Utils/Modal.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faUpload, faFile as falFile, faTimes } from '@/../private/pro-light-svg-icons'
import { faFile as fasFile, faFileDownload } from '@/../private/pro-solid-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
import axios from 'axios'
import Pusher from 'pusher-js'
import { useFormatTime } from '@/Composables/useFormatTime'
import Tabs from "@/Components/Navigation/Tabs.vue";
import {useTabChange} from "@/Composables/tab-change";
import ModelDetails from "@/Pages/ModelDetails.vue";
import TableHistories from "@/Components/Tables/TableHistories.vue";
import TableUploadedWebsites from "@/Components/Tables/TableUploadedWebsites.vue";
import { toRefs } from 'vue'

library.add(falFile, faFileDownload)


const props = defineProps<{
    modelValue: Boolean
    routeUpload: string
    routeDownload: string
    routeHistory: string
}>()

defineEmits(['update:modelValue'])

const isLoadingUpload = ref(false)
const dataHistory: any = ref([])
const isLoadingHistory = ref(false)
const isProgressDone = ref(false)

// Running when file is uploaded
const onUploadFile = async (fileUploaded: any) => {
    isLoadingUpload.value = true
    try {
        await axios.post(
            route(props.routeUpload),
            {
                file: fileUploaded.target.files[0],
            },
            {
                headers: { "Content-Type": "multipart/form-data" },
            }
        )
    } catch (error: any) {
        // console.error("===========================")
        console.error(error.message)
    }
    isLoadingUpload.value = false
}

const compVModel = computed(() => {
    return props.modelValue
})

// Fetch data history when Modal is opened
watch(compVModel, async () => {
    isLoadingHistory.value = true
    // if(!dataHistory.value.length) { // If dataHistory empty (not fetched yet) then fetch agains
        try {
            const data = await axios.get(route(props.routeHistory))
            dataHistory.value = data.data.data
        } catch (error: any) {
            dataHistory.value = []
            console.error(error.message)
        }
    isLoadingHistory.value = false
    // }
})

</script>

<template>
    <Modal :isOpen="modelValue" @onClose="() => $emit('update:modelValue', false)">
        <div class="flex justify-center py-2 text-gray-600 font-medium mb-3">Upload your new website</div>
        <div class="grid grid-cols-2 gap-x-3">
            <!-- Column upload -->
            <div class="space-y-2">
                <div class="relative flex items-center justify-center rounded-lg border border-dashed border-gray-700/25 px-6 h-48 bg-gray-400/10 hover:bg-gray-400/20">
                    <div v-if="!isLoadingUpload">
                        <label for="fileInput"
                            class="absolute cursor-pointer rounded-md inset-0 focus-within:outline-none focus-within:ring-0 focus-within:ring-gray-400 focus-within:ring-offset-0">
                            <input type="file" name="file" id="fileInput" class="sr-only" @change="onUploadFile"
                                ref="fileInput" accept=".xlsx, .xls, .csv"/>
                        </label>
                        <div class="text-center text-gray-500">
                            <FontAwesomeIcon icon="fal fa-file" class="mx-auto h-12 w-12 text-gray-300" aria-hidden="true" />
                            <div class="mt-2 flex justify-center text-lg font-medium leading-6 ">
                                <p class="pl-1">{{ trans("Upload file") }}</p>
                            </div>
                            <div class="flex text-sm leading-6 ">
                                <p class="pl-1">{{ trans("Click or drag & drop") }}</p>
                            </div>
                            <p class="text-xs">
                                {{ trans(".csv, .xls, .xlsx") }}
                            </p>
                        </div>
                    </div>

                    <!-- Loading state: if upload progress -->
                    <div v-else class="text-center">
                        <FontAwesomeIcon icon='fad fa-spinner-third' class='animate-spin h-8' aria-hidden='true' />
                        <p class="text-gray-500">Uploading..</p>
                    </div>
                </div>

                <!-- Download template -->
                <a :href="route(routeDownload)" target="_blank" class="group text-xs text-gray-600 cursor-pointer px-2 w-fit" >
                    <FontAwesomeIcon icon='fas fa-file-download' class='text-gray-400 group-hover:text-gray-600' aria-hidden='true' />
                    Download template .xlsx
                </a>
            </div>

            <!-- Table History -->
            <div class="order-last flex items-start gap-x-2 gap-y-2 flex-col">
                <div class="text-sm text-gray-600">Recent uploaded website:</div>
                <div v-if="!isLoadingHistory" class="flex flex-wrap gap-x-2 gap-y-2">
                    <template v-if="dataHistory.length">
                        <div v-for="(history, index) in dataHistory" :key="index" class="w-36 bg-gray-100 border-t-[3px] border-gray-500 rounded px-2 py-1 flex flex-col justify-start gap-y-1 cursor-pointer hover:bg-gray-200">
                            <p class="text-lg text-gray-700 font-semibold">{{ history.number_rows }} <span class="text-xs text-gray-500 font-normal">rows</span></p>
                            <span class="text-gray-600 text-xs leading-none">{{ history.original_filename }}</span>
                            <span class="text-gray-400 text-xxs">{{ useFormatTime(history.uploaded_at) }}</span>
                        </div>
                    </template>
                    <div v-else class="text-gray-500 text-xs">
                        No history found.
                    </div>
                </div>
                <div v-else class="flex flex-wrap gap-x-2 gap-y-2">
                    <div v-for="(history, index) in 4" :key="index" class="w-36 h-20 skeleton rounded" />
                </div>
            </div>
        </div>
    </Modal>
</template>

<style scoped>

</style>
