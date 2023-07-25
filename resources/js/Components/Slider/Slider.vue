<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sun, 23 Jul 2023 22:01:23 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {ref, computed} from 'vue'
import {Swiper, SwiperSlide} from 'swiper/vue'
import {Autoplay, Pagination, Navigation} from 'swiper/modules'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faExternalLink } from '@/../private/pro-regular-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faExternalLink)

import 'swiper/css'
import 'swiper/css/navigation';
import SlideCorner from "@/Components/Slider/SlideCorner.vue";
import CentralStage from "@/Components/Slider/CentralStage.vue";
import { Link } from '@inertiajs/vue3';

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
                    visibility: boolean
                }
                corners: Corners
                imageAlt: string
                link: string
            }
        >
        delay: number

    }

}>()

const generateThumbnail = (set) => {
    if (set.imageSrc && set.imageSrc instanceof File) {
        let fileSrc = URL.createObjectURL(set.imageSrc);
        setTimeout(() => {
            URL.revokeObjectURL(fileSrc);
        }, 1000);
        return fileSrc;
    } else {
        return getImageUrl(set.imageSrc);
    }
};

const getImageUrl = (name: string) => {
    return new URL(`@/../../../art/banner/` + name, import.meta.url).href
}

const swiperRef = ref()


const filteredNulls = (corners: Corners) => {
    if(corners) {
        return Object.fromEntries(Object.entries(corners).filter(([_, v]) => v != null));
    }

    return ''
};

const filteredSlide = computed(() => {
    return props.data.components.filter((i) => i.layout.visibility === true)
})

</script>

<template>
    <div class="w-full aspect-[16/4] overflow-hidden relative">
        <Swiper ref="swiperRef"
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
            <SwiperSlide v-for="component in filteredSlide" :key="component.id">
                <img :src="component.image_source" :alt="component.imageAlt">
                <FontAwesomeIcon v-if="component.layout.link" icon='far fa-external-link' class='text-gray-300/50 text-xl absolute top-2 right-2' aria-hidden='true' />
                <Link v-if="component.layout.link" :href="component.layout.link" class="absolute bg-transparent w-full h-full" />
                <SlideCorner v-for="(corner, position) in filteredNulls(component.layout.corners)" :position="position" :corner="corner"/>
                <CentralStage v-if="component.layout.centralStage" :data="component.layout.centralStage" />
            </SwiperSlide>
        </Swiper>

        <!-- Reserved Corner: Button Controls -->
        <SlideCorner class="z-50" v-for="(corner, position) in filteredNulls(data.common.corners)" :position="position" :corner="corner" :swiperRef="swiperRef"/>
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
    @apply w-full h-full;
    display: block;
    object-fit: cover;
}
</style>
