<script setup lang="ts">
import UnderMaintenance from '@/Components/Utils/UnderMaintenance.vue'

const props = defineProps<{
    data: {
        slug: string
        state: string
        status: string
        created_at: string
        updated_at: string
    }
}>()


</script>

<template>
    <div class="px-6 py-24 sm:py-20 lg:px-8">
        <UnderMaintenance  v-if="!(data.state==='live' && data.status)" :data="data" />
        <div v-else>Live</div>    
    </div>
</template>