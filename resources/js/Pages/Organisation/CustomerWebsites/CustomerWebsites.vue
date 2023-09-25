<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 19 Sep 2023 13:37:29 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { Head } from '@inertiajs/vue3'
import PageHeading from '@/Components/Headings/PageHeading.vue'
import TableCustomerWebsites from "@/Components/Tables/TableCustomerWebsites.vue"
import { capitalize } from "@/Composables/capitalize"
import Modal from '@/Components/Utils/Modal.vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faUpload, faFile as falFile, faTimes } from '@/../private/pro-light-svg-icons'
import { faFile as fasFile, faFileDownload } from '@/../private/pro-solid-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
import { trans } from 'laravel-vue-i18n'
import axios from 'axios'
import Pusher from 'pusher-js'
import { useFormatTime } from '@/Composables/useFormatTime'
import Tabs from "@/Components/Navigation/Tabs.vue";
import {useTabChange} from "@/Composables/tab-change";
import ModelDetails from "@/Pages/ModelDetails.vue";
import TableHistories from "@/Components/Tables/TableHistories.vue";

library.add(faUpload, falFile, faTimes, faFileDownload, fasFile)

const props = defineProps<{
    pageHead: any
    title: string
    websites: object
    changelog: object
    uploaded_websites: object
    tabs: {
        current: string;
        navigation: object;
    }
}>()

let currentTab = ref(props.tabs.current);
const handleTabUpdate = (tabSlug) => useTabChange(tabSlug, currentTab);

const component = computed(() => {
    const components = {
        details: ModelDetails,
        changelog: TableHistories,
        websites: TableCustomerWebsites
    };

    return components[currentTab.value];
});

const dataPusher = ref({
    data: {
        total_uploads: 0,
        total_complete: 0
    }
})
const isModalOpen = ref(false)
const isLoadingUpload = ref(false)
const dataHistory: any = ref([])
const isProgressDone = ref(false)


// Pusher: subscribe
const pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
    cluster: 'ap1'
})
const channel = pusher.subscribe('uploads.aiku')
channel.bind('WebsiteUpload', (data: any) => {
    dataPusher.value = data

})

// On upload file website
const onUpload = async (fileUploaded: any) => {
    isLoadingUpload.value = true
    try {
        await axios.post(
            route('customer.models.websites.upload'),
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

// Progress bar for adding website
const compProgressBar = computed(() => {
    return dataPusher.value?.data.total_uploads ? dataPusher.value?.data.total_complete/dataPusher.value?.data.total_uploads * 100 : 0
})

// Fetch data history when Modal is opened
watch(isModalOpen, async () => {
    // if(!dataHistory.value.length) { // If dataHistory empty (not fetched yet) then fetch agains
        try {
            const data = await axios.get(route('customer.portfolio.website.uploads.history'))
            dataHistory.value = data.data.data
        } catch (error: any) {
            console.error(error.message)
        }
    // }
})

// Close progress bar if 100%
watch(compProgressBar, () => {
    if(compProgressBar.value >= 100){
        setTimeout(() => {
            isProgressDone.value = true
        }, 2000)
    }
})

</script>

<template layout="OrgApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead">
        <template #button0>
            <!-- Button Upload -->
            <Button @click="isModalOpen = true" :style="'secondary'" class="rounded-l-none border-none rounded-r-none"  alt="Import website via file">
                <FontAwesomeIcon icon='fas fa-upload' class='' aria-hidden='true' />
            </Button>
        </template>
    </PageHeading>
    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate"/>
    <component :is="component"  :tab="currentTab" :data="props[currentTab]"></component>

    <!-- Modal: Upload -->
    <Modal :isOpen="isModalOpen" @onClose="isModalOpen = false">
        <div class="flex justify-center py-2 text-gray-600 font-medium mb-3">Upload your new website</div>
        <div class="grid grid-cols-2 gap-x-3">
            <!-- Column upload -->
            <div class="space-y-2">
                <div class="relative flex items-center justify-center rounded-lg border border-dashed border-gray-700/25 px-6 h-48 bg-gray-400/10 hover:bg-gray-400/20">
                    <div v-if="!isLoadingUpload">
                        <label for="fileInput"
                            class="absolute cursor-pointer rounded-md inset-0 focus-within:outline-none focus-within:ring-0 focus-within:ring-gray-400 focus-within:ring-offset-0">
                            <input type="file" name="file" id="fileInput" class="sr-only" @change="onUpload"
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
                <a :href="route('customer.portfolio.website.uploads.template.download')" target="_blank" class="group text-xs text-gray-600 cursor-pointer px-2 w-fit" >
                    <FontAwesomeIcon icon='fas fa-file-download' class='text-gray-400 group-hover:text-gray-600' aria-hidden='true' />
                    Download template .xlsx
                </a>
            </div>

            <!-- Table History -->
            <div class="order-last flex items-start gap-x-2 gap-y-2 flex-col">
                <div class="text-sm text-gray-600">Recent uploaded website:</div>
                <div v-if="dataHistory.length" class="flex flex-wrap gap-x-2 gap-y-2">
                    <div v-for="(history, index) in dataHistory" :key="index" class="w-36 bg-gray-100 border-t-[3px] border-gray-500 rounded px-2 py-1 flex flex-col justify-start gap-y-1 cursor-pointer hover:bg-gray-200">
                        <p class="text-lg text-gray-700 font-semibold">{{ history.number_rows }} <span class="text-xs text-gray-500 font-normal">rows</span></p>
                        <span class="text-gray-600 text-xs leading-none">{{ history.original_filename }}</span>
                        <span class="text-gray-400 text-xxs">{{ useFormatTime(history.uploaded_at) }}</span>
                    </div>
                </div>
                <div v-else class="flex flex-wrap gap-x-2 gap-y-2">
                    <div v-for="(history, index) in 4" :key="index" class="w-36 h-20 skeleton rounded" />
                </div>
            </div>
        </div>
    </Modal>

    <!-- Progress Bar -->
    <div :class="compProgressBar && !isProgressDone ? 'bottom-12' : '-bottom-12'" class="z-50 fixed right-1/2 translate-x-1/2 transition-all duration-200 ease-in-out flex gap-x-1">
        <div class="flex justify-center items-center flex-col gap-y-1">
            <div v-if="compProgressBar >=100">Finished!!🥳</div>
            <div v-else>Adding websites ({{ dataPusher.data.total_complete }}/<span class="font-semibold inline">{{ dataPusher.data.total_uploads }}</span>)</div>
            <div class="overflow-hidden rounded-full bg-gray-200 w-64">
                <div class="h-2 rounded-full bg-slate-600 transition-all duration-100 ease-in-out" :style="`width: ${compProgressBar}%`" />
            </div>
        </div>
        <div class="px-2 py-1 cursor-pointer text-gray-400 hover:text-gray-600">
            <FontAwesomeIcon icon='fal fa-times' class='text-xs' aria-hidden='true' />
        </div>
    </div>

</template>
