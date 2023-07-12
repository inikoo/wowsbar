

<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Wed, 12 Jul 2023 16:12:16 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Head} from '@inertiajs/vue3';
import {library} from '@fortawesome/fontawesome-svg-core';
import {
    faAnalytics, faBrowser,
    faChartLine, faDraftingCompass, faRoad, faSlidersH, faUsersClass
} from "../../../private/pro-light-svg-icons";

import PageHeading from '@/Components/Headings/PageHeading.vue';
import { computed, defineAsyncComponent, ref } from "vue";
import { useTabChange } from "@/Composables/tab-change";
import ModelDetails from "@/Pages/ModelDetails.vue";
import Tabs from "@/Components/Navigation/Tabs.vue";
import { faClock } from "../../../private/pro-solid-svg-icons";
import { capitalize } from "@/Composables/capitalize"
import TableHistories from "@/Pages/Tables/TableHistories.vue";

library.add(
    faChartLine,
    faClock,
    faAnalytics,
    faUsersClass,
    faDraftingCompass,
    faSlidersH,
    faRoad,
    faClock,
    faBrowser,
);

const ModelChangelog = defineAsyncComponent(() => import('@/Pages/ModelChangelog.vue'))

const props = defineProps<{
    title: string,
    pageHead: object,
    tabs: {
        current: string;
        navigation: object;
    }
    changelog?: object
}>()

let currentTab = ref(props.tabs.current);
const handleTabUpdate = (tabSlug) => useTabChange(tabSlug, currentTab);

const component = computed(() => {

    const components = {
        details: ModelDetails,
        changelog: TableHistories,
    };
    return components[currentTab.value];

});

</script>


<template layout="App">
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead"></PageHeading>
    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate"/>
    <component :is="component" :data="props[currentTab]"></component>
</template>

