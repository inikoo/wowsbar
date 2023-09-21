<template>
    <div>
        <component :is="component[theme]" :data="data"/>
    </div>
</template>

<script setup lang="ts">
import HeaderTheme1 from './HeaderTheme1.vue';
import HeaderTheme2 from './HeaderTheme2.vue';

const props = defineProps<{
    theme: Number,
    data: Object
}>()


const component = {
   1: HeaderTheme1,
   2: HeaderTheme2,
}

</script>

<style scoped></style>