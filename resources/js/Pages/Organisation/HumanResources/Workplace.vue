<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Thu, 08 Sept 2022 00:38:38 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import {library} from '@fortawesome/fontawesome-svg-core';
import { faChessClock } from '@fal';
import { capitalize } from "@/Composables/capitalize"
import TableHistories from "@/Components/Tables/TableHistories.vue";

import PageHeading from '@/Components/Headings/PageHeading.vue';

library.add(
   faChessClock
)
import { computed, defineAsyncComponent, ref } from "vue";
import { useTabChange } from "@/Composables/tab-change";
import ModelDetails from "@/Components/ModelDetails.vue";
import Tabs from "@/Components/Navigation/Tabs.vue";
// import TableClockingMachine from "@/Pages/Tables/TableClockingMachines.vue";
// import TableClockings from "@/Pages/Tables/TableClockings.vue";


const ModelChangelog = defineAsyncComponent(() => import('@/Components/ModelChangelog.vue'))

const props = defineProps<{
    title: string,
    pageHead: object,
    tabs: {
        current: string;
        navigation: object;
    }
    clocking_machines?: object;
    clockings?: object;
    history?: object;

}>()

let currentTab = ref(props.tabs.current);
const handleTabUpdate = (tabSlug) => useTabChange(tabSlug, currentTab);

const component = computed(() => {

    const components = {
        // clocking_machines: TableClockingMachine,
        // clockings: TableClockings,
        details: ModelDetails,
        history: TableHistories,
    };
    return components[currentTab.value];

});



</script>


<template layout="OrgApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"></PageHeading>
    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate"/>
    <component :is="component" :tab="currentTab" :data="props[currentTab]"></component>
</template>

