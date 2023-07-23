<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sun, 23 Jul 2023 23:21:23 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">

import {computed} from 'vue'
import SlideControls from "@/Components/Slider/Corners/SlideControls.vue";

const props = defineProps<{
    position: string,
    corner?: {
        type: string,
        data?: object
    },
}>()


const positionClasses = computed(() => {
    let classes;
    switch (props.position) {
        case 'topRight':
            classes = 'top-5 right-5';
            break;
        case 'topLeft':
            classes = 'top-5 left-5';
            break;
        case 'bottomRight':
            classes = 'bottom-5 right-5';
            break;
        case 'bottomLeft':
            classes = 'bottom-5 left-5';
            break;

    }

    return classes;
});

const components = {
    'slideControls': SlideControls
}
const getComponent = (componentName) => {
    return components[componentName] ?? null;
};

</script>

<template>
    <div :class="positionClasses" class="absolute">
        <component :is="getComponent(corner.type)">
        </component>
    </div>

</template>

