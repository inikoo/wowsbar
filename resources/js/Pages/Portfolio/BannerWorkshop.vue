<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";
import PageHeading from "@/Components/Headings/PageHeading.vue";
import { capitalize } from "@/Composables/capitalize";

import SlidesWorkshop from "@/Components/Workshop/SlidesWorkshop.vue";
import Slider from "@/Components/Slider/Slider.vue";


const props = defineProps<{
    title: string;
    pageHead: object;
    bannerLayout: object;
    updateRoute: {
        name: string,
        parameters: string | string[]
    }
}>();


const data = ref(props.bannerLayout);

const filesChange = (value) => {
    data.value = value;
};


const changeLink = (file, value) => {
    const index = data.value.findIndex((item) => item.id === file.id);
    if (index !== -1) data.value[index].link = value;
};


</script>

<template layout="App">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"></PageHeading>

    <Slider :layout="data" />
    <SlidesWorkshop class="clear-both"
        :data="data"
        :filesChange="filesChange"
        :changeLink="changeLink"
    />
</template>
