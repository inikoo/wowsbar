<script setup lang='ts'>
const props = withDefaults(defineProps<{
    total: number
    success: number
    fails?: number
}>(), {
    fails: 0
})
</script>

<template>
    <div class="flex items-center gap-x-1.5">
        <div class="overflow-hidden rounded-full bg-white w-48 flex justify-start shadow ring-1 ring-gray-300">
            <div class="h-1.5 bg-lime-500 transition-all duration-100 ease-in-out" :style="`width: ${(success/total)*100}%`" />
            <div v-if="fails" class="h-1.5 bg-red-500 transition-all duration-100 ease-in-out" :style="`width: ${(fails/total)*100}%`" />
        </div>
        <div class="text-xs text-gray-500 tabular-nums">
            {{ total == 0 ? 'Calculating..' : `${(((success + fails)/total)*100).toFixed(1)}%` }}
        </div>
    </div>
</template>