<script setup lang='ts'>
const props = defineProps<{
    total: number
    success: number
    fails: number
}>()
</script>

<template>
    <div class="flex items-center gap-x-1.5">
        <div class="overflow-hidden rounded-full bg-white w-48 flex justify-start shadow ring-1 ring-gray-300">
            <div class="h-1.5 bg-lime-500 transition-all duration-100 ease-in-out" :style="`width: ${(success/total)*100}%`" />
            <div class="h-1.5 bg-red-500 transition-all duration-100 ease-in-out" :style="`width: ${(fails/total)*100}%`" />
        </div>
        <div class="text-xs text-gray-500 tabular-nums">
            {{ ((success + fails)/total)*100 }}%
        </div>
    </div>
</template>