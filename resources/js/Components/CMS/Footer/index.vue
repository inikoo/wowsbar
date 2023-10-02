<script setup lang="ts">
// import FooterThemeOne from './FooterThemeOneWorkshop.vue'
import FooterThemeTwo from './FooterThemeTwoWorkshop.vue'
import FooterThemeOne from '@/Components/CMS/Footer/FooterThemeOneWorkshop.vue'

const props = defineProps<{
    selectedColumn: Function,
    columnSelected: {
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
          :columnSelected="columnSelected" 
          :selectedColumn="selectedColumn" 
          :data=data 
          :tool="tool" 
          :layout="layout"
          @uploadImage="(file)=>emits('uploadImage',file)"/>
    </div>
</template>

<style scoped></style>