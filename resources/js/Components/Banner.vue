<script setup lang="ts">
// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Link } from "@inertiajs/vue3"
import { Autoplay, Pagination, Navigation } from 'swiper/modules'

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
                    id: number
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

</script>

<template>
    <div class="w-full aspect-[16/4]">
        <Swiper :spaceBetween="-1" :slidesPerView="1" :centeredSlides="true" :loop="true"
            :autoplay="{
                delay: data.data.delay,
                disableOnInteraction: false,
            }"
            :pagination="{
                clickable: true,
            }"
            :navigation="false" :modules="[Autoplay, Pagination, Navigation]" class="mySwiper"
        >
            <SwiperSlide v-for="slide in $props.data.data.slides" :key="slide.id">
                <img :src="`@/../art/banner/${slide.imageSrc}`" :alt="slide.imageAlt" srcset="">
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