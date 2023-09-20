<script setup lang="ts">
import Text from '@/Components/CMS/Workshops/WorkshopComponents/Text.vue'
const props = defineProps<{
    dataStats: any
}>()
</script>

<template>
    <div class="bg-white py-24">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:max-w-none space-y-10">
                <div class="text-center">
                    <Text v-model="dataStats.title" :key="'title'" :placeholder="'Enter your headline value'" :class="``" />
                    <Text v-model="dataStats.description" :key="'description'" :placeholder="'Enter your headline value'" :class="``" />
                </div>
                <dl class="flex gap-4 overflow-hidden rounded-2xl text-center">
                    <div v-for="stat in dataStats.statsList" :key="stat.id" class="flex flex-col w-full bg-gray-400/5 p-8">
                        <Text v-model="stat.value" :key="'value'" :placeholder="'Enter your headline value'" :class="``" />
                        <Text v-model="stat.name" :key="'title'" :placeholder="'Enter your headline title'" :class="``" />
                    </div>
                </dl>
            </div>
        </div>
    </div>
</template>