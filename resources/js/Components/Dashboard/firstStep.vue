<script setup lang="ts">
// import PureInputWithAddOn from '@/Components/Pure/PureInputWithAddOn.vue'
import { ref, toRef } from 'vue'

const props = defineProps<{
    data: {
        websiteValue: string
    }
}>()

</script>

<template>
    <div class="mx-auto">
        <label for="inputWebsite" class="text-xl">Enter your website</label>
        <div class="relative flex rounded-md px-4 shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-gray-500">
            <div class="text-3xl flex items-center gap-x-1.5">
                <div class="flex select-none items-center text-gray-400">
                    <span class="leading-none mb-0.5">https://</span>
                </div>
            </div>

            <input v-model="data.websiteValue"
                name="inputWebsite'" id="inputWebsite'"
                class="text-3xl block flex-1 border-0 bg-transparent w-auto py-4 px-1 mb-0.5 placeholder:text-gray-400 read-only:text-gray-600 focus:ring-0"
                placeholder="enteryourwebsite.com" />

        </div>
    </div>
</template>