<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref, defineExpose } from "vue"
import { useEditor, EditorContent, BubbleMenu } from '@tiptap/vue-3'
/* import type DataTable from "@/models/table" */
import Select from 'primevue/select'
import { useFontFamilyList } from '@/Composables/useFont'

import TiptapToolbarButton from "@/Components/Forms/Fields/BubleTextEditor/TiptapToolbarButton.vue"
import TiptapToolbarGroup from "@/Components/Forms/Fields/BubleTextEditor/TiptapToolbarGroup.vue"
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
import { Youtube } from "@tiptap/extension-youtube"
import Dropcursor from "@tiptap/extension-dropcursor"
import { HorizontalRule } from "@tiptap/extension-horizontal-rule"
import { Table } from "@tiptap/extension-table"
import { TableHeader } from "@tiptap/extension-table-header"
import { TableRow } from "@tiptap/extension-table-row"
import { TableCell } from "@tiptap/extension-table-cell"
import Gapcursor from "@tiptap/extension-gapcursor"
import Image from "@tiptap/extension-image"
import TextStyle from '@tiptap/extension-text-style'
import { Color } from '@tiptap/extension-color'
import FontSize from 'tiptap-extension-font-size'
import FontFamily from '@tiptap/extension-font-family'
/* import ColorPicker from '@/Components/CMS/Fields/ColorPicker.vue' */
import ColorPicker from 'primevue/colorpicker';
import suggestion from './Variables/suggestion'
import Dialog from 'primevue/dialog';
import Highlight from '@tiptap/extension-highlight'

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
    faText,
    faTextSize,
    faDraftingCompass,
    faExternalLink,
    faTimesCircle
} from "@far"

import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"

import TiptapLinkCustomDialog from "@/Components/Forms/Fields/BubleTextEditor/TiptapCustomLinkDialog.vue"
import TiptapLinkDialog from "@/Components/Editor/TiptapLinkDialog.vue"
import TiptapVideoDialog from "@/Components/Forms/Fields/BubleTextEditor/TiptapVideoDialog.vue"
/* import TiptapTableDialog from "@/Components/Forms/Fields/BubleTextEditor/TiptapTableDialog.vue" */
import TiptapImageDialog from "@/Components/Forms/Fields/BubleTextEditor/TiptapImageDialog.vue"
import { Plugin } from "prosemirror-state"
import Variabel from "./Variables/Variables"
import CustomLink from "./CustomLink/CustomLink.vue"
import { trans } from "laravel-vue-i18n"
import { routeType } from "@/types/route"
import { faTint } from "@fas"

const props = withDefaults(defineProps<{
    modelValue: string,
    toogle?: string[],
    type?: string,
    editable?: boolean
    placeholder?: any | String
    uploadImageRoute? : routeType
}>(), {
    editable: true,
    type: 'Bubble',
    placeholder: '',
    toogle: () => [
        'heading', 'fontSize', 'bold', 'italic', 'underline', 'bulletList', 'query', "fontFamily",
        'orderedList', 'blockquote', 'divider', 'alignLeft', 'alignRight', "link",
        'alignCenter', 'undo', 'redo', 'highlight', 'color', 'clear', "image", "video"
    ]
})

const emits = defineEmits<{
    (e: 'update:modelValue', value: string): void
    (e: 'onEditClick', value: any): void
}>()

const _bubbleMenu = ref(null)
const showDialog = ref(false)
const contentResult = ref<string>()
const currentLinkInDialog = ref<string | undefined>()
const showLinkDialogCustom = ref<boolean>()
const showAddYoutubeDialog = ref<boolean>(false)
const showAddTableDialog = ref<boolean>(false)
const showAddImageDialog = ref<boolean>(false)
const showLinkDialog = ref<boolean>()
const CustomLinkConfirm = ref(false)
const attrsCustomLink = ref<Object>(null)

const editorInstance = useEditor({
    content: props.modelValue,
    editable: props.editable,
    editorProps: {
        attributes: {
            class: "editor-class",
        },
    },
    extensions: [
        Paragraph,
        Document,
        Text,
        History,
        FontFamily.configure({
            types: ['textStyle'],
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
        Link.configure({
            openOnClick: false,
            HTMLAttributes: {
                rel: null,
            },
        }),
        HardBreak.extend({
            addKeyboardShortcuts() {
                return {
                    'Mod-Enter': () => this.editor.commands.setHardBreak(),
                    'Shift-Enter': () => this.editor.commands.setHardBreak(),
                };
            },
        }),
        Blockquote,
        CharacterCount,
        Youtube,
        Dropcursor.configure({
            width: 2,
            color: "#2563eb",
        }),
        HorizontalRule,
        Table.configure({
            resizable: false,
            allowTableNodeSelection: true,
        }),
        TableRow,
        TableHeader,
        TableCell,
        Gapcursor,
        Image,
        TextStyle,
        FontSize.configure({
            types: ['textStyle'],
        }),
        Color.configure({
            types: ['textStyle'],
        }),
        Variabel.configure({
          HTMLAttributes: {
            class: 'mention',
          },
          suggestion,
        }),
        Highlight.configure({
            multicolor: true
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




function insertImage(url: string) {
    editorInstance.value?.chain().focus().setImage({ src: url }).run()
}

function insertYoutubeVideo(url: string) {
    editorInstance.value?.commands.setYoutubeVideo({
        src: url,
        width: 400,
        height: 300,
    })
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
    editor: editorInstance
})


const setVariabel = (value) => {
    // console.log('dfsdfsfd',value)
    const content = `<span class="mention" data-type="mention" data-id="username" contenteditable="false">{{ ${value} }}</span>`;
    editorInstance.value?.chain().focus().insertContent(content).run();
};


const irisVariablesList = [
    {
        label: trans("Name"),
        value: "{{ name }}",
    },
    {
        label: trans("Username"),
        value: "{{ username }}",
    },
    {
        label: trans("Email"),
        value: "{{ email }}",
    },
    {
        label: trans("Favourites count"),
        value: "{{ favourites_count }}",
    },
    {
        label: trans("Cart count"),
        value: "{{ cart_count }}",
    },
    {
        label: trans("Cart amount"),
        value: "{{ cart_amount }}",
    },
]

// console.log(editorInstance)
</script>

<template>
    <div id="tiptap" class="divide-y divide-gray-400">
        <BubbleMenu ref="_bubbleMenu" :editor="editorInstance" :tippy-options="{ duration: 100 }"
            v-if="editorInstance && !showDialog">
            <div class="bg-gray-100 rounded-xl border border-gray-300 divide-y divide-gray-400 isolate">
                <section id="tiptap-toolbar" class="flex items-center divide-x divide-gray-400">
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

                    <!-- Section: Heading 1,2,3 -->
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
                    
                    <!-- Section: Font size -->
                    <div class="my-1.5 inline-flex flex-row flex-wrap items-center space-x-1 px-2">
                        <div :class="[
                            'inline-flex h-12 shrink-0 flex-row items-center justify-center rounded-md disabled:bg-transparent disabled:text-gray-300',
                            'text-gray-600 hover:bg-blue-50',
                        ]" type="button" v-tooltip="'font size'" :aria-label="'font size'">
                            <div class="group relative">
                                <div
                                    class="text-sm py-1 px-2 cursor-pointer hover:border-gray-400 flex items-center justify-between transition h-8">
                                    <FontAwesomeIcon v-if="!editorInstance?.getAttributes('textStyle').fontSize"
                                        :icon="faTextSize" class="h-5 w-5" />
                                    <div v-else id="tiptapfontsize" class="text-gray-600 text-sm font-semibold h-5">
                                        {{ editorInstance?.getAttributes('textStyle').fontSize }}
                                    </div>
                                    <FontAwesomeIcon v-if="editorInstance?.getAttributes('textStyle').fontSize"
                                        @click="editorInstance?.chain().focus().unsetFontSize().run()"
                                        icon="fal fa-times" class="text-red-500 ml-2 cursor-pointer"
                                        aria-hidden="true" />
                                </div>
                                <div
                                    class="min-w-16 w-min h-48 overflow-y-auto text-gray-800 cursor-pointer overflow-hidden hidden group-hover:block absolute left-0 right-0 border border-gray-500 rounded bg-white z-[1]">
                                    <div v-for="fontsize in ['8', '9', '12', '14', '16', '20', '24', '28', '36', '44', '52', '64']"
                                        :key="fontsize"
                                        class="px-4 py-2 text-left text-sm cursor-pointer "
                                        :class="[parseInt(editorInstance?.getAttributes('textStyle').fontSize, 10) === parseInt(fontsize) ? 'bg-indigo-600 text-white' : 'hover:bg-gray-100' ]"
                                        @click="editorInstance?.chain().focus().setFontSize(fontsize + 'px').run()">
                                        {{ fontsize }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

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

                       <!--  <TiptapToolbarButton v-if="toogle.includes('color')" label="Text Color">
                            <ColorPicker
                                v-model="editorInstance.getAttributes('textStyle').color"
                                :baseZIndex="9999"
                                @update:model-value="color => editorInstance?.chain().focus().setColor(`#${color}`).run()"
                            />
                        </TiptapToolbarButton> -->

                        <TiptapToolbarButton v-if="toogle.includes('color')" label="Text Color">
                            <div class="relative w-8 h-8 ">
                                <!-- Color Input -->
                                <input type="color" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                    @input="editorInstance?.chain().focus().setColor($event.target.value).run()"
                                    :value="editorInstance.getAttributes('textStyle').color"
                                />
                                <!-- Icon -->
                                <div class="flex items-center justify-center w-full h-full border border-gray-200 shadow rounded"
                                    :style="{ color: editorInstance.getAttributes('textStyle').color || 'gray'}"
                                > 
                                    <FontAwesomeIcon :icon="faTint" />
                                </div>
                            </div>
                        </TiptapToolbarButton>

                        <TiptapToolbarButton v-if="toogle.includes('highlight')" label="Text highlight">
                            <div class="relative w-8 h-8">
                                <!-- Color Input -->
                                <input type="color" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                    @input="editorInstance.chain().focus().setHighlight({ color: $event.target.value }).run()"
                                    :value="editorInstance.getAttributes('highlight').color" />
                                <!-- Icon -->
                                <div class="flex items-center justify-center w-full h-full border border-gray-200 shadow rounded"
                                    :style="{ backgroundColor: editorInstance?.getAttributes('highlight').color}"
                                > 
                                    <FontAwesomeIcon :icon="faPaintBrushAlt" />
                                </div>
                            </div>

                        </TiptapToolbarButton>
                    </TiptapToolbarGroup>
                    
                    <TiptapToolbarGroup v-if="toogle.includes('bulletList') || toogle.includes('orderedList')">
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

                     <!--    <TiptapToolbarButton v-if="toogle.includes('link')" label="Link Internal & External"
                            @click="openLinkDialogCustom" :is-active="editorInstance?.isActive('link')">
                            <FontAwesomeIcon :icon="faLink" class="h-5 w-5" />
                        </TiptapToolbarButton> -->

                        <TiptapToolbarButton v-if="toogle.includes('image')" label="Image"
                            @click="()=>{showAddImageDialog = true, showDialog = true }">
                            <FontAwesomeIcon :icon="faImage" class="h-5 w-5" />
                        </TiptapToolbarButton>

                        <TiptapToolbarButton v-if="toogle.includes('video')" label="Youtube Video"
                            @click="() => { showAddYoutubeDialog = true, showDialog = true }">
                            <FontAwesomeIcon :icon="faFileVideo" class="h-5 w-5" />
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

                <!-- 2nd row -->
                <section id="tiptap-toolbar" class="py-1 px-2 flex items-center divide-x divide-gray-400 gap-2">
                    <Select v-if="toogle.includes('query')"
                        @change="(e) => setVariabel(e.value.value)"
                        :options="irisVariablesList" optionLabel="label" size="small"
                        :placeholder="trans('Select a variable to put')" class="w-full md:w-56" />

                    <div class="my-1.5 inline-flex flex-row flex-wrap items-center space-x-1 px-2">
                        <div :class="[
                            'inline-flex h-8 shrink-0 flex-row items-center justify-center rounded-md disabled:bg-transparent disabled:text-gray-300',
                            'text-gray-600 hover:bg-blue-50',
                        ]" type="button" v-tooltip="'font Family'" :aria-label="'font family'">
                            <div class="group relative">
                                <div
                                    class="text-sm py-1 px-2 cursor-pointer hover:border-gray-400 flex items-center justify-between transition h-8 bg-white border rounded">
                                    <div v-if="!editorInstance?.getAttributes('textStyle').fontFamily"
                                        id="tiptapfontsize" class="text-gray-400 text-sm font-semibold h-5 px-1">
                                        {{ trans("Select font family") }}
                                    </div>
                                    <div v-else id="tiptapfontsize" class="text-gray-600 text-sm font-semibold h-5">
                                        {{ useFontFamilyList.find(font => font.value === editorInstance?.getAttributes('textStyle').fontFamily)?.label || editorInstance?.getAttributes('textStyle').fontFamily }}
                                    </div>
                                    
                                    <FontAwesomeIcon v-if="editorInstance?.getAttributes('textStyle').fontFamily"
                                        @click="editorInstance?.chain().focus().unsetFontFamily().run()"
                                        icon="fal fa-times" class="text-red-500 ml-2 cursor-pointer"
                                        aria-hidden="true" />
                                </div>
                                <div
                                    class="min-w-16 w-min h-48 overflow-y-auto text-gray-800 cursor-pointer overflow-hidden hidden group-hover:block absolute left-0 right-0 border border-gray-500 rounded bg-white z-[1]">
                                    <div v-for="font in useFontFamilyList"
                                        :key="font.value"
                                        class="px-4 py-2 text-left text-sm cursor-pointer hover:bg-gray-100"
                                        :style="{ fontFamily: font.value }"
                                        @click="editorInstance?.chain().focus().setFontFamily(font.value).run()">
                                        {{ font.label }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </section>
            </div>
        </BubbleMenu>

        <div class="flex flex-col">
            <slot name="editor-content" :editor="editorInstance">
                <EditorContent @click="onEditorClick" :editor="editorInstance" />
            </slot>
        </div>

        <TiptapImageDialog 
            v-if="showAddImageDialog" 
            :show="showAddImageDialog" 
            @close="()=>{showAddImageDialog = false, showDialog = false}"
            @insert="insertImage" 
            :uploadImageRoute="uploadImageRoute"
        />
        <TiptapLinkDialog v-if="showLinkDialog" :show="showLinkDialog" :current-url="currentLinkInDialog"
            @close="() => { showLinkDialog = false; showDialog = false; }" @update="updateLink" />
        <TiptapVideoDialog v-if="showAddYoutubeDialog" :show="showAddYoutubeDialog" @insert="insertYoutubeVideo"
            @close="() => { showAddYoutubeDialog = false; showDialog = false; }" />


        <Dialog v-model:visible="CustomLinkConfirm" :style="{ width: '25rem' }" modal :closable="false"
            :dismissableMask="true" :showHeader="false">
            <div class="pt-5">
                <ul class="list-none p-0">
                    <li class="mb-2">
                        <a :href="attrsCustomLink?.workshop" target="_blank"
                            class="block px-4 py-2 bg-blue-500 text-white rounded-lg text-center hover:bg-blue-600 transition">
                            <FontAwesomeIcon :icon="faDraftingCompass" /> Go to Workshop
                        </a>
                    </li>
                    <li>
                        <a :href="attrsCustomLink?.href" target="_blank"
                            class="block px-4 py-2 bg-blue-500 text-white rounded-lg text-center hover:bg-blue-600 transition">
                            <FontAwesomeIcon :icon="faExternalLink" /> Go to Page
                        </a>
                    </li>
                </ul>
            </div>
        </Dialog>
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

.editor-class p {
    display: block;
    margin-block-start: 0em;
    margin-block-end: 0em;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
    unicode-bidi: isolate;
}

:deep(.ProseMirror) {
    @apply focus:outline-none px-0 py-0 min-h-[10px] relative;
}

:deep(.editor-class) {
    @apply flex flex-col;
}

/* :deep(.editor-class p) {
    @apply leading-4 mb-0 mt-0 mr-0 ml-0
} */

:deep(.editor-class p) {
    display: block;
    margin-block-start: 0em;
    margin-block-end: 0em;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
    unicode-bidi: isolate;
}

:deep(.editor-class h1) {
    @apply text-4xl font-semibold;
}

:deep(.editor-class h2) {
    @apply text-3xl font-semibold mt-0 mb-0 mr-0 ml-0;
}

:deep(.editor-class h3) {
    @apply text-2xl font-semibold;
}

:deep(.editor-class ol),
:deep(.editor-class ul) {
    @apply ml-8 list-outside mt-2;
}

:deep(.editor-class ol) {
    @apply list-decimal;
}

:deep(.editor-class ul) {
    @apply list-disc;
}

:deep(.editor-class ol li),
:deep(.editor-class ul li) {
    @apply mt-2 first:mt-0;
}

:deep(.editor-class blockquote) {
    @apply italic border-l-4 border-gray-300 p-4 py-2 ml-6 mt-6 mb-2 bg-gray-50;
}

:deep(.editor-class a) {
    @apply hover:underline text-blue-600 cursor-pointer;
}

:deep(.editor-class hr) {
    @apply border-gray-400 my-4;
}

:deep(.editor-class table) {
    @apply border border-gray-400 table-fixed border-collapse w-full my-4;
}

:deep(.editor-class table th),
:deep(.editor-class table td) {
    @apply border border-gray-400 py-2 px-4 text-left relative;
}

:deep(.editor-class table th) {
    @apply bg-blue-100 font-semibold;
}

:deep(.editor-class .tableWrapper) {
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

:deep(.mention) {
    background-color: #F6F2FF;
    border-radius: 0.4rem;
    box-decoration-break: clone;
    color: #6A00F5;
    padding: 0.1rem 0.3rem;
  }

/* 
:deep(.ProseMirror > p > br:first-child:last-child) {
  display: none;
} */


@keyframes ProseMirror-cursor-blink {
    to {
        visibility: hidden;
    }
}

:deep(.ProseMirror-focused .ProseMirror-gapcursor) {
    @apply block;
}
</style>
