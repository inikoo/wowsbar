<script setup lang="ts">
import { ref, watch } from 'vue'
import axios from 'axios'

import Image from '@/Components/Image.vue'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCloudUpload, faImagePolaroid } from '@/../private/pro-light-svg-icons'
import { faSpinnerThird } from '@/../private/pro-duotone-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
import { useGalleryStore } from '@/Stores/gallery.js'
import { useTruncate } from '@/Composables/useTruncate.js'
import Button from '../Elements/Buttons/Button.vue'
import EmptyState from '@/Components/Utils/EmptyState.vue'
import { trans } from "laravel-vue-i18n"

library.add(faCloudUpload, faImagePolaroid, faSpinnerThird)

const props = defineProps({
    addImage: Function,
    closeModal: Function,
    multiple: {
        type: Boolean,
        default: true
    }
});

const galleryStore = useGalleryStore()

// const galleryData: any = reactive({
//     'uploaded_images': [],
//     'stock_images': []
// })

const activeTab = ref(0)
const activeSidebar = ref('uploaded_images')
const loadingState = ref(false)

const changeTab = (index: number) => {
    activeTab.value = index
}

// Fetch images from API
const getData = async (tabName: string, routeUrl: string) => {
    loadingState.value = true
    try {
        const response = await axios.get(
            route(routeUrl)
        )
        galleryStore[tabName].push(...response.data.data)
        // console.log(galleryStore[tabName])
        loadingState.value = false
    } catch (error) {
        console.log(error)
        loadingState.value = false
    }
}

// Use watch to fetch at first load
watch(activeSidebar, (newSidebar: string) => {
    if(newSidebar == 'uploaded_images') {
        galleryStore[newSidebar].length === 0 ? getData(newSidebar, 'portfolio.uploaded.images') : false
    } else if (newSidebar == 'stock_images') {
        galleryStore[newSidebar].length === 0 ? getData(newSidebar, 'portfolio.stock.images') : false
    }
}, { immediate: true })

const ImageDataCollect = ref({data : []})

const collectImage = (image) => {
    // console.log(image)
    const index = ImageDataCollect.value.data.findIndex((item)=>item.id == image.id)
    if(props.multiple){
        if(ImageDataCollect.value.data.length > 0){
        if(index == -1) ImageDataCollect.value.data.push(image)
        else ImageDataCollect.value.data.splice(index, 1)
    }else ImageDataCollect.value.data.push(image)
    }else{
        ImageDataCollect.value.data = [{...image}]
    }
  
}

</script>

<template>
    <div class="flex gap-x-2">
        <!-- Sidebar -->
        <section class="bg-gray-50 w-64">
            <div class="py-2 px-2 cursor-pointer hover:bg-gray-200 text-sm flex gap-x-1 items-center text-gray-600 hover:text-gray-700"
                :class="[activeSidebar == 'uploaded_images' ? 'tabNavigationActive' : 'tabNavigation']"
                @click="activeSidebar = 'uploaded_images'" id="uploaded_images"
            >
                <FontAwesomeIcon icon='fal fa-cloud-upload' class='w-4 h-4 text-gray-400' aria-hidden='true' />
                <span>Uploaded Images</span>
            </div>
            <div class="py-2 px-2 cursor-pointer hover:bg-gray-200 text-sm flex gap-x-1 items-center text-gray-600 hover:text-gray-700"
                :class="[activeSidebar == 'stock_images' ? 'tabNavigationActive' : 'tabNavigation']"
                @click="activeSidebar = 'stock_images'" id="stock_images"
            >
                <FontAwesomeIcon icon='fal fa-image-polaroid' class='w-4 h-4 text-gray-400' aria-hidden='true' />
                <span>Stock Images</span>
            </div>
        </section>

        <!-- Main content -->
        <section class="bg-gray-50 h-96 w-full rounded-r-md">
            <div v-if="loadingState" class="w-full h-full flex justify-center items-start">
                <div class="pt-6 px-4 grid grid-cols-4 gap-x-3 gap-y-6 max-h-96 w-full overflow-auto">
                    <div v-for="imageData in 7" class="relative flex flex-col gap-y-1">
                        <div class="skeleton w-full aspect-[4/1] rounded" />
                        <div class="skeleton w-2/3 h-5" />
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div v-else>
                <div v-if="galleryStore?.[activeSidebar].length == 0" class="h-full flex justify-center items-center">
                    <EmptyState :data="{
                        title: trans('You haven\'t uploaded any images yet.'),
                        description: trans('Create new slides in the workshop to get started.'),
                    }" />
                </div>
                
                <div v-else class="pt-6 px-4 grid grid-cols-4 gap-x-3 gap-y-6 max-h-96 overflow-auto">
                    <div  v-for="imageData in galleryStore?.[activeSidebar]" :key="imageData.id"
                        @click="() => collectImage(imageData)"
                        class="group cursor-pointer relative flex flex-col gap-y-1"
                        :class="ImageDataCollect.data.find((item: any) => item.id === imageData.id) ? 'font-bold text-gray-500 rounded-md' : 'text-gray-500 opacity-70 hover:opacity-100'"
                    >
                        <div class="flex-none aspect-[4/1] bg-white overflow-hidden rounded" :id="imageData.id"
                            :class="ImageDataCollect.data.find((item: any) => item.id === imageData.id) ? 'ring-2 ring-orange-500 ring-offset-2' : 'ring-offset-2 group-hover:ring-2 group-hover:ring-gray-300'"
                        >
                            <Image :src="imageData.source" :alt="imageData.imageAlt" class="h-full w-full object-cover object-center" />
                        </div>
                        <h3 class="overflow-hidden text-xs flex justify-start items-center">
                            {{ useTruncate(imageData.name, 17, 4) }}
                        </h3>
                    </div>
                </div>
            </div>
            
            
        </section>
    
    </div>
    <div class="flex justify-end p-2.5 gap-3 pb-0">
        <Button @click="closeModal" :style="'tertiary'">Cancel</Button>
        <Button @click="addImage(ImageDataCollect)" id="add-image" >Add image ({{ ImageDataCollect.data.length }})</Button>
    </div>
    
</template>
