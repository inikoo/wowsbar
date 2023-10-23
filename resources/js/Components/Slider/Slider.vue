<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sun, 23 Jul 2023 22:01:23 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { ref, watch, computed } from 'vue'
import { get, isNull } from 'lodash'
import SlideCorner from "@/Components/Slider/SlideCorner.vue"
import Image from "@/Components/Image.vue"
import CentralStage from "@/Components/Slider/CentralStage.vue"
import { Link } from '@inertiajs/vue3'
import { breakpointType } from '@/Composables/useWindowSize'
import { useWindowSize } from '@vueuse/core'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faEyeSlash } from '@fas/'
import { faExternalLink, faExclamationTriangle } from '@far/'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faExternalLink, faEyeSlash, faExclamationTriangle)

import { Swiper, SwiperSlide } from 'swiper/vue'
import { Autoplay, Pagination, Navigation } from 'swiper/modules'
import 'swiper/css'
import 'swiper/css/navigation'


interface CornersPositionData {
    data: {
        text: string
        target: string
    }
    type: string
}

interface Corners {
    topLeft?: CornersPositionData
    topRight?: CornersPositionData
    bottomLeft?: CornersPositionData
    bottomRight?: CornersPositionData
}

const props = defineProps<{
    production?: boolean
    jumpToIndex?: number
    data: {
        common: {
            centralStage: {
                subtitle?: string
                text?: string
                title?: string
            }
            corners: Corners
        }
        components: {
            id: number
            ulid: string
            image_id: number
            image_source: string
            layout: {
                link?: string,
                centralStage: {
                    title?: string
                    subtitle?: string
                    // text?: string,
                    // footer?: string
                }
            }
            image: {
                desktop: {} | string
                tablet: {} | string
                mobile: {} | string
            }
            background: {
                desktop: string
                tablet: string
                mobile: string
            }
            backgroundType: {
                desktop: string
                tablet: string
                mobile: string
            }
            visibility: boolean
            corners: Corners
            imageAlt: string
            link: string
        }[]

        delay: number
        type: string
    }
    view?: string

}>()

const swiperRef = ref()
const { width: screenWidth, height: screenHeight }: any = useWindowSize()  // To detect responsive

const filteredNulls = (corners: Corners) => {
    if(corners) {
        return Object.fromEntries(Object.entries(corners).filter(([_, v]) => v != null))
    }

    return ''
}

// Jump view to slide (banner) on click slide (SlidesWorkshop)
watch(() => props.jumpToIndex, (newVal) => {
    swiperRef.value.$el.swiper.slideToLoop(newVal, 0, false)
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
    return props.data.components.filter((item)=>item.ulid)
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

const cleanUrl = (url: string) => {
    if(!url) { return ''}
    return url.replace(/^https?:\/\/?/, '')
}
</script>

<template>
    <!-- <pre>{{ data.components[0] }}</pre> -->
    <!-- <div>{{ screenBreakpoint }} <br>compHandleBannerLessSlide: {{ compHandleBannerLessSlide.length }} <br> slide per view:{{  compSlidesPerView }}
    <br> actualSlides: {{ actualSlides.length }}
    </div> -->

    <!-- Landscape -->
    <div v-if="data.type == 'landscape'" class="relative w-full">
        <div class="transition-all duration-200 ease-in-out" :class="[
            // production ? 'w-full' : 'mx-auto',
            $props.view
                ? { 'aspect-[2/1] w-[50%]' : $props.view == 'mobile',
                    'aspect-[3/1] w-[75%]' : $props.view == 'tablet',
                    'aspect-[4/1] w-full' : $props.view == 'desktop'}
                : 'aspect-[2/1] md:aspect-[3/1] lg:aspect-[4/1] w-full'
        ]">
            <Swiper ref="swiperRef"
                :slideToClickedSlide="true"
                :spaceBetween="-1"
                :slidesPerView="1"
                :centeredSlides="true"
                :loop="true"
                :autoplay="{
                    delay: data.delay,
                    disableOnInteraction: false,
                }"
                :pagination="{
                    clickable: true,
                }"
                :navigation="false"
                :modules="[Autoplay, Pagination, Navigation]" class="mySwiper">
                <SwiperSlide v-for="component in data.components.filter((item)=>item.ulid)" :key="component.id">
                    <!-- Slide: Image -->
                    <div v-if="get(component, ['backgroundType', $props.view ? $props.view : 'desktop'], 'image') == 'image'" class="relative w-full h-full">
                        <Image :src="get(component, ['image', `${$props.view ? props.view : 'desktop'}`, 'source'], null)" alt="Wowsbar" />
                    </div>
                    <div v-else :style="{ background: get(component, ['background', $props.view ? props.view : 'desktop'], 'gray')}" class="w-full h-full" />


                    <!-- Section: Not Visible (for workshop) -->
                    <div v-if="get(component, ['visibility'], true) === false" class="absolute h-full w-full bg-gray-800/50 z-10 " />
                    <div class="z-[11] absolute left-7 flex flex-col gap-y-2">
                        <FontAwesomeIcon v-if="get(component, ['visibility'], true) === false" icon='fas fa-eye-slash' class=' text-orange-400 text-4xl' aria-hidden='true' />
                        <span v-if="get(component, ['visibility'], true) === false" class="text-orange-400/60 text-sm italic select-none" aria-hidden='true'>
                            <FontAwesomeIcon icon='far fa-exclamation-triangle' class='' aria-hidden='true' />
                            Not visible
                        </span>
                    </div>
                    <!-- <FontAwesomeIcon v-if="!!component?.layout?.link" icon='far fa-external-link' class='text-gray-300/50 text-xl absolute top-2 right-2' aria-hidden='true' /> -->
                    <a v-if="!!component?.layout?.link" :href="`https://${cleanUrl(component?.layout?.link)}`" target="_top" class="absolute bg-transparent w-full h-full" />
                    <SlideCorner v-for="(slideCorner, position) in filteredNulls(component?.layout?.corners)" :position="position" :corner="slideCorner" :commonCorner="data.common.corners" />

                    <!-- CentralStage: slide-centralstage (prioritize) and common-centralStage -->
                    <CentralStage v-if="component?.layout?.centralStage?.title?.length > 0 || component?.layout?.centralStage?.subtitle?.length > 0" :data="component?.layout?.centralStage" />
                    <CentralStage v-else-if="data.common?.centralStage?.title?.length > 0 || data.common?.centralStage?.subtitle?.length > 0" :data="data.common?.centralStage" />
                </SwiperSlide>
            </Swiper>
            <!-- Reserved Corner: Button Controls -->
            <SlideCorner class="z-10" v-for="(corner, position) in filteredNulls(data.common.corners)" :position="position" :corner="corner"   :swiperRef="swiperRef"/>
        </div>
    </div>

    <!-- Square -->
    <div v-else
        class="relative shadow overflow-hidden"
        :class="[
            production ? 'h-full w-fit' : 'h-48 lg:h-64 xl:h-96 w-fit',
            compSlidesPerView == 1 ? 'aspect-square' : `aspect-[${compSlidesPerView}/1]`]"
    >
        <Swiper ref="swiperRef"
            :slideToClickedSlide="false"
            :spaceBetween="0"
            :slidesPerView="compSlidesPerView"
            :centeredSlides="false"
            :loop="true"
            :autoplay="{
                delay: data.delay,
                disableOnInteraction: false,
            }"
            :pagination="{
                clickable: true,
            }"
            :navigation="false"
            :modules="[Autoplay, Pagination, Navigation]" class="mySwiper">
            <SwiperSlide v-for="component in compHandleBannerLessSlide" :key="component.id" class="h-full overflow-hidden aspect-square">

                <!-- Section: image or background -->
                <div v-if="get(component, ['backgroundType', $props.view ? $props.view : 'desktop'], 'image') === 'image'" class="relative w-full h-full">
                    <Image :src="get(component, ['image', $props.view ? $props.view : 'desktop', 'source'], null)" alt="Wowsbar" />
                </div>
                <div v-else :style="{ background: get(component.background, $props.view ? $props.view : 'desktop', component.background?.desktop ?? 'gray')}" class="w-full h-full" />

                <!-- Section: Not Visible (for workshop) -->
                <div v-if="get(component, ['visibility'], true) === false" class="absolute h-full w-full bg-gray-800/50 z-10 " />
                <div class="z-[11] absolute left-7 flex flex-col gap-y-2">
                    <FontAwesomeIcon v-if="get(component, ['visibility'], true) === false" icon='fas fa-eye-slash' class=' text-orange-400 text-4xl' aria-hidden='true' />
                    <span v-if="get(component, ['visibility'], true) === false" class="text-orange-400/60 text-sm italic select-none" aria-hidden='true'>
                        <FontAwesomeIcon icon='far fa-exclamation-triangle' class='' aria-hidden='true' />
                        Not visible
                    </span>
                </div>

                <!-- <FontAwesomeIcon v-if="!!component?.layout?.link" icon='far fa-external-link' class='text-gray-300/50 text-xl absolute top-2 right-2' aria-hidden='true' /> -->
                <a target="_top" v-if="!!component?.layout?.link" :href="component?.layout?.link" class="absolute bg-transparent w-full h-full" />

                <SlideCorner v-for="(slideCorner, position) in filteredNulls(component?.layout?.corners)" :position="position" :corner="slideCorner" :commonCorner="data.common.corners" />

                <!-- CentralStage: slide-centralstage (prioritize) and common-centralStage -->
                <CentralStage v-if="component?.layout?.centralStage?.title?.length > 0 || component?.layout?.centralStage?.subtitle?.length > 0" :data="component?.layout?.centralStage" />
                <CentralStage v-else-if="data.common?.centralStage?.title?.length > 0 || data.common?.centralStage?.subtitle?.length > 0" :data="data.common?.centralStage" />
            </SwiperSlide>
        </Swiper>

        <!-- Reserved Corner: Button Controls -->
        <SlideCorner class="z-10" v-for="(corner, position) in filteredNulls(data.common.corners)" :position="position" :corner="corner"   :swiperRef="swiperRef"/>
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
