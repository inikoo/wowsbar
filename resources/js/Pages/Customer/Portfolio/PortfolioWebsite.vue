<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 19 Sep 2023 13:37:29 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Head} from '@inertiajs/vue3';
import {library} from '@fortawesome/fontawesome-svg-core';
import PageHeading from '@/Components/Headings/PageHeading.vue';
import { ref , computed} from "vue";
import {useTabChange} from "@/Composables/tab-change";
import Tabs from "@/Components/Navigation/Tabs.vue";
import {capitalize} from "@/Composables/capitalize"
import CMS from '@/Components/CMS/cmsCustomer/CmsCustomer.vue'

import {faSign, faGlobe,faObjectGroup} from '@fal'

library.add(faSign, faGlobe,faObjectGroup)

const props = defineProps<{
    title: string,
    pageHead: object,
    tabs: {
        current: string;
        navigation: object;
    }
    changelog?: object
    banners?: object
    firstBanner?: any
    hasFirstBanner: boolean
}>()

let currentTab = ref(props.tabs.current);
const handleTabUpdate = (tabSlug) => useTabChange(tabSlug, currentTab);



const component = computed(() => {

    const components = {
        cms: CMS,
    };
    console.log(components)
    return components[currentTab.value];
});

</script>


<template layout="CustomerApp">
    <!-- <pre>{{ props }}</pre> -->
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead"></PageHeading>
    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate"/>
    <component :is="component" :tab="currentTab" :data="props[currentTab]"></component>
</template>

