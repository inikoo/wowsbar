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
    unsubscribe : object
}>()
let currentTab = ref(props.tabs.current);
const handleTabUpdate = (tabSlug) => useTabChange(tabSlug, currentTab);
const loading = ref(false)
const component = computed(() => {

    const components = {
        showcase: ProspectShowcase,
        history: TableHistories,
    };
    return components[currentTab.value];

});


const setUnsubscribe = async (close) => {
    loading.value = true
    try {
        const response = await axios.patch(
            route(
                props.unsubscribe.route.name,
                props.unsubscribe.route.parameters
            ),
            { data: {} }
        )

        notify({
            title: "Success!",
            text: 'Successfuly to set unsubscribe.',
            type: "success"
        })
        close()
        loading.value = false
        props.showcase.info.dont_contact_me_at = 'unsubscribe'

    } catch (error) {
        console.log(error)
        loading.value = false
        notify({
            title: "Failed",
            text: 'failed to set unsubscribe',
            type: "error"
        });
    }
}

</script>

<template layout="OrgApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead">
        <template v-if="!showcase.info.dont_contact_me_at" #other>
            <div>
                <Popover :width="'w-full'" position="right-[20px]" ref="_popover">
                    <template #button>
                        <div class="relative">
                            <Button :style="'tertiary'">Unsubscribe</Button>
                        </div>
                    </template>
                    <template #content="{ close: closed }">
                        <div class="p-2 w-64">
                            <p class="mb-2 text-gray-500 text-xs">{{trans('Are you sure you want to unsubscribe this prospect?')}}</p>
                            <div class="flex justify-end gap-2">
                                <Button :style="'tertiary'" size="xs" @click="closed()" label="Cancel"></Button>
                                <Button size="xs" @click="setUnsubscribe(closed)" :loading="loading" label="Ok"/>
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
