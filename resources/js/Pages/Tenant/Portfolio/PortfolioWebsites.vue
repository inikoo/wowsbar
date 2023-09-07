<!--
  -  Author: Jonathan Lopez <raul@inikoo.com>
  -  Created: Wed, 12 Oct 2022 16:50:56 Central European Summer Time, Benalmádena, Malaga,Spain
  -  Copyright (c) 2022, Jonathan Lopez
  -->

<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import PageHeading from '@/Components/Headings/PageHeading.vue'
import TablePortfolioWebsites from "@/Pages/Tables/TablePortfolioWebsites.vue"
import { capitalize } from "@/Composables/capitalize"
import Modal from '@/Components/Utils/Modal.vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faUpload } from '@/../private/pro-regular-svg-icons'
import { faFile, faTimes } from '@/../private/pro-light-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
import { trans } from 'laravel-vue-i18n'
import axios from 'axios'

import Pusher from 'pusher-js'


const pusher = new Pusher('3217cf955501353f32b2', {
    cluster: 'ap1'
});

const asdfg = ref()

const channel = pusher.subscribe('uploads.aiku');
    channel.bind('WebsiteUpload', (data: any) => {
        asdfg.value = data
        console.log("==============")
        console.log(data)
    });

library.add(faUpload, faFile, faTimes)

const props = defineProps<{
    pageHead: object
    title: string
    data: object
}>()

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
    { name: 'dummy.xlsx', date_uploaded: 'August 02 2023'},
    { name: 'default.csv', date_uploaded: 'Sept 05 2023'},
    { name: 'untitled.csv', date_uploaded: 'Oct 03 2023'},
    { name: 'new file.xlsx', date_uploaded: 'August 19 2023'},
]

const numberr = ref(0)

// setInterval(() => {
//     numberr.value = numberr.value + 200
// }, 500)

const compProgressBar = computed(() => {
    return numberr.value/1000 * 100
})
</script>

<template layout="TenantApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead">
        <template #button0>
            <!-- Button Upload -->
            <Button @click="isModalOpen = true" :style="'secondary'" class="rounded-l-none border-none rounded-r-none"  alt="Import website via file">
                <FontAwesomeIcon icon='fal fa-upload' class='' aria-hidden='true' />
            </Button>
        </template>
    </PageHeading>

    <!-- Progress Bar -->
    <div :class="true ? 'bottom-12' : '-bottom-12'" class="fixed right-1/2 translate-x-1/2 transition-all duration-200 ease-in-out flex gap-x-1">
        <div class="flex justify-center items-center flex-col gap-y-1">
            <div>Uploading website ({{numberr}}/<span class="font-semibold inline">1000</span>)</div>
            <div class="overflow-hidden rounded-full bg-gray-200 w-64">
                <div class="h-2 rounded-full bg-slate-600 transition-all duration-100 ease-in-out" :style="`width: ${compProgressBar}%`" />
            </div>
        </div>
        <div class="px-2 py-1 cursor-pointer text-gray-400 hover:text-gray-600">
            <FontAwesomeIcon icon='fal fa-times' class='text-xs' aria-hidden='true' />
        </div>
    </div>

    <TablePortfolioWebsites :data="data" />

    <!-- Modal: Upload -->
    <Modal :isOpen="isModalOpen" @onClose="isModalOpen = false">
        <div class="grid grid-cols-2 grid-rows-2">
            <div class="relative mt-2 flex justify-center rounded-lg border border-dashed border-gray-700/25 px-6 py-10 bg-gray-400/10 hover:bg-gray-400/20">
                <label for="fileInput" 
                    class="absolute cursor-pointer rounded-md inset-0 focus-within:outline-none focus-within:ring-0 focus-within:ring-gray-400 focus-within:ring-offset-0">
                    <input type="file" name="file" id="fileInput" class="sr-only" @change="onUpload"
                        ref="fileInput" accept=".xlsx, .xls, .csv"/>
                </label>
                <div class="text-center text-gray-500">
                    <FontAwesomeIcon :icon="['fal', 'file']" class="mx-auto h-12 w-12 text-gray-300" aria-hidden="true" />
                    <div class="mt-2 flex  justify-center text-lg font-medium leading-6 ">
                        <p class="pl-1">{{ trans("Upload file") }}</p>
                    </div>
                    <div class="flex text-sm leading-6 ">
                        <p class="pl-1">{{ trans("Click or drag & drop") }}</p>
                    </div>
                    <p class="text-[0.8rem]">
                        {{ trans(".csv, .xls, .xlsx") }}
                    </p>
                </div>
            </div>
            <div >
                <FontAwesomeIcon v-if="isLoadingFetch" icon='fad fa-spinner-third' class='animate-spin w-1/4 h-1/4' aria-hidden='true' />
            </div>
            <div />
            <div class="order-last flex items-end">
                <table class="flex-none w-full text-left text-gray-500 text-sm rounded overflow-hidden ring-1 ring-gray-300">
                    <thead class="bg-gray-100">
                        <tr class="border-b border-gray-300">
                            <th scope="col" class="px-2 pt-2 pb-1 text-sm font-semibold text-gray-700 text-center">
                                No.
                            </th>
                            <th scope="col" class="px-2 pt-2 pb-1 text-left text-sm font-semibold text-gray-700">
                                Name
                            </th>
                            <th scope="col" class="px-3 pt-2 pb-1 text-left text-sm font-semibold text-gray-700">
                                Date uploaded
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(person, index) in uploadHistory" :key="index" class="">
                            <td class="py-0.5 text-sm text-center">
                                {{ index+1 }}.
                            </td>
                            <td class="px-3 text-gray-600">
                                {{ person.name }}
                            </td>
                            <td class="px-3">
                                {{ person.date_uploaded }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </Modal>

</template>