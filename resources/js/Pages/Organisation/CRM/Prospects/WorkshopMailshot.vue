<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 30 Oct 2023 13:54:55 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { library } from '@fortawesome/fontawesome-svg-core';
import PageHeading from '@/Components/Headings/PageHeading.vue';
import { capitalize } from "@/Composables/capitalize"
import LabelEstimated from '@/Components/Mailshots/LabelEstimated.vue'
import { ref } from "vue"
import { faSign, faGlobe, faPencil, faSeedling, faPaste, faLayerGroup, faSpellCheck } from '@fal/'
import { faFlask } from '@fad/'
import { faCaretDown, faPaperPlane, faCheckCircle, faStopwatch, faAsterisk } from '@fas/'
import MailshotWorkshopComponent from "@/Components/Workshop/MailshotWorkshopComponent.vue";
import axios from 'axios'
import { notify } from "@kyvg/vue3-notification"
import Button from '@/Components/Elements/Buttons/Button.vue';
import Popover from '@/Components/Utils/Popover.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { DatePicker } from 'v-calendar';
import 'v-calendar/style.css';
import { Link } from "@inertiajs/vue3"
import { routeType } from '@/types/route'
import PureInput from '@/Components/Pure/PureInput.vue';
import { isNull, get } from 'lodash';

library.add(faSign, faGlobe, faPencil, faSeedling, faPaste, faLayerGroup, faCheckCircle, faStopwatch, faSpellCheck, faCaretDown, faPaperPlane, faFlask, faAsterisk)

const props = defineProps<{
    title: string,
    pageHead: object,
    // tabs: {
    //     current: string;
    //     navigation: object;
    // }
    mailshot: {}
    changelog?: object,
    workshop?: object,
    imagesUploadRoute: routeType
    setAsReadyRoute: routeType
    updateRoute: routeType
    loadRoute: routeType
    setAsScheduledRoute: routeType
    sendRoute: routeType
    sendTestRoute: routeType
}>()

const OpenModal = ref(false)
const date = ref(new Date())


const getLocalStorage = () => {
    let storage = localStorage.getItem("mailshotWorkshop")
    if (storage) return JSON.parse(storage)
    return null
}

const localStorageData = ref(getLocalStorage())

const getEmailTest = () => {
    const emailTest = localStorageData.value
    let value = ''
    if (emailTest) {
        const item = emailTest.mailshotWorkshop?.testEmail.find((item) => item.key == 'project')
        value = get(item, 'email', '')
    }
    return value
}

const testEmail = ref({
    emails: getEmailTest(),
    status: null,
    errorMessage: null
})

const onCancel = () => {
    OpenModal.value = false
    date.value = new Date()
}

const sendEmailtest = async (closedPopover) => {
    try {
        const response = await axios.post(
            route(
                props.sendTestRoute.name,
                props.sendTestRoute.parameters
            ),
            { emails: testEmail.value.emails }
        )
        console.log('send test email......')
        onSuccess(response,closedPopover)
    } catch (error) {
        onError(error)
    }
}

const onError = (error) => {
    notify({
        title: "Failed",
        text: error.response.data.message,
        type: "error"
    });

    testEmail.value = {
        emails: testEmail.value.emails,
        status: 'error',
        errorMessage: error.response.data.message
    }
}

const onSuccess = (response,closedPopover) => {
    console.log(response)
    if (response.data.data[0].state !== "error") {
        let newLocalStorage = localStorageData.value
        if (newLocalStorage) {
            const index = newLocalStorage.mailshotWorkshop.testEmail.findIndex((item) => item.key == 'project')
            if (index != -1) newLocalStorage.mailshotWorkshop.testEmail[index] = { email: response.data.data[0].email, key: 'project' }
        } else {
            newLocalStorage = {
                mailshotWorkshop: {
                    testEmail: [
                        { email: response.data.data[0].email, key: 'project' }
                    ]
                }
            };
        }

        let localStorageString = JSON.stringify(newLocalStorage);
        localStorage.setItem('mailshotWorkshop', localStorageString);
        notify({
            title: "Succeed",
            text: "The test email has been sent, please check your email",
            type: "success"
        });
    } else {
        notify({
            title: "Failed",
            text: response.data.data[0].state_label,
            type: "error"
        });
    }
    testEmail.value = {
        emails: testEmail.value.emails,
        status: null,
        errorMessage: null
    }
    if(closedPopover)closedPopover()
};
</script>


<template layout="OrgApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead">
        <template #other="{ dataPageHead: head }">
            <div class="relative">
                <Popover :width="'w-full'" position="right-[-170px]" ref="_popover">
                    <template #button>
                        <div class="relative" title="testing email">
                            <Button class="rounded" :style="`secondary`">
                                Send Test <font-awesome-icon :icon="['fad', 'flask']" aria-hidden='true' />
                            </Button>
                        </div>
                    </template>
                    <template #content="{ close: closed }">
                        <dd class="w-64">
                            <div class="flex items-center border border-gray-300 rounded-md overflow-hidden">
                                <PureInput v-model="testEmail.emails" placeholder="Email" type="email" :clear="true"
                                    class="rounded-r-none ring-1 ring-transparent focus-within:ring-2 focus-within:ring-transparent" />
                                <!-- Assuming sendEmailtest() is a method to send an email -->
                                <Button @click="sendEmailtest(closed)" icon="fas fa-paper-plane" size="xl" class="py-3.5 border-0 border-l rounded-l-none "
                                    :style="testEmail.emails.length ? 'primary' : 'disabled'" :key="testEmail.emails" />
                            </div>
                            <p v-if="testEmail.status == 'error'" class="text-xs italic text-red-500 mt-2">{{
                                testEmail.errorMessage }}</p>
                        </dd>
                    </template>
                </Popover>
            </div>

            <div class="flex rounded-md overflow-hidden">
                <Link v-if="setAsReadyRoute?.name" as="button" method="post" :href="route(
                    props.setAsReadyRoute?.name,
                    props.setAsReadyRoute?.parameters
                )">
                    <Button class="rounded-r-none" key="4" :style="'orgSolid'">
                        <FontAwesomeIcon icon='fas fa-check-circle' class='h-4' aria-hidden='true' />
                    </Button>
                </Link>
                <Popover>
                    <template #button>
                        <div class="relative border-x border-fuchsia-400" title="Scheduled publish">
                            <Button class="rounded-none" :style="'orgSolid'">
                                <FontAwesomeIcon :icon="['fas', 'stopwatch']" class='h-4' aria-hidden='true' />
                                <div class="absolute inset-0 w-full flex items-center justify-center" />
                            </Button>
                        </div>
                    </template>
                    <template #content>
                        <div>
                            <div class="text-xl font-semibold border-b pb-2 text-org-500">Select date and time</div>
                            <div class="my-2">
                                <DatePicker expanded color='purple' transparent borderless v-model="date" mode="dateTime"
                                    is24hr :min-date="new Date()" />
                            </div>
                            <div class="flex justify-between">
                                <div class="p-[4px] cursor-pointer" @click="onCancel">Cancel</div>
                                <Link as="button" method="post" :data="{ schedule_at: date.toISOString() }" :href="route(
                                    props.setAsScheduledRoute.name,
                                    props.setAsScheduledRoute.parameters
                                )">
                                    <Button>Schedule</Button>
                                </Link>
                            </div>
                        </div>
                    </template>
                </Popover>

                <Link as="button" method="post" :href="route(
                    props.sendRoute.name,
                    props.sendRoute.parameters
                )">
                    <Button class="rounded-none">
                        Send Now
                        <FontAwesomeIcon icon='fas fa-paper-plane' class='' aria-hidden='true' />
                    </Button>
                </Link>
            </div>
        </template>
    </PageHeading>

    <LabelEstimated :emailsEstimated="mailshot.stats.number_estimated_dispatched_emails" />

    <MailshotWorkshopComponent :useBasic="false" :imagesUploadRoute="imagesUploadRoute" :updateRoute="updateRoute"
        :loadRoute="loadRoute" />
</template>

<style lang="scss">
.vc-time-select-group select {
    background: transparent;
    padding: 0px 4px;
    border: none;
}

.vc-header {
    margin-bottom: 10px;
}
</style>
