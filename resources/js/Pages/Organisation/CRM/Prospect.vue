<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 20 Jun 2023 20:46:53 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Head} from '@inertiajs/vue3';
import PageHeading from "@/Components/Headings/PageHeading.vue";
import { capitalize } from "@/Composables/capitalize"
import { library } from "@fortawesome/fontawesome-svg-core";
import {
    faCodeCommit,
    faGlobe,
    faGraduationCap,
    faMoneyBill,
    faPaperclip, faPaperPlane, faStickyNote,
    faTags,faCube,faCodeBranch
} from '@fal/';

import {useTabChange} from "@/Composables/tab-change";
import {computed, defineAsyncComponent, ref} from "vue";
import Tabs from "@/Components/Navigation/Tabs.vue";
import ProspectShowcase from "@/Pages/Organisation/Prospects/ProspectShowcase.vue"
import TableHistories from "@/Components/Tables/TableHistories.vue";

library.add(
    faStickyNote,
    faGlobe,
    faMoneyBill,
    faGraduationCap,
    faTags,
    faCodeCommit,
    faPaperclip,
    faPaperPlane,
    faCube,
    faCodeBranch
)

const ModelChangelog = defineAsyncComponent(() => import('@/Pages/ModelChangelog.vue'))

const props = defineProps<{
    title: string,
    pageHead: object,
    tabs: {
        current: string;
        navigation: object;
    }
    showcase?:object
    history?: object
}>()
let currentTab = ref(props.tabs.current);
const handleTabUpdate = (tabSlug) => useTabChange(tabSlug, currentTab);

const component = computed(() => {

    const components = {
        showcase: ProspectShowcase,
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
