<script setup lang="ts">
import { ref } from "vue"
import { ulid } from "ulid"
import Modal from '@/Components/Utils/Modal.vue'
import CropImage from "./CropImage/CropImage.vue"
import GalleryImages from "@/Components/Workshop/GalleryImages.vue"
import SlideAddMode from '@/Components/Banner/SlideAddMode.vue'

const props = defineProps<{
    data: {
        common: {
            centralStage: {
                subtitle?: string
                text?: string
                title?: string
            }
            corners: Corners
        }
        components: Array<{
            id: number
            image_id: number
            image_source: string
            layout: {
                link?: string
                centralStage: {
                    title?: string
                    subtitle?: string
                    // text?: string,
                    // footer?: string
                }
            }
            corners: Object
            imageAlt: string
            link: string
            visibility: boolean
        }>
        delay: number
    }
    imagesUploadRoute: {
        name: string
        parameters: string[]
    }
    bannerType: string
}>()

const isOpenModalCrop = ref(false)
const addedFiles = ref([])
const isOpenGalleryImages = ref(false)

const closeModal = () => {
    addedFiles.value.files = null
    isOpenModalCrop.value = false
    // fileInput.value.value = ""
}

const isDragging = ref(false)


const dragover = (e) => {
    console.log(e)
    e.preventDefault()
    isDragging.value = true
}

const dragleave = () => {
    isDragging.value = false
}

const drop = (e) => {
    e.preventDefault()
    addedFiles.value = e.dataTransfer.files
    isOpenModalCrop.value = true
    isDragging.value = false
}

const uploadImageRespone = (res) => {
    let setData = []
    for (const set of res.data) {
        setData.push({
            id: null,
            ulid: ulid(),
            layout: {
                imageAlt: set.name,
            },
            image: {
                desktop : set
            },
            visibility: true,
        })
    }
    const newFiles = [...setData]
    props.data.components = [...props.data.components, ...newFiles]
    isOpenModalCrop.value = false
}
</script>

<template layout="CustomerApp">
    <Modal :isOpen="isOpenModalCrop" @onClose="closeModal">
        <div>
            <CropImage :data="addedFiles" :imagesUploadRoute="props.imagesUploadRoute" :response="uploadImageRespone" />
        </div>
    </Modal>
    
    <Modal :isOpen="isOpenGalleryImages" @onClose="()=>isOpenGalleryImages = false">
        <div>
            <GalleryImages :addImage="uploadImageRespone" :closeModal="()=>isOpenGalleryImages = false"/>
        </div>
    </Modal>
    
    <div class="col-span-full p-3" >
        <SlideAddMode
            :bannerType="bannerType"
            :resetInput="true"
            @addedFiles="(files: any ) => addedFiles = files"
            @dragover="dragover"
            @dragleave="dragleave"
            @drop="drop"
            @onClickButtonGallery="isOpenGalleryImages = true"
            @onChangeInput="isOpenModalCrop = true"
        />
    </div>
</template>
