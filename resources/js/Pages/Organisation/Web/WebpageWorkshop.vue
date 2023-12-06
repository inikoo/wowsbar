<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { ref, computed } from "vue"
import { capitalize } from "@/Composables/capitalize"
import PageHeading from '@/Components/Headings/PageHeading.vue'
import GrapeEditor from '@/Components/CMS/Workshops/GrapeEditor/GrapeEditor.vue'

// import TailwindComponents from "grapesjs-tailwind";
import { HeaderPlugins, FooterPlugins, HeroPlugins, AppointmentPlugins, BlogPlugins, StatisticsPlugins, PricingPlugins, CtaPlugins  } from "@/Components/CMS/Workshops/GrapeEditor/CustomBlocks/CustomBlock";

import Publish from '@/Components/Utils/Publish.vue'
import { cloneDeep } from 'lodash'
import axios from 'axios'

import { library } from "@fortawesome/fontawesome-svg-core";
import {
    faRocketLaunch,
    faClock,
    faVideo,
} from '@far';
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

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

</script>

<template layout="OrgApp">
    <Head :title="capitalize(title)" />

    <PageHeading :data="pageHead">
        <template #other="{ dataPageHead: head }">
            <Publish v-model="comment" :isDataFirstTimeCreated="compIsDataFirstTimeCreated" :isHashSame="!isDataDirty"
                :isLoading="isLoading" :saveFunction="sendDataToServer" :firstPublish="websiteState != 'live'" />
        </template>
    </PageHeading>

    <GrapeEditor @onSaveToServer="(isDirtyFromServer) => isDataDirty = isDirtyFromServer" :useBasic="true"
        :plugins="pageCode == 'appointment' ? [AppointmentPlugins] : [HeroPlugins, BlogPlugins, StatisticsPlugins, PricingPlugins, CtaPlugins]" :updateRoute="updateRoute" :loadRoute="loadRoute"
        :imagesUploadRoute="imagesUploadRoute">

    </GrapeEditor>
</template>

