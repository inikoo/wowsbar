<script setup lang="ts">
import { ref } from 'vue'
import Text from '@/Components/CMS/Workshops/WorkshopComponents/Text.vue'
import ButtonWorkshop from '@/Components/CMS/Workshops/WorkshopComponents/ButtonWorkshop.vue'
import ImageWorkshop from '@/Components/CMS/Workshops/WorkshopComponents/ImageWorkshop.vue'

const props = defineProps<{
    dataCTA: any
}>()

const tempImage = ref('')
</script>


<template>
    <div class="relative bg-gray-100 grid grid-cols-2">
        <!-- Left content: Image -->
        <div class="relative overflow-hidden" :style="[`background: ${dataCTA.image.color}`]">
            <img class="z-10 absolute h-full w-full object-cover mix-blend-multiply grayscale" :src="dataCTA.image.link" alt="" />
            <ImageWorkshop :dataImage="dataCTA.image" />
        </div>

        <!-- Right content: Text -->
        <div class="relative mx-auto py-24 sm:py-32 lg:px-8 lg:py-40">
            <Text v-model="dataCTA.title" :key="dataCTA.title" :placeholder="'Enter your headline title'"
                :class="`tracking-tight`" />
            <Text v-model="dataCTA.subtitle" :key="dataCTA.subtitle" :placeholder="'Enter your headline description'"
                :class="`leading-7`" />
            <div class="mt-8">
                <ButtonWorkshop :dataButton="dataCTA.button"/>
            </div>
        </div>
    </div>
</template>