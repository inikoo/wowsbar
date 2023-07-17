<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";
import PageHeading from "@/Components/Headings/PageHeading.vue";
import { capitalize } from "@/Composables/capitalize";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Link } from "@inertiajs/vue3";
import { Autoplay, Pagination, Navigation } from "swiper/modules";
import DropZone from "./Dropzone/Dropzone.vue";
import { get } from 'lodash'
// Import Swiper styles
import "swiper/css";
// import 'swiper/css/pagination';
import "swiper/css/navigation";

const props = defineProps<{
    title: string;
    pageHead: object;
    banner: object;
}>();

console.log(props)

const data = ref(props.banner.layout.data.slides);
const dropZone = ref(null);

const filesChange = (value) => {
    data.value = value;
    console.log(value);
};

const generateThumbnail = (file) => {
    if (file.file && file.file instanceof File) {
        let fileSrc = URL.createObjectURL(file.file);
        setTimeout(() => {
            URL.revokeObjectURL(fileSrc);
        }, 1000);
        return fileSrc;
    } else {
        return file.imageSrc;
    }
};

const changeLink = (file, value) => {
    const index = data.value.findIndex(
        (item) => item.imageAlt === file.imageAlt
    );
    if (index !== -1) {
        data.value[index].link = value;
    }
};

const set =()=>{
    console.log(data.value)
}
</script>

<template layout="App">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"></PageHeading>
    <div>
        <button @click="set"
        class="px-4 py-2 font-semibold text-sm bg-orange-600 text-white rounded-lg float-right m-2.5 w-60 shadow-sm"
    >
        Save Changes
    </button>
    </div>
   

    <div class="p-3">
        <div class="p-2.5" style="background-color: #fafafa">
            <div class="h-96 p-3">
                <Swiper
                    :spaceBetween="-1"
                    :slidesPerView="1"
                    :centeredSlides="true"
                    :loop="true"
                    :autoplay="{
                        delay: banner.layout.data.delay,
                        disableOnInteraction: false,
                    }"
                    :pagination="{
                        clickable: true,
                    }"
                    :navigation="false"
                    :modules="[Autoplay, Pagination, Navigation]"
                    class="mySwiper"
                >
                    <SwiperSlide v-for="imgBanner in data">
                        <img
                            :src="generateThumbnail(imgBanner)"
                            :alt="imgBanner.imageAlt"
                            srcset=""
                        />
                        <Link
                            v-if="imgBanner.link"
                            :href="imgBanner.link.target"
                            class="bg-gray-800/40 text-gray-100 border border-gray-50/50 absolute bottom-6 right-11 rounded px-3 py-1 hover:bg-gray-900/60"
                        >
                            {{ imgBanner.link.label }}
                        </Link>
                    </SwiperSlide>
                </Swiper>
            </div>
            <div class="m-2.5">
                <DropZone
                    :files="data"
                    :filesChange="filesChange"
                    :changeLink="changeLink"
                />
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
    border: 1px solid;
}

.swiper-slide img {
    @apply w-full h-full;
    display: block;
    object-fit: cover;
}
</style>
