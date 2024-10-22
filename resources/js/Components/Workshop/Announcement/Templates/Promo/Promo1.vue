<script setup lang='ts'>
import Moveable from "vue3-moveable"
import { propertiesToHTMLStyle } from '@/Composables/usePropertyWorkshop'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faTimes } from '@fal'
import { library } from '@fortawesome/fontawesome-svg-core'
import { onMounted, ref } from "vue"
library.add(faTimes)
const props = defineProps<{
    announcementData: {
        fields: {

        }
        container_properties: {

        }
    }
    isEditable?: boolean
}>()

const _parentComponent = ref(null)
const _text_1 = ref(null)
const _buttonClose = ref(null)
const closeIcon = '<svg class="svg-inline--fa fa-times fa-fw" aria-hidden="true" focusable="false" data-prefix="fal" data-icon="times" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path class="" fill="currentColor" d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z"></path></svg>'


const onDrag = (e, block_properties) => {
    const parentWidth = _parentComponent.value?.clientWidth
    const parentHeight = _parentComponent.value?.clientHeight

    const percentageLeft = e.left / parentWidth * 100
    const percentageTop = e.top / parentHeight * 100
    // console.log('kokok', percentageLeft)

    // Update position based on the dragging
    block_properties.position.x = `${percentageLeft}%`
    block_properties.position.y = `${percentageTop}%`

    // console.log('111', block_properties.position)
    // console.log('qqq', e)
    // position.value.left += e.delta[0]
    // position.value.top += e.delta[1]
    // calculatePercentagePosition()
}

onMounted(() => {

})


</script>

<template>
    <div ref="_parentComponent" class="relative isolate flex items-center gap-x-6 bg-gray-50 px-6 py-2.5 sm:px-3.5 transition-all" :style="propertiesToHTMLStyle(announcementData.container_properties, {toRemove: ['position']})">
        <div ref="_text_1" class="-translate-x-1/2 -translate-y-1/2 text-sm leading-6 whitespace-nowrap" v-html="announcementData.fields.text_1.text" :style="propertiesToHTMLStyle(announcementData.fields.text_1.block_properties)">
            
        </div>

        <!-- <pre>{{ propertiesToHTMLStyle(announcementData.fields.text_1.block_properties) }}</pre> -->

        <Moveable
            v-if="isEditable"
            :target="_text_1"
            :draggable="true"
            :snapGap="true"
            :throttleDrag="1"
            :edgeDraggable="false"
            :snappable="true"
            :snapDirections="{top: true, left: true, bottom: true, right: true }"
            :elementSnapDirections='{"top":true,"left":true,"bottom":true,"right":true,"center":true,"middle":true}'
            :startDragRotate="0"
            :throttleDragRotate="0"
            @drag="(e) => onDrag(e, announcementData.fields.text_1.block_properties)"
        />
        
        <!-- Close Button -->
        <button
            ref="_buttonClose"
            type="button"
            class="flex flex-1 justify-end p-2 -translate-x-1/2 -translate-y-1/2"
            :style="propertiesToHTMLStyle(announcementData.fields.close_button.block_properties)"
        >
            <span class="sr-only">Dismiss</span>
            <span v-html="closeIcon"></span>
        </button>

        <Moveable
            v-if="isEditable"
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
            @drag="(e) => onDrag(e, announcementData.fields.close_button.block_properties)"
        />
    </div>

</template>