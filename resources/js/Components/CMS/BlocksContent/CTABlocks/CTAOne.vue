<script setup lang="ts">
import Text from '@/Components/CMS/Workshops/WorkshopComponents/Text.vue'
import ButtonWorkshop from '@/Components/CMS/Workshops/WorkshopComponents/ButtonWorkshop.vue'
import { useHelperLayout } from '@/Stores/helperLayout.js'
import { ulid } from 'ulid'

const helperLayout = useHelperLayout()

const props = defineProps<{
    dataCTA: any
}>()
</script>


<template>
    <div class="relative isolate bg-slate-50 px-6 py-24 sm:px-16">
        <Text v-model="dataCTA.title" :placeholder="'Enter your headline title'" :class="``" />
        <div class="flex justify-center">
            <div class="w-[80%] isolate">
                <Text v-model="dataCTA.subtitle" :placeholder="'Enter your headline description'"
                    :class="`mx-auto`" />
            </div>
        </div>
        <div class="mt-10 flex items-center justify-center gap-x-6">
            <ButtonWorkshop :dataButton="dataCTA"/>
        </div>
        
        <!-- Background -->
        <div class="-z-10 absolute w-full h-full top-0 left-0 overflow-hidden">
            <svg viewBox="0 0 1024 1024"
                class="absolute left-1/2 top-1/4 -z-10 h-[64rem] w-[64rem] -translate-x-1/2 [mask-image:radial-gradient(closest-side,white,transparent)]"
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
    </div>
</template>