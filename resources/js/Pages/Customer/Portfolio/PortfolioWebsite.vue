<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 19 Sep 2023 13:37:29 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Head} from '@inertiajs/vue3';
import {library} from '@fortawesome/fontawesome-svg-core';
import PageHeading from '@/Components/Headings/PageHeading.vue';
import {computed, ref} from "vue";
import {useTabChange} from "@/Composables/tab-change";
import ModelDetails from "@/Components/ModelDetails.vue";
import Tabs from "@/Components/Navigation/Tabs.vue";
import {capitalize} from "@/Composables/capitalize"
import TableCustomerHistories from "@/Components/Tables/TableCustomerHistories.vue";
import TableBanners from "@/Components/Tables/TableBanners.vue";
import FirstBannerWidget from "@/Components/EmptyState/FirstBannerWidget.vue";

import {faSign, faGlobe,faObjectGroup, faShoePrints} from '@fal'

library.add(faSign, faGlobe,faObjectGroup, faShoePrints)

const props = defineProps<{
    title: string,
    pageHead: object,
    tabs: {
        current: string;
        navigation: object;
    }
    changelog?: object
    banners?: object
    firstBanner?: any
    hasFirstBanner: boolean
}>()

let currentTab = ref(props.tabs.current);
const handleTabUpdate = (tabSlug) => useTabChange(tabSlug, currentTab);

const component = computed(() => {

    const components = {
        details: ModelDetails,
        changelog: TableCustomerHistories,
        banners: props.hasFirstBanner ? FirstBannerWidget : TableBanners
    };
    return components[currentTab.value];

});

</script>


<template layout="CustomerApp">
    <!-- <pre>{{ props }}</pre> -->
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead"></PageHeading>
    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate"/>
    <component :is="component" :tab="currentTab" :data="props[currentTab]"></component>
</template>

