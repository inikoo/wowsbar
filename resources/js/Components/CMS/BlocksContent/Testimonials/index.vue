<template>
    <div>
        <component :is="component" />
    </div>
</template>

<script setup lang="ts">
import TestimonialsOne from './TestimonialsOne.vue';
import TestimonialsTwo from './TestimonialsTwo.vue';
import TestimonialsThree from './TestimonialsThree.vue';
import { computed } from 'vue';

const props = defineProps<{
    theme: number,
}>()


const components = {
    1: TestimonialsOne,
    2: TestimonialsTwo,
    3: TestimonialsThree
}


const component = computed(()=>{
    return components[props.theme]
})

</script>

<style scoped></style>