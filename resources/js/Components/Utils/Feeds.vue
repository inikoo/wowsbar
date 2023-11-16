<script setup lang='ts'>
import { useFormatTime, useRangeFromNow } from '@/Composables/useFormatTime'

const props = defineProps<{
    dataFeeds: { 
        name: string; 
        action: string; 
        comment?: string; 
        dateTime: string; 
    }[]
}>()


</script>

<template>
    <!-- Vertical -->
    <ul role="list" class="space-y-6 text-gray-600">
        <li v-for="(feed, feedIdx) in dataFeeds" :key="feedIdx" class="relative flex gap-x-4">
            <div
                :class="[feedIdx === dataFeeds.length - 1 ? 'h-6' : '-bottom-6', 'absolute left-0 top-0 flex w-6 justify-center']">
                <div class="w-px bg-gray-200" />
            </div>
            
            <div class="relative flex h-6 w-6 flex-none items-center justify-center">
                <div class="h-2 aspect-square rounded-full bg-gray-500 ring-1 ring-gray-300" />
            </div>

            <!-- Condition: have comment -->
            <div v-if="feed.comment" class="bg-gray-100 flex-auto rounded-md p-3 ring-1 ring-inset ring-gray-200">
                <div class="flex justify-between gap-x-4">
                    <div class="flex text-xs leading-5 gap-x-1">
                        <div class="font-medium">{{ feed.name }}</div>
                        <div class="capitalize text-gray-400">{{ feed.action }}</div>
                    </div>
                    <time :datetime="feed.dateTime" class="flex-none text-xs leading-5 text-gray-500" :title="useFormatTime(feed.dateTime)">
                        {{ useRangeFromNow(feed.dateTime) }} ago
                    </time>
                </div>
                <p class="text-sm leading-6 text-gray-500">{{ feed.comment }}</p>
            </div>

            <!-- Condition: normal -->
            <div v-else class="flex justify-between w-full">
                <div class="flex text-xs leading-5 gap-x-1">
                    <div class="font-medium">{{ feed.name }}</div>
                    <div class="capitalize text-gray-400">{{ feed.action }}</div>
                </div>
                <time :datetime="feed.dateTime" class="flex-none text-xs leading-5 text-gray-500" :title="useFormatTime(feed.dateTime)">
                    {{ useRangeFromNow(feed.dateTime) }} ago
                </time>
            </div>
        </li>
    </ul>
</template>