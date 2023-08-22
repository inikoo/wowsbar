<script setup lang="ts">
import { ref, reactive, watch } from 'vue'
import axios from 'axios'

import Image from '@/Components/Image.vue'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCloudUpload, faImagePolaroid } from '@/../private/pro-light-svg-icons'
import { faSpinnerThird } from '@/../private/pro-duotone-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'

library.add(faCloudUpload, faImagePolaroid, faSpinnerThird)

const galleryData: any = reactive({
    'uploaded_images': [],
    'stock_images': []
})

const activeTab = ref(0)
const activeSidebar = ref('uploaded_images')
const loadingState = ref(false)

const changeTab = (index: number) => {
    activeTab.value = index
}

const getData = async (tabName: string, routeUrl: string) => {
    loadingState.value = true
    try {
        const response = await axios.get(
            route(routeUrl)
        )
        console.log(response.data.data)
        galleryData[tabName].push(...response.data.data)
        loadingState.value = false
    } catch (error) {
        console.log("===========================")
        console.log(error)
        loadingState.value = false
    }
}

// Use watch to fetch at first load
watch(activeSidebar, (newSidebar: string) => {
    if(newSidebar == 'uploaded_images') {
        galleryData[newSidebar].length === 0 ? getData(newSidebar, 'portfolio.uploaded.images') : false
    } else if (newSidebar == 'stock_images') {
        galleryData[newSidebar].length === 0 ? getData(newSidebar, 'portfolio.stock.images') : false
    }
}, { immediate: true })

const truncate = (string: string, length: number, different: number) => {
    if (string.length > length) {
        if(string.length > length + different) {
            return `${string.substring(0, length)}...`
        }
        return string
    }
    return string
}

</script>

<template>
    <div class="flex gap-x-2">
        <!-- Sidebar -->
        <section class="bg-gray-50 w-64">
            <div class="py-2 px-2 cursor-pointer hover:bg-gray-200 text-sm flex gap-x-1 items-center text-gray-600 hover:text-gray-700"
                :class="[activeSidebar == 'uploaded_images' ? 'tabNavigationActive' : 'tabNavigation']"
                @click="activeSidebar = 'uploaded_images'"
            >
                <FontAwesomeIcon icon='fal fa-cloud-upload' class='w-4 h-4 text-gray-400' aria-hidden='true' />
                <span>Uploaded Images</span>
            </div>
            <div class="py-2 px-2 cursor-pointer hover:bg-gray-200 text-sm flex gap-x-1 items-center text-gray-600 hover:text-gray-700"
                :class="[activeSidebar == 'stock_images' ? 'tabNavigationActive' : 'tabNavigation']"
                @click="activeSidebar = 'stock_images'"
            >
                <FontAwesomeIcon icon='fal fa-image-polaroid' class='w-4 h-4 text-gray-400' aria-hidden='true' />
                <span>Stock Images</span>
            </div>
        </section>

        <!-- Main content -->
        <section class="bg-gray-50 w-full rounded-r-md">
            <div v-if="loadingState" class="w-full h-full flex justify-center items-center">
                <FontAwesomeIcon icon='fad fa-spinner-third' class='animate-spin h-1/6' aria-hidden='true' />
            </div>
            <!-- Tab -->    
            <!-- <div class="md:flex md:items-center md:justify-between">
                <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
                    <ul class="flex flex-wrap -mb-px">
                        <li v-for="(tab, index) in galleryData[activeSidebar]" :key="tab" class="mr-2">
                            <a href="#" :class="{
                                'inline-block py-2 px-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300': index !== activeTab,
                                'inline-block py-2 px-4 text-orange-500 border-b-2 border-orange-500 rounded-t-lg dark:text-orange-500 dark:border-orange-500': index === activeTab
                            }" @click="changeTab(index)">
                                {{ tab.name }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div> -->

            <!-- Images list -->
            <div v-else class="pt-6 pl-4 grid grid-cols-4 gap-x-3 gap-y-6 max-h-96 overflow-auto">
                <div v-for="imageData in galleryData[activeSidebar]" :key="imageData.id" class="group opacity-75 hover:opacity-100 cursor-pointer relative flex flex-col gap-y-1">
                    <div class="flex-none aspect-[4/1] bg-white overflow-hidden rounded group-hover:ring-2 group-hover:ring-gray-500">
                        <Image :src="imageData.source" :alt="imageData.imageAlt" class="h-full w-full object-cover object-center" />
                    </div>
                    <h3 class="overflow-hidden text-xs text-gray-700 flex justify-start items-center">
                        {{ truncate(imageData.name, 17, 4) }}
                    </h3>
                </div>
            </div>
        </section>
    </div>
</template>
