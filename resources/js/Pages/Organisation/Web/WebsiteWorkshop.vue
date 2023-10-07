<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faArrowAltToTop, faArrowAltToBottom, faBars, faBrowser, faCube, faPalette, faCookieBite, faLayerGroup } from "@/../private/pro-light-svg-icons"

import PageHeading from '@/Components/Headings/PageHeading.vue'
import { computed, ref, watch, reactive } from "vue"
import { useTabChange } from "@/Composables/tab-change"
import Tabs from "@/Components/Navigation/Tabs.vue"
import { capitalize } from "@/Composables/capitalize"
import HeaderGrape from '@/Components/CMS/Workshops/HeaderWorkshop/HeaderGrape.vue'
import FooterGrape from '@/Components/CMS/Workshops/FooterWorkshop/FooterGrape.vue'
import LayoutWorkshop from "@/Components/CMS/Workshops/LayoutWorkshop.vue";
import Button from '@/Components/Elements/Buttons/Button.vue'
import Modal from '@/Components/Utils/Modal.vue'
import Publish from '@/Components/Utils/Publish.vue'
import { notify } from "@kyvg/vue3-notification"
import { useForm } from '@inertiajs/vue3'
import {trans} from 'laravel-vue-i18n'
import {  setDataFirebase } from '@/Composables/firebase'
import axios from 'axios'
import { useBannerHash } from "@/Composables/useBannerHash"

library.add(
    faArrowAltToTop,
    faArrowAltToBottom,
    faBars,
    faBrowser,
    faCube,
    faPalette,
    faCookieBite,
    faLayerGroup
)


const props = defineProps<{
    title: string,
    pageHead: any,
    tabs: {
        current: string
        navigation: object
    }
    structure: Object
    imagesUploadRoute: Object
    updateRoutes: Object
    publishRoutes:Object
    websiteState:String
}>()

console.log(props.websiteState)

let currentTab = ref(props.tabs.current)

const structure = ref(props.structure)

const handleTabUpdate = (tabSlug) => {
    useTabChange(tabSlug, currentTab)
    RouteActive.value = props.publishRoutes[tabSlug]
}

const component = computed(() => {
    const components = {
        'workshop_header': HeaderGrape,
        'workshop_footer': FooterGrape,
        'workshop_layout': LayoutWorkshop,
    }
    return components[currentTab.value]
})


const RouteActive = ref(props.publishRoutes[currentTab.value])
const comment = ref('')
const isLoading = ref(false)


const sendDataToServer = async () => {
    isLoading.value = true
    try {
        const response = await axios.post(
            route(
                RouteActive.value.name,
                RouteActive.value.parameters
            ),
            { comment : comment.value },
        );
        if (response) {
            console.log('saving......')
            comment.value = ''
        }
    } catch (error) {
        comment.value = ''
        console.log(error)
    }
    isLoading.value = false
}

const compIsDataFirstTimeCreated = computed(() => {
    // Check no changes made after created the data (compared to hash from initial data)
    return false
})
</script>


<template layout="OrgApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead">
        <template #other="{ dataPageHead: head }">
            <Publish
                v-model="comment"
                :isDataFirstTimeCreated="compIsDataFirstTimeCreated"
                :isHashSame="false"
                :isLoading="isLoading"
                :saveFunction="sendDataToServer"
                :firstPublish="websiteState != 'live'"
            />
        </template>
    </PageHeading>

    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate"/>

    <component :is="component" :data="structure" :imagesUploadRoute="imagesUploadRoute" :updateRoutes="updateRoutes"></component>

</template>

