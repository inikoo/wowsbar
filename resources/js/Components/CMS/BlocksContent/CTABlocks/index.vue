<template>
    <div>
        <component :is="component" />
    </div>
</template>

<script setup lang="ts">
import CTAThree from './CTAThree.vue';
import CTAOne from './CTAOne.vue';
import CTATwo from './CTATwo.vue';
import { computed } from 'vue';

const props = defineProps<{
    theme: number,
}>()


const components = {
    1: CTAOne,
    2: CTATwo,
    3: CTAThree
}


const component = computed(()=>{
    return components[props.theme]
})

</script>

<style scoped></style>