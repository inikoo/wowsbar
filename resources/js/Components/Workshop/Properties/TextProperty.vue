<script setup lang='ts'>
import { trans } from 'laravel-vue-i18n'
import PureMultiselect from '@/Components/Pure/PureMultiselect.vue'
import { faBorderTop, faBorderLeft, faBorderBottom, faBorderRight, faBorderOuter } from "@fad"
import { library } from "@fortawesome/fontawesome-svg-core"
import { faLink, faUnlink } from "@fal"
import { faExclamation } from "@fas"
import ColorPicker from '@/Components/Utils/ColorPicker.vue'
import { get, set } from 'lodash';
library.add(faExclamation, faBorderTop, faBorderLeft, faBorderBottom, faBorderRight, faBorderOuter, faLink, faUnlink)

interface Borderproperty {
    color: string,
    fontFamily : String
}

const model = defineModel<Borderproperty>()

const useFontFamilyList = [
    {
        label: "Inter",
        value: "Inter, sans-serif",
    },
    {
        label: "Arial",
        value: "Arial, sans-serif",
    },
    {
        label: "Comfortaa",
        value: "'Comfortaa', sans-serif",
    },
    {
        label: "Lobster",
        value: "'Lobster', cursive",
    },
    {
        label: "Laila",
        value: "'Laila', sans-serif",
    },
    {
        label: "Port Lligat Slab",
        value: "'Port Lligat Slab', serif",
    },
    {
        label: "Playfair",
        value: "'Playfair Display', serif",
    },
    {
        label: "Raleway",
        value: "'Raleway', sans-serif",
    },
    {
        label: "Roman Melikhov",
        value: "'Roman Melikhov', serif",
    },
    {
        label: "Source Sans Pro",
        value: "'Source Sans Pro', sans-serif",
    },
    {
        label: "Quicksand",
        value: "'Quicksand', sans-serif",
    },
    {
        label: "Times New Roman",
        value: "'Times New Roman', serif",
    },

]

</script>

<template>
    <div class="flex flex-col pt-1 pb-3">
        <div class="pb-2">
            <div v-if="model?.color" class="px-3 flex justify-between items-center mb-2">
                <div class="text-xs">{{ trans('Color') }}</div>
                <ColorPicker :color="model?.color"
                    @changeColor="(newColor) => set(model, 'color', `rgba(${newColor.rgba.r}, ${newColor.rgba.g}, ${newColor.rgba.b}, ${newColor.rgba.a})`)"
                    closeButton>
                    <template #button>
                        <div v-bind="$attrs"
                            class="overflow-hidden h-7 w-7 rounded-md border border-gray-300 cursor-pointer flex justify-center items-center"
                            :style="{
                                background: `${model?.color}`
                            }">
                        </div>
                    </template>
                </ColorPicker>
            </div>

            <div class="px-3 items-center">
                <div class="text-xs mb-2">{{ trans('Font Families') }}</div>
                <div class="col-span-4">
                    <PureMultiselect
                        :modelValue="get(model, 'fontFamily', '')"
                        @update:modelValue="e => set(model, 'fontFamily', e)"
                        class=""
                        required
                        :options="useFontFamilyList"
                    >
                        <template #option="{ option, isSelected, isPointed, search }">
                            <span :style="{
                                fontFamily: option.value
                            }">
                                {{ option.label }}
                            </span>
                        </template>
                        <template #label="{ value }">
                            <div class="multiselect-single-label" :style="{
                                fontFamily: value.value
                            }">
                                {{ value.label }}
                            </div>
                        </template>
                    </PureMultiselect>
                </div>
            </div>

        </div>
    </div>
</template>