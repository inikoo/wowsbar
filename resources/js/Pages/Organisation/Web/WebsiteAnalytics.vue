<script setup lang="ts">
import UnderMaintenance from '@/Components/Utils/UnderMaintenance.vue'

const props = defineProps<{
    data: any
}>()
</script>

<template>
    <div class="px-6 py-24 sm:py-20 lg:px-8">
        <UnderMaintenance />    
    </div>
</template>

<style scoped></style>
