<script setup lang="ts">
import { useLayoutStore } from "@/Stores/layout"

const props = defineProps<{
    data: any
}>()

const layout = useLayoutStore()

</script>

<template>
    <div class="my-3 mx-4">
        <div class="rounded-md bg-gray-200 w-fit relative shadow">

    
        </div>
    </div>
</template>

<style scoped>

</style>
