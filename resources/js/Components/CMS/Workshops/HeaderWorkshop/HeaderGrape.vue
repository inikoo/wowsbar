<script setup lang="ts">
import { faArrowAltToTop, faArrowAltToBottom, faBars, faBrowser, faCube, faPalette, faCookieBite, faTrashAlt, faTimes } from "@/../private/pro-light-svg-icons"
import { faText, faWindowMaximize } from "@/../private/pro-regular-svg-icons"
import { faEye, faEyeSlash } from '@/../private/pro-solid-svg-icons'
import { faRectangleWide } from "@/../private/pro-duotone-svg-icons"
import { library } from '@fortawesome/fontawesome-svg-core'
import GrapeEditor from '@/Components/CMS/Workshops/GrapeEditor/GrapeEditor.vue'
import Basic from "grapesjs-blocks-basic";
import grapesjsIcons from "grapesjs-icons";
import { usePlugin } from "grapesjs";
import GrapesForm from "grapesjs-plugin-forms";
import { headerBlock1, headerBlock2, headerBlock3, headerBlock4 } from '@/Components/CMS/Workshops/GrapeEditor/CustomBlocks/Header/Header'


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
    <GrapeEditor :data="data" @changeData="(value) => data = value" :plugins="[Basic, usePlugin(grapesjsIcons, options), GrapesForm]"
        :customBlocks="[headerBlock1(), headerBlock2(), headerBlock3(), headerBlock4()]" />
</template>

