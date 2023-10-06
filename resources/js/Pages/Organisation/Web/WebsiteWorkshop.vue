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
import { notify } from "@kyvg/vue3-notification"
import { useForm } from '@inertiajs/vue3'
import {trans} from 'laravel-vue-i18n'
import {  setDataFirebase } from '@/Composables/firebase'

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
const isModalOpen = ref(false)
const comment = ref('')

// const setForm = () => {
//     let form = null
//     if(currentTab.value == 'workshop_header') form = useForm(structure.value['header'])
//     if(currentTab.value == 'workshop_footer') form = useForm(structure.value['footer'])
//     if(currentTab.value == 'workshop_layout') form = useForm(structure.value['layout'])
//    return form
// }

const sendDataToServer = async () => {
    console.log(RouteActive.value)
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
}


const chekIsLive  = ()=>{
  if(props.websiteState != 'live') sendDataToServer()
  else isModalOpen.value = true
}

</script>


<template layout="OrgApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead">
        <template #other="{ dataPageHead: head }">
            <div class="flex items-center gap-2">
                <span v-if="websiteState !== 'in-process'">
                    <Button @click="chekIsLive" :label="'Publish'" :style="'save'" icon="far fa-rocket-launch"></Button>
                </span>
                <span v-else>
                    <Button :label="'Set to Ready'"></Button>
                </span>
            </div>
        </template>
    </PageHeading>

    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate"/>

    <component :is="component" :data="structure" :imagesUploadRoute="imagesUploadRoute" :updateRoutes="updateRoutes"></component>

    <Modal :isOpen="isModalOpen" @onClose="isModalOpen = false">
            <div>
                <div class="inline-flex items-start leading-none">
                    <FontAwesomeIcon :icon="'fas fa-asterisk'" class="font-light text-[12px] text-red-400 mr-1" />
                    <span>{{ trans('Comment') }}</span>
                </div>
                <div class="py-2.5">
                    <textarea rows="3" v-model="comment"
                        class="block w-full rounded-md shadow-sm dark:bg-gray-600 dark:text-gray-400 border-gray-300 dark:border-gray-500 focus:border-gray-500 focus:ring-gray-500 sm:text-sm" />
                </div>
                <div class="flex justify-end">
                    <Button size="xs" @click="sendDataToServer" icon="far fa-rocket-launch" label="Publish" />
                </div>
            </div>
    </Modal>
</template>

