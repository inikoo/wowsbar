<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faArrowAltToTop, faArrowAltToBottom, faBars, faBrowser, faCube, faPalette, faCookieBite, faLayerGroup } from '@fal/'

import PageHeading from '@/Components/Headings/PageHeading.vue'
import { computed, ref } from "vue"
import { useTabChange } from "@/Composables/tab-change"
import Tabs from "@/Components/Navigation/Tabs.vue"
import { capitalize } from "@/Composables/capitalize"
import HeaderGrape from '@/Components/CMS/Workshops/HeaderWorkshop/HeaderGrape.vue'
import FooterGrape from '@/Components/CMS/Workshops/FooterWorkshop/FooterGrape.vue'
import LayoutTemplateWorkshop from '@/Components/CMS/Workshops/LayoutWorkshop/LayoutTemplateWorkshop.vue'
import Publish from '@/Components/Utils/Publish.vue'
import axios from 'axios'
import Edit from '@/Components/Edit.vue';

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
        navigation: object,
    }
    structure: Object
    imagesUploadRoute: Object
    updateRoutes: Object
    publishRoutes:Object
    websiteState:String
    workshop_layout?: any
}>()

console.log(props.websiteState)

let currentTab = ref(props.tabs.current)

const handleTabUpdate = (tabSlug) => {
    useTabChange(tabSlug, currentTab)
    RouteActive.value = props.publishRoutes[tabSlug]
}

const component = computed(() => {
    const components = {
        'workshop_header': HeaderGrape,
        'workshop_footer': FooterGrape,
        'workshop_layout': LayoutTemplateWorkshop,
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
    <!-- <pre>{{ props }}</pre> -->
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

    <component :is="component" :data="props[currentTab]" :imagesUploadRoute="imagesUploadRoute" :updateRoutes="updateRoutes"></component>

</template>

