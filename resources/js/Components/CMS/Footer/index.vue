<script setup lang="ts">
import FooterThemeOne from './FooterThemeOneWorkshop.vue'
import FooterThemeTwo from './FooterThemeTwoWorkshop.vue'
// import FooterThemeThree from './FooterThemeThreeWorkshop.vue'

const props = defineProps<{
    selectedColums: Function,
    columSelected: {
        type: Object,
        required: false,
    }
    theme: String,
    tool: Object
    data: Object
}>()

const emits = defineEmits();

const component = {
    'simple image': FooterThemeOne,
    'simple': FooterThemeTwo,
    //    'simple two': FooterThemeThree,
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
          @uploadImage="(file)=>emits('uploadImage',file)"/>
    </div>
</template>

<style scoped></style>