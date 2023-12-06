<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 11 Sep 2023 14:50:05 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Head} from '@inertiajs/vue3';
import PageHeading from '@/Components/Headings/PageHeading.vue';
import {capitalize} from "@/Composables/capitalize"
import Tabs from "@/Components/Navigation/Tabs.vue";
import {computed, ref} from "vue";
import {useTabChange} from "@/Composables/tab-change";
import {faTachometer, faMailBulk} from '@fal'
import {library} from "@fortawesome/fontawesome-svg-core";
import TableEmailTemplates from "@/Components/Tables/TableEmailTemplates.vue";

library.add(faTachometer, faMailBulk);


const props = defineProps<{
    title: string,
    pageHead: object,
    tabs: {
        current: string;
        navigation: object;
    },
    email_template?: object
}>()

let currentTab = ref(props.tabs.current);
const handleTabUpdate = (tabSlug) => useTabChange(tabSlug, currentTab);

const component = computed(() => {

    const components = {
        showcase: null,
        email_template: TableEmailTemplates
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
