<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 30 Oct 2023 12:48:06 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Head, router} from '@inertiajs/vue3';
import {library} from '@fortawesome/fontawesome-svg-core';
import PageHeading from '@/Components/Headings/PageHeading.vue';
import {computed, ref, onMounted, onUnmounted} from "vue";
import {useTabChange} from "@/Composables/tab-change";
import ModelDetails from "@/Components/ModelDetails.vue";
import Tabs from "@/Components/Navigation/Tabs.vue";
import {capitalize} from "@/Composables/capitalize"
import MailshotShowcase from "@/Components/Showcases/Organisation/MailshotShowcase.vue";
import EmailPreview from "@/Components/Email/EmailPreview.vue";
import {faStop, faPlay, faPaperPlane as fasPaperPlane} from '@fas'

import {faEnvelopeSquare, faAt, faPaperPlane, faSpellCheck} from '@fal'
import TableHistories from "@/Components/Tables/TableHistories.vue";
import TableDispatchedEmails from "@/Components/Tables/TableDispatchedEmails.vue";
import LabelEstimated from "@/Components/Mailshots/LabelEstimated.vue";
import { PageHeading as TSPageHeading } from '@/types/PageHeading'

library.add(faEnvelopeSquare, faAt, faPaperPlane, faStop, faPlay, fasPaperPlane, faSpellCheck)

const props = defineProps<{
    title: string,
    pageHead: TSPageHeading
    tabs: {
        current: string;
        navigation: object;
    }
    changelog?: object,
    showcase: {
        stats: {
            id: number
            number_estimated_dispatched_emails: number
        }
        timeline: {
            label: string
            timestamp: string
        }[]
    },
    recipients?: object
    email?: object
    mailshot: {
        id: number
        state: string
        emailEstimated: number
    }
    // [key: string]: any
}>()

let currentTab = ref(props.tabs.current);
const handleTabUpdate = (tabSlug: string) => useTabChange(tabSlug, currentTab);

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

onMounted(() => {
    window.Echo.private('org.general')
        .listen(`.mailshot.${props.mailshot.id}`, (e: any) => {
            if(e.state == 'sent'){
                let timelineSent = props.showcase.timeline.find((item) => item.label == 'Sent')
                timelineSent.timestamp = e.sent_at  // update the timline data
                props.pageHead.actions = []  // clear the button 'Stop'
            }

        })
})

onUnmounted(() => {
    window.Echo.private(`org.general`)
    .stopListening(`.mailshot.${props.mailshot.id}`)
})

</script>


<template layout="OrgApp">
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead"></PageHeading>
    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate"/>
    <LabelEstimated :idMailshot="mailshot.id" :emailsEstimated="mailshot.emailEstimated" :state="mailshot.state"/>

    <KeepAlive>
        <Transition name="slide-to-right" mode="out-in">
            <component :is="component" :key="currentTab" :tab="currentTab" :data="props[currentTab]"></component>
        </Transition>
    </KeepAlive>
</template>