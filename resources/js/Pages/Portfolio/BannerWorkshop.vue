<script setup lang="ts">
import {Head} from '@inertiajs/vue3';
import PageHeading from '@/Components/Headings/PageHeading.vue';
import { capitalize } from "@/Composables/capitalize"
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Link } from "@inertiajs/vue3"
import { Autoplay, Pagination, Navigation } from 'swiper/modules'
import DropZone from '@/Components/Dropzone/Dropzone.vue'
// Import Swiper styles
import 'swiper/css'
// import 'swiper/css/pagination';
import 'swiper/css/navigation';


const data = {
    delay: 2500,
    slides: [
        {
            imageSrc: "https://tailwindui.com/img/logos/mark.svg" ,
            imageAlt: "Front of men's Basic Tee in sienna",
            link: {
                label: "Open",
                target: "#"
            }
        },
        {
            imageSrc: "https://tailwindui.com/img/logos/mark.svg" ,
            imageAlt: "Lorem ipsum dolor sit amet consectetur.",
        },
        {
            imageSrc: "https://tailwindui.com/img/logos/mark.svg" ,
            imageAlt: "Lorem ipsum dolor Basic Tee in sienna",
            link: {
                label: "Browse Promo",
                target: "#"
            }
        },
        {
            imageSrc: "https://tailwindui.com/img/ecommerce-images/home-page-01-category-01.jpg" ,
            imageAlt: "Continuous infinite slider",
        },
    ]
}
 
const props = defineProps<{
    title: string,
    pageHead: object,
    banner: object

}>()




</script>


<template layout="App">
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead"></PageHeading>


    <div class="p-3">
        <div class="p-2.5" style="background-color: #fafafa;">
        <div class="h-96 p-3">
        <Swiper :spaceBetween="-1" :slidesPerView="1" :centeredSlides="true" :loop="true"
            :autoplay="{
                delay: data.delay,
                disableOnInteraction: false,
            }"
            :pagination="{
                clickable: true,
            }"
            :navigation="false" :modules="[Autoplay, Pagination, Navigation]" class="mySwiper">
            <SwiperSlide v-for="imgBanner in data.slides">
                <img :src="imgBanner.imageSrc" :alt="imgBanner.imageAlt" srcset="">
                <Link v-if="imgBanner.link" :href="imgBanner.link.target" class="bg-gray-800/40 text-gray-100 border border-gray-50/50 absolute bottom-6 right-11 rounded px-3 py-1 hover:bg-gray-900/60">
                    {{imgBanner.link.label}}
                </Link>
            </SwiperSlide>
        </Swiper>
    </div>
    <div class="m-2.5"><DropZone /></div>
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
    border:1px solid
}

.swiper-slide img {
    @apply w-full h-full;
    display: block;
    object-fit: cover;
}
</style>