<!--
  - Author: Jonathan Lopez Sanchez <jonathan@ancientwisdom.biz>
  - Created: Tue, 28 Feb 2023 10:07:36 Central European Standard Time, Malaga, Spain
  - Copyright (c) 2023, Inikoo LTD
  -->

<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import PageHeading from "@/Components/Headings/PageHeading.vue"
import TableProspects from "@/Components/Tables/TableProspects.vue"
import {capitalize} from "@/Composables/capitalize"
import { computed, reactive, ref, watch } from 'vue';
import ButtonGroup from '@/Components/Elements/Buttons/ButtonGroup.vue'
import UploadExcel from '@/Components/Upload/UploadExcel.vue'
import {PageHeading as TSPageHeading} from '@/types/PageHeading'
import {routeType} from "@/types/route"
import Tabs from "@/Components/Navigation/Tabs.vue";
import {useTabChange} from "@/Composables/tab-change";


import TableProspectsMailshots from "@/Components/Tables/TableProspectsMailshots.vue"
import TableHistories from "@/Components/Tables/TableHistories.vue"
import ProspectsDashboard from "@/Pages/Organisation/CRM/Prospects/ProspectsDashboard.vue"

import {library} from "@fortawesome/fontawesome-svg-core";
import {faSeedling,faTachometerAlt, faTransporter, faCodeBranch, faMailBulk, faStore, faClock, faInfo, faTags,faThumbsDown, faLaugh, faChair} from '@fal'
import TableProspectLists from "@/Components/Tables/TableProspectLists.vue";
import { useEchoOrgPersonal } from '@/Stores/echo-org-personal';

library.add(faSeedling,faTachometerAlt, faTransporter, faCodeBranch, faMailBulk, faStore, faClock, faInfo, faTags, faThumbsDown, faLaugh, faChair)


const props = defineProps<{
    pageHead: TSPageHeading
    title: string
    tabs: {
        current: string;
        navigation: {}
    },
    uploads: {
        templates: {
            routes: routeType
        }
        channel: string
        event: string
    }
    uploadRoutes: {
        upload: routeType
        history: routeType
    }
    dashboard?: {}
    prospects?: {}
    mailshots?: {}
    lists?: {}
    history?: {}
    tags: {
        data: {
            id: number
        }[]
    }
}>()

const dataModal = reactive({isModalOpen: false})

const currentTab = ref<string>(props.tabs.current)
const handleTabUpdate = (tabSlug: string) => useTabChange(tabSlug, currentTab)

const component = computed(() => {
    const components: any = {
        dashboard: ProspectsDashboard,
        prospects: TableProspects,
        mailshots: TableProspectsMailshots,
        history: TableHistories,
        lists: TableProspectLists
    }
    
    return components[currentTab.value]
})

// Watch the recently uploaded file then reload the props to update data table
watch(useEchoOrgPersonal().recentlyUploaded, () => {
    router.reload(
        {
            only: ['prospects'],  // only reload the props with dynamic name tabSlug
        }
    )
})
</script>

<template layout="OrgApp">
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead">
        <template v-if="pageHead.actions[0].type === 'buttonGroup'" #button>
            <ButtonGroup :dataButton="pageHead.actions[0].buttons" :dataModal="dataModal"/>
        </template>
    </PageHeading>

    <Tabs :current="currentTab" :navigation="tabs.navigation" @update:tab="handleTabUpdate"/>
    <component :is="component" :tab="currentTab" :data="props[currentTab]" :tagsList="tags.data" class="isolate"></component>

    <!-- Modal: after click 'upload' button -->
    <UploadExcel
        :propName="'prospects'"
        description="Adding prospect"
        :routes="{
            upload: props.pageHead.actions[0].buttons[0].route,
            download: uploads.templates.routes,
            history: props.uploadRoutes.history
        }"
        :dataModal="dataModal"
        :dataPusher="{
            channel: uploads.channel,
            event: uploads.event
        }"
    />
</template>

