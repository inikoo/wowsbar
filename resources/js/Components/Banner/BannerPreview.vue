<script setup lang='ts'>
import { useRangeFromNow } from '@/Composables/useFormatTime'
import Slider from "@/Components/Slider/Slider.vue"
import Image from '@/Components/Image.vue'

const props = defineProps<{
    data: any
}>()

</script>

<template>
    <div v-if="data.type == 'landscape'">
        <div class="w-full bg-white flex items-center justify-between py-3 px-4">
            <div class="flex gap-x-2">
                <div class="h-5 aspect-square rounded-full overflow-hidden ring-1 ring-gray-300">
                    <Image :src="data.published_snapshot.publisher_avatar" />
                </div>
                <div class="font-bold text-lg leading-none">{{ data.published_snapshot.publisher }}</div>
                <div v-if="data.published_snapshot.comment" class="text-sm text-gray-500 italic">
                    ({{ data.published_snapshot.comment }})
                </div>
            </div>
            <div class="text-sm text-gray-600 tracking-wide text-right">Published at <span class="font-bold">{{ useRangeFromNow(data.published_snapshot.published_at) }}</span> ago</div>
        </div>
        <Slider :data="data.compiled_layout" />
    </div>
    <div v-else>
        Portrait
    
    </div>
</template>