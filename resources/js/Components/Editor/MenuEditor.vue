<script setup lang="ts">
import ColorPicker from '@/Components/Utils/ColorPicker.vue'

import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { library } from "@fortawesome/fontawesome-svg-core"
import { faText, faUndoAlt, faRedoAlt } from '@far'
import { faHorizontalRule, faQuoteRight, faMarker } from '@fas'
import { faBold, faItalic, faUnderline, faStrikethrough, faAlignLeft, faUnlink, faAlignCenter, faAlignRight, faAlignJustify, faSubscript, faSuperscript, faEraser, faListUl, faListOl, faPaintBrushAlt, faTextHeight, faLink } from '@fal'
library.add(faBold, faQuoteRight, faMarker, faHorizontalRule, faItalic, faUnlink, faUnderline, faStrikethrough, faAlignLeft, faAlignCenter, faAlignRight, faAlignJustify, faSubscript, faSuperscript, faEraser, faListUl, faListOl, faUndoAlt, faRedoAlt, faPaintBrushAlt, faTextHeight, faLink, faText)


const props = withDefaults(defineProps<{
    editor: any,
    action: Object,
}>(), {

});


const onHeadingClick = (value: any) => {
    props.editor.chain().focus().toggleHeading(value).run()
}


</script>

<template>
    <div v-if="action.key == 'heading'" class="group relative inline-block">
        <div class="text-xs min-w-16 p-1 appearance-none rounded cursor-pointer border border-gray-200"
            :class="{ 'bg-slate-700 text-white font-bold': editor?.isActive('heading') }">
            Heading <span id="headingIndex"></span>
        </div>
        <div
            class="cursor-pointer overflow-hidden hidden group-hover:block absolute left-0 right-0 border border-gray-500 rounded bg-white z-[1]">
            <div v-for="index in 6" class="block py-1.5 px-3 text-center cursor-pointer hover:bg-gray-300"
                :class="{ 'bg-slate-700 text-white hover:bg-slate-700': editor?.isActive('heading', { level: index }) }"
                :style="{ fontSize: (20 - index) + 'px' }" role="button" @click="onHeadingClick(index)">
                H{{ index }}
            </div>
        </div>
    </div>

    <div v-else-if="action.key == 'fontSize'" class="group relative inline-block">
        <div class="flex items-center text-xs min-w-10 py-1 pl-1.5 pr-0 appearance-none rounded cursor-pointer border border-gray-500"
            :class="{ 'bg-slate-700 text-white font-bold': editor?.getAttributes('textStyle').fontSize }">
            <div id="tiptapfontsize" class="pr-1.5">
                <span class="hidden last:inline">Text size</span>
            </div>
            <div v-if="editor?.getAttributes('textStyle').fontSize"
                @click="editor?.chain().focus().unsetFontSize().run()" class="px-1">
                <FontAwesomeIcon icon='fal fa-times' class='' fixed-width aria-hidden='true' />
            </div>
        </div>
        <div
            class="w-min cursor-pointer overflow-hidden hidden group-hover:block absolute left-0 right-0 border border-gray-500 rounded bg-white z-[1]">
            <div v-for="fontsize in ['8', '9', '12', '14', '16', '20', '24', '28', '36', '44', '52', '64']"
                class="w-full block py-1.5 px-3 leading-none text-left cursor-pointer hover:bg-gray-300"
                :class="{ 'bg-slate-700 text-white hover:bg-slate-700': parseInt(editor?.getAttributes('textStyle').fontSize, 10) == fontsize }"
                @click="editor?.chain().focus().setFontSize(fontsize + 'px').run()" role="button">
                <div v-if="parseInt(editor?.getAttributes('textStyle').fontSize, 10) == fontsize" to="#tiptapfontsize">
                </div>
                {{ fontsize }}
            </div>
        </div>
    </div>

    <div v-else-if="action.key == 'color'" class=" flex justify-between items-center bg-white">
        <div class="flex items-center">
            <input type="color" class="w-5 h-5" @input="editor.chain().focus().setColor($event.target.value).run()"
                :value="editor.getAttributes('textStyle').color">
        </div>
    </div>

    <button v-else type="button" @click="action?.action" :class="{
        'bg-gray-200 rounded': editor?.isActive(action.active),
        '': !editor?.isActive(action.active)
    }" class="p-1">
        <span v-if="action.icon">
            <FontAwesomeIcon :icon='action.icon' />
        </span>
    </button>
</template>

<style scoped lang="scss">
.ToolbarContainer {
    background: #000000;
    border-bottom: 2px solid #999999;
    border-radius: 9px 9px 0 0;
    display: flex;
    align-items: center;
    height: 40px;
    margin: -20px -20px 20px -20px;
    position: sticky;
    top: 0;
    z-index: 1;
    padding: 0 20px;

    &.sticky {
        border-top: 2px solid transparent;
        box-shadow: 0px 3px 5px -3px rgba(#333333, 0.5);
    }
}

.Toolbar {
    display: flex;
    width: 100%;

    .icon {
        border-radius: 5px;
        width: 28px;
        height: 28px;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #fff;

        &.disabled {
            color: rgba(#ffffff, 0.5);
            pointer-events: none;
        }
    }

    .text {
        font-size: 20px;
        line-height: 1.4;
        margin-top: 2px;
    }

    .icon:hover {
        background: #3d3d3d;
        color: #5fccff;
        cursor: pointer;
    }

    .divider {
        border: none;
        border-left: 2px solid rgba(#fff, 0.4);
        margin: 2px 10px;
    }

    .icon+.icon {
        margin-left: 4px;
    }

    .rightItems {
        display: flex;
        align-items: center;
        margin-left: auto;
    }
}
</style>