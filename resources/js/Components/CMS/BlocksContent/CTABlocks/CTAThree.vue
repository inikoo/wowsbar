<script setup lang="ts">
import Text from '@/Components/CMS/Workshops/WorkshopComponents/Text.vue'
import ButtonWorkshop from '@/Components/CMS/Workshops/WorkshopComponents/ButtonWorkshop.vue'
import ImageWorkshop from '@/Components/CMS/Workshops/WorkshopComponents/ImageWorkshop.vue'

const props = defineProps<{
    dataCTA: any
}>()

</script>

<template>
    <div class="relative bg-gray-300 py-16">
        <div class="absolute inset-x-0 top-0 hidden h-1/2 bg-gray-50 lg:block" aria-hidden="true" />
        <div class="mx-auto max-w-7xl bg-transparent lg:px-8">
            <div class="lg:grid lg:grid-cols-12">
                <div class="relative z-10 lg:col-span-4 lg:col-start-1 lg:row-start-1 lg:bg-transparent lg:py-10">
                    <div class="absolute inset-x-0 h-1/2 bg-amber-500 lg:hidden" aria-hidden="true" />
                    <div class="mx-auto max-w-md px-6 sm:max-w-3xl lg:max-w-none lg:p-0">
                        <div class="aspect-[4/6] relative flex justify-center items-center rounded-3xl overflow-hidden" :style="[`background: ${dataCTA.image.color}`]">
                            <img class="absolute h-full w-full object-cover mix-blend-multiply grayscale"
                                :src="dataCTA.image.link"
                                alt="" />
                            <ImageWorkshop :dataImage="dataCTA.image" class="" />
                        </div>
                    </div>
                </div>

                <div class="relative bg-gray-100 lg:col-span-10 lg:col-start-3 lg:row-start-1 lg:grid lg:grid-cols-10 lg:items-center lg:rounded-3xl">
                    <div class="absolute inset-0 hidden overflow-hidden rounded-3xl lg:block" aria-hidden="true">
                        <svg class="absolute bottom-full left-full -translate-x-2/3 translate-y-1/3 transform xl:bottom-auto xl:top-0 xl:translate-y-0"
                            width="404" height="384" fill="none" viewBox="0 0 404 384" aria-hidden="true">
                            <defs>
                                <pattern id="64e643ad-2176-4f86-b3d7-f2c5da3b6a6d" x="0" y="0" width="20" height="20"
                                    patternUnits="userSpaceOnUse">
                                    <rect x="0" y="0" width="4" height="4" class="text-gray-200" fill="currentColor" />
                                </pattern>
                            </defs>
                            <rect width="404" height="384" fill="url(#64e643ad-2176-4f86-b3d7-f2c5da3b6a6d)" />
                        </svg>
                        <svg class="absolute top-full -translate-x-1/3 -translate-y-1/3 transform xl:-translate-y-1/2"
                            width="404" height="384" fill="none" viewBox="0 0 404 384" aria-hidden="true">
                            <defs>
                                <pattern id="64e643ad-2176-4f86-b3d7-f2c5da3b6a6d" x="0" y="0" width="20" height="20"
                                    patternUnits="userSpaceOnUse">
                                    <rect x="0" y="0" width="4" height="4" class="text-indigo-500" fill="currentColor" />
                                </pattern>
                            </defs>
                            <rect width="404" height="384" fill="url(#64e643ad-2176-4f86-b3d7-f2c5da3b6a6d)" />
                        </svg>
                    </div>
                    <div
                        class="mx-auto max-w-md lg:col-span-6 lg:col-start-4 lg:max-w-none lg:p-0">
                        <Text v-model="dataCTA.title" :key="dataCTA.title" :placeholder="'Enter your headline title'" />
                        <Text v-model="dataCTA.subtitle" :key="dataCTA.subtitle"
                            :placeholder="'Enter your headline description'" />
                        <ButtonWorkshop :dataButton="dataCTA.button"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>