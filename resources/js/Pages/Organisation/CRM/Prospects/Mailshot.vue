<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 30 Oct 2023 12:48:06 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Head} from '@inertiajs/vue3';
import {library} from '@fortawesome/fontawesome-svg-core';
import PageHeading from '@/Components/Headings/PageHeading.vue';
import {computed, ref} from "vue";
import {useTabChange} from "@/Composables/tab-change";
import ModelDetails from "@/Pages/ModelDetails.vue";
import Tabs from "@/Components/Navigation/Tabs.vue";
import {capitalize} from "@/Composables/capitalize"
import MailshotShowcase from "@/Components/Showcases/Organisation/MailshotShowcase.vue";
import EmailPreview from "@/Components/Email/EmailPreview.vue";
import {faStop, faPlay} from '@fas/'

import {faEnvelopeSquare, faAt, faPaperPlane, faSpellCheck} from '@fal/'
import TableHistories from "@/Components/Tables/TableHistories.vue";
import TableDispatchedEmails from "@/Components/Tables/TableDispatchedEmails.vue";
import EditModel from "@/Pages/Organisation/EditModel.vue";

library.add(faEnvelopeSquare, faAt, faPaperPlane, faStop, faPlay, faSpellCheck)

const props = defineProps<{
    title: string,
    pageHead: object,
    tabs: {
        current: string;
        navigation: object;
    }
    changelog?: object,
    showcase?: object,
    email?: object
    recipients?: object
}>()

let currentTab = ref(props.tabs.current);
const handleTabUpdate = (tabSlug) => useTabChange(tabSlug, currentTab);

const component = computed(() => {

    const components = {
        showcase: MailshotShowcase,
        email: EmailPreview,
        details: ModelDetails,
        changelog: TableHistories,
        recipients: TableDispatchedEmails
    };
    return components[currentTab.value];

});


</script>


<template layout="OrgApp">
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead"></PageHeading>
    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate"/>
    <KeepAlive>
        <Transition name="slide-to-right" mode="out-in">
            <component :is="component" :key="currentTab" :tab="currentTab" :data="props[currentTab]"></component>
        </Transition>
    </KeepAlive>
</template>

