<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sun, 23 Jul 2023 21:46:49 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {faPlay, faPause, faChevronRight, faChevronLeft} from "@/../private/pro-solid-svg-icons"
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {library} from "@fortawesome/fontawesome-svg-core";
import {ref} from 'vue'

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
    <div class="opacity-50  flex justify-center items-center gap-x-3 my-3 text-gray-500">

        <div @click="toggleAutoplay(swiperRef.$el.swiper)" class="flex items-center justify-center cursor-pointer w-10 aspect-square bg-gray-200 rounded-full hover:ring-1 hover:ring-gray-400 active:bg-gray-300"
             title="Pause/resume autoplay">
            <FontAwesomeIcon v-if="swiperAutoplayPause" icon="fas fa-play" class='text-xl' aria-hidden='true'/>
            <FontAwesomeIcon v-if="!swiperAutoplayPause" icon="fas fa-pause" class='text-xl' aria-hidden='true'/>
        </div>

        <div @click="() => swiperRef.$el.swiper.slidePrev()" v-if="swiperAutoplayPause"
             class="flex items-center justify-center cursor-pointer w-8 h-fit aspect-square bg-gray-200 rounded-full hover:ring-1 hover:ring-gray-400 active:bg-gray-300" title="Go to previous banner">
            <FontAwesomeIcon icon='fas fa-chevron-left' class='text-xl' aria-hidden='true'/>
        </div>
        <div @click="() => swiperRef.$el.swiper.slideNext()" v-if="swiperAutoplayPause"
             class="flex items-center justify-center cursor-pointer w-8 h-fit aspect-square bg-gray-200 rounded-full hover:ring-1 hover:ring-gray-400 active:bg-gray-300" title="Go to next banner">
            <FontAwesomeIcon icon='fas fa-chevron-right' class='text-xl' aria-hidden='true'/>
        </div>
    </div>
</template>
