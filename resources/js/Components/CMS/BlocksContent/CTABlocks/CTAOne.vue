<script setup lang="ts">
import Text from '@/Components/CMS/Workshops/WorkshopComponents/Text.vue'

const props = defineProps<{
    dataCTA: any
}>()
</script>


<template>
    <div class="relative isolate overflow-hidden bg-slate-50 px-6 py-24 shadow-md sm:px-16">
        <Text v-model="dataCTA.title" :key="dataCTA.title" :placeholder="'Enter your headline title'" :class="``" />
        <Text v-model="dataCTA.subtitle" :key="dataCTA.subtitle" :placeholder="'Enter your headline description'"
            :class="`w-[80%] mx-auto`" />
        <div class="mt-10 flex items-center justify-center gap-x-6">
            <a href="#"
                class="rounded-md bg-slate-700 px-3.5 py-2.5 text-sm font-semibold text-gray-100 shadow-sm hover:bg-gray-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                <Text v-model="dataCTA.button" :key="dataCTA.button" :placeholder="'Enter your headline description'" />
            </a>
        </div>
        
        <!-- Background -->
        <svg viewBox="0 0 1024 1024"
            class="absolute left-1/2 top-1/2 -z-10 h-[64rem] w-[64rem] -translate-x-1/2 [mask-image:radial-gradient(closest-side,white,transparent)]"
            aria-hidden="true">
            <circle cx="512" cy="512" r="512" fill="url(#827591b1-ce8c-4110-b064-7cb85a0b1217)" fill-opacity="0.7" />
            <defs>
                <radialGradient id="827591b1-ce8c-4110-b064-7cb85a0b1217">
                    <stop stop-color="rgba(99, 102, 241, 0.3)" />
                    <stop offset="1" stop-color="rgba(99, 102, 241, 0.1)" />
                </radialGradient>
            </defs>
        </svg>
    </div>
</template>