<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { library } from '@fortawesome/fontawesome-svg-core'
import {
    faArrowAltToTop, faArrowAltToBottom, faBars, faBrowser, faCube, faPalette, faCookieBite, faLayerGroup
} from "@/../private/pro-light-svg-icons"

import PageHeading from '@/Components/Headings/PageHeading.vue'
import { computed, defineAsyncComponent, ref } from "vue"
import { useTabChange } from "@/Composables/tab-change"
import Tabs from "@/Components/Navigation/Tabs.vue"
import { capitalize } from "@/Composables/capitalize"
import FooterWorkshop from "@/Components/CMS/Workshops/FooterWorkshop.vue";
import MenuWorkshop from "@/Components/CMS/Workshops/MenuWorkshop.vue";
import HeaderWorkshop from "@/Components/CMS/Workshops/HeaderWorkshop/HeaderTemplateWorkshop.vue";
import LayoutWorkshop from "@/Components/CMS/Workshops/LayoutWorkshop.vue";
import GrapeEditor from '@/Components/CMS/Workshops/GrapeEditor/GrapeEditor.vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
import { notify } from "@kyvg/vue3-notification"
import { router,useForm } from '@inertiajs/vue3'

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
}>()

let currentTab = ref(props.tabs.current)

const handleTabUpdate = (tabSlug) => {
    useTabChange(tabSlug, currentTab)
    RouteActive.value = props.updateRoutes[tabSlug]
}

const component = computed(() => {
    const components = {
        'header': HeaderWorkshop,
        'menu': MenuWorkshop,
        'footer': FooterWorkshop,
        'layout': GrapeEditor,
    }
    return components[currentTab.value]
})

console.log('props', props)

const RouteActive = ref(props.updateRoutes[currentTab.value])

const sendDataToServer = async () => {
    const form = useForm(props.structure[currentTab.value]);
    form.patch(
        route(RouteActive.value.name,RouteActive.value.parameters), {
        onSuccess: async (res) => {
            console.log('res',res)
            notify({
                title: "Success Update",
                type: "success",
                text: "Banner already update and publish",
            });
        },
        onError: (errors: any) => {
            console.log(errors)
            notify({
                title: "Failed to Update Banner",
                text: errors,
                type: "error"
            });
        },
    })
}

</script>


<template layout="OrgApp"> 
    <notifications
        group="custom-style"
        position="top center"
        classes="n-light"
        dangerously-set-inner-html
        :max="3"
        :width="400"
    />
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"></PageHeading>
    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate">
        <template #content>
            <div class="flex justify-center align-middle">
                <div class="h-6 m-auto">
                    <Button @click="sendDataToServer">save</Button>
                </div>
            </div>
        </template>
    </Tabs>
    <component :is="component" :data="structure" :imagesUploadRoute="imagesUploadRoute"></component>
</template>

