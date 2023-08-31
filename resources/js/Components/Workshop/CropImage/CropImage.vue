<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sun, 23 Jul 2023 22:01:23 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { ref, watch } from "vue"
import { Swiper, SwiperSlide } from "swiper/vue"
import { Pagination, Navigation } from "swiper/modules"
import "swiper/css"
import "swiper/css/navigation"

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faSpinnerThird } from '@/../private/pro-duotone-svg-icons'
import { faExclamation } from '@/../private/pro-solid-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faExclamation, faSpinnerThird)

import { trans } from "laravel-vue-i18n"
import Button from "@/Components/Elements/Buttons/Button.vue"
import CropComponents from "@/Components/Workshop/CropImage/CropComponents.vue"


const props = withDefaults(defineProps<{
    data: FileList;
    imagesUploadRoute: object;
    respone : Function
    ratio?:  object, 
}>(), {
    ratio: { w : 4, h : 1 } 
})

const setData2 = () => {
    const data = []
    for (const set of props.data) {
        data.push({
            originalFile: set,
        })
    }
    return data
}

const setData = ref(setData2())

const generateThumbnail = (file) => {
    if (
        file.originalFile &&
        file.originalFile instanceof File &&
        !file.imagePosition
    ) {
        let fileSrc = URL.createObjectURL(file.originalFile);
        setTimeout(() => {
            URL.revokeObjectURL(fileSrc);
        }, 1000)
        return fileSrc
    } else if (file.imagePosition) {
        return file.imagePosition.canvas.toDataURL()
    } else {
        return file.originalFile
    }
};

const form = ref(new FormData())
const catchError = ref()
const loadingState = ref(false)

const addComponent = async () => {
    loadingState.value = true
    const SendData = []
    const processItem = async (item) => {
        return new Promise((resolve, reject) => {
            if (item.imagePosition) {
                item.imagePosition.canvas.toBlob((blob) => {
                    // SendData.push(blob)
                    form.value.append("blob", blob, item.originalFile.name)
                    resolve()
                })
            } else {
                resolve()
            }
        });
    };

    await Promise.all(setData.value.map(processItem));
    for (const [key, value] of form.value.entries()) {
        SendData.push(value)
    }

    try {
        const response = await axios.post(
            route(
                props.imagesUploadRoute.name,
                props.imagesUploadRoute.arguments
            ),
            { images: SendData },
            {
                headers: { "Content-Type": "multipart/form-data" },
            }
        );
        form.value = new FormData()
        props.respone(response.data)
        loadingState.value = false
    } catch (error) {
        console.log(error)
        form.value = new FormData()
        catchError.value = error
        // props.respone(error.response)
        loadingState.value = false
    }
}

const swiperRef = ref()
const current = ref(0)

watch(current, (newVal) => {
    swiperRef.value.$el.swiper.slideToLoop(newVal, 0, false)
})

</script>

<template>
    <div
        class="mb-6 overflow-hidden relative border border-gray-300 shadow-md w-full aspect-[2/1] md:aspect-[3/1] lg:aspect-[4/1]"
    >
        <Swiper
            ref="swiperRef"
            :slideToClickedSlide="true"
            :spaceBetween="-1"
            :slidesPerView="1"
            :centeredSlides="true"
            :loop="true"
            :navigation="false"
            :modules="[Pagination, Navigation]"
            class="mySwiper"
        >
            <SwiperSlide v-for="(component, index) in setData" :key="index">
                <div class="relative w-full h-full overflow-hidden">
                    <img
                        :src="generateThumbnail(component)"
                        alt=""
                        class="absolute"
                    />
                </div>
            </SwiperSlide>
        </Swiper>
    </div>
    <div class="mb-6 space-y-3">
        <div class="max-w-full py-5 px-6 h-96 overflow-y-auto border border-solid border-gray-300 rounded-lg">
            <ul
                role="list"
                class="mx-auto grid max-w-full grid-cols-1 gap-x-8 gap-y-16 sm:grid-cols-2 lg:mx-0 lg:max-w-none lg:grid-cols-3"
            >
                <li v-for="(item, index) in setData" :key="index">
                    <div @click="current = index" :class="['p-2.5 border border-solid rounded-lg cursor-pointer ', setData[current] == item ?  'border-gray-400 bg-gray-200'  : 'border-gray-300']">
                        <CropComponents :data="item"  :ratio="ratio"/>
                        <div class="flex justify-center align-middle">
                            <h3
                                :class="['leading-4 tracking-tight', setData[current] == item ? 'text-orange-500 font-semibold' : 'text-gray-500']"
                            >
                                {{ item.originalFile.name }}
                            </h3>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div v-if="catchError?.response" class="text-red-500">
            <FontAwesomeIcon icon='fas fa-exclamation' class='' aria-hidden='true' />
            {{ catchError.response.statusText}}
        </div>
    </div>
    <div class="">
        <Button
            :style="`primary`"
            icon="fas fa-upload"
            class="relative"
            :disabled="loadingState"
            size="xs"
            @click="addComponent"
        >
            {{ trans("Save Image") }}
        </Button>
        <FontAwesomeIcon v-if="loadingState" icon='fad fa-spinner-third' class='animate-spin ml-2' aria-hidden='true' />
    </div>
</template>

<style lang="scss">
.swiper {
    @apply w-full h-full;
}

.swiper-slide {
    @apply bg-gray-200;
    text-align: center;
    font-size: 18px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.swiper-slide img {
    @apply w-full h-auto;
    display: block;
    object-fit: cover;
}
</style>
