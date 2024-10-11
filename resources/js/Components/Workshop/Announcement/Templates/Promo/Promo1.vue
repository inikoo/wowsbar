<script setup lang='ts'>
import Moveable from "vue3-moveable";
import { propertiesToHTMLStyle } from '@/Composables/usePropertyWorkshop'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faTimes } from '@fal'
import { library } from '@fortawesome/fontawesome-svg-core'
import { ref } from "vue";
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
    console.log('aaaaaaa', e)
    e.target.style.transform = e.transform;
};
const _aaaw = ref(null)
const closeIcon = '<svg class="svg-inline--fa fa-times fa-fw" aria-hidden="true" focusable="false" data-prefix="fal" data-icon="times" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path class="" fill="currentColor" d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z"></path></svg>'
</script>

<template>
    <div class="relative isolate flex items-center gap-x-6 bg-gray-50 px-6 py-2.5 sm:px-3.5" :style="propertiesToHTMLStyle(announcementData.container_properties)">
        <div ref="_aaaw" class="mx-auto text-sm leading-6" v-html="announcementData.fields.text_1.text">
            
        </div>

        <Moveable
            v-if="isEditable"
            :target="_aaaw"
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
        <button type="button" class="absolute flex flex-1 justify-end p-3 -translate-y-1/4 -translate-x-1/4" :style="{
            top: announcementData.fields.close_button.position_top + '%',
            left: announcementData.fields.close_button.position_left + '%'
        }">
            <span class="sr-only">Dismiss</span>
            <span v-html="closeIcon"></span>
        </button>
    </div>

</template>