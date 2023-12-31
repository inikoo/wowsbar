<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sat, 16 Sep 2023 11:54:32 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import { library } from "@fortawesome/fontawesome-svg-core";
import {
    faCube,
    faFileInvoice,
    faFolder,
    faFolderTree,
    faChartLine,
    faShoppingCart, faStickyNote
} from '@fal';
import { faCheckCircle } from '@fas';

import PageHeading from "@/Components/Headings/PageHeading.vue";
import { capitalize } from "@/Composables/capitalize";
import Tabs from "@/Components/Navigation/Tabs.vue";
import { computed, ref } from "vue";
import { useTabChange } from "@/Composables/tab-change";
import TableDepartments from "@/Components/Tables/TableDepartments.vue";
import TableProducts from "@/Components/Tables/TableProducts.vue";
import ModelDetails from "@/Components/ModelDetails.vue";
import TableHistories from "@/Components/Tables/TableHistories.vue";
import ShopShowcase from "@/Components/Showcases/ShopShowcase.vue";

library.add(faChartLine, faCheckCircle, faFolderTree, faFolder, faCube, faShoppingCart, faFileInvoice, faStickyNote);

const props = defineProps<{
    pageHead: object
    tabs: {
        current: string;
        navigation: object;
    },
    title: string
    showcase?: object
    departments?: object
    products?: object

}>();

let currentTab = ref(props.tabs.current);
const handleTabUpdate = (tabSlug) => useTabChange(tabSlug, currentTab);

const component = computed(() => {

    const components = {
        showcase: ShopShowcase,
        departments: TableDepartments,
        products: TableProducts,
        details: ModelDetails,
        history: TableHistories
    };
    return components[currentTab.value];

});

</script>


<template layout="OrgApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"></PageHeading>
    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate" />
    <component :is="component" :tab="currentTab" :data="props[currentTab]"></component>
</template>

