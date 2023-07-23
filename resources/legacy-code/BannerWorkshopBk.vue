<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sun, 23 Jul 2023 23:04:24 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head, router } from "@inertiajs/vue3";
import { ref } from "vue";
import PageHeading from "@/Components/Headings/PageHeading.vue";
import { capitalize } from "@/Composables/capitalize";
import DropZone from "@/Components/Workshop/Dropzone/Dropzone.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Banner from "@/Components/Slider/Banner.vue";
import {trans} from "laravel-vue-i18n";


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

const data = ref(
    props.banner.layout.data.slides.length > 0
        ? [...props.banner.layout.data.slides]
        : [defaultSlide]
);

const filesChange = (value) => {
    data.value = value;
};


const changeLink = (file, value) => {
    const index = data.value.findIndex((item) => item.id === file.id);
    if (index !== -1) data.value[index].link = value;
};

const submit = () => {
    router.post(
        route(props.updateRoute.name, props.updateRoute.parameters)
        , data.value);
};
</script>

<template layout="App">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"></PageHeading>
    <div>
        <PrimaryButton class="float-right m-2.5" @click="submit">
            {{trans('Save')}}
        </PrimaryButton>
    </div>

    <div class="p-3">
        <div class="p-2.5">
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
