<script setup lang="ts">
const props = defineProps<{
    selectedComponent: any
    modelValue: any
}>()

defineEmits<{
    (e: 'update:modelValue'): string
}>()


</script>

<template>
    <div class="h-44 flex justify-center items-center">
        <input class="text-center bg-transparent border-2 focus:border-0 focus:ring-0 overflow-visible w-max"
            :value="modelValue" @input="$emit('update:modelValue', selectedComponent.value)" />
    </div>
</template>

<style>
[contenteditable=true]:empty:before {
    content: attr(placeholder);
    color: gray;
}

/* found this online --- it prevents the user from being able to make a (visible) newline */
[contenteditable=true] br {
    display: none;
}
</style>