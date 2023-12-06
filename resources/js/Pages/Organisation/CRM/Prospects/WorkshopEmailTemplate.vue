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
import { faSign, faGlobe, faPencil, faSeedling, faPaste, faLayerGroup, faCheckCircle, faStopwatch, faSpellCheck } from '@fal'
import { faCaretDown, faPaperPlane } from '@fas'
import MailshotWorkshopComponent from "@/Components/Workshop/MailshotWorkshopComponent.vue";
import axios from 'axios'
import { notify } from "@kyvg/vue3-notification"
import Button from '@/Components/Elements/Buttons/Button.vue';
import Popover from '@/Components/Utils/Popover.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import Modal from '@/Components/Utils/Modal.vue';
import { DatePicker  } from 'v-calendar';
import 'v-calendar/style.css';
import { Link } from "@inertiajs/vue3"
import { routeType } from '@/types/route'

library.add(faSign, faGlobe, faPencil, faSeedling, faPaste, faLayerGroup, faCheckCircle, faStopwatch, faSpellCheck, faCaretDown, faPaperPlane)

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
    updateRoute: routeType
    publishRoute: routeType
    loadRoute: routeType

}>()

const OpenModal = ref(false)
const date = ref(new Date())

/* const save = (schedule = false) => {
    const reqData = {
        ...(schedule ?
            { route: props.setAsScheduledRoute, data: { schedule_at: date.value.toISOString() } }
            : { route: props.sendRoute }
        )
    }
    sendDataToServer(schedule, reqData)
}
 */
/* const setReady=()=>{

}
 */
/*
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
        onCancel()
    } catch (error) {
        console.log(error)
        notify({
            title: "Failed",
            text: schedule ? "Failed to schedule emaile" : "Your email failed to send",
            type: "error"
        });
    }
} */

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
                <Link
                    method="post"
                    :href="route(
                        props.publishRoute.name,
                        props.publishRoute.parameters
                )">
                    <Button  class="rounded-none">
                        Publish
                        <FontAwesomeIcon icon='fas fa-paper-plane' class='' aria-hidden='true' />
                    </Button>
                </Link>
            </div>
        </template>
    </PageHeading>
    <MailshotWorkshopComponent :useBasic="false" :imagesUploadRoute="imagesUploadRoute" :updateRoute="updateRoute" :loadRoute="loadRoute" />
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
