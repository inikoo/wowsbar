<template>
    <div>
        <component :is="component" />
    </div>
</template>

<script setup lang="ts">
import ContentDescriptionsOne from './ContentDescriptionsOne.vue';
import ContentDescriptionsTwo from './ContentDescriptionsTwo.vue';

import { computed } from 'vue';

const props = defineProps<{
    theme: number,
}>()


const components = {
    1: ContentDescriptionsOne,
    2: ContentDescriptionsTwo,
}


const component = computed(()=>{
    return components[props.theme]
})

</script>

<style scoped></style>