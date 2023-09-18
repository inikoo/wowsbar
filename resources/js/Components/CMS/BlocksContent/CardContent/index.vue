<template>
    <div>
        <component :is="component" />
    </div>
</template>

<script setup lang="ts">
import CardContentOne from './CardContentOne.vue';
import CardContentTwo from './CardContentTwo.vue';
import CardContentThree from './CardContentThree.vue';

import { computed } from 'vue';

const props = defineProps<{
    theme: number,
}>()


const components = {
    1: CardContentOne,
    2: CardContentTwo,
    3: CardContentThree
}


const component = computed(()=>{
    return components[props.theme]
})

</script>

<style scoped></style>