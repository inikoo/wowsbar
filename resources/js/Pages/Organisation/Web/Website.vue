<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 15 Aug 2023 16:06:13 Malaysia Time, Pantai Lembeng, Bali
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { capitalize } from "@/Composables/capitalize"
import {computed, ref} from "vue";
import {useTabChange} from "@/Composables/tab-change";

import TableWebpages from "@/Components/Tables/TableWebpages.vue";
import ModelDetails from "@/Components/ModelDetails.vue";
import TableHistories from "@/Components/Tables/TableHistories.vue";
import WebsiteShowcase from '@/Pages/Organisation/Web/WebsiteShowcase.vue';
import WebsiteAnalytics from '@/Components/DataDisplay/WebsiteAnalytics.vue';


import PageHeading from "@/Components/Headings/PageHeading.vue";
import Tabs from "@/Components/Navigation/Tabs.vue";
import {library} from '@fortawesome/fontawesome-svg-core';

import {
    faAnalytics, faBrowser,
    faChartLine, faDraftingCompass, faRoad, faSlidersH,faClock
} from '@fal';

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
        'webpages': TableWebpages,
        'details': ModelDetails,
        'changelog': TableHistories,
        'showcase': WebsiteShowcase,
        'analytics': WebsiteAnalytics
    }

    return components[currentTab.value]
})

</script>

<template layout="OrgApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"></PageHeading>
    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate"/>
    <!-- <pre>{{ props.showcase }}</pre> -->
    <component :is="component" :tab="currentTab" :data="props[currentTab]"></component>

</template>
