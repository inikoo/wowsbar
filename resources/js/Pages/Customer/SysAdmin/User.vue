<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 19 Sep 2023 13:37:29 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Head} from '@inertiajs/vue3'

import PageHeading from '@/Components/Headings/PageHeading.vue'
import {computed, ref} from "vue"
import {useTabChange} from "@/Composables/tab-change"
import ModelDetails from "@/Pages/ModelDetails.vue"
import TableUserRequestLogs from "@/Components/Tables/TableUserRequestLogs.vue"
import TableHistories from "@/Components/Tables/TableHistories.vue"
import Tabs from "@/Components/Navigation/Tabs.vue"
import {faIdCard, faUser, faClock, faDatabase, faEnvelope, faHexagon, faFile} from "../../../../private/pro-light-svg-icons"
import {library} from "@fortawesome/fontawesome-svg-core"
import {capitalize} from "@/Composables/capitalize"
import UsersShowcase from  '@/Components/Showcases/Customer/UsersShowcase.vue'

library.add(
    faIdCard,
    faUser,
    faClock,
    faDatabase,
    faEnvelope,
    faHexagon,
    faFile,
)


const props = defineProps<{
    title: string
    pageHead: object
    tabs: {
        current: string
        navigation: object
    },
    request_logs?: object
    history?: object
    details?: object
    showcase?: object
}>()

let currentTab = ref(props.tabs.current);
const handleTabUpdate = (tabSlug) => useTabChange(tabSlug, currentTab);

const component = computed(() => {

    const components = {
        details: ModelDetails,
        request_logs: TableUserRequestLogs,
        history: TableHistories,
        showcase: UsersShowcase
    }
    return components[currentTab.value]

})

</script>

<template layout="CustomerApp">
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead"></PageHeading>
    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate"/>
    <component :is="component" :data="props[currentTab]"></component>
</template>
