<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Wed, 09 Aug 2023 11:25:44 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Head} from '@inertiajs/vue3';
import PageHeading from '@/Components/Headings/PageHeading.vue';
import { capitalize } from "@/Composables/capitalize"
import {faWindowMaximize} from "@/../private/pro-light-svg-icons"
import {library} from "@fortawesome/fontawesome-svg-core";
import Tabs from "@/Components/Navigation/Tabs.vue";
import {computed, ref} from "vue";
import {useTabChange} from "@/Composables/tab-change";
import ModelDetails from "@/Pages/ModelDetails.vue";
import TableHistories from "@/Pages/Tables/TableHistories.vue";
import TableBanners from "@/Pages/Tables/TableBanners.vue";
library.add(faWindowMaximize)

const props = defineProps <{
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
        banners: TableBanners
    };
    return components[currentTab.value];

});
</script>

<template layout="TenantApp">
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead"></PageHeading>
    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate"/>
    <component :is="component"  :tab="currentTab" :data="props[currentTab]"></component>
</template>

