<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faArrowAltToTop, faArrowAltToBottom, faBars, faBrowser, faCube, faPalette, faCookieBite, faLayerGroup } from "@/../private/pro-light-svg-icons"

import PageHeading from '@/Components/Headings/PageHeading.vue'
import { computed, ref, watch, reactive } from "vue"
import { useTabChange } from "@/Composables/tab-change"
import Tabs from "@/Components/Navigation/Tabs.vue"
import { capitalize } from "@/Composables/capitalize"
import FooterWorkshop from "@/Components/CMS/Workshops/FooterWorkshop.vue";
import HeaderGrape from '@/Components/CMS/Workshops/HeaderWorkshop/HeaderGrape.vue'
import LayoutWorkshop from "@/Components/CMS/Workshops/LayoutWorkshop.vue";
import Button from '@/Components/Elements/Buttons/Button.vue'
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
}>()


let currentTab = ref(props.tabs.current)

const structure = ref(props.structure)

const handleTabUpdate = (tabSlug) => {
    useTabChange(tabSlug, currentTab)
    RouteActive.value = props.updateRoutes[tabSlug]
}

const component = computed(() => {
    const components = {
        'workshop_header': HeaderGrape,
        'workshop_footer': FooterWorkshop,
        'workshop_layout': LayoutWorkshop,
    }
    return components[currentTab.value]
})


const RouteActive = ref(props.updateRoutes[currentTab.value])

const setForm = () => {
    let form = null
    if(currentTab.value == 'workshop_header') form = useForm(structure.value['header'])
    if(currentTab.value == 'workshop_footer') form = useForm(structure.value['footer'])
    if(currentTab.value == 'workshop_layout') form = useForm(structure.value['layout'])
   return form
}

const sendDataToServer = async () => {
    const form = setForm()
    form.patch(
        route(RouteActive.value.name,RouteActive.value.parameters), {
        onSuccess: async (res) => {
            notify({
                title: trans("Success"),
                type: "success",
                text: "",
            });
        },
        onError: (errors: any) => {
            console.log(errors)
            notify({
                title: trans("Error"),
                text: errors,
                type: "error"
            });
        },
    })
}


async function setToFirebase() {
    const column = "org/websites/structure";
    try {
        await setDataFirebase(column,props.structure);
    } catch (error) {
        console.log(error);
    }
}

// watch(structure,(newValue)=> structure.value = newValue, { deep: true });

setToFirebase();

</script>


<template layout="OrgApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead">
        <template #other="{ dataPageHead: head }">
            <div class="flex items-center gap-2">
                <span>
                    <Button @click="sendDataToServer" :label="'Save'" :style="'save'"></Button>
                </span>
            </div>
        </template>
    </PageHeading>

    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate"/>

    <component :is="component" :data="structure" :imagesUploadRoute="imagesUploadRoute"></component>
</template>

