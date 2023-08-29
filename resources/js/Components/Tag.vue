<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    color: {
        type: String,
        default: '#2db7f5' // Replace 'defaultColorValue' with your desired default color
    },
    size: {
        type: String,
        default: 'xs' 
    }
});
</script>

<template>
   <span :class="`inline-block rounded-md px-2 py-1 text-white text-${size} w-fit font-medium`" :style="`background-color: ${color};`">
   <slot></slot>
   </span>
</template>
