<script setup lang="ts">
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue'
import { ColorPicker } from 'vue-color-kit'
import 'vue-color-kit/dist/vue-color-kit.css'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faTimes } from '@fal'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faTimes)

interface RGBA {
    r: number
    g: number
    b: number
    a: number
}

interface HSV {
    h: number
    s: number
    v: number
}

interface Color {
    rgba: RGBA
    hsv: HSV
    hex: string
}

// To avoid the class (from parent) is inherit to first element
defineOptions({
    inheritAttrs: false
})

const props = withDefaults(defineProps<{
    color: string
    closeButton?: boolean
}>(), {
    color: 'rgba(0, 0, 0, 0)'
})

const emits = defineEmits<{
    (e: 'changeColor', value: Color): void
    (e: 'inputBlur'): void
}>()

// Method: change 0.2 to '33' (hexadecimal)
const opacityToHexCode = (opacity: number) => {
    if (opacity < 0 || opacity > 1) {
        return 'ff'
    }

    // Convert the opacity to a value between 0 and 255
    const alphaValue = Math.round(opacity * 255);
    
    // Return the base color with the new alpha value
    return alphaValue.toString(16).padStart(2, '0');
}

</script>


<template>
    <Popover v-slot="{ open }" class="relative">
        <PopoverButton as="template">
            <slot name="button">
                <div v-bind="$attrs" class="h-12 w-12 cursor-pointer" :style="{
                    backgroundColor: color
                }">
                </div>
            </slot>
        </PopoverButton>

        <PopoverPanel v-slot="{ close }" class="absolute left-8 top-0 z-10 mt-3">
            <div class="rounded-lg shadow-lg ring-1 ring-black ring-opacity-5">
                <div class="relative  bg-white p-2.5">
                    <ColorPicker
                        style="width: 220px;"
                        theme="dark"
                        :color="color"
                        :sucker-hide="true"
                        @inputBlur="emits('inputBlur')"
                        @changeColor="(e) => emits('changeColor', {...e, hex: e.hex + opacityToHexCode(e.rgba.a)})"
                        class="top-0 bottom-0"
                    />
                </div>
            </div>

            <div @click="() => close()" class="absolute -top-1 -right-6">
                <FontAwesomeIcon icon='fal fa-times' class='text-gray-400 hover:text-gray-600 cursor-pointer' fixed-width aria-hidden='true' />
            </div>
        </PopoverPanel>
    </Popover>
</template>

<style>
.hu-color-picker {
    height: fit-content
}
</style>