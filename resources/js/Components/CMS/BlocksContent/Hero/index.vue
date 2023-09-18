<template>
    <div>
        <component :is="component" />
    </div>
</template>

<script setup lang="ts">
import HeroOne from './HeroOne.vue';
import HeroTwo from './HeroTwo.vue';
import HeroThree from './HeroThree.vue';
import { computed } from 'vue';

const props = defineProps<{
    theme: number,
}>()


const components = {
    1: HeroOne,
    2: HeroTwo,
    3: HeroThree
}


const component = computed(()=>{
    return components[props.theme]
})

</script>

<style scoped></style>