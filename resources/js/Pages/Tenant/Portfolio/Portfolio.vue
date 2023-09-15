<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Thu, 31 Aug 2023 10:07:53 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import PageHeading from '@/Components/Headings/PageHeading.vue'
import { capitalize } from "@/Composables/capitalize.ts"
import Tabs from '@/Components/Navigation/Tabs.vue';
import {computed, ref} from 'vue';
import {useTabChange} from '@/Composables/tab-change.js';
import TableHistories from '@/Components/Tables/TableHistories.vue';
import PortfolioDashboard from "@/Components/Dashboard/PortfolioDashboard.vue";

const props = defineProps <{
    pageHead: object
    tabs: {
        current: string;
        navigation: object;
    },
    title: string
    dashboard?: object
    history?: object
}>()


let currentTab = ref(props.tabs.current);
const handleTabUpdate = (tabSlug) => useTabChange(tabSlug, currentTab);

const component = computed(() => {

    const components = {
        dashboard: PortfolioDashboard,
        history: TableHistories
    };
    return components[currentTab.value];

});

</script>

<template layout="TenantApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"></PageHeading>
    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate"/>
    <component :is="component" :tab="currentTab"  :data="props[currentTab]"></component>
</template>

