<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Thu, 08 Sept 2022 00:38:38 Malaysia Time, Kuala Lumpur, Malaysia
  -  Copyright (c) 2022, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import {library} from '@fortawesome/fontawesome-svg-core';
import { faUserSlash,faUser, faPaperclip, faCameraRetro,faIdCard} from '@fal/';
import { router } from '@inertiajs/vue3'
import { capitalize } from "@/Composables/capitalize"

import PageHeading from '@/Components/Headings/PageHeading.vue';


import { computed, defineAsyncComponent, ref } from "vue";
import { useTabChange } from "@/Composables/tab-change";
import ModelDetails from "@/Pages/ModelDetails.vue";
import DataModel from "@/Pages/DataModel.vue";

import Tabs from "@/Components/Navigation/Tabs.vue";
import TableHistories from "@/Components/Tables/TableHistories.vue";
import TableProspects from "@/Components/Tables/TableProspects.vue";

library.add(
    faUserSlash,
    faUser,
    faPaperclip,
    faCameraRetro,faIdCard
)

const ModelChangelog = defineAsyncComponent(() => import('@/Pages/ModelChangelog.vue'))

const props = defineProps<{
    title: string,
    pageHead: object,
    tabs: {
        current: string;
        navigation: object;
    },
    history?: object
    prospects?:object
    tags: {
        id: number
        slug: string
        name: string
        type: boolean
    }

}>()

let currentTab = ref(props.tabs.current);
const handleTabUpdate = (tabSlug) => useTabChange(tabSlug, currentTab);

const component = computed(() => {

    const components = {
        details: ModelDetails,
        history: TableHistories,
        prospects: TableProspects
    };
    return components[currentTab.value];

});

</script>

<template layout="OrgApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"></PageHeading>
    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate"/>
    <component :is="component" :tab="currentTab" :data="props[currentTab]" :tagsList="[tags]"></component>
</template>
