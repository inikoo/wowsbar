<script setup lang='ts'>
import { ref } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import 'swiper/css'
import 'swiper/css/navigation'
import { useFormatTime } from '@/Composables/useFormatTime'

const props = defineProps<{
    timeline: {
        [key: string]: string[]
    }
}>()

const _swiperRef = ref()
</script>

<template>
    <div class="w-full py-7">
        <Swiper ref="_swiperRef"
            :slideToClickedSlide="false"
            :spaceBetween="0"
            :slidesPerView="4"
            :centerInsufficientSlides="true"
            :pagination="{ clickable: true, }"
            class="w-full h-fit mx-12 px-12"
        >
            <template v-for="(tlValue, tlKey, index) in timeline">
                <SwiperSlide class="swiper-slide">
                    <div class="w-full mb-5 flex flex-col items-center text-gray-500 text-sm">
                        <span class="date">{{ useFormatTime(tlKey, { formatTime: 'hm'}) }}</span>
                    </div>
                    <div class="px-2 flex justify-center relative"
                        :class="Object.keys(timeline).length > 1 ? 'border-t-4 border-gray-700' : ''"
                    >
                        <span class="font-medium pt-5 before:content-[''] before:w-4 before:aspect-square before:bg-gray-200 before:rounded-full before:border-4 before:border-gray-700 before:absolute before:-top-[10px] before:left-[46%] transition-all duration-200 ease-in-out">
                            {{ tlValue.join(", ") }}
                        </span>
                    </div>
                </SwiperSlide>
            </template>
            <!-- <SwiperSlide class="swiper-slide">
                <div class="w-full mb-5 flex flex-col items-center text-gray-500 text-sm">
                    <span class="date">Now</span>
                </div>
                <div class="px-2 flex justify-center border-t-4 border-gray-700 relative transition-all duration-200 ease-in-out">
                    <span class="font-medium pt-5 before:content-[''] before:w-4 before:aspect-square before:bg-gray-200 before:rounded-full before:border-4 before:border-gray-700 before:absolute before:-top-[10px] before:left-[46%] transition-all duration-200 ease-in-out">
                        ...
                    </span>
                </div>
            </SwiperSlide> -->
        </Swiper>
    </div>
</template>

<style scoped>
.swiper-slide {
    width: 200px;
    text-align: center;
    font-size: 18px;
}

.swiper-slide:nth-child(2n) {
    width: 40%;
}

.swiper-slide:nth-child(3n) {
    width: 20%;
}
</style>