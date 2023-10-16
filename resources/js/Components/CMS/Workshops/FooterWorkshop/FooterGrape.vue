<script setup lang="ts">
import { faArrowAltToTop, faArrowAltToBottom, faBars, faBrowser, faCube, faPalette, faCookieBite, faTrashAlt, faTimes } from '@fal/'
import { faText, faWindowMaximize } from '@far/'
import { faEye, faEyeSlash } from '@fas/'
import { faRectangleWide } from '@fad/'
import { library } from '@fortawesome/fontawesome-svg-core'
import GrapeEditor from '@/Components/CMS/Workshops/GrapeEditor/GrapeEditor.vue'

import { FooterPlugins } from "@/Components/CMS/Workshops/GrapeEditor/CustomBlocks/CustomBlock";


const props = defineProps<{
    imagesUploadRoute: Object
    updateRoutes: Array
}>();

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



</script>

<template layout="OrgApp">
    <GrapeEditor :data="data" @changeData="(value) => data = value" :plugins="[FooterPlugins]" :updateRoute="updateRoutes.workshop_footer" :loadRoute="updateRoutes.workshop_footer" :imagesUploadRoute="imagesUploadRoute.workshop_footer" />
</template>

