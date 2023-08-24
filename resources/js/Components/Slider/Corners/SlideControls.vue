<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sun, 23 Jul 2023 21:46:49 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { faPlay, faPause, faChevronRight, faChevronLeft } from "@/../private/pro-solid-svg-icons"
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { library } from "@fortawesome/fontawesome-svg-core";
import { ref } from 'vue'

const props = defineProps<{
    swiperRef: Element
}>()

library.add(faPlay, faPause, faChevronRight, faChevronLeft)

const swiperAutoplayPause = ref(false)
const toggleAutoplay = (swiper: any) => {
    if (swiperAutoplayPause.value) {
        swiper.autoplay.start(); // Resume autoplay
    } else {
        swiper.autoplay.stop(); // Pause autoplay
    }
    swiperAutoplayPause.value = !swiperAutoplayPause.value; // Toggle the autoplay state
}

</script>

<template>
    <div class="opacity-50  flex justify-center items-center gap-x-3 my-3 text-gray-800/40">


        <div @click="toggleAutoplay(props.swiperRef.$el.swiper)"
            class="flex items-center justify-center cursor-pointer w-9 aspect-square bg-gray-300/50 rounded-full hover:text-gray-800/60 hover:ring-1 hover:ring-gray-400 active:bg-gray-300/80"
            title="Pause/resume autoplay" id="autoplay">
            <FontAwesomeIcon v-if="swiperAutoplayPause" icon="fas fa-play" class='text-xl ml-1' aria-hidden='true' />
            <FontAwesomeIcon v-if="!swiperAutoplayPause" icon="fas fa-pause" class='text-xl' aria-hidden='true' />
        </div>

        <div v-if="swiperAutoplayPause" @click="() => props.swiperRef.$el.swiper.slidePrev()"
             class="flex items-center justify-center cursor-pointer w-8 h-fit aspect-square bg-gray-300/50 rounded-full hover:text-gray-800/60 hover:ring-1 hover:ring-gray-400 active:bg-gray-300/80"
             title="Go to previous banner" id="previous">
            <FontAwesomeIcon icon='fas fa-chevron-left' class='text-xl mr-0.5' aria-hidden='true' />
        </div>
        <div v-if="swiperAutoplayPause" @click="() => props.swiperRef.$el.swiper.slideNext()"
            class="flex items-center justify-center cursor-pointer w-8 h-fit aspect-square bg-gray-300/50 rounded-full hover:text-gray-800/60 hover:ring-1 hover:ring-gray-400 active:bg-gray-300/80"
            title="Go to next banner" id="next">
            <FontAwesomeIcon icon='fas fa-chevron-right' class='text-xl ml-0.5' aria-hidden='true' />
        </div>
    </div></template>
