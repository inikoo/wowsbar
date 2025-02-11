<script setup lang="ts">
import { ref } from "vue"
import { ulid } from "ulid"
import Modal from '@/Components/Utils/Modal.vue'
import CropImage from "./CropImage/CropImage.vue"
import GalleryImages from "@/Components/Workshop/GalleryImages.vue"
import SlideAddMode from '@/Components/Banner/SlideAddMode.vue'
import { useBannerBackgroundColor, useHeadlineText } from '@/Composables/useStockList'
import { CommonData, SlideWorkshopData } from "@/types/BannerWorkshop"
import { routeType } from '@/types/route';

const props = defineProps<{
    data: {
        common: CommonData
        components: SlideWorkshopData[]
        delay: number
        type: string
    }
    imagesUploadRoute: routeType
}>()

const isOpenModalCrop = ref(false)
const addedFiles = ref([])
const isOpenGalleryImages = ref(false)

const closeModal = () => {
    addedFiles.value.files = null
    isOpenModalCrop.value = false
}

const isDragging = ref(false)


const dragover = (e) => {
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
                backgroundType: {
                    desktop: 'image'
                },
            },
            image: {
                desktop: set,
            },
            visibility: true,
        })
    }
    const newFiles = [...setData]
    props.data.components = [...props.data.components, ...newFiles]
    isOpenModalCrop.value = false
}

const onClickQuickStart = () => {
    if(props.data.type === 'square'){
        // 'Quick Start' if square
        props.data.components.push({
            ulid: ulid(),
            layout: {
                imageAlt: 'New slide',
                centralStage: {
                    title: useHeadlineText()[Math.floor(Math.random() * useHeadlineText().length)],
                    style: {
                        color: "rgba(253, 224, 71, 255)",
                        fontSize: {
                            fontTitle: "text-[18px] lg:text-[32px]",
                            fontSubtitle: "text-[15px] lg:text-[25px]"
                        }
                    }
                },
                background: {
                    desktop: useBannerBackgroundColor()[Math.floor(Math.random() * useBannerBackgroundColor().length)], // To random the background color on new slide
                },
                backgroundType: {
                    desktop: 'color',
                },
                stopwatch : {
                date : "",
                style: {
                    color: "rgba(253, 224, 71, 255)",
                    fontSize: {
                        fontTitle: "text-[18px] lg:text-[32px]",
                        fontSubtitle: "text-[10px] lg:text-[15px]"
                    }
                }
            }
            },
            image: {
                desktop: {},
            },
            visibility: true,
        })
    } else {
        // 'Quick Start' if landscape
        props.data.components.push({
            ulid: ulid(),
            layout: {
                imageAlt: 'New slide',
                centralStage: {
                    title: "Hello World!",
                    style: {
                        color: "rgba(253, 224, 71, 255)",
                        fontSize: {
                            fontTitle: "text-[18px] lg:text-[32px]",
                            fontSubtitle: "text-[10px] lg:text-[15px]"
                        }
                    }
                },
                background: {
                    desktop: useBannerBackgroundColor()[Math.floor(Math.random() * useBannerBackgroundColor().length)], // To random the background color on new slide
                    tablet: useBannerBackgroundColor()[Math.floor(Math.random() * useBannerBackgroundColor().length)],
                    mobile: useBannerBackgroundColor()[Math.floor(Math.random() * useBannerBackgroundColor().length)],
                },
                backgroundType: {
                    desktop: 'color',
                },
                stopwatch : {
                date : "",
                style: {
                    color: "rgba(253, 224, 71, 255)",
                    fontSize: {
                        fontTitle: "text-[18px] lg:text-[32px]",
                        fontSubtitle: "text-[10px] lg:text-[15px]"
                    }
                }
            }
            },
            image: {
                desktop: {},
                tablet: {},
                mobile: {},
            },
            visibility: true,
        })
    }
}

</script>

<template layout="CustomerApp">
    <Modal :isOpen="isOpenModalCrop" @onClose="closeModal">
        <div>
            <CropImage :ratio="data.type == 'square' ? {w: 1, h: 1} : {w: 4, h: 1}" :data="addedFiles" :imagesUploadRoute="props.imagesUploadRoute" :response="uploadImageRespone" />
        </div>
    </Modal>
    
    <Modal :isOpen="isOpenGalleryImages" @onClose="()=>isOpenGalleryImages = false">
        <div>
            <GalleryImages
                :imagesUploadRoute="imagesUploadRoute"
                :addImage="uploadImageRespone"
                :closeModal="()=>isOpenGalleryImages = false"
                :ratio="data.type === 'square' ? {w : 1, h : 1} : undefined"
            />
        </div>
    </Modal>
    
    <div class="col-span-full p-3" >
        <SlideAddMode
            :bannerType="data.type"
            :resetInput="true"
            @addedFiles="(files: any ) => addedFiles = files"
            @dragover="dragover"
            @dragleave="dragleave"
            @drop="drop"
            @onClickButtonGallery="isOpenGalleryImages = true"
            @onChangeInput="isOpenModalCrop = true"
            @onClickQuickStart="onClickQuickStart"
        />
    </div>
</template>