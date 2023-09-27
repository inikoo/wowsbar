<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { ref } from "vue"

import WebpageBlocksOrder from "@/Components/Workshop/Webpage/WebpageBlocksOrder.vue"
import WebpageBlocksContent from "@/Components/CMS/Workshops/WebpageBlocksContent.vue"

import { componentBlocks } from '@/types/WebPageWorkshop'

import PageHeading from '@/Components/Headings/PageHeading.vue'
import { capitalize } from "@/Composables/capitalize"
import { ulid } from 'ulid'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faArrowAltToTop, faArrowAltToBottom, faBars, faBrowser, faCube, faPalette, faCookieBite, faTrashAlt, faTimes } from "@/../private/pro-light-svg-icons"
import { faText, faWindowMaximize } from "@/../private/pro-regular-svg-icons"
import { faEye, faEyeSlash } from '@/../private/pro-solid-svg-icons'
import { faRectangleWide } from "@/../private/pro-duotone-svg-icons"
import { library } from '@fortawesome/fontawesome-svg-core'
import GrapeEditor from '@/Components/CMS/Workshops/GrapeEditor/GrapeEditor.vue'

library.add(
    faArrowAltToTop,
    faArrowAltToBottom,
    faBars,
    faBrowser,
    faCube,
    faPalette, faEye, faEyeSlash,
    faCookieBite, faTrashAlt, faTimes, faRectangleWide,
    faText, faWindowMaximize
)

const props = defineProps<{
    title: string
    pageHead: any
    tabs: {
        current: string
        navigation: object
    }
}>()


const dataComponent = ref([
    {
        "component": "CTA",
        "icon": "far fa-text",
        "ulid": ulid(),
        "visibility": true
    },
    {
        "component": "testimonial",
        "icon": "far fa-text",
        "ulid": ulid(),
        "visibility": true,
        "value": 'What a good sunshine! Lorem Ipsum Dolor Sit Amet hehe!'
    },
    {
        "component": "banner",
        "icon": "far fa-window-maximize",
        "ulid": ulid(),
        "visibility": true
    },
    {
        "component": "stats",
        "icon": "far fa-text",
        "ulid": ulid(),
        "visibility": true
    },
    {
        "component": "blogSection",
        "icon": "far fa-text",
        "ulid": ulid(),
        "visibility": true
    },
    {
        "component": "CardContent",
        "icon": "far fa-text",
        "ulid": ulid(),
        "visibility": true
    },
    {
        "component": "pricing",
        "icon": "far fa-text",
        "ulid": ulid(),
        "visibility": true
    },

    {
        "component": "hero",
        "icon": "fad fa-rectangle-wide",
        "ulid": ulid(),
        "visibility": true
    },
])

const loadingState = ref(false)
const selectedComponent = ref(dataComponent.value[0])


</script>

<template layout="OrgApp">
    <!--suppress HtmlRequiredTitleElement -->

    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"></PageHeading>
    <GrapeEditor />
    <!-- <div id="webpageWorkshop"> 
        <div v-if="loadingState" class="w-full min-h-screen flex justify-center items-center">
            <FontAwesomeIcon icon='fad fa-spinner-third' class='animate-spin h-12  text-gray-600' aria-hidden='true' />
        </div>
        <div v-else class="h-full w-full px-2 py-3 flex gap-x-2"> -->
            <!-- Section: Blocks Order -->
            <!-- <WebpageBlocksOrder @handleSelectComponent="(component: componentBlocks) => selectedComponent = component" :dataComponent="dataComponent" :selectedComponent="selectedComponent"/> -->
            <!-- Section: Main content -->
            <!-- <div class="flex w-full h-full p-3 border border-gray-200 rounded shadow-sm">
                <div class="w-full h-fit">
                    <WebpageBlocksContent :selectedComponent="selectedComponent" v-model="selectedComponent.value"/>
                </div>
            </div> -->
            <!-- Section: Tools editor -->
            <!-- <div class="w-64 ring-1 ring-gray-300 rounded-md px-2 py-4">
        
            </div> -->
        <!-- </div>
    </div> -->
</template>

