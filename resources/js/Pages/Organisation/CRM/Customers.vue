<!--
  -  Author: Raul Perusquia <raul@inikoo.com>
  -  Created: Mon, 17 Oct 2022 17:33:07 British Summer Time, Sheffield, UK
  -  Copyright (c) 2022, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Head} from '@inertiajs/vue3';
import PageHeading from '@/Components/Headings/PageHeading.vue';
import TableCustomers from '@/Components/Tables/TableCustomers.vue';
import {capitalize} from "@/Composables/capitalize"
import {PageHeading as TSPageHeading} from "@/types/PageHeading";
import {computed, ref} from "vue";
import {useTabChange} from "@/Composables/tab-change";
import Tabs from "@/Components/Navigation/Tabs.vue";
import {library} from "@fortawesome/fontawesome-svg-core";
import {faTachometerAlt, faCodeBranch, faMailBulk, faStore, faClock, faInfo, faTags, faNewspaper, faPollPeople} from '@fal'
import CustomersDashboard from "@/Pages/Organisation/CRM/Customers/CustomersDashboard.vue";

library.add(faTachometerAlt, faCodeBranch, faMailBulk, faNewspaper, faStore, faClock, faInfo, faTags, faPollPeople)


const props = defineProps<{
    pageHead: TSPageHeading
    title: string
    tabs: {
        current: string;
        navigation: object;
    },
    dashboard?: object
    customers?: object
}>()

let currentTab = ref(props.tabs.current);
const handleTabUpdate = (tabSlug) => useTabChange(tabSlug, currentTab);

const component = computed(() => {

    const components: any = {
        dashboard: CustomersDashboard,
        customers: TableCustomers,
    };
    return components[currentTab.value];

});

</script>

<template layout="OrgApp">
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead"></PageHeading>

    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate"/>
    <component :is="component" :tab="currentTab" :data="props[currentTab]"></component>
</template>
