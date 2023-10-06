<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { ref, watch, reactive } from "vue"
import { capitalize } from "@/Composables/capitalize"
import PageHeading from '@/Components/Headings/PageHeading.vue'
import GrapeEditor from '@/Components/CMS/Workshops/GrapeEditor/GrapeEditor.vue'
import GrapesForm from "grapesjs-plugin-forms";
import TailwindComponents from "grapesjs-tailwind";
import { HeaderPlugins } from "@/Components/CMS/Workshops/GrapeEditor/CustomBlocks/CustomBlock";



const props = defineProps<{
    title: string
    pageHead: any
    tabs: {
        current: string
        navigation: object
    }
    updateRoute : object,
    loadRoute : object
}>()

const data = ref()

</script>

<template layout="OrgApp">
    <Head :title="capitalize(title)"/>
    <PageHeading :data="pageHead"  :dataToSubmit="data"></PageHeading>
    <GrapeEditor
     :data="data" 
     @changeData="(value)=>data = value"
     :plugins="[GrapesForm,HeaderPlugins]"
     :updateRoute="updateRoute"
     :loadRoute="loadRoute"
     />
</template>

