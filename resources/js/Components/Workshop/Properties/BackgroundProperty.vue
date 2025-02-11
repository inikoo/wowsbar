<script setup lang='ts'>
import { trans } from 'laravel-vue-i18n'
import PureInputNumber from '@/Components/Pure/PureInputNumber.vue'
import { Popover, PopoverButton, PopoverPanel, Switch } from '@headlessui/vue'
import { ref } from 'vue'
import Image from '@/Components/Image.vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
import GalleryManagement from '@/Components/Utils/GalleryManagement/GalleryManagement.vue'
import Modal from '@/Components/Utils/Modal.vue'
import PureRadio from '@/Components/Pure/PureRadio.vue'
import ColorPickerWithGradient from '@/Components/Utils/ColorPickerWithGradient.vue'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faPencil } from '@fal'
import { library } from '@fortawesome/fontawesome-svg-core'
import { get, set } from 'lodash';
import { ImageData } from '@/types/Image'
library.add(faPencil)

interface BackgroundProperty {
    type: string
    color: string
    image: string
}

const model = defineModel<BackgroundProperty>()

const isOpenGallery = ref(false)


const routeList = {
    'imagesUploadedRoutes': {
        'name': 'customer.gallery.uploaded-images.index',
    },
    'stockImagesRoute': {
        'name': 'customer.gallery.stock-images.index',
        'parameters': {
            'filter[scope]': 'landscape,announcement'
        }
    },
    // 'uploadImageRoute': {
    //     'name': 'grp.models.org.product.images.store',
    //     'parameters': {
    //         'organisation': '$product->organisation_id',
    //         'product': '$product->id'
    //     }
    // },
    // 'attachImageRoute': {
    //     'name': 'grp.models.org.product.images.attach',
    //     'parameters': {
    //         'organisation': '$product->organisation_id',
    //         'product': '$product->id'
    //     }
    // },
    'deleteImageRoute': {
        'name': 'grp.models.org.product.images.delete',
        'parameters': {
            'organisation': '$product->organisation_id',
            'product': '$product->id'
        }
    }
}
const onSubmitSelectedImages = (images: ImageData[]) => {
    set(model.value, ['image'], images[0]?.source?.original)
    isOpenGallery.value = false
}

</script>

<template>
    <div class="flex items-center justify-between gap-x-3 flex-wrap px-6 w-full relative">
        <!-- Background image -->
        <div class="flex items-center gap-x-2 py-1" >
            <div class="group/background rounded-md overflow-hidden relative h-12 w-12 aspect-square shadow ">
                <Image
                    :src="{ 'original': get(model, 'image', '') }"
                    :alt="trans('Background image')"
                    :imageCover="true"
                    @click="true"
                />

                <div @click="() => isOpenGallery = true" class="hidden group-hover/background:flex absolute inset-0 bg-black/20 items-center justify-center cursor-pointer">
                    <FontAwesomeIcon icon='fal fa-pencil' class='text-white' fixed-width aria-hidden='true' />
                </div>
            </div>

            <PureRadio
                :modelValue="get(model, 'type', 'image')"
                @update:modelValue="e => set(model, 'type', e)"
                :options="[{ name: 'image'}]"
                by="name"
                key="image"
            />
        </div>
        
        <!-- Background Color -->
        <div class="flex items-center gap-x-4 h-min" >
            <ColorPickerWithGradient
                :color="model?.color"
                class="h-8 w-8 rounded-md border border-gray-300"
                @changeColor="(newColor)=> model.color = newColor"
                closeButton
                classPopup="absolute -left-44 top-0 z-10 mt-3"
            />
            <!-- <div v-else class="h-8 w-8 rounded-md border border-gray-300 shadow" :style="{background: model.color}" /> -->

            <PureRadio
                :modelValue="get(model, 'type', '')"
                @update:modelValue="e => set(model, 'type', e)"
                :options="[{ name: 'color'}]"
                by="name"
                key="color"
            />
        </div>
    </div>
    

    <Modal :isOpen="isOpenGallery" @onClose="() => isOpenGallery = false" width="w-3/4" >
        <GalleryManagement
            :imagesUploadedRoutes="routeList.imagesUploadedRoutes"
            :stockImagesRoute="routeList.stockImagesRoute"
            :maxSelected="1"
            :closePopup="() => isOpenGallery = false"
            @selectImage="(image: {}) => console.log('image', image)"
            @submitSelectedImages="(images: ImageData[]) => onSubmitSelectedImages(images)"
            
        />
    </Modal>
</template>