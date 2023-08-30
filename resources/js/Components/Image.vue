<script setup lang="ts">
import { toRefs, watch, ref, onBeforeMount } from 'vue'
import { cloneDeep, get, isNull } from 'lodash'
const props = withDefaults(defineProps<{
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
}>(), {
    src: {
        original: '/fallback/fallback.svg'
    }
})

const { src } = toRefs(props)


const imageSrc = ref(cloneDeep(src))
const avif = ref(get(imageSrc,['value','avif'],'/fallback/fallback.svg'))
const webp = ref(get(imageSrc,['value','webp'],'/fallback/fallback.svg'))
const original = ref(get(imageSrc,['value','original'],'/fallback/fallback.svg'))

watch(src, (newValue) => {
    imageSrc.value = newValue
    avif.value = newValue.avif
    webp.value = newValue.webp
    original.value = newValue.original
    setImage()
})

const setImage = () => {
    if(!isNull(imageSrc.value)){
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
  
}

onBeforeMount(setImage)

</script>

<template>
    <picture :class="[props.class ?? 'w-full h-full flex justify-center items-center']">
        <source v-if="get(src,'avif')" type="image/avif" :srcset="avif">
        <source v-if="get(src,'webp')" type="image/webp" :srcset="webp">
        <img :srcset="original" :src="get(src,'original')" :alt="alt">
    </picture>
</template>
