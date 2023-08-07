<script setup lang="ts">

    const props = defineProps<{
        src: {
            original:string,
            webp?:string
        }
        alt?:string
    }>();


</script>

<template>
    <picture>
        <source v-if="src.webp" type="image/webp" :srcset="src.webp">
        <img :src="src.original" :alt="alt">
    </picture>
</template>
