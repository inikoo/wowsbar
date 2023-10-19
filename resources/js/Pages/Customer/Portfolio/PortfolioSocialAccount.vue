<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 19 Sep 2023 13:37:29 Malaysia Time, Pantai Lembeng, Bali, Indonesia
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
import TableCustomerHistories from "@/Components/Tables/TableCustomerHistories.vue";

import {faRectangleWide, faGlobe, faMoneyBill, faLayerGroup} from '@fal/'
import {
    faFacebook,
    faInstagram,
    faLinkedin,
    faPinterest,
    faTiktok,
    faTwitter, faYoutube
} from "@fortawesome/free-brands-svg-icons";
import { faMicrophoneStand } from '@fal/'
import TablePortfolioSocialAccountPosts from "@/Components/Tables/TablePortfolioSocialAccountPosts.vue";
import TablePortfolioSocialAccountAds from "@/Components/Tables/TablePortfolioSocialAccountAds.vue";

library.add(faRectangleWide, faMoneyBill, faLayerGroup, faGlobe, faFacebook, faTwitter, faTiktok, faPinterest, faLinkedin, faInstagram, faYoutube, faMicrophoneStand)

const props = defineProps<{
    title: string,
    pageHead: object,
    tabs: {
        current: string;
        navigation: object;
    }
    changelog?: object
    account?: object
    post?: object
    ads?: object
}>()

let currentTab = ref(props.tabs.current);
const handleTabUpdate = (tabSlug) => useTabChange(tabSlug, currentTab);

const component = computed(() => {

    const components = {
        details: ModelDetails,
        changelog: TableCustomerHistories,
        post: TablePortfolioSocialAccountPosts,
        ads: TablePortfolioSocialAccountAds,
    };
    return components[currentTab.value];

});

</script>

<template layout="CustomerApp">
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead"></PageHeading>
    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate"/>
    <component :is="component" :key="currentTab"  :tab="currentTab" :data="props[currentTab]"></component>
</template>
