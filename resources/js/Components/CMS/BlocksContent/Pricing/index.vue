<template>
    <div>
        <component :is="component" />
    </div>
</template>

<script setup lang="ts">
import PricingOne from './PricingOne.vue';
import PricingTwo from './PricingTwo.vue';
import PricingThree from './PricingThree.vue';
import { computed } from 'vue';

const props = defineProps<{
    theme: number,
}>()


const components = {
    1: PricingOne,
    2: PricingTwo,
    3: PricingThree
}


const component = computed(()=>{
    return components[props.theme]
})

</script>

<style scoped></style>