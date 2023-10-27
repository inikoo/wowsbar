<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Thu, 17 Aug 2023 14:08:37 Malaysia Time, Sanur, Bali
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Tabs from "@/Components/Navigation/Tabs.vue";
import { computed, ref } from "vue";
import { useTabChange } from "@/Composables/tab-change";
import TableUserRequestLogs from "@/Components/Tables/TableUserRequestLogs.vue";
import { capitalize } from "@/Composables/capitalize"
import TableOrganisationUsers from "@/Components/Tables/TableOrganisationUsers.vue";
import PageHeading from "@/Components/Headings/PageHeading.vue";

const props = defineProps<{
    pageHead: object
    tabs: {
        current: string;
        navigation: object;
    },
    title: string
    users?: object
    users_requests?: object
}>()


let currentTab = ref(props.tabs.current);
const handleTabUpdate = (tabSlug) => useTabChange(tabSlug, currentTab);

const component = computed(() => {

    const components = {
        users: TableOrganisationUsers,
        users_requests: TableUserRequestLogs
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
