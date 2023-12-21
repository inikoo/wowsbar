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
import {faStop, faPlay, faPaperPlane as fasPaperPlane, faBookmark} from '@fas'

import {faEnvelopeSquare, faAt, faPaperPlane, faSpellCheck} from '@fal'
import TableHistories from "@/Components/Tables/TableHistories.vue";
import TableDispatchedEmails from "@/Components/Tables/TableDispatchedEmails.vue";
import LabelEstimated from "@/Components/Mailshots/LabelEstimated.vue";
import { PageHeading as TSPageHeading } from '@/types/PageHeading'
import Modal from '@/Components/Utils/Modal.vue'
import PureInput from '@/Components/Pure/PureInput.vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
import axios from 'axios'
import { notify } from '@kyvg/vue3-notification'
import { Stats } from '@/types/Mailshot'


library.add(faEnvelopeSquare, faAt, faPaperPlane, faStop, faPlay, fasPaperPlane, faBookmark, faSpellCheck)

const props = defineProps<{
    title: string,
    pageHead: TSPageHeading
    tabs: {
        current: string;
        navigation: object;
    }
    changelog?: object,
    showcase: {
        id: number
        state: string
        stats: Stats
        timeline: {
            label: string
            timestamp: string
        }[]
    },
    recipients?: object
    email?: object
    mailshot: {
    //     id: number
    //     state: string
        emailEstimated: number
    }
    saved_as_template: boolean
    // [key: string]: any
}>()

let currentTab = ref(props.tabs.current);
const handleTabUpdate = (tabSlug: string) => useTabChange(tabSlug, currentTab);
const isAddTemplateOpen = ref(false)
const isLoading = ref(false)
const templateName = ref('')
const templateState = ref(props.saved_as_template)

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

// When click on button Add to Template
const submitAddTemplate = async () => {
    isLoading.value = true
    try {
        const response = await axios.post(
            route('org.models.prospect-mailshot.email_templates.store', props.mailshot),
            { name: templateName.value }
        )
        templateState.value = true
        
        setTimeout(() => {
            isAddTemplateOpen.value = false
        }, 1000)
        notify({
            text: "Add email design to template is successfully!",
            // text: error,
            type: 'success'
        })
    } catch (error: any) {
        notify({
            title: "Can't add to template",
            text: error,
            type: 'error'
        })
    } finally {
        isLoading.value = false
    }
}

onMounted(() => {
    window.Echo.private('org.general')
        .listen(`.mailshot.${props.showcase.id}`, (e: any) => {
            // If send Mailshot is finish
            if(e.state == 'sent'){
                props.showcase.state = e.state

                // Update: Timeline
                let timelineSent = props.showcase.timeline.find((item) => item.label == 'Sent')
                timelineSent.timestamp = e.sent_at

                // Update: PageHeading
                props.pageHead.actions = []  // clear the button 'Stop'
                props.pageHead.iconRight = e.state_icon  // update the icon
            }
        })
})

onUnmounted(() => {
    window.Echo.private(`org.general`)
    .stopListening(`.mailshot.${props.showcase.id}`)
})


</script>


<template layout="OrgApp">
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead" />

    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate"/>
    
    <!-- Label Estimated -->
    <LabelEstimated :idMailshot="showcase.id" :emailsEstimated="showcase.stats.number_estimated_dispatched_emails" :state="showcase.state" :stats="showcase.stats">
        <!-- Template: save to template -->
        <template #rightSide v-if="showcase.state == 'sent' && !templateState">
            <Button @click="isAddTemplateOpen = true" label="Add to template" icon="fas fa-bookmark" size="xs" :style="'tertiary'" />
        </template>
    </LabelEstimated>

    <!-- Modal: Add to template -->
    <Modal :isOpen="isAddTemplateOpen" @onClose="isAddTemplateOpen = false">
        <div class="max-w-sm mx-auto">
            <label for="" class=" text-gray-600">
                Template name:
            </label>
            <PureInput v-model="templateName" placeholder="Input template name" class="max-w-sm" />
            <div class="mx-auto mt-4 w-fit">
                <Button @click="() => submitAddTemplate()" :style="isLoading ? 'disabled' : templateState ? 'disabled' : templateName ? 'rainbow' : 'disabled'" :loading="isLoading" label="Add" :key="templateName + isLoading.toString()" class="" />
            </div>
        </div>
    </Modal>

    <!-- Component: Tabs -->
    <KeepAlive>
        <Transition name="slide-to-right" mode="out-in">
            <component :is="component" :key="currentTab" :tab="currentTab" :data="props[currentTab]"></component>
        </Transition>
    </KeepAlive>
</template>
