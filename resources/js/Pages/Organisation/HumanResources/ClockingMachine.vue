<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Fri, 16 Sept 2022 12:56:59 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PageHeading from '@/Components/Headings/PageHeading.vue';


import {library} from '@fortawesome/fontawesome-svg-core';
import { faEnvelope, faIdCard, faPhone, faSignature, faUser, faBuilding, faBirthdayCake, faVenusMars, faHashtag, faHeading, faHospitalUser, faClock, faPaperclip, faTimes, faCameraRetro} from '@fal';
import Tabs from "@/Components/Navigation/Tabs.vue";
import { computed, defineAsyncComponent, ref } from "vue";
import { useTabChange } from "@/Composables/tab-change";
import ModelDetails from "@/Components/ModelDetails.vue";
// import TableClockings from "@/Pages/Tables/TableClockings.vue";
import TableHistories from "@/Components/Tables/TableHistories.vue";

import { capitalize } from "@/Composables/capitalize"
import {faCheckCircle} from '@fas';
library.add(
    faIdCard,
    faUser,
    faCheckCircle,
    faSignature,
    faEnvelope,
    faPhone,
    faIdCard,
    faBirthdayCake,
    faVenusMars,
    faHashtag,
    faHeading,
    faHospitalUser,
    faClock,
    faPaperclip,
    faTimes,
    faCameraRetro,
    faBuilding
);

const ModelChangelog = defineAsyncComponent(() => import('@/Components/ModelChangelog.vue'))

const props = defineProps<{
    title: string,
    pageHead: object,
    tabs: {
        current: string;
        navigation: object;
    }
    clockings?: object;
    history?: object;
}>()
let currentTab = ref(props.tabs.current);
const handleTabUpdate = (tabSlug) => useTabChange(tabSlug, currentTab);

const component = computed(() => {

    const components = {
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

