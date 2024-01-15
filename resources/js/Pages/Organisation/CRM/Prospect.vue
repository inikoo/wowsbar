<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 20 Jun 2023 20:46:53 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PageHeading from "@/Components/Headings/PageHeading.vue";
import { capitalize } from "@/Composables/capitalize"
import { library } from "@fortawesome/fontawesome-svg-core";
import {
    faCodeCommit,
    faGlobe,
    faGraduationCap,
    faMoneyBill,
    faPaperclip, faPaperPlane, faStickyNote,
    faTags, faCube, faCodeBranch, faThumbsDown, faLaugh, faChair
} from '@fal';

import { useTabChange } from "@/Composables/tab-change";
import { computed, defineAsyncComponent, ref } from "vue";
import Tabs from "@/Components/Navigation/Tabs.vue";
import ProspectShowcase from "@/Pages/Organisation/Prospects/ProspectShowcase.vue"
import TableHistories from "@/Components/Tables/TableHistories.vue";
import Button from '@/Components/Elements/Buttons/Button.vue';
import Popover from '@/Components/Utils/Popover.vue';
import { trans } from 'laravel-vue-i18n'
import axios from 'axios'
import { notify } from "@kyvg/vue3-notification"
import { routeType } from '../../../types/route'

library.add(
    faStickyNote,
    faGlobe,
    faMoneyBill,
    faGraduationCap,
    faTags,
    faCodeCommit,
    faPaperclip,
    faPaperPlane,
    faCube,
    faCodeBranch,
    faThumbsDown, faLaugh, faChair
)

const ModelChangelog = defineAsyncComponent(() => import('@/Components/ModelChangelog.vue'))

const props = defineProps<{
    title: string,
    pageHead: object,
    tabs: {
        current: string;
        navigation: object;
    }
    showcase?: object
    history?: object
    unsubscribeActions: {
        is_subscribed: boolean
        unsubscribe: {
            route: routeType
            label: string
            confirmation: string
        }
        undo: {
            route: routeType
            label: string
            confirmation: string
        }
    }
}>()

let currentTab = ref(props.tabs.current);
const handleTabUpdate = (tabSlug: string) => useTabChange(tabSlug, currentTab);
const loading = ref(false)
const component = computed(() => {
    const components = {
        showcase: ProspectShowcase,
        history: TableHistories,
    };
    return components[currentTab.value];

});

// From subscribe to unsubscribe
const setUnsubscribe = async (routeAction: routeType, closePopover: Function) => {
    loading.value = true
    try {
        await axios.patch(
            route(
                routeAction.name,
                routeAction.parameters
            ),
            { data: {} }
        )

        notify({
            title: "Success!",
            text: 'Prospect is now unsubscribe to mailshot.',
            type: "success"
        })
        closePopover()
        loading.value = false
        props.unsubscribeActions.is_subscribed = false

    } catch (error) {
        console.log(error)
        loading.value = false
        notify({
            title: "Failed",
            text: 'Failed to set unsubscribe.',
            type: "error"
        });
    }
}

// From unsubscribe to subscribe
const setSubscribe = async (routeAction: routeType, closePopover: Function) => {
    loading.value = true
    try {
        await axios.patch(
            route(
                routeAction.name,
                routeAction.parameters
            ),
            { data: {} }
        )

        notify({
            title: "Success!",
            text: 'Prospect is now subscribe to mailshot🥳',
            type: "success"
        })
        closePopover()
        loading.value = false
        props.unsubscribeActions.is_subscribed = true

    } catch (error) {
        console.log(error)
        loading.value = false
        notify({
            title: "Failed",
            text: 'Failed to set the user to subscribe.',
            type: "error"
        });
    }
}

</script>

<template layout="OrgApp">
    <!-- aa<pre>{{ unsubscribeActions.is_subscribed }}</pre>dd -->
    <!-- <pre>{{ unsubscribeActions }}</pre> -->
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead">
        <template #other>
            <!-- If subscribed -->
            <div v-if="unsubscribeActions.is_subscribed" class="relative">
                <Popover :width="'w-full'" position="right-[0px]" ref="_popover">
                    <template #button="{ open: openPopover }">
                        <Button :style="openPopover ? 'gray' : 'tertiary'" :key="openPopover+'unsubscribe'">{{ unsubscribeActions.unsubscribe.label }}</Button>
                    </template>

                    <template #content="{ close: closePopover }">
                        <div class="p-2 w-64">
                            <p class="mb-2 text-gray-500 text-xs">{{ unsubscribeActions.unsubscribe.confirmation }}</p>
                            <div class="flex justify-end gap-2">
                                <Button :style="'tertiary'" size="xs" @click="closePopover()" label="Cancel"></Button>
                                <Button size="xs" @click="setUnsubscribe(unsubscribeActions.unsubscribe.route, closePopover)" :loading="loading" label="Unsubscribe"/>
                            </div>
                        </div>
                    </template>
                </Popover>
            </div>
            
            <!-- If already unsubscribed -->
            <div v-else class="relative">
                <Popover :width="'w-full'" position="right-[0px]" ref="_popover">
                    <template #button="{ open: openPopover }">
                        <Button :style="openPopover ? 'gray' : 'tertiary'" :key="openPopover+'unsubscribe'">{{ unsubscribeActions.undo.label }}</Button>
                    </template>

                    <template #content="{ close: closePopover }">
                        <div class="p-2 w-64">
                            <p class="mb-2 text-gray-500 text-xs">{{ unsubscribeActions.undo.confirmation }}</p>
                            <div class="flex justify-end gap-2">
                                <Button :style="'tertiary'" size="xs" @click="closePopover()" label="Cancel"></Button>
                                <Button size="xs" @click="setSubscribe(unsubscribeActions.undo.route, closePopover)" :loading="loading" label="Resubscribe"/>
                            </div>
                        </div>
                    </template>
                </Popover>
            </div>
        </template>
    </PageHeading>
    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate" />
    <component :is="component" :tab="currentTab" :data="props[currentTab]"></component>
</template>
