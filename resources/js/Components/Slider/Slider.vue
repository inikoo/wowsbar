<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sun, 23 Jul 2023 22:01:23 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { ref } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Autoplay, Pagination, Navigation } from 'swiper/modules'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faEyeSlash } from '@/../private/pro-solid-svg-icons'
import { get } from 'lodash'
import { faExternalLink, faExclamationTriangle } from '@/../private/pro-regular-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faExternalLink, faEyeSlash, faExclamationTriangle)

import 'swiper/css'
import 'swiper/css/navigation';
import SlideCorner from "@/Components/Slider/SlideCorner.vue";
import CentralStage from "@/Components/Slider/CentralStage.vue";
import { Link } from '@inertiajs/vue3';
import { watch } from 'vue'

interface CornersPositionData {
    data: {
        text: string
        target: string
    }
    type: string
}

interface Corners {
    topLeft?: CornersPositionData
    topRight?: CornersPositionData
    bottomLeft?: CornersPositionData
    bottomRight?: CornersPositionData
}

const props = defineProps<{
    jumpToIndex?: number
    data: {
        common: {
            centralStage: {
                subtitle?: string
                text?: string
                title?: string
            }
            corners: Corners
        }
        components: Array<
            {
                id: number,
                image_id: number
                image_source: string
                layout: {
                    link?: string,
                    centralStage: {
                        title?: string
                        subtitle?: string
                        // text?: string,
                        // footer?: string
                    }
                }
                visibility: boolean
                corners: Corners
                imageAlt: string
                link: string
            }
        >
        delay: number

    }
    view?: string

}>()

const generateThumbnail = (file) => {
    if (file.imageFile && file.imageFile instanceof File) {
        let fileSrc = URL.createObjectURL(file.imageFile)
        setTimeout(() => {
            URL.revokeObjectURL(fileSrc)
        }, 1000)
        return fileSrc
    } else {
        return file.image_source
    }
}

const swiperRef = ref()

const filteredNulls = (corners: Corners) => {
    if(corners) {
        return Object.fromEntries(Object.entries(corners).filter(([_, v]) => v != null));
    }

    return ''
}

watch(() => props.jumpToIndex, (newVal) => {
    swiperRef.value.$el.swiper.slideToLoop(newVal, 0, false)
})

// const getImageStyle=(component)=> {
//     const x = get(component,['imagePosition','x'])
//     const y = get(component,['imagePosition','y'])
//     const style = {
//         transform: `translateX(-${x}px) translateY(-${y}px)`
//     }
//     return style
// }


const getResult =  (component: Object) => {
    if (component.imagePosition) {
        const base64 =  component.imagePosition?.canvas.toDataURL();
        return base64
    } else {
        return generateThumbnail(component);
    }
}

</script>

<template>
    <div class=" overflow-hidden relative border border-gray-300 shadow-md"
        :class="[$props.view
            ? { 'aspect-[2/1] w-1/2' : $props.view == 'mobile',
                'aspect-[3/1] w-3/4' : $props.view == 'tablet',
                'aspect-[4/1]' : $props.view == 'desktop'}
            : 'w-full aspect-[2/1] md:aspect-[3/1] lg:aspect-[4/1]']"
    >
        <Swiper ref="swiperRef"
            :slideToClickedSlide="true"
            :spaceBetween="-1"
            :slidesPerView="1"
            :centeredSlides="true"
            :loop="true"
            :autoplay="{
                delay: data.delay,
                disableOnInteraction: false,
            }"
            :pagination="{
                clickable: true,
            }"
            :navigation="false"
            :modules="[Autoplay, Pagination, Navigation]" class="mySwiper">
            <SwiperSlide v-for="component in data.components" :key="component.id">
                <div class="relative w-full h-full overflow-hidden">
                    <img :src="getResult(component)" :alt="component.layout?.imageAlt" class="absolute">
                </div>
                <div v-if="get(component, ['visibility'], true) === false" class="absolute h-full w-full bg-gray-800/50 z-10 " />
                <div class="z-[11] absolute left-7 flex flex-col gap-y-2">
                    <FontAwesomeIcon v-if="get(component, ['visibility'], true) === false" icon='fas fa-eye-slash' class=' text-orange-400 text-4xl' aria-hidden='true' />
                    <span v-if="get(component, ['visibility'], true) === false" class="text-orange-400/60 text-sm italic select-none" aria-hidden='true'>
                        <FontAwesomeIcon icon='far fa-exclamation-triangle' class='' aria-hidden='true' />
                        Not visible
                    </span>
                </div>
                <FontAwesomeIcon v-if="!!component?.layout?.link" icon='far fa-external-link' class='text-gray-300/50 text-xl absolute top-2 right-2' aria-hidden='true' />
                <Link v-if="!!component?.layout?.link" :href="component?.layout?.link" class="absolute bg-transparent w-full h-full" />
                <SlideCorner v-for="(slideCorner, position) in filteredNulls(component?.layout?.corners)" :position="position" :corner="slideCorner" :commonCorner="data.common.corners" />
                
                <!-- CentralStage: common.centralStage (prioritize) and layout.centralstage -->
                <CentralStage v-if="data.common?.centralStage?.title?.length > 0 || data.common?.centralStage?.subtitle?.length > 0" :data="data.common?.centralStage" />
                <CentralStage v-else="component?.layout?.centralStage" :data="component?.layout?.centralStage" />
            </SwiperSlide>
        </Swiper>

        <!-- Reserved Corner: Button Controls -->
        <SlideCorner class="z-10" v-for="(corner, position) in filteredNulls(data.common.corners)" :position="position" :corner="corner"   :swiperRef="swiperRef"/>
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
