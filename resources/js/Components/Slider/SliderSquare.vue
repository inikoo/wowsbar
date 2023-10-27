<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sun, 23 Jul 2023 22:01:23 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { ref, watch, computed, toRef } from 'vue'
import { get } from 'lodash'
import SlideCorner from "@/Components/Slider/SlideCorner.vue"
import Image from "@/Components/Image.vue"
import CentralStage from "@/Components/Slider/CentralStage.vue"
import { breakpointType } from '@/Composables/useWindowSize'
import { useWindowSize } from '@vueuse/core'
import { BannerWorkshop, CornersData } from '@/types/BannerWorkshop'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faEyeSlash } from '@fas/'
import { faExternalLink, faExclamationTriangle } from '@far/'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faExternalLink, faEyeSlash, faExclamationTriangle)

import { Swiper, SwiperSlide } from 'swiper/vue'
import { Autoplay, Pagination, Navigation } from 'swiper/modules'
import 'swiper/css'
import 'swiper/css/navigation'


const props = defineProps<{
    production?: boolean
    jumpToIndex?: string  // ulid
    data: BannerWorkshop
    view?: string
}>()

const swiperRef = ref()
const { width: screenWidth, height: screenHeight }: any = useWindowSize()  // To detect responsive

const filteredNulls = (corners: CornersData) => {
    if (corners) {
        return Object.fromEntries(Object.entries(corners).filter(([_, v]) => v != null))
    }

    return ''
}

const componentEdited = toRef(() => props.data.components.filter(component => component.ulid == props.jumpToIndex))  // make jumpToIndex to reactive to watch() it 
const compIndexCurrentComponent = computed(() => {
    return props.data.components.findIndex(component => component.ulid == props.jumpToIndex)
})

// Jump view to slide (banner) on click slide (SlidesWorkshop)
watch(componentEdited, (newVal) => {
    swiperRef.value.$el.swiper.slideToLoop(compIndexCurrentComponent.value, 0, false)
})

const screenBreakpoint = computed(() => {
    return breakpointType(screenWidth.value)
})

// SlidesPerView depends on the screen
const compSlidesPerView = computed(() => {
    return !props.view
        ? screenBreakpoint.value == 'sm' || screenBreakpoint.value == 'xs'
            ? 1  // If below md: 1 slide per view
            : screenBreakpoint.value == 'md'
                ? actualSlides.value.length < 3 ? actualSlides.value.length : 3   // If md: 3 slide per view
                : actualSlides.value.length < 4 ? actualSlides.value.length : 4  // If lg and larger: 4 slide per view
        : props.view == 'mobile'
            ? 1  // slidePerview is 1 if responsive button clicked on Mobile
            : props.view == 'tablet'
                ? actualSlides.value.length < 3 ? actualSlides.value.length : 3
                : actualSlides.value.length < 4 ? actualSlides.value.length : 4  // if actualSlides length is less than 4 then slidePerview = actualSlide length
})

// The actual Slides
const actualSlides = computed(() => {
    return props.data.components.filter((item) => item.ulid)
})

// Square: Double the actualSlides length to avoid Swiper bugs (Slides must 2x length from slidesPerView)
const compHandleBannerLessSlide = computed(() => {
    return actualSlides.value.length <= 4
        ? screenBreakpoint.value == 'sm' || screenBreakpoint.value == 'xs'
            ? actualSlides.value.length == 1 ? actualSlides.value : [...actualSlides.value, ...actualSlides.value]
            : screenBreakpoint.value == 'md'
                ? actualSlides.value.length <= 3 ? actualSlides.value : [...actualSlides.value, ...actualSlides.value]
                : actualSlides.value.length <= 4 ? actualSlides.value : [...actualSlides.value, ...actualSlides.value]
        : screenBreakpoint.value == 'sm' || screenBreakpoint.value == 'xs'
            ? actualSlides.value
            : screenBreakpoint.value == 'md'
                ? actualSlides.value.length >= 6 ? actualSlides.value : [...actualSlides.value, ...actualSlides.value]
                : actualSlides.value.length >= 8 ? actualSlides.value : [...actualSlides.value, ...actualSlides.value]
})

</script>

<template>
    <!-- Square -->
    <!-- <pre>{{ props.data.components[1] }}</pre> -->
    <div class="w-full relative shadow overflow-hidden mx-auto transition-all duration-200 ease-in-out">
        <Swiper ref="swiperRef" :slideToClickedSlide="false" :spaceBetween="0" :slidesPerView="compSlidesPerView"
            :centeredSlides="false" :loop="true" :autoplay="{
                delay: data.delay,
                disableOnInteraction: false,
            }"
            :pagination="{ clickable: true, }"
            :navigation="false" :modules="[Autoplay, Pagination, Navigation]" class="mySwiper">
            <SwiperSlide v-for="component in compHandleBannerLessSlide" :key="component.id"
                class="h-full overflow-hidden aspect-square">

                <!-- Section: image or background -->
                <div v-if="get(component, ['backgroundType', 'desktop'], 'image') === 'image'"
                    class="relative w-full h-full">
                    <Image :src="get(component, ['image', 'desktop', 'source'], null)"
                        alt="Wowsbar" />
                </div>
                <div v-else
                    :style="{ background: get(component.background, 'desktop', 'gray') }"
                    class="w-full h-full" />

                <!-- Section: Not Visible (for workshop) -->
                <div v-if="get(component, ['visibility'], true) === false"
                    class="absolute h-full w-full bg-gray-800/50 z-10 " />
                <div class="z-[11] absolute left-7 flex flex-col gap-y-2">
                    <FontAwesomeIcon v-if="get(component, ['visibility'], true) === false" icon='fas fa-eye-slash'
                        class=' text-orange-400 text-4xl' aria-hidden='true' />
                    <span v-if="get(component, ['visibility'], true) === false"
                        class="text-orange-400/60 text-sm italic select-none" aria-hidden='true'>
                        <FontAwesomeIcon icon='far fa-exclamation-triangle' class='' aria-hidden='true' />
                        Not visible
                    </span>
                </div>

                <!-- <FontAwesomeIcon v-if="!!component?.layout?.link" icon='far fa-external-link' class='text-gray-300/50 text-xl absolute top-2 right-2' aria-hidden='true' /> -->
                <a target="_top" v-if="!!component?.layout?.link" :href="`https://${component?.layout?.link.replace(/^https?:\/\//g, '')}`"
                    class="absolute bg-transparent w-full h-full" />

                <SlideCorner v-for="(slideCorner, position) in filteredNulls(component?.layout?.corners)"
                    :position="position" :corner="slideCorner" />

                <!-- CentralStage: slide-centralstage (prioritize) and common-centralStage -->
                <CentralStage
                    v-if="component?.layout?.centralStage?.title?.length > 0 || component?.layout?.centralStage?.subtitle?.length > 0"
                    :data="component?.layout?.centralStage" />
                <CentralStage
                    v-else-if="data.common?.centralStage?.title?.length > 0 || data.common?.centralStage?.subtitle?.length > 0"
                    :data="data.common?.centralStage" />
            </SwiperSlide>
        </Swiper>

        <!-- Reserved Corner: Button Controls -->
        <SlideCorner class="z-10" v-for="(corner, position) in filteredNulls(data.common.corners)" :position="position"
            :corner="corner" :swiperRef="swiperRef" />
    </div>
</template>

<style lang="scss">
.swiper {
    @apply w-full h-full;
}

.swiper-slide {
    @apply bg-gray-200;
    text-align: center;
    font-size: 18px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.swiper-slide img {
    @apply w-full h-full;
    object-fit: cover;
}
</style>
