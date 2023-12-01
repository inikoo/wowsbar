<!--
  - Author: Jonathan Lopez Sanchez <jonathan@ancientwisdom.biz>
  - Created: Wed, 22 Feb 2023 10:36:47 Central European Standard Time, Malaga, Spain
  - Copyright (c) 2023, Inikoo LTD
  -->

<script setup lang="ts">
import {Head} from '@inertiajs/vue3';
import {library} from '@fortawesome/fontawesome-svg-core';
import {
    faCube,
    faFolder, faMoneyBillWave, faRoad
} from '@fal/';
import PageHeading from '@/Components/Headings/PageHeading.vue';
import ModelDetails from "@/Components/ModelDetails.vue";
import {useTabChange} from "@/Composables/tab-change";
import {computed, defineAsyncComponent, ref} from "vue";
import Tabs from "@/Components/Navigation/Tabs.vue";
import DepartmentShowcase from "@/Components/Showcases/Organisation/DepartmentShowcase.vue";
import TableProducts from "@/Components/Tables/TableProducts.vue";

import { capitalize } from "@/Composables/capitalize"

library.add(
    faFolder,
    faCube,
    faMoneyBillWave,
    faRoad
);

const ModelChangelog = defineAsyncComponent(() => import('@/Components/ModelChangelog.vue'))

const props = defineProps<{
    title: string,
    pageHead: object,
    tabs: {
        current: string;
        navigation: object;
    }
    products?:object
    showcase?: object
}>()


let currentTab = ref(props.tabs.current);
const handleTabUpdate = (tabSlug) => useTabChange(tabSlug, currentTab);

const component = computed(() => {

    const components = {
        showcase: DepartmentShowcase,
        products: TableProducts,
        details: ModelDetails,
        history: ModelChangelog,
    };
    return components[currentTab.value];

});

</script>


<template layout="OrgApp">
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead"></PageHeading>
    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate"/>
    <component :is="component" :tab="currentTab"  :data="props[currentTab]"></component>
</template>

