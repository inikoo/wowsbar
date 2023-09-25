<script setup lang="ts">
import { Ref, ref } from 'vue'
import { Popover, PopoverPanel } from '@headlessui/vue'
import Text from '@/Components/CMS/Workshops/WorkshopComponents/Text.vue'
import WebpageColorPicker from '@/Components/CMS/Workshops/WorkshopComponents/WebpageColorPicker.vue'
import Toggle from '@/Components/Pure/Toggle.vue'

import { ulid } from "ulid"

const props = defineProps<{
    // modelValue: any
    dataButton: {
        button: {
            label: string
            background: string
            full: boolean
        }
    }
}>()

// const emit = defineEmits<{
//     (e: 'update:modelValue'): string
// }>()

const popoverValue: Ref<any> = ref(false)
const tempId = ulid() // To show the match Popover

</script>

<template>

    <div class="relative rounded border-2 border-dashed border-gray-400 focus-within:border-transparent transition-all duration-300 ease-in-out"
        :class="[popoverValue == tempId ? 'z-50' : 'z-0',
        dataButton.button.full ? 'w-full' : 'w-fit']">
        <!-- The main editor -->
        <div  class="z-20 relative"
            :class="['min-w-[50px] whitespace-nowrap rounded hover:bg-orange-200 hover:ring-gray-400 focus-within:ring-gray-400']">
                <!-- Editor Button -->
                <div @click="() => { popoverValue = tempId }"
                    :style="[dataButton.button.background ? `background-color: ${dataButton.button.background};` : 'background-color: rgb(239 68 68)']"
                    class="w-full focus:ring focus:ring-gray-400 flex rounded-md hover:opacity-80 cursor-pointer px-3.5 py-2.5 text-sm font-semibold text-gray-100 shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                    <!-- Editor Text -->
                    <div @click.stop="" class="w-full">
                        <Text v-model="dataButton.button.label" :placeholder="'Enter your headline description'" />
                    </div>
                </div>

            <!-- Helper: background transparent -->
            <div :class="[popoverValue ? 'z-10 fixed top-0 left-0 opacity-0' : 'hidden']"
                class="w-screen h-screen bg-yellow-500" @mousedown="popoverValue = false" />
        </div>

        <!-- Popover: Button -->
        <Popover v-slot="{ open }" class="absolute">
            <!-- <PopoverButton ref="_popoverButton" /> -->
            <transition>
                <div v-if="popoverValue">
                    <PopoverPanel static
                        class="absolute left-0 translate-y-3 z-20 w-screen max-w-sm px-4 sm:px-0 lg:max-w-3xl">
                        <!-- Group: editor tools -->
                        <div class="z-50 flex bg-gray-100 absolute w-fit justify-between rounded-r ring-1 ring-gray-300 shadow-md py-0.5 text-slate-800 select-none space-x-1 border border-gray-100 divide-x-2 divide-gray-200"
                            tabindex="0">
                            <div class="flex flex-col justify-center px-2 pb-1">
                                <div class="text-xxs tracking-wide text-center text-gray-700">Background color</div>
                                <WebpageColorPicker v-model="dataButton.button.background"/>
                            </div>
                            <div class="flex flex-col justify-start px-2 pb-1 items-center">
                                <div class="text-xxs tracking-wide text-center text-gray-700 ">Full width</div>
                                <div class="h-full flex items-center">
                                    <Toggle v-model="dataButton.button.full" />
                                </div>
                            </div>
                        </div>
                    </PopoverPanel>
                </div>
            </transition>
        </Popover>
    </div>
</template>