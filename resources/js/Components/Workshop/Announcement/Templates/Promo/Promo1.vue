<script setup lang='ts'>
import Moveable from "vue3-moveable";
import { propertiesToHTMLStyle } from '@/Composables/usePropertyWorkshop'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faTimes } from '@fal'
import { library } from '@fortawesome/fontawesome-svg-core'
import { onMounted, ref } from "vue";
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

const onDrag = e => {
    // const aaa = {
    //     left: `${e.left / e.target.offsetWidth * 100}%`,
    //     top: `${e.top / e.target.offsetHeight * 100}%`
    // }
    // console.log(e, `left: ${e.left}`, `offwidth: ${e.target.offsetWidth}`)
    // console.log(calculatePercentagePosition(e, _parentComponent.value))
    e.target.style.transform = e.transform;
};
const _parentComponent = ref(null)
const _text_1 = ref(null)
const closeIcon = '<svg class="svg-inline--fa fa-times fa-fw" aria-hidden="true" focusable="false" data-prefix="fal" data-icon="times" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path class="" fill="currentColor" d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z"></path></svg>'

function calculatePercentagePosition(moveableComponent, _refParent) {
    // const percentageX = (moveableComponent.right / _refParent.offsetWidth) * 100
    // console.log('per', `${percentageX.toFixed(2)}%`)

    // // Get the parent element's width and height
    // const parentWidth = _refParent.offsetWidth;
    // const parentHeight = _refParent.offsetHeight;

    // // Get the original top and left position of component AAA
    // const originalTop = moveableComponent.offsetTop;
    // const originalLeft = moveableComponent.offsetLeft;

    // // Calculate the new absolute position by adding the changes (changeX and changeY)
    // const newLeft = originalLeft + changeX;
    // const newTop = originalTop + changeY;

    // // Calculate the percentage relative to the parent's dimensions
    // const leftPercentage = (newLeft / parentWidth) * 100;
    // const topPercentage = (newTop / parentHeight) * 100;

    // return {
    //     leftPercentage: leftPercentage,
    //     topPercentage: topPercentage
    // };
}

onMounted(() => {
    console.log('aaa', _parentComponent.value)
})

const aaa ={"text_1": {"text": "<blockquote><p><span style=\"font-size: 52px\">GeneriCon </span><strong>2023 is</strong> on Jun<strong>e 7 – 9 in Denver. Get your ticket&nbsp;→</strong></p></blockquote>", "block_properties": {"position": {"x": "70%", "y": "30%", "type": "absolute"}}}, "text_2": {"text": "<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed laoreet nisi at elit venenatis fringilla. Cras ut semper quam, sit.</p>", "block_properties": {"position": {"x": "20%", "y": "30%", "type": "absolute"}}}, "button_1": {"url": "https://example.com", "label": "Click me", "width": "full", "border": {"top": {"value": 0}, "left": {"value": 0}, "unit": "px", "color": "rgba(20, 20, 20, 1)", "right": {"value": 0}, "bottom": {"value": 0}, "rounded": {"unit": "px", "topleft": {"value": 0}, "topright": {"value": 0}, "bottomleft": {"value": 0}, "bottomright": {"value": 0}}}, "target": "_blank", "background": {"type": "color", "color": "rgba(250, 250, 250, 1)", "image": {"original": null}}, "text_color": "rgba(255, 255, 255, 1)", "block_properties": {"position": {"x": "20%", "y": "30%", "type": "absolute"}}}, "close_button": {"size": "0.5", "text_color": "rgba(0, 0, 0, 0.5)", "block_properties": {"position": {"x": "10%", "y": "50%", "type": "absolute"}}}}
</script>

<template>
    <div ref="_parentComponent" class="relative isolate flex items-center gap-x-6 bg-gray-50 px-6 py-2.5 sm:px-3.5" :style="propertiesToHTMLStyle(announcementData.container_properties, {toRemove: ['position']})">
        <div ref="_text_1" class="mx-auto text-sm leading-6" v-html="announcementData.fields.text_1.text" :style="propertiesToHTMLStyle(announcementData.fields.text_1.block_properties)">
            
        </div>

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
            @drag="onDrag"
        />
        
        <!-- Close Button -->
        <button type="button" class="asdzxc flex flex-1 justify-end p-3"
            :style="propertiesToHTMLStyle(announcementData.fields.close_button.block_properties)">
            <span class="sr-only">Dismiss</span>
            <span v-html="closeIcon"></span>
        </button>
    </div>

</template>