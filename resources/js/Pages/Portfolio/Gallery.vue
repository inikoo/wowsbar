<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Wed, 09 Aug 2023 14:45:02 Malaysia Time, Sanur, Bali
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">

import {Head} from '@inertiajs/vue3';
import PageHeading from '@/Components/Headings/PageHeading.vue';
import {library} from '@fortawesome/fontawesome-svg-core';
import {faImagePolaroid,faCloudUpload} from '@/../private/pro-light-svg-icons';
import Tabs from "@/Components/Navigation/Tabs.vue";
import {computed, ref} from "vue";

import {useTabChange} from "@/Composables/tab-change";
import { capitalize } from "@/Composables/capitalize"
import TableImages from "@/Pages/Tables/TableImages.vue";

library.add(faImagePolaroid,faCloudUpload);

const props = defineProps<{
    pageHead: object
    tabs: {
        current: string;
        navigation: object;
    }
    title: string
    uploaded_images?: object
    stock_images?: object

}>()


let currentTab = ref(props.tabs.current);
const handleTabUpdate = (tabSlug) => useTabChange(tabSlug, currentTab);

const component = computed(() => {

    const components = {
        uploaded_images: TableImages,
        stock_images: TableImages,

    };
    return components[currentTab.value];

});

</script>


<template layout="App">
    <!--suppress HtmlRequiredTitleElement -->
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead"></PageHeading>
    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate"/>
    <component :is="component"  :tab="currentTab" :data="props[currentTab]"></component>
</template>


