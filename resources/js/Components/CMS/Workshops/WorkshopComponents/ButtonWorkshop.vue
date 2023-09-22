<script setup lang="ts">
import Text from '@/Components/CMS/Workshops/WorkshopComponents/Text.vue'

const props = defineProps(["modelValue", "dataButton"])
const emit = defineEmits<{
    (e: 'update:modelValue'): string
}>()
</script>

<template>
    <div class="w-64 flex relative">
        
    </div>
</template>