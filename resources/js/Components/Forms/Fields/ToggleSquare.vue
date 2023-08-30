<script setup lang="ts">
const props = defineProps<{
    form: any
    fieldName: string
    options?: any
    fieldData: {
        placeholder: string
        typeLabel: string[]
        readonly: boolean
        copyButton: boolean
        maxLength?: number
    }
}>()

</script>


<template>
    <div class="flex">
        <div class="grid grid-cols-2 cursor-pointer rounded overflow-hidden text-xs select-none ring-1 ring-gray-300">
            <div @click="form[fieldName] = false" class="py-1.5 px-2 flex justify-center capitalize transition-all duration-200 ease-in-out" :class="[!form[fieldName] ? 'bg-gray-600 text-gray-100' : 'bg-gray-200/70 text-gray-400 hover:bg-gray-300/70']">{{ fieldData.typeLabel[0] }}</div>
            <div @click="form[fieldName] = true" class="py-1.5 px-2 flex justify-center capitalize transition-all duration-200 ease-in-out" :class="[form[fieldName] ? 'bg-gray-600 text-gray-100' : 'bg-gray-200/70 text-gray-400 hover:bg-gray-300/70']">{{ fieldData.typeLabel[1] }}</div>
        </div>
    </div>
</template>