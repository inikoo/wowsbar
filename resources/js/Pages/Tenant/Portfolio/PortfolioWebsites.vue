<!--
  -  Author: Jonathan Lopez <raul@inikoo.com>
  -  Created: Wed, 12 Oct 2022 16:50:56 Central European Summer Time, Benalmádena, Malaga,Spain
  -  Copyright (c) 2022, Jonathan Lopez
  -->

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { Head } from '@inertiajs/vue3'
import PageHeading from '@/Components/Headings/PageHeading.vue'
import TablePortfolioWebsites from "@/Pages/Tables/TablePortfolioWebsites.vue"
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

library.add(faUpload, falFile, faTimes, faFileDownload, fasFile)

const props = defineProps<{
    pageHead: object
    title: string
    data: object
}>()

const dataPusher = ref({
    data: {
        total_uploads: 0,
        total_complete: 0
    }
})

// Pusher: subscribe
const pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
    cluster: 'ap1'
})
const channel = pusher.subscribe('uploads.aiku')
channel.bind('WebsiteUpload', (data: any) => {
    dataPusher.value = data
    // console.log("==============")
    // console.log(data)
})

const isModalOpen = ref(false)
const isLoadingFetch = ref(false)
const onUpload = async (fileUploaded: any) => {
    isLoadingFetch.value = true
    try {
        await axios.post(
            route('models.websites.upload'),
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
    isLoadingFetch.value = false
}

const uploadHistory = [
    { name: 'dummy.xlsx', row: 132, date_uploaded: 'August 02 2023'},
    { name: 'default.csv', row: 15, date_uploaded: 'Sept 05 2023'},
    { name: 'untitled untitled untitled.csv', row: 47, date_uploaded: 'Oct 03 2023'},
    { name: 'new file.xlsx', row: 114, date_uploaded: 'August 19 2023'},
]

const numberr = ref(0)

// setInterval(() => {
//     numberr.value = numberr.value + 200
// }, 500)

const compProgressBar = computed(() => {
    return dataPusher.value?.data.total_uploads ? dataPusher.value?.data.total_complete/dataPusher.value?.data.total_uploads * 100 : 0
})

watch(isModalOpen, async () => {
    try {
        const data = await axios.get(route('portfolio.website.uploads.history'))
        console.log("------------------------")
        console.log(data)
    } catch (error: any) {
        // console.error("===========================")
        console.error(error.message)
    }
})
// watch(isModalOpen, (newValue, oldValue) => {
//   // Check if isOpen changed from false to true
//   console.log('oldValue', oldValue)
//   console.log('newValue', newValue)
// })
</script>

<template layout="TenantApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead">
        <template #button0>
            <!-- Button Upload -->
            <Button @click="isModalOpen = true" :style="'secondary'" class="rounded-l-none border-none rounded-r-none"  alt="Import website via file">
                <FontAwesomeIcon icon='fas fa-upload' class='' aria-hidden='true' />
            </Button>
        </template>
    </PageHeading>
    <TablePortfolioWebsites :data="data" />

    <!-- Modal: Upload -->
    <Modal :isOpen="isModalOpen" @onClose="isModalOpen = false">
        <div class="flex justify-center py-2 text-gray-600 font-medium mb-3">Upload your new website</div>
        <div class="grid grid-cols-2 gap-x-3">
            <!-- Column upload -->
            <div class="space-y-2">
                <div class="relative flex justify-center rounded-lg border border-dashed border-gray-700/25 px-6 py-10 bg-gray-400/10 hover:bg-gray-400/20">
                    <label for="fileInput"
                        class="absolute cursor-pointer rounded-md inset-0 focus-within:outline-none focus-within:ring-0 focus-within:ring-gray-400 focus-within:ring-offset-0">
                        <input type="file" name="file" id="fileInput" class="sr-only" @change="onUpload"
                            ref="fileInput" accept=".xlsx, .xls, .csv"/>
                    </label>
                    <div class="text-center text-gray-500">
                        <FontAwesomeIcon icon="fal fa-file" class="mx-auto h-12 w-12 text-gray-300" aria-hidden="true" />
                        <div class="mt-2 flex  justify-center text-lg font-medium leading-6 ">
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

                <!-- Download template -->
                <a :href="route('portfolio.website.uploads.template.download')" target="_blank" class="group text-xs text-gray-600 cursor-pointer px-2 w-fit" >
                    <FontAwesomeIcon icon='fas fa-file-download' class='text-gray-400 group-hover:text-gray-600' aria-hidden='true' />
                    Download template .xlsx
                </a>
            </div>

            <!-- Table History -->
            <div class="order-last flex items-start gap-x-2 gap-y-2 flex-col">
                <div class="text-sm text-gray-600">Recent uploaded website:</div>
                <div class="flex flex-wrap gap-x-2 gap-y-2">
                    <div v-for="(person, index) in uploadHistory" :key="index" class="w-36 bg-gray-100 border-t-[3px] border-gray-500 rounded px-2 py-1 flex flex-col justify-start gap-y-1 cursor-pointer hover:bg-gray-200">
                        <p class="text-lg text-gray-700 font-semibold">{{ person.row }} <span class="text-xs text-gray-500 font-normal">rows</span></p>
                        <span class="text-gray-600 text-xs leading-none">{{ person.name }}</span>
                        <span class="text-gray-400 text-xxs">{{ person.date_uploaded }}</span>
                    </div>
                </div>
            </div>
        </div>
    </Modal>

    <!-- Progress Bar -->
    <div :class="compProgressBar ? 'bottom-12' : '-bottom-12'" class="z-50 fixed right-1/2 translate-x-1/2 transition-all duration-200 ease-in-out flex gap-x-1">
        <div class="flex justify-center items-center flex-col gap-y-1">
            <div>Uploading website ({{ dataPusher.data.total_complete }}/<span class="font-semibold inline">{{ dataPusher.data.total_uploads }}</span>)</div>
            <div class="overflow-hidden rounded-full bg-gray-200 w-64">
                <div class="h-2 rounded-full bg-slate-600 transition-all duration-100 ease-in-out" :style="`width: ${compProgressBar}%`" />
            </div>
        </div>
        <div class="px-2 py-1 cursor-pointer text-gray-400 hover:text-gray-600">
            <FontAwesomeIcon icon='fal fa-times' class='text-xs' aria-hidden='true' />
        </div>
    </div>

</template>