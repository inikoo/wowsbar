<script setup lang="ts">
// Import Swiper Vue.js components
import {ref} from 'vue'
import {Swiper, SwiperSlide} from 'swiper/vue'
import {Link} from "@inertiajs/vue3"
import {Autoplay, Pagination, Navigation} from 'swiper/modules'
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'

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
                    file: File
                    imageSrc: string
                    imageAlt: string
                    link?: {
                        label: string
                        target: string
                    }
                    text: {
                        position: string
                        title: string
                        subtitle: string
                    }
                    footer: {
                        position: string
                        label: string
                    }
                    bannerLink: string
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
    return new URL(`@/../../../art/banner/` + name, import.meta.url).href
}

const swiperRef = ref()
const swiperAutoplayPause = ref(false)
const toggleAutoplay = (swiper: any) => {
    if (swiperAutoplayPause.value) {
        swiper.autoplay.start(); // Resume autoplay
    } else {
        swiper.autoplay.stop(); // Pause autoplay
    }
    swiperAutoplayPause.value = !swiperAutoplayPause.value; // Toggle the autoplay state
}

</script>

<template>


    <div class="w-full aspect-[16/4] overflow-hidden relative">
        <Swiper ref="swiperRef"
                :spaceBetween="-1"
                :slidesPerView="1"
                :centeredSlides="true"
                :loop="true"
                :autoplay="{
                    delay: $props.data.data.delay,
                    disableOnInteraction: false,
                }"
                :pagination="{
                    clickable: true,
                }"
                :navigation="false"
                :modules="[Autoplay, Pagination, Navigation]" class="mySwiper">
            <SwiperSlide v-for="slide in $props.data.data.slides" :key="slide.id">
                <img :src="generateThumbnail(slide)" :alt="slide.imageAlt">
                <FontAwesomeIcon v-if="slide.bannerLink" icon='far fa-external-link' class='text-gray-100/40 text-xl absolute top-2 right-2' aria-hidden='true'/>
                <Link v-if="slide.bannerLink" :href="slide.bannerLink" class="absolute bg-transparent w-full h-full"/>
                <div class="absolute space-y-2" :class="{
                    'top-5 left-8 text-left': slide.text?.position == 'topLeft',
                    'top-5 right-8 text-right': slide.text?.position == 'topRight',
                    'bottom-5 left-8 text-left': slide.text?.position == 'bottomLeft',
                    'bottom-5 right-8 text-right': slide.text?.position == 'bottomRight',
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
        <div class="opacity-50 absolute bottom-8 left-5 z-40 flex justify-center items-center gap-x-3 my-3 text-gray-500">

            <div @click="toggleAutoplay(swiperRef.$el.swiper)" class="flex items-center justify-center cursor-pointer w-10 aspect-square bg-gray-200 rounded-full hover:ring-1 hover:ring-gray-400 active:bg-gray-300"
                 title="Pause/resume autoplay">
                <FontAwesomeIcon v-if="swiperAutoplayPause" icon="fas fa-play" class='text-xl' aria-hidden='true'/>
                <FontAwesomeIcon v-if="!swiperAutoplayPause" icon="fas fa-pause" class='text-xl' aria-hidden='true'/>
            </div>

            <div @click="() => swiperRef.$el.swiper.slidePrev()" v-if="swiperAutoplayPause"
                 class="flex items-center justify-center cursor-pointer w-8 h-fit aspect-square bg-gray-200 rounded-full hover:ring-1 hover:ring-gray-400 active:bg-gray-300" title="Go to previous banner">
                <FontAwesomeIcon icon='fas fa-chevron-left' class='text-xl' aria-hidden='true'/>
            </div>
            <div @click="() => swiperRef.$el.swiper.slideNext()" v-if="swiperAutoplayPause"
                 class="flex items-center justify-center cursor-pointer w-8 h-fit aspect-square bg-gray-200 rounded-full hover:ring-1 hover:ring-gray-400 active:bg-gray-300" title="Go to next banner">
                <FontAwesomeIcon icon='fas fa-chevron-right' class='text-xl' aria-hidden='true'/>
            </div>
        </div>
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
