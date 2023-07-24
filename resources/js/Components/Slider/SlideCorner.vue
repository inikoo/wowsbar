<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sun, 23 Jul 2023 23:21:23 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">

import {computed} from 'vue'
import SlideControls from "@/Components/Slider/Corners/SlideControls.vue";
import LinkButton from "@/Components/Slider/Corners/LinkButton.vue";
import CornerText from "@/Components/Slider/Corners/CornerText.vue";
import CornerFooter from "@/Components/Slider/Corners/CornerFooter.vue";

const props = defineProps<{
    position: string,
    corner: {
        type: string,
        data?: object
    },
    swiperRef: Element
}>()


const positionClasses = computed(() => {
    let classes;
    switch (props.position) {
        case 'topRight':
            classes = 'top-5 right-5 text-right';
            break;
        case 'topLeft':
            classes = 'top-5 left-5 text-left';
            break;
        case 'bottomRight':
            classes = 'bottom-5 right-5 text-right';
            break;
        case 'bottomLeft':
            classes = 'bottom-5 left-5 text-left';
            break;

    }

    return classes;
});

const components = {
    'slideControls': SlideControls,
    'linkButton': LinkButton,
    'cornerText': CornerText,
    'cornerFooter': CornerFooter,

}
const getComponent = (componentName: any) => {
    return components[componentName] ?? null;
};

</script>

<template>
    <div :class="positionClasses" class="absolute">
        <component :is="getComponent(corner.type)" :data="corner.data" :swiperRef="props.swiperRef" />
    </div>

</template>

