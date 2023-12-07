<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Wed, 20 Sep 2023 13:50:48 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import TableOrganisationCustomerUsers from "@/Components/Tables/TableOrgCustomerUsers.vue"
import Tabs from "@/Components/Navigation/Tabs.vue"
import { computed, ref } from "vue"
import { useTabChange } from "@/Composables/tab-change"
import { faRoad, faTerminal, faUserCircle } from '@fal'
//import TableUserRequestLogs from "@/Components/Tables/TableUserRequestLogs.vue"
import { library } from "@fortawesome/fontawesome-svg-core"
import { capitalize } from "@/Composables/capitalize"
import PageHeading from "@/Components/Headings/PageHeading.vue";
import TableHistories from "@/Components/Tables/TableHistories.vue";

library.add(faRoad, faTerminal, faUserCircle)
const props = defineProps <{
    pageHead: object
    tabs: {
        current: string;
        navigation: object;
    },
    title: string
    users?: object
    users_requests?: object,
    history?: object
}>()


let currentTab = ref(props.tabs.current);
const handleTabUpdate = (tabSlug) => useTabChange(tabSlug, currentTab);

const component = computed(() => {
    const components = {
        users: TableOrganisationCustomerUsers,
       // users_requests: TableUserRequestLogs,
        history: TableHistories
    };
    return components[currentTab.value];

});

</script>

<template layout="OrgApp">
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead"></PageHeading>
    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate"/>
    <component :is="component" :tab="currentTab"  :data="props[currentTab]"></component>
</template>
