  
<script  setup lang="ts">
import { ColorPicker } from 'vue-color-kit'
import 'vue-color-kit/dist/vue-color-kit.css'
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue'
import { ref } from 'vue'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faPaintBrushAlt } from '@/../private/pro-regular-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faPaintBrushAlt)

interface valueColorPicker {
    rgba: {
        r: number
        g: number
        b: number
        a: number
    }
}

const props = defineProps<{
    modelValue: string
}>()

// Declare emit
const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void
}>()

// Emit to v-model on parent
const changeColor = (value: valueColorPicker) => {
    const { r, g, b, a } = value.rgba
    emit('update:modelValue', `rgba(${r}, ${g}, ${b}, ${a})`)
}

</script>

<template>
    <div class="flex gap-3">
        <Popover v-slot="{ open }">
            <div class="relative">
                <PopoverButton>
                    <div class="border border-slate-300 rounded-full w-10 h-10 flex justify-center items-center"
                        :style="`background-color: ${modelValue}`">
                        <FontAwesomeIcon icon='far fa-paint-brush-alt' class='text-gray-300 text-lg' aria-hidden='true' />
                    </div>
                </PopoverButton>

                <Transition enter-active-class="transition duration-200 ease-out" enter-from-class="translate-y-1 opacity-0"
                    enter-to-class="translate-y-0 opacity-100" leave-active-class="transition duration-150 ease-in"
                    leave-from-class="translate-y-0 opacity-100" leave-to-class="translate-y-1 opacity-0">
                    <PopoverPanel class="absolute bottom-full left-1/2 z-10 mb-3 -translate-x-1/2 transform px-4 sm:px-0">
                        <ColorPicker theme="light" @changeColor="changeColor" style="width: 225px;" />
                    </PopoverPanel>
                </Transition>
            </div>
        </Popover>

        <!-- List available color -->
        <div class="flex gap-x-1 items-center">
            <div class="bg-gray-700 border border-slate-300 rounded w-6 h-6 shadow cursor-pointer hover:scale-110 transition-transform duration-100 ease-in-out" @click="() => changeColor({ rgba: { r: 55, g: 65, b: 81, a: 1 } })" />
            <div class="bg-white border border-slate-300 rounded w-6 h-6 shadow cursor-pointer hover:scale-110 transition-transform duration-100 ease-in-out" @click="() => changeColor({ rgba: { r: 255, g: 255, b: 255, a: 255 } })" />
            <div class="bg-orange-500 border border-slate-300 rounded w-6 h-6 shadow cursor-pointer hover:scale-110 transition-transform duration-100 ease-in-out" @click="() => changeColor({ rgba: { r: 249, g: 115, b: 22, a: 255 } })" />
            <div class="bg-fuchsia-500 border border-slate-300 rounded w-6 h-6 shadow cursor-pointer hover:scale-110 transition-transform duration-100 ease-in-out" @click="() => changeColor({ rgba: { r: 99, g: 102, b: 241, a: 255 } })" />
            <div class="bg-rose-500 border border-slate-300 rounded w-6 h-6 shadow cursor-pointer hover:scale-110 transition-transform duration-100 ease-in-out" @click="() => changeColor({ rgba: { r: 244, g: 63, b: 94, a: 255 } })" />
        </div>

        


    </div>
</template>

<style lang="scss">
</style>

