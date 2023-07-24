<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sun, 23 Jul 2023 22:01:23 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {ref} from 'vue'
import {Swiper, SwiperSlide} from 'swiper/vue'
import {Autoplay, Pagination, Navigation} from 'swiper/modules'

import 'swiper/css'
import 'swiper/css/navigation';
import SlideCorner from "@/Components/Slider/SlideCorner.vue";
import CentralStage from "@/Components/Slider/CentralStage.vue";

const props = defineProps<{
        layout: {

            delay: number,
            common: {
                corners:{
                    topLeft?: object,
                    topRight?: object,
                    bottomLeft?: object,
                    bottomRight?: object
                }

            },
            link?:string,
            slides: Array<
                {
                    id: string,
                    imageSrc: string
                    imageAlt: string,
                    link?: string,
                    corners:{
                        topLeft?: {
                            type: string,
                            data?: object
                        },
                        topRight?: object,
                        bottomLeft?: object,
                        bottomRight?: object
                    }
                    centralStage: {
                        title?: string
                        subtitle?: string
                        text?:string,
                        footer?:string
                    }

                }
            >,

        }

}>()

const generateThumbnail = (set) => {
    if (set.file && set.file instanceof File) {
        let fileSrc = URL.createObjectURL(set.file);
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


const filteredNulls = (corners) => {
    return Object.fromEntries(Object.entries(corners).filter(([_, v]) => v != null));
};


</script>

<template>
    <div class="w-full aspect-[16/4] overflow-hidden relative">
        <Swiper ref="swiperRef"
                :spaceBetween="-1"
                :slidesPerView="1"
                :centeredSlides="true"
                :loop="true"
                :autoplay="{
                    delay: layout.delay,
                    disableOnInteraction: false,
                }"
                :pagination="{
                    clickable: true,
                }"
                :navigation="false"
                :modules="[Autoplay, Pagination, Navigation]" class="mySwiper">
            <SwiperSlide v-for="slide in layout.slides" :key="slide.id">
                <img :src="generateThumbnail(slide)" :alt="slide.imageAlt">
                <SlideCorner v-for="(corner,position) in filteredNulls(slide.corners)" :position="position" :corner="corner"/>
                <CentralStage v-if="slide.centralStage" :data="slide.centralStage" />
            </SwiperSlide>
        </Swiper>
        <SlideCorner class="z-50" v-for="(corner,position) in filteredNulls(layout.common.corners)" :position="position" :corner="corner" :swiperRef="swiperRef"/>
    </div>

</template>

<style lang="scss">
.swiper {
    @apply w-full h-full;
}

.swiper-slide {
    @apply bg-white;
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
