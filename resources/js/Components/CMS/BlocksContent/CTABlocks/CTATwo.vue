<script setup lang="ts">
import Text from '@/Components/CMS/Workshops/WorkshopComponents/Text.vue'

const props = defineProps<{
    dataCTA: any
}>()
</script>


<template>
    <div class="relative bg-gray-100 grid grid-cols-2">
        <div class="relative overflow-hidden bg-indigo-400">
            <img class="h-full w-full object-cover mix-blend-multiply grayscale"
                src="https://images.unsplash.com/photo-1525130413817-d45c1d127c42?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1920&q=60"
                alt="" />
            <svg viewBox="0 0 926 676" aria-hidden="true"
                class="absolute -bottom-24 left-24 w-[57.875rem] transform-gpu blur-[118px]">
                <path fill="url(#60c3c621-93e0-4a09-a0e6-4c228a0116d8)" fill-opacity=".4"
                    d="m254.325 516.708-90.89 158.331L0 436.427l254.325 80.281 163.691-285.15c1.048 131.759 36.144 345.144 168.149 144.613C751.171 125.508 707.17-93.823 826.603 41.15c95.546 107.978 104.766 294.048 97.432 373.585L685.481 297.694l16.974 360.474-448.13-141.46Z" />
                <defs>
                    <linearGradient id="60c3c621-93e0-4a09-a0e6-4c228a0116d8" x1="926.392" x2="-109.635" y1=".176"
                        y2="321.024" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#776FFF" />
                        <stop offset="1" stop-color="#FF4694" />
                    </linearGradient>
                </defs>
            </svg>
        </div>
        <div class="relative mx-auto py-24 sm:py-32 lg:px-8 lg:py-40">
                <Text v-model="dataCTA.title" :key="dataCTA.title" :placeholder="'Enter your headline title'"
                    :class="`tracking-tight`" />
                <Text v-model="dataCTA.subtitle" :key="dataCTA.subtitle" :placeholder="'Enter your headline description'"
                    :class="`leading-7`" />
                <div class="mt-8">
                    <a href="#"
                        class="inline-flex rounded-md bg-gray-700 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-gray-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                        Visit the help center
                    </a>
                </div>
        </div>
    </div>
</template>