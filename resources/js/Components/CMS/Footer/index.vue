<template>
    <div>
        <component :is="component[theme]" :columSelected="columSelected" :selectedColums="selectedColums" :data=data
            :tool="tool" />
    </div>
</template>

<script setup lang="ts">
import FooterThemeOne from './FooterThemeOneWorkshop.vue'
import FooterThemeTwo from './FooterThemeTwoWorkshop.vue'
import FooterThemeThree from './FooterThemeThreeWorkshop.vue'

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


const component = {
   1: FooterThemeOne,
   2: FooterThemeTwo,
   3: FooterThemeThree,
}

</script>

<style scoped></style>