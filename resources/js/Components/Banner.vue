<script setup lang="ts">
// Import Swiper Vue.js components
import { ref } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Link } from "@inertiajs/vue3"
import { Autoplay, Pagination, Navigation } from 'swiper/modules'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

// Import Swiper styles
import 'swiper/css'
// import 'swiper/css/pagination';
import 'swiper/css/navigation';

const props = defineProps<{
    data: {
        parameters: {}
        data: {
            delay: number,
            slides: [
                {
                    id: number,
                    file : File,
                    imageSrc: string,
                    imageAlt: string,
                    link?: {
                        label: string,
                        target: string
                    }
                }
            ]
        }
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
    return new URL(`@/../../../art/banner/` + name,import.meta.url).href
}

</script>

<template>
    <div class="w-full aspect-[16/4] overflow-hidden">
        <Swiper :spaceBetween="-1" :slidesPerView="1" :centeredSlides="true" :loop="true"
            :autoplay="{
                delay: 1000000,
                disableOnInteraction: false,
            }"
            :pagination="{
                clickable: true,
            }"
            :navigation="false" :modules="[Autoplay, Pagination, Navigation]" class="mySwiper"
        >
            <SwiperSlide v-for="slide in $props.data.data.slides" :key="slide.id">
                <img :src="generateThumbnail(slide)" :alt="slide.imageAlt">
                <FontAwesomeIcon v-if="slide.bannerLink" icon='far fa-external-link' class='text-gray-100/40 text-xl absolute top-2 right-2' aria-hidden='true' />
                <Link v-if="slide.bannerLink" :href="slide.bannerLink" class="absolute bg-transparent w-full h-full" />
                <div class="absolute space-y-2" :class="{
                    'top-5 left-8 text-left': slide.text?.position == 'topleft',
                    'top-5 right-8 text-right': slide.text?.position == 'topright',
                    'bottom-5 left-8 text-left': slide.text?.position == 'bottomleft',
                    'bottom-5 right-8 text-right': slide.text?.position == 'bottomright',
                }"
                >
                    <div class="text-gray-100 drop-shadow-md text-5xl font-bold">{{ slide.text?.title }}</div>
                    <div class="text-gray-300 drop-shadow text-lg italic tracking-widest">{{ slide.text?.subtitle }}</div>
                </div>
                <div v-if="slide.footer?.label" class="absolute text-gray-400 text-xs bottom-5"
                    :class="{'left-5': slide.footer?.position == 'left', 'right-5': slide.footer?.position == 'right'}">
                    {{ slide.footer.label }}
                </div>
                <Link v-if="slide.link" :href="slide.link.target"
                    class="bg-gray-800/40 text-gray-100 border border-gray-50/50 absolute bottom-6 right-11 rounded px-3 py-1 hover:bg-gray-900/60">
                    {{ slide.link.label }}
                </Link>
            </SwiperSlide>
        </Swiper>
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
