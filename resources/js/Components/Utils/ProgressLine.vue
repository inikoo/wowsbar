<script setup lang='ts'>
import { ref, watch, onUnmounted } from 'vue'
const props = withDefaults(defineProps<{
    total: number
    success: number
    fails?: number
}>(), {
    fails: 0
})

const strCalculating = ref('Calculating')
let secondHelper = 0

// Interval
let intervalHelper = setInterval(() => {
    secondHelper++
    strCalculating.value += '.'
    console.log('interval')
    if(secondHelper%4 === 0) {
        strCalculating.value = 'Calculating'
    }
    if(props.total > 0) {
        clearInterval(intervalHelper)
        watchTotal()
    }
}, 1000)

// Watch total
const watchTotal = watch(() => props.total, () => {
    secondHelper = 0
    strCalculating.value = 'Calculating'
    console.log('watch total')
    if(props.total > 0) {
        clearInterval(intervalHelper)
        watchTotal()
    }
})

// Clear
onUnmounted(() => {
    clearInterval(intervalHelper)
    watchTotal()
})

</script>

<template>
    <div class="flex items-center gap-x-1.5">
        <div class="h-1.5 relative overflow-hidden rounded-full bg-white w-48 flex justify-start shadow ring-1 ring-gray-300">
            <div class="h-full bg-lime-500 transition-all duration-100 ease-in-out" :style="`width: ${(success/total)*100}%`">
            </div>
            <div class="h-full bg-red-500 transition-all duration-100 ease-in-out" :style="`width: ${(fails/total)*100}%`" />
            <!-- <div class="h-full w-full shimmer absolute left-0 z-20 qwezxc"></div> -->
        </div>
        <div class="text-xs text-gray-500 tabular-nums">
            {{ total == 0 ? strCalculating : `${(((success + fails)/total)*100).toFixed(1)}%` }}
        </div>
    </div>
</template>