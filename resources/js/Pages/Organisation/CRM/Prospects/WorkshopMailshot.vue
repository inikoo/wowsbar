<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 30 Oct 2023 13:54:55 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { library } from '@fortawesome/fontawesome-svg-core';
import PageHeading from '@/Components/Headings/PageHeading.vue';
import Publish from '@/Components/Utils/Publish.vue'
import { capitalize } from "@/Composables/capitalize"
import { ref, computed } from "vue"
import { faSign, faGlobe, faPencil, faSeedling, faPaste, faLayerGroup } from '@fal/'
import { faCaretRight } from '@fas/'
import MailshotWorkshopComponent from "@/Components/Workshop/MailshotWorkshopComponent.vue";
import axios from 'axios'
import { cloneDeep } from 'lodash'
import Button from '@/Components/Elements/Buttons/Button.vue';
import ButtonGroup from '@/Components/Elements/Buttons/ButtonGroup.vue'
import Popover from '@/Components/Utils/Popover.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import Modal from '@/Components/Utils/Modal.vue';
import PureDatePicker from '@/Components/Pure/PureDatePicker.vue'

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

}>()

const OpenModal = ref(false)
const date = ref(new Date())


const sendDataToServer = async () => {
    console.log(date.value.toISOString())
    try {
        const response = await axios.post(
            route(
                props.setAsReadyRoute.name,
                props.setAsReadyRoute.parameters
            ),
            { schedule: date.value.toISOString() },
        )
        console.log('publish......')
    } catch (error) {
        console.log(error)
    }
}

</script>


<template layout="OrgApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead">
        <template #other="{ dataPageHead: head }">
            <div class="flex">
                <Button @click="sendDataToServer"
                    class="relative capitalize items-center rounded-none text-sm border-none font-medium shadow-sm focus:ring-transparent focus:ring-offset-transparent focus:ring-0">
                    Send Now
                </Button>
                <Popover>
                    <template #button>
                        <Button 
                            class="capitalize inline-flex items-center h-full rounded-none text-sm border-none font-medium shadow-sm focus:ring-transparent focus:ring-offset-transparent focus:ring-0" style="padding:9px;">
                            <font-awesome-icon :icon="['fas', 'caret-right']" />
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
        <div class="m-2"><VDatePicker v-model="date" mode="dateTime" is24hr /></div>
            <div class="flex justify-between">
            <div class="p-[4px]">Cancel</div>
            <Button @click="sendDataToServer">Schedule</Button>
            </div>
        </div>
     
        </Modal>
</template>

<style>
.vc-time-select-group select {
    background: transparent;
    padding: 0px 4px;
    border: none;
}
</style>