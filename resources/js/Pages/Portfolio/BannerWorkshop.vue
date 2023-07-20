<script setup lang="ts">
import { Head, router } from "@inertiajs/vue3";
import { ref } from "vue";
import PageHeading from "@/Components/Headings/PageHeading.vue";
import { capitalize } from "@/Composables/capitalize";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Link } from "@inertiajs/vue3";
import { Autoplay, Pagination, Navigation } from "swiper/modules";
import DropZone from "./Dropzone/Dropzone.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Banner from "@/Components/Banner.vue";
// Import Swiper styles
import "swiper/css";
// import 'swiper/css/pagination';
import "swiper/css/navigation";

const props = defineProps<{
    title: string;
    pageHead: object;
    banner: object;
}>();

const defaultSlide = {
    id: 3,
    imageAlt: "Lorem ipsum dolor Basic Tee in sienna",
    imageSrc: "/banner/product-page-03-product-01.jpg",
    link: { label: "open", target: "" },
};

const data = ref(
    props.banner.layout.data.slides.length > 0
        ? [...props.banner.layout.data.slides]
        : [defaultSlide]
);

const filesChange = (value) => {
    data.value = value;
};

const generateThumbnail = (file) => {
    if (file.file && file.file instanceof File) {
        let fileSrc = URL.createObjectURL(file.file);
        setTimeout(() => {
            URL.revokeObjectURL(fileSrc);
        }, 1000);
        return fileSrc;
    } else {
        return new URL(
            `@/../../../art/banner/` + file.imageSrc,
            import.meta.url
        ).href;
    }
};

const changeLink = (file, value) => {
    const index = data.value.findIndex((item) => item.id === file.id);
    if (index !== -1) data.value[index].link = value;
};

const set = () => {
    router.post("website.web-block-type.banner.store", data.value);
    console.log(data.value);
};
</script>

<template layout="App">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"></PageHeading>
    <div>
        <PrimaryButton class="float-right m-2.5" @click="set">
            Save Changes
        </PrimaryButton>
    </div>
   
    <div class="p-3">
        <div class="p-2.5" style="background-color: #fafafa">
            <Banner :data="{ data: { slides: [...data], delay: 2500 } }" />
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
