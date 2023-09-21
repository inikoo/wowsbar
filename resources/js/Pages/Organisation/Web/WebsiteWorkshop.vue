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
    header?: object
    menu?: object
    footer?: object
    category?: object
    product?: object
    structure : Object
}>()

let currentTab = ref(props.tabs.current)
const handleTabUpdate = (tabSlug) => useTabChange(tabSlug, currentTab)

const component = computed(() => {
    const components = {
        'header': HeaderWorkshop,
        'menu': MenuWorkshop,
        'footer': FooterWorkshop,
        'layout': LayoutWorkshop,
    }
    return components[currentTab.value]
})

console.log(props)

</script>


<template layout="OrgApp">
    <!--suppress HtmlRequiredTitleElement -->
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead"></PageHeading>
    <Tabs :current="currentTab" :navigation="tabs['navigation']" @update:tab="handleTabUpdate"/>
    <component :is="component" :data="props[currentTab]"></component>
</template>

