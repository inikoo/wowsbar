<script setup lang="ts">
import { reactive } from 'vue'
import BoxCreateBanner from '@/Components/Elements/BoxCreateBanner.vue'

const props = defineProps<{
    data: {
        text: string
        createRoute: {
            name: string
            parameters?: any[]
        }
        websiteOptions: any
    }
}>()

</script>

<template>
    <div class="grid grid-cols-2 max-w-4xl mx-auto pt-5">
    <!-- {{ data }} -->
        <div v-html="data.text"
            class="px-4 py-7 text-4xl font-bold bg-gradient-to-br from-green-300 via-blue-500 to-purple-600 text-transparent bg-clip-text">
        </div>
        <BoxCreateBanner :websiteOptions="data.websiteOptions" :createRoute="data.createRoute"/>
        
    </div>
</template>
