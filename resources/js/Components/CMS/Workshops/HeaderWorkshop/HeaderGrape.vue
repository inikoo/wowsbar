<script setup lang="ts">
import { faArrowAltToTop, faArrowAltToBottom, faBars, faBrowser, faCube, faPalette, faCookieBite, faTrashAlt, faTimes } from "@/../private/pro-light-svg-icons"
import { faText, faWindowMaximize } from "@/../private/pro-regular-svg-icons"
import { faEye, faEyeSlash } from '@/../private/pro-solid-svg-icons'
import { faRectangleWide } from "@/../private/pro-duotone-svg-icons"
import { library } from '@fortawesome/fontawesome-svg-core'
import GrapeEditor from '@/Components/CMS/Workshops/GrapeEditor/GrapeEditor.vue'
import { HeaderPlugins, BlogPlugins } from "@/Components/CMS/Workshops/GrapeEditor/CustomBlocks/CustomBlock";


const props = defineProps<{
    imagesUploadRoute: Object
    updateRoutes: Object
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
    <GrapeEditor :plugins="[HeaderPlugins,BlogPlugins]" :updateRoute="updateRoutes.workshop_header" :loadRoute="updateRoutes.workshop_header" :imagesUploadRoute="imagesUploadRoute.workshop_header" />
</template>

