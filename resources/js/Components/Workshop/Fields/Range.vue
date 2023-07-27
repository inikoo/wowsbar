<template>
    <div class="flex flex-col space-y-2 p-2 w-full">
        <p>Duration: {{ inputValue }}</p>
        <input v-model="inputValue" type="range" class="w-full range accent-orange-500" min="2.5" max="15" step="0.5" />
        <ul class="flex justify-between w-full px-[10px]">
            <li class="flex justify-center relative"><span class="absolute">2.5</span></li>
            <li class="flex justify-center relative"><span class="absolute">5</span></li>
            <li class="flex justify-center relative"><span class="absolute">7.5</span></li>
            <li class="flex justify-center relative"><span class="absolute">10</span></li>
            <li class="flex justify-center relative"><span class="absolute">12.5</span></li>
            <li class="flex justify-center relative"><span class="absolute">15</span></li>
        </ul>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const inputValue = ref(5000)
</script>

<style scoped></style>