<template>
    <div>
        <component :is="component[theme]" :columSelected="columSelected" :selectedColums="selectedColums" :data=data
            :tool="tool" />
    </div>
</template>

<script setup lang="ts">
import FooterTheme1 from './FooterTheme1.vue'
import FooterTheme2 from './FooterTheme2.vue'
import FooterTheme3 from './FooterTheme3.vue'

const props = defineProps<{
    selectedColums: Function,
    columSelected: Object
    theme: number,
    tool: Object
    data: Object
}>()


console.log(props)

const component = {
    1: FooterTheme1,
    2: FooterTheme2,
    3: FooterTheme3,
}

</script>

<style scoped></style>