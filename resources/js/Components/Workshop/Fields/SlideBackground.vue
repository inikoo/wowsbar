<script setup lang="ts">
import { trans } from "laravel-vue-i18n"
import Button from "@/Components/Elements/Buttons/Button.vue"
import { ref, toRefs, watch } from "vue"
import 'vue-advanced-cropper/dist/style.css'
import 'vue-advanced-cropper/dist/theme.compact.css'
import Modal from '@/Components/Utils/Modal.vue'
import CropImage from '@/Components/Workshop/CropImage/CropImage.vue'
import GalleryImages from "@/Components/Workshop/GalleryImages.vue"
import Image from '@/Components/Image.vue'
import { set, get } from 'lodash'
import ScreenView from "@/Components/ScreenView.vue"

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

const value = ref(setFormValue(props.data, props.fieldName))

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

const uploadImageRespone = (res) => {
    const set =  {...value.value}
    set[screenView.value] = { ...res.data[0] }
    value.value = set
    isOpenCropModal.value = false
    isOpen.value = false
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
            <ScreenView @screenView="screenViewChange" />
        </div>

        <div class="flex justify-center w-full">
            <div class="w-fit h-32 lg:h-44 xl:h-64 overflow-hidden border border-gray-300 shadow transition-all duration-200 ease-in-out" :class="[
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
                <div class="relative w-full h-full flex items-center bg-gray-100">
                    <Image :src="get(value, [`${screenView}`, 'source'], value.desktop.source)"
                        :alt="value.name" :imageCover="true"/>
                </div>
            </div>
        </div>

        <div class="w-full relative space-y-4 mt-2.5">
            <div class="flex gap-x-2">
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
            </div>
        </div>
    </div>
</template>
