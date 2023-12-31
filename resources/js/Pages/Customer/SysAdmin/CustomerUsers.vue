<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 19 Sep 2023 13:37:29 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import TableCustomerUsers from "@/Components/Tables/TableCustomerUsers.vue"
import Tabs from "@/Components/Navigation/Tabs.vue"
import { computed, ref } from "vue"
import { useTabChange } from "@/Composables/tab-change"
import { faRoad, faTerminal, faUserCircle } from '@fal'
import TableCustomerUserRequestLogs from "@/Components/Tables/TableCustomerUserRequestLogs.vue"
import { library } from "@fortawesome/fontawesome-svg-core"
import { capitalize } from "@/Composables/capitalize"
import PageHeading from "@/Components/Headings/PageHeading.vue";
import TableCustomerHistories from "@/Components/Tables/TableCustomerHistories.vue";

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
        users: TableCustomerUsers,
        users_requests: TableCustomerUserRequestLogs,
        history: TableCustomerHistories
    };
    return components[currentTab.value];

});

</script>

<template layout="CustomerApp">
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead"></PageHeading>
    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate"/>
    <Transition name="slide-to-left" mode="out-in">
        <component :is="component" :tab="currentTab"  :data="props[currentTab]"></component>
    </Transition>
</template>
