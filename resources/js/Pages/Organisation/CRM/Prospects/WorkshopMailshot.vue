<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 30 Oct 2023 13:54:55 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { library } from '@fortawesome/fontawesome-svg-core';
import PageHeading from '@/Components/Headings/PageHeading.vue';
import { capitalize } from "@/Composables/capitalize"
import { ref } from "vue"
import { faSign, faGlobe, faPencil, faSeedling, faPaste, faLayerGroup, faCheckCircle, faStopwatch, faSpellCheck } from '@fal/'
import { faFlask } from '@fad/'
import { faCaretDown, faPaperPlane, faAsterisk } from '@fas/'
import MailshotWorkshopComponent from "@/Components/Workshop/MailshotWorkshopComponent.vue";
import axios from 'axios'
import { notify } from "@kyvg/vue3-notification"
import Button from '@/Components/Elements/Buttons/Button.vue';
import Popover from '@/Components/Utils/Popover.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import Modal from '@/Components/Utils/Modal.vue';
import { DatePicker } from 'v-calendar';
import 'v-calendar/style.css';
import { Link } from "@inertiajs/vue3"
import { routeType } from '@/types/route'
import Input from '@/Components/Pure/PureInput.vue';

library.add(faSign, faGlobe, faPencil, faSeedling, faPaste, faLayerGroup, faCheckCircle, faStopwatch, faSpellCheck, faCaretDown, faPaperPlane, faFlask, faAsterisk)

const props = defineProps<{
    title: string,
    pageHead: object,
    tabs: {
        current: string;
        navigation: object;
    }
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
const testEmail = ref('')

const onCancel = () => {
    OpenModal.value = false
    date.value = new Date()
}

// console.log(props)
</script>


<template layout="OrgApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead">
        <template #other="{ dataPageHead: head }">
            <div class="flex rounded-md overflow-hidden">
                <Link v-if="setAsReadyRoute?.name" method="post" :href="route(
                    props.setAsReadyRoute?.name,
                    props.setAsReadyRoute?.parameters
                )">
                <Button class="rounded-r-none" :style="`secondary`">
                    <FontAwesomeIcon icon='fal fa-check-circle' class='' aria-hidden='true' />
                </Button>
                </Link>

                <Popover>
                    <template #button>
                        <div class="relative" title="testing email">
                            <Button class="rounded-r-none" :style="`secondary`">
                                <font-awesome-icon :icon="['fad', 'flask']" aria-hidden='true' />
                            </Button>
                        </div>
                    </template>
                    <template #content>
                        <div>
                            <div class="inline-flex items-start leading-none">
                                <FontAwesomeIcon :icon="'fas fa-asterisk'"
                                    class="font-light text-[12px] text-red-400 mr-1" />
                                <span class="capitalize">Email</span>
                            </div>
                            <div class="py-2.5">
                                <Input v-model="testEmail" />
                            </div>
                            <div class="flex justify-end">
                                <Link method="post" :data="{ email: testEmail }" :href="route(
                                    props.sendTestRoute.name,
                                    props.sendTestRoute.parameters
                                )">
                                <Button size="xs" icon="far fa-rocket-launch" label="Send Email" :style="'primary'">
                                </Button>
                                </Link>
                            </div>
                        </div>
                    </template>
                </Popover>


                <Popover>
                    <template #button>
                        <div class="relative" title="Scheduled publish">
                            <Button class="rounded-none">
                                <FontAwesomeIcon :icon="['fal', 'stopwatch']" class='leading-6 border-transparent border'
                                    aria-hidden='true' />
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
                                <Link method="post" :data="{ schedule_at: date.toISOString() }" :href="route(
                                    props.setAsScheduledRoute.name,
                                    props.setAsScheduledRoute.parameters
                                )">
                                <Button>Schedule</Button>
                                </Link>
                            </div>
                        </div>
                    </template>
                </Popover>

                <Link method="post" :href="route(
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
