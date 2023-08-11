<script setup lang="ts">
import { toRefs, watch } from 'vue'
const props = defineProps<{
    src: {
        original: string
        original_2x?: string
        avif?: string,
        avif_2x?: string,
        webp?: string,
        webp_2x?: string,
    }
    alt?: string,
    class?: string
}>()

const { src } = toRefs(props);

watch(src, (newValue) => {
     console.log(newValue)
  });

let avif = props.src.avif;
if(props.src.avif_2x){
    avif+=' 1x, '+props.src.avif_2x+' 2x'
}

let webp = props.src.webp;
if(props.src.webp_2x){
    webp+=' 1x, '+props.src.webp_2x+' 2x'
}

let original = props.src.original;
if(props.src.original_2x){
    original+=' 1x, '+props.src.original_2x+' 2x'
}

</script>

<template>
    <picture>
        <source v-if="src.avif" type="image/avif" :srcset="avif">
        <source v-if="src.webp" type="image/webp" :srcset="webp">
        <img :class="class" :srcset="original" :src="src.original" :alt="alt">
    </picture>
</template>
