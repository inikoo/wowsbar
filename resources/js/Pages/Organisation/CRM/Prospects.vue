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
import TableProspectsMailshots from "@/Components/Tables/TableProspectsMailshots.vue";
import TableHistories from "@/Components/Tables/TableHistories.vue";

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
    mailshots?: object
    history?: object
    tags: string[]
}>()

const dataModal = reactive({isModalOpen: false})

let currentTab = ref(props.tabs.current);
const handleTabUpdate = (tabSlug) => useTabChange(tabSlug, currentTab);

const component = computed(() => {

    const components = {
        prospects: TableProspects,
        mailshots: TableProspectsMailshots,
        history: TableHistories
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
    <component :is="component" :tab="currentTab" :data="props[currentTab]" :tagsList="tags.data"></component>

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

