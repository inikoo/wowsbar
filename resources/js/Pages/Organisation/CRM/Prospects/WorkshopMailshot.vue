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
import { faSign, faGlobe, faPencil, faSeedling, faPaste, faLayerGroup } from '@fal/'
import { faCaretRight } from '@fas/'
import MailshotWorkshopComponent from "@/Components/Workshop/MailshotWorkshopComponent.vue";
import axios from 'axios'
import { notify } from "@kyvg/vue3-notification"
import { get } from 'lodash'
import Button from '@/Components/Elements/Buttons/Button.vue';
import ButtonGroup from '@/Components/Elements/Buttons/ButtonGroup.vue'
import Popover from '@/Components/Utils/Popover.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import Modal from '@/Components/Utils/Modal.vue';


library.add(faSign, faGlobe, faPencil, faSeedling, faPaste, faLayerGroup, faCaretRight)

const props = defineProps<{
    title: string,
    pageHead: object,
    tabs: {
        current: string;
        navigation: object;
    }
    changelog?: object,
    workshop?: object,
    imagesUploadRoute: Object,
    setAsReadyRoute: Object,
    updateRoute: Object,
    loadRoute: Object
    setAsScheduledRoute: object

}>()

const OpenModal = ref(false)
const date = ref(new Date())

const save = (schedule = false) => {
    const reqData = {
        ...(schedule ?
            { route: props.setAsScheduledRoute, data: { schedule_add: date.value.toISOString() } }
            : { route: props.setAsReadyRoute }
        )
    }
    sendDataToServer(schedule, reqData)
}


const sendDataToServer = async (schedule = false, reqData: Object) => {
    try {
        const response = await axios.post(
            route(
                reqData.route.name,
                reqData.route.parameters
            ),
            reqData?.data,
        )
        console.log('publish......')
        notify({
            title: "Succeed",
            text: schedule ? "The email will be scheduled" : "Your email has been sent",
            type: "success"
        });
    } catch (error) {
        console.log(error)
        notify({
            title: "Failed",
            text: schedule ? "Failed to schedule emaile" : "Your email failed to send",
            type: "error"
        });
    }
}

const onCancel = () => {
    OpenModal.value = false
    date.value = new Date()
}

</script>


<template layout="OrgApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead">
        <template #other="{ dataPageHead: head }">
            <div class="flex">
                <Button class="button-send">
                    Send
                </Button>
                <Popover>
                    <template #button>
                        <Button class="dropdwon-button">
                            <font-awesome-icon :icon="['fas', 'caret-right']" class='' aria-hidden='true' />
                            <div class="absolute inset-0 w-full flex items-center justify-center" />
                        </Button>
                    </template>
                    <template #content>
                        <div @click="OpenModal = true">Send with schedule</div>
                    </template>
                </Popover>
            </div>
        </template>
    </PageHeading>
    <MailshotWorkshopComponent :useBasic="false" :imagesUploadRoute="imagesUploadRoute" :updateRoute="updateRoute"
        :loadRoute="loadRoute" />
    <Modal :isOpen="OpenModal" @onClose="OpenModal = false" width="w-fit">

        <div>
            <div class="text-xl font-semibold border-b pb-2 text-org-500">Select date and time</div>
            <div class="my-2">
                <VDatePicker expanded color='purple' transparent borderless v-model="date" mode="dateTime" is24hr
                    :min-date="new Date()" />
            </div>
            <div class="flex justify-between">
                <div class="p-[4px] cursor-pointer" @click="onCancel">Cancel</div>
                <Button @click="save(true)">Schedule</Button>
            </div>
        </div>

    </Modal>
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

.dropdwon-button {
    padding: 9px 15px;
    border-radius: 0px 10px 10px 0px;
}

.button-send {
    border-radius: 10px 0px 0px 10px;
}
</style>