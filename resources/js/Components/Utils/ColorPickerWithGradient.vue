<script setup lang="ts">
import { ref } from 'vue'
import OverlayPanel from 'primevue/overlaypanel'
import { ColorPicker } from 'vue-color-kit'
import 'vue-color-kit/dist/vue-color-kit.css'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faTimes } from '@fortawesome/free-solid-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
import { useColorGradient, useSolidColor } from '@/Composables/useStockList'
library.add(faTimes)

import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue'

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

// Props and emits setup
const props = withDefaults(defineProps<{
    color: string
    closeButton?: boolean
    isEditable?: boolean
    classPopup?: string
    colorType?: string  // 'hex' || 'rgba'
}>(), {
    color: 'rgba(0, 0, 0, 0)',
    isEditable: true,
    colorType: 'rgba'
})

const emits = defineEmits<{
    (e: 'changeColor', value: string): void
}>()

// Ref for OverlayPanel
const overlayPanel = ref<null | InstanceType<typeof OverlayPanel>>(null)

// Helper function: converts opacity to hexadecimal
const opacityToHexCode = (opacity: number) => {
    const alphaValue = Math.round(opacity * 255)
    return alphaValue.toString(16).padStart(2, '0')
}

const adjustColor = (color: Color) => {
    if (props.colorType == 'rgba') {
        
        emits('changeColor', `rgba(${color.rgba.r}, ${color.rgba.g}, ${color.rgba.b}, ${color.rgba.a})`)
    } else if (props.colorType == 'hex') {
        emits('changeColor', color.hex)
    }

}
</script>

<template>

    <Popover v-slot="{ open }" class="relative">
        <PopoverButton>
            <slot>
                <div
                    v-bind="$attrs"
                    class="h-12 w-12 cursor-pointer"
                    :style="{ backgroundColor: color }"
                />
            </slot>
        </PopoverButton>

        <PopoverPanel :class="classPopup || 'absolute left-8 top-0 z-10 mt-3'">
            <div class="relative w-full h-full">
                <slot name="before-main-picker">
                    
                </slot>

                <div class="relative w-[220px] h-full">
                    <ColorPicker
                        style="width: 100%;"
                        theme="dark"
                        :colorsDefault="useSolidColor"
                        :color="color"
                        :sucker-hide="true"
                        @changeColor="(e) => adjustColor(e)"
                    />
                    <div v-if="!isEditable" class="rounded absolute inset-0 bg-black/50" />
                </div>
                
                <div class="flex gap-x-2 gap-y-2 w-full flex-wrap px-2 bg-gray-800 py-2 border-t border-dashed border-gray-300">
                    <div
                        v-for="color in useColorGradient"
                        @click="() => emits('changeColor', color)"
                        class="rounded-sm h-5 w-5 cursor-pointer hover:scale-125 transition-all"
                        :style="{background: color}"
                    >

                    </div>
                </div>

                <!-- <div @click="overlayPanel?.hide()" class="absolute -top-5 -right-10 mt-1 mr-1">
                    <FontAwesomeIcon icon="fal fa-times" class="text-red-400 hover:text-red-600 cursor-pointer" fixed-width aria-hidden="true" />
                </div> -->
            </div>
        </PopoverPanel>
    </Popover>

</template>

<style scoped lang="scss">
:deep(.colors) {
    display: block
}
</style>
