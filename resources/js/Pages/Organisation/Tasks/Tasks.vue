<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 26 Sep 2023 02:02:12 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { Head } from '@inertiajs/vue3'
import PageHeading from '@/Components/Headings/PageHeading.vue'
import TableCustomerWebsites from "@/Components/Tables/TableCustomerWebsites.vue"
import { capitalize } from "@/Composables/capitalize"
import Modal from '@/Components/Utils/Modal.vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faUpload, faFile as falFile, faTimes } from '@fal'
import { faFile as fasFile, faFileDownload } from '@fas'
import { library } from '@fortawesome/fontawesome-svg-core'
import { trans } from 'laravel-vue-i18n'
import axios from 'axios'
import { useFormatTime } from '@/Composables/useFormatTime'
import Tabs from "@/Components/Navigation/Tabs.vue";
import {useTabChange} from "@/Composables/tab-change";
import ModelDetails from "@/Components/ModelDetails.vue";
import TableHistories from "@/Components/Tables/TableHistories.vue";

library.add(faUpload, falFile, faTimes, faFileDownload, fasFile)

const props = defineProps<{
    pageHead: any
    title: string
    changelog?: object
    data?: object
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
        changelog: TableHistories
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
