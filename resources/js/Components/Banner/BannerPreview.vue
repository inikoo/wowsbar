<script setup lang='ts'>
import { useRangeFromNow } from '@/Composables/useFormatTime'
import Slider from "@/Components/Slider/Slider.vue"
import Image from '@/Components/Image.vue'

const props = defineProps<{
    data: any
}>()

</script>

<template>
    <!-- <pre>{{ data }}</pre> -->
    <!-- If banner is 'landscape' -->
    <div v-if="data.type == 'landscape'">
        <div v-if="data.published_snapshot" class="w-full bg-white flex items-center justify-between py-3 px-4">
            <div class="flex gap-x-2">
                <div class="h-5 aspect-square rounded-full overflow-hidden ring-1 ring-gray-300">
                    <Image :src="data.published_snapshot.publisher_avatar" />
                </div>
                <div class="font-bold text-lg leading-none">{{ data.published_snapshot.publisher }}</div>
                <div v-if="data.published_snapshot.comment" class="text-sm text-gray-500 italic">
                    ({{ data.published_snapshot.comment }})
                </div>
            </div>
            <div v-if="data.published_snapshot.published_at" class="text-sm text-gray-600 tracking-wide text-right">
                Published at <span class="font-bold">{{ useRangeFromNow(data.published_snapshot.published_at) }}</span> ago
            </div>
        </div>
        <div class="aspect-[2/1] md:aspect-[3/1] lg:aspect-[4/1] w-fit h-56 md:h-60">
            <Slider :data="data.compiled_layout" :production="false" />
        </div>
    </div>

    <!-- If banner is 'square' -->
    <div v-else>
        <div class="bg-white flex flex-col md:flex-row items-center justify-between py-3 px-4 gap-y-1 gap-x-1">
            <!-- Title -->
            <div v-if="data.published_snapshot.publisher_avatar || data.published_snapshot.publisher || data.published_snapshot.comment"
                class="flex gap-x-2"
            >
                <div class="h-5 aspect-square rounded-full overflow-hidden ring-1 ring-gray-300">
                    <Image :src="data.published_snapshot.publisher_avatar" />
                </div>
                <div class="font-bold text-base md:text-lg leading-none">{{ data.published_snapshot.publisher }}</div>
                <div v-if="data.published_snapshot.comment" class="text-sm text-gray-500 italic">
                    ({{ data.published_snapshot.comment }})
                </div>
            </div>
            
            <div v-else class="text-gray-600 text-xxs md:text-sm">
                This banner is not published yet.
            </div>

            <!-- Published at -->
            <div v-if="data.published_snapshot.published_at" class="text-xs md:text-sm italic text-gray-500 tracking-wide text-right font-light">
                Published at
                <span class="font-bold">{{ useRangeFromNow(data.published_snapshot.published_at) }}</span> ago
            </div>
        </div>
        <div class="flex flex-col h-48 lg:h-64 xl:h-96 w-fit ">
            <Slider :data="data.compiled_layout" :production="false" />
        </div>
    </div>
</template>
