<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Wed, 07 Jun 2023 02:45:27 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { library } from '@fortawesome/fontawesome-svg-core'
import { inject, ref } from 'vue'
import { faBrowser, faDraftingCompass, faRectangleWide, faStars, faBars, faText, faChevronDown } from '@fal'
// import draggable from "vuedraggable"
import PanelProperties from '@/Components/Workshop/PanelProperties.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import Button from '@/Components/Elements/Buttons/Button.vue'
import { debounce } from 'lodash'
import LoadingIcon from '@/Components/Utils/LoadingIcon.vue'
import Modal from "@/Components/Utils/Modal.vue"

// import { Root, Daum } from '@/types/webBlockTypes'
// import { Root as RootWebpage } from '@/types/webpageTypes'
import { Collapse } from 'vue-collapsed'
import { trans } from 'laravel-vue-i18n'
import Editor from '@/Components/Editor/Editor.vue'
import PureInputNumber from '@/Components/Pure/PureInputNumber.vue'
import { propertiesToHTMLStyle } from '@/Composables/usePropertyWorkshop'
import Moveable from "vue3-moveable"
import ColorPicker from '@/Components/Utils/ColorPicker.vue'


library.add(faBrowser, faDraftingCompass, faRectangleWide, faStars, faBars, faText, faChevronDown)

const props = defineProps<{
}>()

const selectedBlockOpenPanel = ref<string | null>('content')
const isOnDrag = ref(false)

const announcementData = inject('announcementData', {})

const _parentOfButtonClose = ref<Element | null>(null)
const _buttonClose = ref<Element | null>(null)
const onDrag = (e, block_properties) => {
    const parentWidth = _parentOfButtonClose.value?.clientWidth || 0
    const parentHeight = _parentOfButtonClose.value?.clientHeight || 0

    const percentageLeft = e.left / parentWidth * 100
    const percentageTop = e.top / parentHeight * 100

    // Update position based on the dragging
    block_properties.position.x = `${percentageLeft}%`
    block_properties.position.y = `${percentageTop}%`
}

const toVerticalCenter = (block_properties: {}) => {
    block_properties.position.y = '50%'
}
const toHorizontalCenter = (block_properties: {}) => {
    block_properties.position.x = '50%'
}

const debounceSetIsOnDrag = debounce(() => isOnDrag.value = false, 50)
</script>

<template>
    <!-- <div class="flex justify-between">
        <h2 class="text-sm font-semibold leading-6">Blocks List</h2>
        <Button icon="fas fa-plus" type="dashed" size="xs" @click="openModalBlockList" />
    </div> -->

    <div class="rounded bg-white">
        <div @click="() => selectedBlockOpenPanel === 'container' ? selectedBlockOpenPanel = null : selectedBlockOpenPanel = 'container'"
            class="w-full bg-gray-200 py-2 px-3 flex justify-between items-center cursor-pointer">
            <div class="select-none font-semibold">{{ trans('Container Settings') }}</div>
            <FontAwesomeIcon icon='fal fa-chevron-down' :class="selectedBlockOpenPanel === 'container' ? 'rotate-180' : ''" class="transition-all" fixed-width aria-hidden='true' />
        </div>

        <Collapse as="section" :when="selectedBlockOpenPanel === 'container'">
            <PanelProperties
                v-model="announcementData.container_properties"
            />
        </Collapse>
    </div>

    <div class="rounded bg-white">
        <div @click="() => selectedBlockOpenPanel === 'content' ? selectedBlockOpenPanel = null : selectedBlockOpenPanel = 'content'"
            class="w-full bg-gray-200 py-2 px-3 flex justify-between items-center cursor-pointer">
            <div class="select-none font-semibold">{{ trans('Content') }}</div>
            <FontAwesomeIcon icon='fal fa-chevron-down' :class="selectedBlockOpenPanel === 'content' ? 'rotate-180' : ''" class="transition-all" fixed-width aria-hidden='true' />
        </div>

        <Collapse as="section" :when="selectedBlockOpenPanel === 'content'">
            <div  class="border-t border-gray-300 pb-3">
                <div class="flex justify-between items-center">
                    <div class="w-full py-1 select-none text-sm">{{ trans('Text 1') }}</div>
                </div>
                <div class="mx-1 border border-gray-300">
                    <Editor v-model="announcementData.fields.text_1.text" />
                </div>
            </div>

            <div  class="border-t border-gray-300 pb-3">
                <div class="w-full py-1 select-none text-sm">{{ trans('Text 2') }}</div>
                <div class="mx-1 border border-gray-300">
                    <Editor v-model="announcementData.fields.text_2.text" />
                </div>
            </div>

            <!-- Section: Close button -->
            <div  class="border-t border-gray-300 pb-3">
                <div class="flex justify-between items-center">
                    <div class="w-full py-1 select-none text-sm">{{ trans('Close button') }}</div>
                    <div>
                        <ColorPicker
                            class="h-5 w-5 rounded shadow-lg border border-gray-300"
                            closeButton
                            :color="announcementData.fields.close_button.block_properties.text.color"
                            @changeColor="(newColor) => announcementData.fields.close_button.block_properties.text.color = `rgba(${newColor.rgba.r}, ${newColor.rgba.g}, ${newColor.rgba.b}, ${newColor.rgba.a})`"
                        />
                            <!-- {{ announcementData.fields.close_button.block_properties.text.color }} -->
                    </div>
                </div>
                

                <div>
                    <div ref="_parentOfButtonClose" class="relative w-full h-24 bg-gray-100 border border-gray-300">
                        <div ref="_buttonClose" class="absolute -translate-x-1/2 -translate-y-1/2 mx-auto h-6 w-6 flex justify-center items-center rounded-sm border border-gray-300"
                            :style="propertiesToHTMLStyle(announcementData.fields.close_button.block_properties)"
                            :class="isOnDrag ? 'cursor-grabbing' : 'cursor-grab'"
                        >
                            <FontAwesomeIcon icon='fal fa-times' class='text-gray-500' size="xs" fixed-width aria-hidden='true' />
                        </div>
                    
                    </div>

                    <div class="flex gap-x-2 items-center justify-between">
                        <!-- <div @click="() => toAbsoluteCenter(announcementData.fields.close_button.block_properties)" class="underline text-xs whitespace-nowrap text-gray-500 hover:text-blue-500 cursor-pointer">{{ trans('Make center') }}</div> -->
                        <div @click="() => toVerticalCenter(announcementData.fields.close_button.block_properties)" class="underline text-xs whitespace-nowrap text-gray-500 hover:text-blue-500 cursor-pointer">{{ trans('Vertical center') }}</div>
                        <div @click="() => toHorizontalCenter(announcementData.fields.close_button.block_properties)" class="underline text-xs whitespace-nowrap text-gray-500 hover:text-blue-500 cursor-pointer">{{ trans('Horizontal center') }}</div>
                    </div>
                </div>

                <Moveable
                    :target="_buttonClose"
                    :draggable="true"
                    :snapGap="true"
                    :throttleDrag="1"
                    :edgeDraggable="false"
                    :snappable="true"
                    :snapDirections="{top: true, left: true, bottom: true, right: true }"
                    :elementSnapDirections='{"top":true,"left":true,"bottom":true,"right":true,"center":true,"middle":true}'
                    :startDragRotate="0"
                    :throttleDragRotate="0"
                    @dragStart="() => isOnDrag = true"
                    @dragEnd="() => debounceSetIsOnDrag()"
                    @drag="(e) => onDrag(e, announcementData.fields.close_button.block_properties)"
                />
            </div>
        </Collapse>
    </div>

    <!-- <pre>{{ announcementData.container_properties }}</pre> -->

</template>