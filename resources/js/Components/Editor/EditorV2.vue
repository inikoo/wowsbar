<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref, defineExpose } from "vue"
import { useEditor, EditorContent, BubbleMenu } from '@tiptap/vue-3'
/* import type DataTable from "@/models/table" */

import TiptapToolbarButton from "@/Components/Editor/TiptapToolbarButton.vue"
import TiptapToolbarGroup from "@/Components/Editor/TiptapToolbarGroup.vue"
import Paragraph from "@tiptap/extension-paragraph"
import Document from "@tiptap/extension-document"
import Text from "@tiptap/extension-text"
import History from "@tiptap/extension-history"
import Heading from "@tiptap/extension-heading"
import Bold from "@tiptap/extension-bold"
import Italic from "@tiptap/extension-italic"
import Underline from "@tiptap/extension-underline"
import Strike from "@tiptap/extension-strike"
import ListItem from "@tiptap/extension-list-item"
import BulletList from "@tiptap/extension-bullet-list"
import OrderedList from "@tiptap/extension-ordered-list"
import Link from "@tiptap/extension-link"
import TextAlign from '@tiptap/extension-text-align'
import { Blockquote } from "@tiptap/extension-blockquote"
import { HardBreak } from "@tiptap/extension-hard-break"
import { CharacterCount } from "@tiptap/extension-character-count"
import Dropcursor from "@tiptap/extension-dropcursor"
import { HorizontalRule } from "@tiptap/extension-horizontal-rule"
import Gapcursor from "@tiptap/extension-gapcursor"
import TextStyle from '@tiptap/extension-text-style'
import { Color } from '@tiptap/extension-color'
import FontSize from 'tiptap-extension-font-size'
import Highlight from '@tiptap/extension-highlight'
import PureColorPicker from '@/Components/Utils/ColorPicker.vue'
import ColorPicker from 'primevue/colorpicker';
import Popover from 'primevue/popover'
import Placeholder from '@tiptap/extension-placeholder'

import {
    faUndo,
    faRedo,
    faQuoteLeft,
    faBold,
    faH1,
    faH2,
    faH3,
    faItalic,
    faLink,
    faUnderline,
    faStrikethrough,
    faImage,
    faVideo,
    faMinus,
    faList,
    faListOl,
    faAlignLeft,
    faAlignCenter,
    faAlignRight,
    faFileVideo,
    faPaintBrushAlt,
    faText
} from "@far"

import { faTimes } from "@fal"
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faTimes)

import TiptapLinkDialog from "@/Components/Editor/TiptapLinkDialog.vue"
import TiptapVideoDialog from "@/Components/Editor/TiptapVideoDialog.vue"
import { trans } from "laravel-vue-i18n"


const props = withDefaults(defineProps<{
    modelValue: string,
    toogle?: string[],
    type?: string,
    editable?: boolean
    placeholder?: any | String
}>(), {
    editable: true,
    type: 'Bubble',
    placeholder: '',
    toogle: () => [
        'heading', 'fontSize', 'bold', 'italic', 'underline', 'bulletList',
        'orderedList', 'blockquote', 'divider', 'alignLeft', 'alignRight', 
        'alignCenter', 'undo', 'redo', 'highlight', 'color', 'clear',"link"
    ]
})

const emits = defineEmits<{
    (e: 'update:modelValue', value: string): void
    (e: 'onEditClick', value: any): void
}>()

const op = ref();
const _bubbleMenu = ref(null)
const showDialog = ref(false)
const contentResult = ref<string>()
const currentLinkInDialog = ref<Object | undefined>()
const showLinkDialog = ref<boolean>()

const editorInstance = useEditor({
    content: props.modelValue,
    editable: props.editable,
    editorProps: {
        attributes: {
            class: "blog",
        },
    },
    extensions: [
        Paragraph,
        Document,
        Text,
        History,
        Placeholder.configure({
            placeholder: props.placeholder || trans('Type something here'),
        }),
        Heading.configure({
            levels: [1, 2, 3],
        }),
        Bold,
        TextAlign.configure({
            types: ['heading', 'paragraph'],
        }),
        Italic,
        Underline,
        Strike,
        ListItem,
        BulletList,
        OrderedList,
        Highlight.configure({
            multicolor: true
        }),
        Link.configure({
            openOnClick: false,
            HTMLAttributes: {
                rel: null,
            },
        }),
        HardBreak,
        Blockquote,
        CharacterCount,
        Dropcursor.configure({
            width: 2,
            color: "#2563eb",
        }),
        HorizontalRule,
        Gapcursor,
        TextStyle,
        FontSize.configure({
            types: ['textStyle'],
        }),
        Color.configure({
            types: ['textStyle'],
        }),
    ],
    onUpdate: ({ editor }) => {
        contentResult.value = editor.getHTML()
        emits('update:modelValue', editor.getHTML())
    },
})


function openLinkDialog() {
    currentLinkInDialog.value = editorInstance.value?.getAttributes("link")
    showLinkDialog.value = true
    showDialog.value = true;
}

function updateLink(value?: string, target_data = '_parent') {
    if (!value) {
        editorInstance.value
            ?.chain()
            .focus()
            .extendMarkRange("link")
            .unsetLink()
            .run()
        return
    }

    editorInstance.value
        ?.chain()
        .focus()
        .extendMarkRange("link")
        .setLink({ href: value , target: target_data })
        .run()
}


onMounted(() => {
    setTimeout(() => (contentResult.value = editorInstance.value?.getHTML()), 250)
})

onBeforeUnmount(() => {
    editorInstance.value?.destroy()
})

const onEditorClick = () => {
    emits('onEditClick', editorInstance.value)
}

defineExpose({
    editor : editorInstance
})

const toggle = (event: any) => {
    op.value.toggle(event);
}


</script>

<template>
    <div v-if="editable" id="tiptap" class="divide-y divide-gray-400">
        <BubbleMenu ref="_bubbleMenu" :editor="editorInstance" :tippy-options="{ duration: 100 }"
            v-if="editorInstance && !showDialog">
            <section id="tiptap-toolbar"
                class="flex items-center bg-gray-100 rounded-xl border border-gray-300 divide-x divide-gray-400">
                <TiptapToolbarGroup>
                    <TiptapToolbarButton v-if="toogle.includes('undo')" label="Undo"
                        @click="editorInstance?.chain().focus().undo().run()"
                        :disabled="!editorInstance?.can().chain().focus().undo().run()">
                        <FontAwesomeIcon :icon="faRedo" class="h-5 w-5" />
                    </TiptapToolbarButton>
                    <TiptapToolbarButton v-if="toogle.includes('redo')" label="Redo"
                        @click="editorInstance?.chain().focus().redo().run()"
                        :disabled="!editorInstance?.can().chain().focus().redo().run()">
                        <FontAwesomeIcon :icon="faUndo" class="h-5 w-5" />
                    </TiptapToolbarButton>
                </TiptapToolbarGroup>
                <TiptapToolbarGroup>
                    <TiptapToolbarButton v-if="toogle.includes('heading')" label="Heading 1"
                        :is-active="editorInstance?.isActive('heading', { level: 1 })"
                        @click="editorInstance?.chain().focus().toggleHeading({ level: 1 }).run()"
                        class="toolbar-button">
                        <FontAwesomeIcon :icon="faH1" class="h-5 w-5" />
                    </TiptapToolbarButton>

                    <TiptapToolbarButton v-if="toogle.includes('heading')" label="Heading 2"
                        :is-active="editorInstance?.isActive('heading', { level: 2 })"
                        @click="editorInstance?.chain().focus().toggleHeading({ level: 2 }).run()"
                        class="toolbar-button">
                        <FontAwesomeIcon :icon="faH2" class="h-5 w-5" />
                    </TiptapToolbarButton>

                    <TiptapToolbarButton v-if="toogle.includes('heading')" label="Heading 3"
                        :is-active="editorInstance?.isActive('heading', { level: 3 })"
                        @click="editorInstance?.chain().focus().toggleHeading({ level: 3 }).run()"
                        class="toolbar-button">
                        <FontAwesomeIcon :icon="faH3" class="h-5 w-5" />
                    </TiptapToolbarButton>
                </TiptapToolbarGroup>

                <TiptapToolbarGroup>
                    <div class="group relative inline-block w-20">
                        <div
                            class="text-sm py-1 px-2 border rounded-md cursor-pointer bg-white border-gray-300 hover:border-gray-400 flex items-center justify-between transition">
                            <span id="tiptapfontsize" class="text-black">
                                {{ editorInstance?.getAttributes('textStyle').fontSize || 'Text size' }}
                            </span>
                            <FontAwesomeIcon v-if="editorInstance?.getAttributes('textStyle').fontSize"
                                @click="editorInstance?.chain().focus().unsetFontSize().run()" :icon="faTimes"
                                class="text-red-500 ml-2 cursor-pointer" aria-hidden="true" />
                        </div>

                        <div
                            class="w-min h-32 overflow-y-auto text-black cursor-pointer overflow-hidden hidden group-hover:block absolute left-0 right-0 border border-gray-500 rounded bg-white z-[1]">
                            <div v-for="fontsize in ['8', '9', '12', '14', '16', '20', '24', '28', '36', '44', '52', '64']"
                                :key="fontsize" class="px-4 py-2 text-left text-sm cursor-pointer hover:bg-gray-100"
                                :class="{ 'bg-indigo-600 text-white': parseInt(editorInstance?.getAttributes('textStyle').fontSize, 10) === parseInt(fontsize) }"
                                @click="editorInstance?.chain().focus().setFontSize(fontsize + 'px').run()">
                                {{ fontsize }}
                            </div>
                        </div>
                    </div>
                </TiptapToolbarGroup>


                <TiptapToolbarGroup>
                    <TiptapToolbarButton v-if="toogle.includes('bold')" label="Bold"
                        :is-active="editorInstance?.isActive('bold')"
                        @click="editorInstance?.chain().focus().toggleBold().run()">
                        <FontAwesomeIcon :icon="faBold" class="h-5 w-5" />
                    </TiptapToolbarButton>
                    <TiptapToolbarButton v-if="toogle.includes('italic')" label="Italic"
                        :is-active="editorInstance?.isActive('italic')"
                        @click="editorInstance?.chain().focus().toggleItalic().run()">
                        <FontAwesomeIcon :icon="faItalic" class="h-5 w-5" />
                    </TiptapToolbarButton>
                    <TiptapToolbarButton v-if="toogle.includes('underline')" label="Underline"
                        :is-active="editorInstance?.isActive('underline')"
                        @click="editorInstance?.chain().focus().toggleUnderline().run()">
                        <FontAwesomeIcon :icon="faUnderline" class="h-5 w-5" />
                    </TiptapToolbarButton>
                    <TiptapToolbarButton v-if="toogle.includes('strikethrough')" label="Strikethrough"
                        :is-active="editorInstance?.isActive('strike')"
                        @click="editorInstance?.chain().focus().toggleStrike().run()">
                        <FontAwesomeIcon :icon="faStrikethrough" class="h-5 w-5" />
                    </TiptapToolbarButton>
                </TiptapToolbarGroup>

                <TiptapToolbarGroup>
                    <TiptapToolbarButton v-if="toogle.includes('bulletList')" label="Bullet List"
                        :is-active="editorInstance?.isActive('bulletList')"
                        @click="editorInstance?.chain().focus().toggleBulletList().run()">
                        <FontAwesomeIcon :icon="faList" class="h-5 w-5" />
                    </TiptapToolbarButton>
                    <TiptapToolbarButton v-if="toogle.includes('orderedList')" label="Ordered List"
                        :is-active="editorInstance?.isActive('orderedList')"
                        @click="editorInstance?.chain().focus().toggleOrderedList().run()">
                        <FontAwesomeIcon :icon="faListOl" class="h-5 w-5" />
                    </TiptapToolbarButton>
                </TiptapToolbarGroup>

                <TiptapToolbarGroup>
                    <TiptapToolbarButton v-if="toogle.includes('alignLeft')" label="Align Left"
                        :is-active="editorInstance?.isActive('textAlign', 'left')"
                        @click="editorInstance?.chain().focus().setTextAlign('left').run()">
                        <FontAwesomeIcon :icon="faAlignLeft" class="h-5 w-5" />
                    </TiptapToolbarButton>
                    <TiptapToolbarButton v-if="toogle.includes('alignCenter')" label="Align Center"
                        :is-active="editorInstance?.isActive('textAlign', 'center')"
                        @click="editorInstance?.chain().focus().setTextAlign('center').run()">
                        <FontAwesomeIcon :icon="faAlignCenter" class="h-5 w-5" />
                    </TiptapToolbarButton>
                    <TiptapToolbarButton v-if="toogle.includes('alignRight')" label="Align Right"
                        :is-active="editorInstance?.isActive('textAlign', 'right')"
                        @click="editorInstance?.chain().focus().setTextAlign('right').run()">
                        <FontAwesomeIcon :icon="faAlignRight" class="h-5 w-5" />
                    </TiptapToolbarButton>
                </TiptapToolbarGroup>

                <TiptapToolbarGroup>
                    <TiptapToolbarButton v-if="toogle.includes('link')" label="Link" @click="openLinkDialog"
                        :is-active="editorInstance?.isActive('link')">
                        <FontAwesomeIcon :icon="faLink" class="h-5 w-5" />
                    </TiptapToolbarButton>
                    <TiptapToolbarButton v-if="toogle.includes('blockquote')" label="Blockquote"
                        :is-active="editorInstance?.isActive('blockquote')"
                        @click="editorInstance?.chain().focus().toggleBlockquote().run()">
                        <FontAwesomeIcon :icon="faQuoteLeft" class="h-5 w-5" />
                    </TiptapToolbarButton>
                    <TiptapToolbarButton v-if="toogle.includes('divider')"
                        @click="editorInstance?.chain().focus().setHorizontalRule().run()" label="Horizontal Line">
                        <FontAwesomeIcon :icon="faMinus" class="h-5 w-5" />
                    </TiptapToolbarButton>
                </TiptapToolbarGroup>
            </section>
        </BubbleMenu>

        <div class="flex flex-col">
            <slot name="editor-content" :editor="editorInstance">
                <EditorContent @click="onEditorClick" :editor="editorInstance" />
            </slot>
        </div>

        <TiptapLinkDialog v-if="showLinkDialog" :show="showLinkDialog" :current-url="currentLinkInDialog"
            @close="() => { showLinkDialog = false; showDialog = false; }" @update="updateLink" />
    </div>

    <div v-else id="blockTextContent">
        <div v-html="modelValue" />
    </div>
</template>


<style scoped>
:deep(.tippy-box) {
    min-width: 10px !important;
    max-width: max-content !important
}

:deep(.font-inter) {
    font-family: "Inter", sans-serif;
}

:deep(.ProseMirror) {
    @apply focus:outline-none px-0 py-0 min-h-[10px] relative;
}

:deep(.blog) {
    @apply flex flex-col space-y-4;
}

:deep(.blog h1) {
    @apply text-4xl font-semibold;
}

:deep(.blog h2) {
    @apply text-3xl font-semibold;
}

:deep(.blog h3) {
    @apply text-2xl font-semibold;
}

:deep(.blog ol),
:deep(.blog ul) {
    @apply ml-8 list-outside mt-2;
}

:deep(.blog ol) {
    @apply list-decimal;
}

:deep(.blog ul) {
    @apply list-disc;
}

:deep(.blog ol li),
:deep(.blog ul li) {
    @apply mt-2 first:mt-0;
}

:deep(.blog blockquote) {
    @apply italic border-l-4 border-gray-300 p-4 py-2 ml-6 mt-6 mb-2 bg-gray-50;
}

/* :deep(.blog a) {
    @apply hover:underline text-blue-600 cursor-pointer;
} */

:deep(.blog hr) {
    @apply border-gray-400 my-4;
}

:deep(.blog table) {
    @apply border border-gray-400 table-fixed border-collapse w-full my-4;
}

:deep(.blog table th),
:deep(.blog table td) {
    @apply border border-gray-400 py-2 px-4 text-left relative;
}

:deep(.blog table th) {
    @apply bg-blue-100 font-semibold;
}

:deep(.blog .tableWrapper) {
    @apply overflow-auto;
}

:deep(.ProseMirror iframe) {
    @apply w-full h-auto max-w-[480px] min-h-[320px] aspect-video mr-6;
}

:deep(.ProseMirror img) {
    @apply mr-6 w-full max-w-[480px] max-h-[320px] object-contain object-center;
}

:deep(.ProseMirror img.ProseMirror-selectednode),
:deep(.ProseMirror div[data-youtube-video]) {
    @apply cursor-move;
}

:deep(.ProseMirror .selectedCell:after) {
    @apply z-[2] absolute inset-0 bg-gray-400/30 pointer-events-none content-[''];
}

:deep(.ProseMirror-gapcursor) {
    @apply hidden pointer-events-none relative;
    @apply after:content-[''] after:block after:relative after:h-5 after:border-l after:border-t-0 after:border-black after:mt-1;
}

:deep(.ProseMirror-gapcursor:after) {
    animation: ProseMirror-cursor-blink 1.1s steps(2, start) infinite;
}

@keyframes ProseMirror-cursor-blink {
    to {
        visibility: hidden;
    }
}

:deep(.ProseMirror-focused .ProseMirror-gapcursor) {
    @apply block;
}
</style>
