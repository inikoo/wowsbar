<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { ref, computed } from "vue"
import { capitalize } from "@/Composables/capitalize"
import PageHeading from '@/Components/Headings/PageHeading.vue'
import GrapeEditor from '@/Components/CMS/Workshops/GrapeEditor/GrapeEditor.vue'

// import TailwindComponents from "grapesjs-tailwind";
import { HeaderPlugins, FooterPlugins } from "@/Components/CMS/Workshops/GrapeEditor/CustomBlocks/CustomBlock";

import Publish from '@/Components/Utils/Publish.vue'
import { cloneDeep } from 'lodash'
import axios from 'axios'

import { library } from "@fortawesome/fontawesome-svg-core";
import {
    faRocketLaunch,
    faClock,
    faVideo,
} from "@/../private/pro-regular-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { isNull } from "lodash";
import { useFormatTime } from "@/Composables/useFormatTime";

library.add(faRocketLaunch, faClock, faVideo);


const props = defineProps<{
    title: string
    pageHead: any
    tabs: {
        current: string
        navigation: object
    }
    updateRoute: object,
    loadRoute: object
    webpageState: String,
    websiteState: String
    publishRoute: Object
    setAsReadyRoute: Object
    isDirty: boolean
    imagesUploadRoute: Object
    pageCode: String
}>()

const isLoading = ref(false)
const comment = ref('')

const isDataDirty = ref(cloneDeep(props.isDirty))

const sendDataToServer = async () => {
    isLoading.value = true
    try {
        const response = await axios.post(
            route(
                props.publishRoute.name,
                props.publishRoute.parameters
            ),
            { comment: comment.value },
        )
        console.log('publish......')
        comment.value = ''
    } catch (error) {
        comment.value = ''
        console.log(error)
    }
    isLoading.value = false
}

const compIsDataFirstTimeCreated = computed(() => {
    return false
})


const Book = {
    description: "The Basic tee is an honest new take on a classic. The tee uses super soft, pre-shrunk cotton for true comfort and a dependable fit",
    meet: {
        customerService: "Arya",
        duration: "30 mnt",
    },
    title: "Discovery Call - Wowsbar",
    meetInformation: "Web conferencing details provided upon confirmation.",
};

</script>

<template layout="OrgApp">
    <Head :title="capitalize(title)" />

    <PageHeading :data="pageHead">
        <template #other="{ dataPageHead: head }">
            <Publish v-model="comment" :isDataFirstTimeCreated="compIsDataFirstTimeCreated" :isHashSame="!isDataDirty"
                :isLoading="isLoading" :saveFunction="sendDataToServer" :firstPublish="websiteState != 'live'" />
        </template>
    </PageHeading>

    <GrapeEditor @onSaveToServer="(isDirtyFromServer) => isDataDirty = isDirtyFromServer" :useBasic="false"
        :plugins="pageCode == 'appointment' ? [] : [HeaderPlugins, FooterPlugins]" :updateRoute="updateRoute" :loadRoute="loadRoute"
        :imagesUploadRoute="imagesUploadRoute">
        <template #defaultComponents v-if="pageCode == 'appointment'" data-gjs-editable="false" data-gjs-removable="false">
            <div class="bg-white mt-auto">
                    <div class="mx-auto max-w-2xl px-4 sm:px-6  lg:px-8 border" data-gjs-editable="false" data-gjs-removable="false">
                        <div class="mt-8 lg:col-span-5 lg:col-start-1 lg:row-span-3 lg:row-start-1 lg:mt-0">
                            <div class="flex justify-center align-middle p-20" data-gjs-removable="false" >
                                <img src="https://dummyimage.com/" data-gjs-removable="false" class="w-32 h-32  p-2" alt="Description of the image">
                            </div>
                            <hr />
                            <div class="text-lg text-slate-400">
                                {{ Book.meet.customerService }}
                            </div>
                            <div class="text-4xl font-medium">
                                {{ Book.title }}
                            </div>
                            <div>
                                <div class="flex justify-start my-2 gap-3">
                                    <div>
                                        <font-awesome-icon :icon="['far', 'clock']" class="w-4 h-4" />
                                    </div>
                                    <div>{{ Book.meet.duration }}</div>
                                </div>
                                <div class="flex justify-start my-2 gap-3">
                                    <div>
                                        <font-awesome-icon :icon="['far', 'video']" />
                                    </div>
                                    <div>{{ Book.meetInformation }}</div>
                                </div>
                            </div>

                            <div class="my-3">
                                <h2 class="text-sm font-medium text-gray-900">
                                    Description
                                </h2>

                                <div class="mt-1 mb-2 text-gray-500 text-xs" v-html="Book.description" />
                            </div>
                        </div>
                    </div>
            </div>
        </template>
    </GrapeEditor>
</template>

