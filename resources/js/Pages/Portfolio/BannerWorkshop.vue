<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";
import PageHeading from "@/Components/Headings/PageHeading.vue";
import { capitalize } from "@/Composables/capitalize";
import Slider from "@/Components/Slider/Slider.vue";
import SlidesWorkshop from "@/Components/Workshop/SlidesWorkshop.vue";


const props = defineProps<{
    title: string;
    pageHead: object;
    bannerLayout: {};
    updateRoute: {
        name: string,
        parameters: string | string[]
    }
}>();


const layout = ref(props.bannerLayout);

const filesChange = (value) => {
    layout.value = value;
};


const changeLink = (file, value) => {
    const index = layout.value.findIndex((item) => item.id === file.id);
    if (index !== -1) layout.value[index].link = value;
};

</script>

<template layout="App">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"></PageHeading>

    <Slider :layout="layout" />
    <SlidesWorkshop class="clear-both"
        :files="layout.slides"
        :filesChange="filesChange"
        :changeLink="changeLink"
    />
</template>
