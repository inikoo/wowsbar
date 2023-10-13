<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 19 Sep 2023 13:37:29 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import PageHeading from '@/Components/Headings/PageHeading.vue'
import TablePortfolioWebsites from "@/Components/Tables/TablePortfolioWebsites.vue"
import { capitalize } from "@/Composables/capitalize"
import { faUpload, faFile as falFile, faTimes } from '@/../private/pro-light-svg-icons'
import { faFile as fasFile, faFileDownload } from '@/../private/pro-solid-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
import Tabs from "@/Components/Navigation/Tabs.vue";
import {useTabChange} from "@/Composables/tab-change";
import ModelDetails from "@/Pages/ModelDetails.vue";
import TableCustomerHistories from "@/Components/Tables/TableCustomerHistories.vue";
import TableUploadedWebsites from "@/Components/Tables/TableUploadedWebsites.vue";

library.add(faUpload, falFile, faTimes, faFileDownload, fasFile)

const props = defineProps<{
    pageHead: any
    title: string
    websites?: object
    changelog?: object
    uploaded_websites?: object
    tabs: {
        current: string;
        navigation: object;
    }
}>()

let currentTab = ref(props.tabs.current);
const handleTabUpdate = (tabSlug) => useTabChange(tabSlug, currentTab);

const component = computed(() => {
    const components = {
        details: ModelDetails,
        changelog: TableCustomerHistories,
        websites: TablePortfolioWebsites,
        uploaded_websites: TableUploadedWebsites
    };

    return components[currentTab.value];
});



</script>

<template layout="CustomerApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead">
    </PageHeading>
    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate"/>
    <component :is="component"  :tab="currentTab" :data="props[currentTab]"></component>
</template>
