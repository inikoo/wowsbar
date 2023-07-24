<script setup lang="ts">
import { Head, router } from "@inertiajs/vue3";
import { ref } from "vue";
import PageHeading from "@/Components/Headings/PageHeading.vue";
import { capitalize } from "@/Composables/capitalize";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Banner from "@/Components/Slider/Slider.vue";
import {trans} from "laravel-vue-i18n";
import SlidesWorkshop from "@/Components/Workshop/SlidesWorkshop.vue";


const props = defineProps<{
    title: string;
    pageHead: object;
    banner: object;
    updateRoute: {
        name: string,
        parameters: string | string[]
    }
}>();

const defaultSlide = {
    id: 3,
    imageAlt: "Lorem ipsum dolor Basic Tee in sienna",
    imageSrc: "/banner/product-page-03-product-01.jpg",
    link: { label: "open", target: "" },
};
console.log(props.banner)
const data = ref(
    props.banner.layout.slides.length > 0
        ? [...props.banner.layout.slides]
        : [defaultSlide]
);

const filesChange = (value) => {
    data.value = value;
};


const changeLink = (file, value) => {
    const index = data.value.findIndex((item) => item.id === file.id);
    if (index !== -1) data.value[index].link = value;
};

console.log(data)
</script>

<template layout="App">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"></PageHeading>

    <!-- <Banner :layout="{ slides: { slides: [...data], delay: 2500 } }" /> -->
    <SlidesWorkshop class="clear-both"
        :slide="data"
        :filesChange="filesChange"
        :changeLink="changeLink"
    />
</template>
