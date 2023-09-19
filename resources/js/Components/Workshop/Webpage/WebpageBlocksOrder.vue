<script setup lang="ts">
import { trans } from "laravel-vue-i18n"
import { ref } from "vue"
import draggable from "vuedraggable"

import Button from '@/Components/Elements/Buttons/Button.vue'

import { componentBlocks } from '@/types/WebPageWorkshop'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faTrashAlt, faTimes } from "@/../private/pro-light-svg-icons"
import { faText, faWindowMaximize } from "@/../private/pro-regular-svg-icons"
import { faEye, faEyeSlash } from '@/../private/pro-solid-svg-icons'
import { faRectangleWide } from "@/../private/pro-duotone-svg-icons"
import { library } from '@fortawesome/fontawesome-svg-core'

const props = defineProps<{
    dataComponent: any
    selectedComponent: any
}>()

const emits = defineEmits<{
    (e: 'handleSelectComponent', componentName: componentBlocks): void
}>()

library.add(faEye, faEyeSlash,faTrashAlt, faTimes, faRectangleWide, faText, faWindowMaximize)

const handleSelectComponent = (componentName: componentBlocks) => {
    console.log(componentName)
    emits('handleSelectComponent', componentName)
}

</script>

<template>
    <div v-if="dataComponent" class="h-full p-2.5 border rounded shadow space-y-2" @dragover="true" @dragleave="true" @drop="true">
        <!-- Button: Add slide, Gallery -->
        <div class="flex justify-center flex-wrap md:flex-row gap-x-2 gap-y-1 md:gap-y-0">
            <Button :style="`secondary`" icon="fas fa-plus" size="xs" class="relative">
                {{ trans("Add block") }}
            </Button>
        </div>

        <!-- Slides/Drag area -->
        <draggable :list="dataComponent" group="slide " item-key="ulid" handle=".handle" class="space-y-2 h-fit p-0.5"
            :onChange="true">
            <template #item="{ element: item }">
                <div @mousedown="handleSelectComponent(item)" v-if="item.ulid" class="relative handle items-center justify-between"
                    :class="[item.visibility ? '' : 'opacity-40']"
                >
                    <!-- Item -->
                    <div class="relative rounded grid grid-flow-col gap-x-1 lg:gap-x-0 sm:py-1 lg:py-0">
                        <!-- Icon: Bars -->
                        <FontAwesomeIcon icon="fal fa-bars"
                            class="text-xs sm:text-base text-gray-700 cursor-grab place-self-center" />

                        <!-- Icon: Blocks -->
                        <div class="flex items-center justify-center rounded-sm px-2 py-1 cursor-pointer border-gray-400 hover:bg-gray-300"
                            :class="[item.ulid == selectedComponent.ulid ? 'border bg-gray-200' : 'border border-dashed']"
                        >
                            <!-- <FontAwesomeIcon :icon='item.icon' class='' aria-hidden='true' /> -->
                            {{ item.component }}
                        </div>

                        <!-- Button: Show/hide, delete item -->
                        <div class="flex justify-center items-center pr-2 justify-self-end" v-if="true">
                            <button class="px-2 py-1 text-gray-400 hover:text-gray-500" type="button"
                                @click="item.visibility = !item.visibility" title="Show/hide the item">
                                <FontAwesomeIcon v-if="item.hasOwnProperty('visibility') ? item.visibility : true"
                                    icon="fas fa-eye" class="text-xs sm:text-sm " />
                                <FontAwesomeIcon v-else icon="fas fa-eye-slash" class="text-xs sm:text-sm" />
                            </button>
                        </div>

                    </div>

                    <!-- Button: delete item -->
                    <div class="absolute flex justify-center items-center h-3 aspect-square rounded-full top-0 right-0 translate-x-1/2 -translate-y-1/2 leading-none bg-gray-200 hover:bg-gray-300"
                        type="button" v-if="!item.visibility" @click="true" title="Delete the item">
                        <FontAwesomeIcon :icon="['fal', 'fa-times']"
                            class="text-[7px] leading-none text-red-400 hover:text-red-500" />
                    </div>


                    <!-- If locked -->
                    <!-- <div v-else class="flex justify-center items-center pr-2 justify-self-end">
                            <div class="px-2 py-1" type="button" title="Edited by other user">
                                <FontAwesomeIcon :icon="['fal', 'lock']" class="text-xs sm:text-sm text-gray-600" />
                            </div>
                        </div> -->

                </div>
            </template>
        </draggable>
    </div>
</template>