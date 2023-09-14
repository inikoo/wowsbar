<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Wed, 13 Sep 2023 23:58:37 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { capitalize } from "@/Composables/capitalize"
import {computed, ref} from "vue";
import {useTabChange} from "@/Composables/tab-change";

import ModelDetails from "@/Pages/ModelDetails.vue";
import TableHistories from "@/Pages/Tables/TableHistories.vue";

import PageHeading from "@/Components/Headings/PageHeading.vue";
import Tabs from "@/Components/Navigation/Tabs.vue";
import {library} from '@fortawesome/fontawesome-svg-core';

import {
    faAnalytics, faBrowser,
    faChartLine, faDraftingCompass, faRoad, faSlidersH,faClock
} from "@/../private/pro-light-svg-icons";
import WebpageShowcase from "@/Pages/Organisation/Web/WebpageShowcase.vue";
import WebpageAnalytics from "@/Pages/Organisation/Web/WebpageAnalytics.vue";

library.add(
    faChartLine,
    faClock,
    faAnalytics,
    faDraftingCompass,
    faSlidersH,
    faRoad,
    faBrowser,
);

const props = defineProps<{
    title: string
    pageHead: any
    tabs: {
        current: string
        navigation: object
    }
    webpages?: string
    changelog?: object
    showcase: any
}>()


const currentTab = ref(props.tabs.current);
const handleTabUpdate = (tabSlug) => useTabChange(tabSlug, currentTab);

const component = computed(() => {
    const components = {
        'details': ModelDetails,
        'changelog': TableHistories,
        'showcase': WebpageShowcase,
        'analytics': WebpageAnalytics
    }

    return components[currentTab.value]
})

</script>

<template layout="OrgApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"></PageHeading>
    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate"/>
    <component :is="component" :data="props[currentTab]"></component>

</template>
