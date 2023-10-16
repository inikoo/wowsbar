<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Wed, 20 Sep 2023 09:06:45 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import PageHeading from '@/Components/Headings/PageHeading.vue'
import { capitalize } from "@/Composables/capitalize.ts"
import Tabs from '@/Components/Navigation/Tabs.vue';
import {computed, ref} from 'vue';
import {useTabChange} from '@/Composables/tab-change.js';
import TableCustomerHistories from '@/Components/Tables/TableCustomerHistories.vue';
import PortfolioDashboard from "@/Components/Dashboard/PortfolioDashboard.vue";
import {faTransporter2
} from '@fal/'
import {library} from "@fortawesome/fontawesome-svg-core";

library.add(faTransporter2)

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
        history: TableCustomerHistories
    };
    return components[currentTab.value];

});

</script>

<template layout="CustomerApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"></PageHeading>
    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate"/>
    <component :is="component" :tab="currentTab"  :data="props[currentTab]"></component>
</template>

