<script setup lang='ts'>
const props = defineProps<{
    min: number
    max: number
    step: number
    range?: string[] | number[]
}>()

const model = defineModel<{}>({
})
    
</script>

<template>
    <div class="flex flex-col space-y-2 py-1 w-full">
        <input
            v-model="model"
            @input="e => model = e.target.value"
            type="range"
            class="w-full range accent-amber-400 hover:accent-amber-300 cursor-pointer transition-all"
            :min
            :max
            :step
        />

        <ul v-if="range?.length" class="flex justify-between w-full px-2.5">
            <li v-for="item in range" :key="item" class="flex justify-center relative">
                <span class="absolute">{{ item }}</span>
            </li>
        </ul>
    </div>
</template>