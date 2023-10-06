<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 02 Oct 2023 03:20:36 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Head} from '@inertiajs/vue3';
import {library} from '@fortawesome/fontawesome-svg-core';
import PageHeading from '@/Components/Headings/PageHeading.vue';
import {computed, ref} from "vue";
import {useTabChange} from "@/Composables/tab-change";
import ModelDetails from "@/Pages/ModelDetails.vue";
import Tabs from "@/Components/Navigation/Tabs.vue";
import {capitalize} from "@/Composables/capitalize"
import TableHistories from "@/Components/Tables/TableHistories.vue";
import BannerShowcase from "@/Components/Showcases/Customer/BannerShowcase.vue";

import {faRectangleWide, faGlobe, faPencil, faSeedling, faPaste,faLayerGroup} from "../../../../private/pro-light-svg-icons"
import TableSnapshots from '@/Components/Tables/TableSnapshots.vue';

library.add(faRectangleWide, faGlobe, faPencil,faSeedling, faPaste,faLayerGroup)

const props = defineProps<{
    title: string,
    pageHead: object,
    banner: {
        'slug': string,
        'ulid': string,
        'id': number,
        'code': string,
        'name': string,
        'state' : String
    }
    tabs: {
        current: string;
        navigation: object;
    }
    changelog?: object,
    showcase?: object,
    snapshots?: object,
}>()

let currentTab = ref(props.tabs.current);
const handleTabUpdate = (tabSlug) => useTabChange(tabSlug, currentTab);

const component = computed(() => {

    const components = {
        showcase: BannerShowcase,
        details: ModelDetails,
        changelog: TableHistories,
        snapshots: TableSnapshots
    };
    return components[currentTab.value];

});


</script>


<template layout="CustomerApp">
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead"></PageHeading>
    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate"/>
    <component :is="component" :tab="currentTab" :data="props[currentTab]"  :banner="banner"></component>
</template>

