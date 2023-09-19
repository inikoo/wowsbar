<script setup lang="ts">
const props = defineProps<{
    modelValue: number  // v-model on parent
    label: string[]
}>()

defineEmits<{
    (e: 'update:modelValue'): number
}>()

</script>

<template>
    <div class="z-50 flex justify-center gap-x-2">
        <div v-for="(button, index) in label" @click="() => $emit('update:modelValue', index)" class="py-0.5 px-2 rounded cursor-pointer ring-1 ring-gray-500 capitalize" :class="[modelValue == index ? 'bg-gray-600 text-white' : 'hover:bg-gray-200' ]">{{ button }}</div>
    </div>
</template>