<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 27 Oct 2023 15:52:01 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Tabs from "@/Components/Navigation/Tabs.vue";
import { computed, ref } from "vue";
import { useTabChange } from "@/Composables/tab-change";
import { faRoad, faTerminal, faUserCircle,faSpellCheck } from '@fal/'
import { library } from "@fortawesome/fontawesome-svg-core"
import { capitalize } from "@/Composables/capitalize"
import PageHeading from "@/Components/Headings/PageHeading.vue";
import TableProspectsMailshots from "@/Components/Tables/TableProspectsMailshots.vue";
import {faStop, faPlay} from '@fas/'
import Edit from "@/Components/Edit.vue";

library.add(faRoad, faTerminal, faUserCircle, faSpellCheck,  faStop, faPlay)
const props = defineProps<{
    pageHead: object
    tabs: {
        current: string;
        navigation: object;
    },
    title: string
    mailshots?: object
    settings?: object
}>()


let currentTab = ref(props.tabs.current);
const handleTabUpdate = (tabSlug) => useTabChange(tabSlug, currentTab);

const component = computed(() => {

    const components = {
        mailshots: TableProspectsMailshots,
        settings: Edit
    };
    return components[currentTab.value];

});

</script>

<template layout="OrgApp">
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead"></PageHeading>
    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate"/>
    <component :is="component" :key="currentTab" :tab="currentTab" :data="props[currentTab]" :formData="{...props[currentTab]}"></component>
</template>
