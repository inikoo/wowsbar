<script setup lang="ts">
import { useLayoutStore } from "@/Stores/layout"
const layout = useLayoutStore()

</script>

<template>
    <div class="my-3 mx-4">
        <div class="rounded-md bg-gray-200 w-fit relative shadow">
            <img v-if="layout.tenant.logo_id" class="w-32" :src="`/media/${layout.tenant.logo_id}`" :alt="layout.tenant.code" />
            <div class="absolute bottom-0 inset-x-0 h-8 pr-4 pb-1 bg-gradient-to-t from-gray-50 to-gray-50/0 flex justify-end items-end">
                {{layout.tenant.name }}
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>