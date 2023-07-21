<script setup lang="ts">
// Import Swiper Vue.js components
import { ref } from 'vue'
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

const dummyAbc = ref({
                "delay": 5000,
                "slides": [
                    {
                        "id": 1,
                        "imageSrc": "incentives-07-hero.jpg",
                        "imageAlt": "Front of men's Basic Tee in sienna",
                        "link": {
                            "label": "Open",
                            "target": "#"
                        },
                        "text" : {
                            "title": "Welcome to the jungle",
                            "subtitle": "Jungle is the best place to go and healing with your family",
                        }
                    },
                    {
                        "id": 2,
                        "imageSrc": "product-page-01-featured-product-shot.jpg",
                        "imageAlt": "Lorem ipsum dolor sit amet consectetur.",
                        "text" : {
                            "position": "topleft",
                            "title": "Welcome to the jungle",
                            "subtitle": "Jungle is the best place to go and healing with your family",
                        },
                        "footer": {
                            "position": "right",
                            "label": "The banner officially disclaimed by XYZ Company."
                        }
                    },
                    {
                        "id": 3,
                        "imageSrc": "product-page-03-product-01.jpg",
                        "imageAlt": "Lorem ipsum dolor Basic Tee in sienna",
                        "link": {
                            "label": "Browse Promo",
                            "target": "#"
                        },
                        "text" : {
                            "title": "Welcome to the jungle",
                            "subtitle": "Jungle is the best place to go and healing with your family",
                        }
                    },
                    {
                        "id": 4,
                        "imageSrc": "product-page-05-product-01.jpg",
                        "imageAlt": "Continuous infinite slider"
                    }
                ]
        })

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
            <SwiperSlide v-for="slide in dummyAbc.slides" :key="slide.id">
                <img :src="generateThumbnail(slide)" :alt="slide.imageAlt">
                <div class="absolute space-y-2" :class="{
                    'top-5 left-5 text-left': slide.text?.position == 'topleft',
                    'top-5 right-5 text-right': slide.text?.position == 'topright',
                    'bottom-5 left-5 text-left': slide.text?.position == 'bottomleft',
                    'bottom-5 right-5 text-right': slide.text?.position == 'bottomright',
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
