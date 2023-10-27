<!--
  - Author: Jonathan Lopez Sanchez <jonathan@ancientwisdom.biz>
  - Created: Tue, 28 Feb 2023 10:07:36 Central European Standard Time, Malaga, Spain
  - Copyright (c) 2023, Inikoo LTD
  -->

<script setup lang="ts">
import { Head } from "@inertiajs/vue3"
import PageHeading from "@/Components/Headings/PageHeading.vue"
import TableProspects from "@/Components/Tables/TableProspects.vue"
import { capitalize } from "@/Composables/capitalize"
import {computed, reactive, ref} from "vue"
import ButtonGroup from '@/Components/Elements/Buttons/ButtonGroup.vue'
import UploadExcel from '@/Components/Upload/UploadExcel.vue'

import { PageHeading as TSPageHeading } from '@/types/PageHeading'
import { routeType } from "@/types/route"
import Tabs from "@/Components/Navigation/Tabs.vue";
import {useTabChange} from "@/Composables/tab-change";

const props = defineProps <{
    pageHead: TSPageHeading
    title: string
    tabs: {
        current: string;
        navigation: object;
    },
    uploads: {
        templates: {
            routes: routeType
        }
        channel: string
        event: string
    }
    prospects?: object
}>()

const dataModal = reactive({isModalOpen: false})

let currentTab = ref(props.tabs.current);
const handleTabUpdate = (tabSlug) => useTabChange(tabSlug, currentTab);

const component = computed(() => {

    const components = {
        prospects: TableProspects,
    };
    return components[currentTab.value];

});
</script>

<template layout="OrgApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead">
        <template v-if="pageHead.actions[0].type === 'buttonGroup'" #button>
            <ButtonGroup :dataButton="pageHead.actions[0].buttons" :dataModal="dataModal" />
        </template>
    </PageHeading>

    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate"/>
    <component :is="component" :tab="currentTab"  :data="props[currentTab]"></component>

    <!-- Modal: after click 'upload' button -->
    <UploadExcel
        :routesModalUpload="{
            upload: props.pageHead.actions[0].buttons[0].route,
            download: uploads.templates.routes
        }"
        :dataModal="dataModal"
        :dataPusher="{
            channel: uploads.channel,
            event: uploads.event
        }"
    />
</template>

