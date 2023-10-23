<script setup lang="ts">
import { trans } from "laravel-vue-i18n"
import Button from "@/Components/Elements/Buttons/Button.vue"
import { ref, toRefs, watch, reactive } from "vue"
import 'vue-advanced-cropper/dist/style.css'
import 'vue-advanced-cropper/dist/theme.compact.css'
import Modal from '@/Components/Utils/Modal.vue'
import CropImage from '@/Components/Workshop/CropImage/CropImage.vue'
import GalleryImages from "@/Components/Workshop/GalleryImages.vue"
import Image from '@/Components/Image.vue'
import { set, get } from 'lodash'
import ScreenView from "@/Components/ScreenView.vue"
import { useBannerBackgroundColor } from "@/Composables/useColorList"

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faUpload } from '@fas/'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faUpload)

const props = defineProps<{
    data: any
    fieldName: string
    fieldData: any
    bannerType: string
}>()


const { data, fieldName } = toRefs(props)
const isOpen = ref(false)
const fileInput = ref(null)
const screenView = ref('desktop')
const closeModal = () => {
    isOpen.value = false
}
const isOpenCropModal = ref(false)

const closeModalisOpenCropModal = () => {
    addFiles.value = []
    isOpenCropModal.value = false
    fileInput.value.value = ''
}

const setFormValue = (data, fieldName) => {
    if (Array.isArray(fieldName)) {
        return getNestedValue(data, fieldName)
    } else {
        return data[fieldName]
    }
}

const getNestedValue = (obj, keys) => {
    return keys.reduce((acc, key) => {
        if (acc && typeof acc === 'object' && key in acc) return acc[key]
        return null
    }, obj)
}

const value = ref(setFormValue(props.data, 'background'))

watch(data, (newValue) => {
    value.value = setFormValue(newValue, props.fieldName)
})

const addFiles = ref([])
const onFileChange = (event) => {
    addFiles.value = event.target.files
    isOpenCropModal.value = true
}

watch(value, (newValue) => {
    updateLocalFormValue({ ...newValue })
})

const updateLocalFormValue = (newValue) => {
    let localData = { ...props.data }
    if (Array.isArray(props.fieldName)) {
        set(localData, props.fieldName,newValue )
    } else {
        localData[props.fieldName] = newValue
    }
    set(props.data, [props.fieldName], newValue )
}

// When select image from modal Gallery
const uploadImageRespone = (res) => {
    props.data.background.image = {
        [screenView.value ?? 'desktop']: res.data[0]
    }
    props.data.background.color.isSelected = false
    props.data.background.image.isSelected = true

    isOpenCropModal.value = false
    isOpen.value = false
}

// When click on the list background color
const onChangeBackgroundColor = (bgColor: string) => {
    props.data.background.color = {
        [screenView.value ?? 'desktop']: bgColor
    }
    props.data.background.color.isSelected = true,
    props.data.background.image.isSelected = false
}

const ratio = ref(props.bannerType == 'square' ? { w: 1 , h: 1} : { w: 4 , h: 1})  // if Square then 1:1

const screenViewChange = (value: string) => {
    screenView.value = value
    if(props.bannerType == 'square'){
        ratio.value = { w: 1 , h: 1}
    } else {
        switch (value) {
            case 'mobile':
                ratio.value = { w : 2 , h : 1};
                break;
            case 'tablet':
                ratio.value = { w : 3 , h : 1};
                break;
            case 'desktop':
                ratio.value = { w : 4 , h : 1};
                break;
            default:
                ratio.value = { w : 4 , h : 1}; // Default ratio value if none of the cases match
                break;
        }
    }
}

const backgroundColorList = useBannerBackgroundColor() // Fetch color list from Composables

</script>

<template>
    <div class="block w-full">
        <!-- Popup: add image from Gallery -->
        <Modal :show="isOpen" @onClose="closeModal">
            <div>
                <GalleryImages :addImage="uploadImageRespone" :closeModal="() => isOpen = false" :multiple="false" />
            </div>
        </Modal>

        <!-- Popup: Crop when add image -->
        <Modal :isOpen="isOpenCropModal" @onClose="closeModalisOpenCropModal">
            <div>    
                <CropImage
                    :data="addFiles"
                    :imagesUploadRoute="props.fieldData.uploadRoute"
                    :response="uploadImageRespone"
                    :ratio="ratio"/>
            </div>
        </Modal>

        <div v-if="bannerType != 'square'" class="flex justify-end">
            <!-- Screenview only for landscape view on each breakpoints -->
            <ScreenView @screenView="screenViewChange" />
        </div>

        <!-- Preview -->
        <div class="flex justify-center w-full">
            <div class="w-fit max-h-20 lg:max-h-32 border border-gray-300 shadow transition-all duration-200 ease-in-out" :class="[
                bannerType == 'square'
                    ? 'aspect-square'  // If banner is a square
                    : screenView
                        ? {
                            'aspect-[2/1]': screenView === 'mobile',
                            'aspect-[3/1]': screenView === 'tablet',
                            'aspect-[4/1]': screenView === 'desktop'
                        }
                        : 'aspect-[2/1] md:aspect-[3/1] lg:aspect-[4/1]'
            ]">
                <div class="h-full relative flex items-center" >
                    <div v-if="data?.background?.image?.isSelected == true"
                        class="group h-full relative"
                    >
                        <!-- <div class="group-hover:bg-gray-700/50 inset-0 absolute h-full"></div>
                        <div class="hidden group-hover:flex absolute inset-0 justify-center items-center text-white">
                            <FontAwesomeIcon icon='fal fa-trash-alt' class='text-3xl text-red-500' aria-hidden='true' />
                        </div>
                        <FontAwesomeIcon icon='fal fa-times' class='absolute top-0 -right-8 text-3xl text-red-400 hover:text-red-500' aria-hidden='true' /> -->
                        <Image v-if="get(data.background, ['image', `${screenView ? screenView : 'desktop'}`, 'source'], false)" :src="get(data.background, ['image', `${screenView ?? 'desktop'}`, 'source'], data.background.image?.desktop?.source)"
                            :alt="data.background.name" :imageCover="true"/>
                    </div>
                    <div v-else class="h-full w-96" :style="{ background: data.background?.color?.[screenView ?? 'desktop']}">
                        <!-- If the background is a color -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Button -->
        <div class="w-full relative space-y-4 mt-2.5">
            <div class="flex flex-col gap-y-2">
                <div class="flex items-center gap-x-4">
                    <div>An Image:</div>
                    <div class="flex items-center gap-x-2">
                        <Button v-if="bannerType != 'square'" :style="`secondary`" class="relative" size="xs">
                            <FontAwesomeIcon icon='fas fa-upload' class='' aria-hidden='true' />
                            {{ trans(`Upload image ${screenView}`) }}
                            <label class="bg-transparent inset-0 absolute inline-block cursor-pointer" id="input-slide-large-mask"
                                for="input-slide-large" />
                            <input type="file" @change="onFileChange" id="input-slide-large" name="input-slide-large"
                                ref="fileInput" accept="image/*"
                                class="absolute cursor-pointer rounded-md border-gray-300 sr-only" />
                        </Button>
                        <Button :style="`tertiary`" icon="fal fa-photo-video" label="Gallery" size="xs" class="relative" @click="isOpen = !isOpen" />
                        
                        <Image v-if="get(data.background, ['image', `${screenView ?? 'desktop'}`, 'thumbnail'], false)" :src="get(data.background, ['image', `${screenView ?? 'desktop'}`, 'thumbnail'], data.background.image?.desktop?.thumbnail)"
                            :alt="data.background.name" :imageCover="true"
                            @click="data.background.image.isSelected = true, data.background.color.isSelected = false"
                            class="h-auto w-3/12 cursor-pointer rounded overflow-hidden"
                            :class="get(data.background, ['image', 'isSelected'], false) ? 'ring-2 ring-offset-2 ring-gray-600' : ''"
                        />
                    </div>
                </div>
                
                <!-- List: Background Color -->
                <div class="flex items-center gap-x-4">
                    <div class="whitespace-nowrap">Or a color:</div>
                    <!-- Add conditional click() to avoid user change color via inspect -->
                    <div class="h-8 flex items-center w-fit gap-x-1.5">
                        <div v-for="bgColor in backgroundColorList"
                            @click="onChangeBackgroundColor(bgColor)"
                            class="w-full rounded h-full aspect-square shadow cursor-pointer"
                            :class="data?.background?.color?.[screenView ?? 'desktop'] ===  bgColor && data?.background?.color?.isSelected 
                                ? 'ring-2 ring-offset-2 ring-gray-600'
                                : 'hover:ring-2 hover:ring-offset-0 hover:ring-gray-500'"
                            :style="{background: bgColor}" />
                    </div>
                </div>
            </div>
<!-- <pre>{{ value }}</pre>
<pre>{{ data.background }}</pre> -->
        </div>
    </div>
</template>
