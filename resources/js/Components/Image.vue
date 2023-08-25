<script setup lang="ts">
import { toRefs, watch, ref, onBeforeMount } from 'vue'
import { cloneDeep } from 'lodash'
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

if(props.src){
    const { src } = toRefs(props)

    const imageSrc = ref(cloneDeep(src))
    const avif = ref(imageSrc.value.avif)
    const webp = ref(imageSrc.value.webp)
    const original = ref(imageSrc.value.original)

    watch(src, (newValue) => {
        imageSrc.value = newValue
        avif.value = newValue.avif
        webp.value = newValue.webp
        original.value = newValue.original
        setImage()
    })

    const setImage = () => {
        if (imageSrc.value.avif_2x) {
            avif.value += ' 1x, ' + imageSrc.value.avif_2x + ' 2x'
        }

        if (imageSrc.value.webp_2x) {
            webp.value += ' 1x, ' + imageSrc.value.webp_2x + ' 2x'
        }

        if (imageSrc.value.original_2x) {
            original.value += ' 1x, ' + imageSrc.value.original_2x + ' 2x'
        }
    }

    onBeforeMount(setImage)
}
</script>

<template>
    <picture v-if="$props.src">
        <source v-if="src.avif" type="image/avif" :srcset="avif">
        <source v-if="src.webp" type="image/webp" :srcset="webp">
        <img :class="class" :srcset="original" :src="src.original" :alt="alt">
    </picture>
    <img v-else :class="class" src="@/../art/fallback/fallback.svg" :alt="alt">
</template>
