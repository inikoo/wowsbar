<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Wed, 12 Jul 2023 16:12:16 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Head} from '@inertiajs/vue3';
import {library} from '@fortawesome/fontawesome-svg-core';
import PageHeading from '@/Components/Headings/PageHeading.vue';
import {computed, ref} from "vue";
import {useTabChange} from "@/Composables/tab-change";
import ModelDetails from "@/Pages/ModelDetails.vue";
import Tabs from "@/Components/Navigation/Tabs.vue";
import {capitalize} from "@/Composables/capitalize"
import TableHistories from "@/Pages/Tables/TableHistories.vue";
import TableBanners from "@/Pages/Tables/TableBanners.vue";

import {faWindowMaximize, faGlobe} from "@/../private/pro-light-svg-icons"

library.add(faWindowMaximize, faGlobe)

const props = defineProps<{
    title: string,
    pageHead: object,
    tabs: {
        current: string;
        navigation: object;
    }
    changelog?: object
    banners?: object
}>()

let currentTab = ref(props.tabs.current);
const handleTabUpdate = (tabSlug) => useTabChange(tabSlug, currentTab);

const component = computed(() => {

    const components = {
        details: ModelDetails,
        changelog: TableHistories,
        banners: TableBanners
    };
    return components[currentTab.value];

});

</script>


<template layout="App">
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead"></PageHeading>
    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate"/>
    <component :is="component"  :tab="currentTab" :data="props[currentTab]"></component>
</template>

