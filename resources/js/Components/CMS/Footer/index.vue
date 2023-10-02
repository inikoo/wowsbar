<script setup lang="ts">
// import FooterThemeOne from './FooterThemeOneWorkshop.vue'
import FooterThemeTwo from './FooterThemeTwoWorkshop.vue'
import FooterThemeOne from '@/Components/Footer/Public/FooterThemeOne.vue'

const props = defineProps<{
    selectedColums: Function,
    columSelected: {
        type: Object,
        required: false,
    }
    theme: String,
    tool: Object
    data: Object
    layout : Object
}>()

const emits = defineEmits();

const component = {
    'simple image': FooterThemeOne,
    'simple': FooterThemeTwo,
}

</script>

<template>
    <div>
        <component 
          :is="component[theme]" 
          :columSelected="columSelected" 
          :selectedColums="selectedColums" 
          :data=data 
          :tool="tool" 
          :layout="layout"
          @uploadImage="(file)=>emits('uploadImage',file)"/>
    </div>
</template>

<style scoped></style>