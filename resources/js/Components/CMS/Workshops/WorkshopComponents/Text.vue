<script setup lang="ts">
import { ref, watch, onBeforeUnmount, Ref } from "vue"
import Multiselect from "@vueform/multiselect"
import { lowerCase, snakeCase } from 'lodash'

import { FontSize } from '@/Composables/useTiptapFontSize'

import StarterKit from "@tiptap/starter-kit"
import Placeholder from "@tiptap/extension-placeholder"
import Underline from "@tiptap/extension-underline"
import CharacterCount from "@tiptap/extension-character-count"
import { Editor, EditorContent } from "@tiptap/vue-3"
import { Color } from '@tiptap/extension-color'
import TextStyle from '@tiptap/extension-text-style'
import Highlight from '@tiptap/extension-highlight'
import FontFamily from '@tiptap/extension-font-family'
import TextAlign from '@tiptap/extension-text-align'

import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { library } from "@fortawesome/fontawesome-svg-core"
import { faAlignLeft, faAlignCenter, faAlignRight } from '@/../private/pro-light-svg-icons'
import { faBold, faItalic, faUnderline, faTrashAlt, faListUl, faListOl, faUndo, faFont, faRedo, faFillDrip } from "@/../private/pro-regular-svg-icons"
library.add(faBold, faItalic, faUnderline, faTrashAlt, faListUl, faListOl, faUndo, faFont, faRedo, faFillDrip, faAlignLeft, faAlignCenter, faAlignRight)

const props = defineProps(["modelValue", "showStats", "placeholder", "class"])
const emit = defineEmits<{
    (e: 'update:modelValue'): string
}>()

const editor: Ref<any> = ref(false)

const fontOptions = [
    "Arial",
    "Comfortaa",
    "Lobster",
    "Laila",
    "Inter",
    "Port Lligat Slab",
    "Playfair",
    "Quicksand",
    "Times New Roman",
    "Yatra One"
]

const textAlignOptions = [
    {
        "label": "Align left",
        "value": "left",
        "icon": "fal fa-align-left"
    },
    {
        "label": "Align center",
        "value": "center",
        "icon": "fal fa-align-center"
    },
    {
        "label": "Align right",
        "value": "right",
        "icon": "fal fa-align-right"
    }
]

const fontSizeOptions = [
    {
        "name": '12',
        "value": '12px'
    },
    {
        "name": '16',
        "value": '16px'
    },
    {
        "name": '20',
        "value": '20px'
    },
    {
        "name": '24',
        "value": '24px'
    },
    {
        "name": '28',
        "value": '28px'
    },
    {
        "name": '36',
        "value": '36px'
    },
    {
        "name": '44',
        "value": '44px'
    },
    {
        "name": '52',
        "value": '52px'
    },
]

// Handle v-model
// watch(props.modelValue,
//     (newValue) => {
//         const isSame = editor.value.getHTML() === newValue

//         if (isSame) {
//             return
//         }

//         editor.commands.setContent(newValue, false)
//     }
// )

// Declare Tiptap editor
editor.value = new Editor({
    editorProps: {
        attributes: {
            class: "focus:outline-none",
        },
    },
    extensions: [
        StarterKit,
        CharacterCount.configure({
            limit: null,
        }),
        Placeholder.configure({
            placeholder: props.placeholder,
        }),
        Underline,
        TextStyle,
        FontSize,
        Color.configure({
            types: ['textStyle'],
        }),
        FontFamily.configure({
            types: ['textStyle'],
        }),
        Highlight.configure({ multicolor: true }),
        TextAlign.configure({
            types: ['heading', 'paragraph'],
        }),
    ],
    content: props.modelValue,
    onUpdate: () => {
        emit("update:modelValue", editor.value.getHTML())
    },
})

// Destroy editor on unmount
onBeforeUnmount(() => {
    editor.value.destroy()
})

// Declare font family
const selectedFontFamily = ref('Comfortaa')
watch(selectedFontFamily, () => {
    editor.value.chain().focus().setFontFamily(selectedFontFamily.value).run()
})

// Declare & watch Font Size
const selectedFontSize = ref('16px')
watch(selectedFontSize, () => {
    editor.value.chain().focus().setFontSize(selectedFontSize.value).run()
})

const selectedTextAlign = ref('left')

</script>

<template>
    <div class="group ">
        <div class="relative rounded focus-within:ring-2 focus-within:ring-gray-300">
            <!-- Group: editor tools -->
            <div class="hidden group-focus-within:flex bg-gray-100 absolute bottom-full w-fit justify-between text-slate-800 select-none space-x-1 border border-gray-100" tabindex="0">
                <div class="flex justify-start items-center divide-x-2 divide-gray-200">
                
                    <!-- Font Family -->
                    <div class="relative rounded-sm min-w-max flex px-2 gap-x-2">
                        <Multiselect v-model="selectedFontFamily" :options="fontOptions" :placeholder="'Select your option'"
                            :canClear="false" :closeOnSelect="true" :canDeselect="false" :hideSelected="false"
                            :searchable="true"
                        >
                            <template v-slot:singlelabel="{ value }">
                                <div class="multiselect-single-label bg-red-50 z-10 text-gray-600 whitespace-nowrap">
                                    <span :style="`font-family : ${snakeCase(lowerCase(value.value))}`">
                                        {{ value.value }}
                                    </span>
                                </div>
                            </template>
                            <template #option="{ option }">
                                <span :style="`font-family : ${snakeCase(lowerCase(option.value))}`">
                                    {{ option.label }}
                                </span>
                            </template>
                        </Multiselect>
                        
                        <!-- Font Size -->
                        <!-- <div>
                            <select v-model="editor.getAttributes('textStyle').fontSize" @change="editor.chain().focus().setFontSize(selectedFontSize).run()">
                                <option v-for="fontSizeOption in fontSizeOptions"
                                    :key="fontSizeOption" :value="fontSizeOption">{{ fontSizeOption }}</option>
                            </select>
                        </div> -->
                        <Multiselect v-model="selectedFontSize" :options="fontSizeOptions" :placeholder="'Select font size'"
                            :canClear="false" :closeOnSelect="true" :canDeselect="false" :hideSelected="false"
                            :searchable="false">
                            <template v-slot:singlelabel="{ value }">
                                <div class="multiselect-single-label bg-red-50 z-10 text-gray-600 whitespace-nowrap w-44">
                                    {{ value.value }}
                                </div>
                            </template>
                            <template #option="{ option }">
                                    {{ option.name }}
                            </template>
                        </Multiselect>
                    </div>

                    <!-- Bold, Italic, Underline -->
                    <div class="flex px-2 h-full items-center text-gray-500 gap-x-2">
                        <!-- Bold -->
                        <div :class="{ 'bg-gray-500 text-white': editor.isActive('bold') }"
                            class="rounded-sm grid justify-center items-center border border-transparent active:border-gray-700 box-content cursor-pointer px-1 py-0.5"
                            @click="editor.chain().focus().toggleBold().run()">
                            <FontAwesomeIcon aria-hidden="true" icon="far fa-bold" />
                        </div>

                        <!-- Italic -->
                        <div :class="{ 'bg-gray-500 text-white': editor.isActive('italic') }"
                            class="rounded-sm grid justify-center items-center border border-transparent active:border-gray-700 box-content cursor-pointer px-1 py-0.5"
                            @click="editor.chain().focus().toggleItalic().run()">
                            <FontAwesomeIcon aria-hidden="true" icon="far fa-italic" />
                        </div>

                        <!-- Underline -->
                        <div :class="{ 'bg-gray-500 text-white': editor.isActive('underline') }"
                            class="rounded-sm grid justify-center items-end border border-transparent active:border-gray-700 box-content cursor-pointer px-1 py-0.5"
                            @click="editor.chain().focus().toggleUnderline().run()">
                            <FontAwesomeIcon aria-hidden="true" icon="far fa-underline" />
                        </div>
                    </div>

                    <!-- Text Align -->
                    <div class="flex h-full items-center px-4 gap-x-2">
                        <div v-for="option in textAlignOptions" @click="editor.chain().focus().setTextAlign(option.value).run()" class="flex items-center justify-center bg-gray-50 rounded-sm aspect-square h-6 ring-1 ring-gray-300 cursor-pointer"
                            :class="[ editor.isActive({ textAlign: option.value }) ? 'bg-gray-500 text-gray-100' : 'hover:bg-gray-200 text-gray-600']"
                        >
                            <FontAwesomeIcon :icon='option.icon' class='text-xs' aria-hidden='true' />
                        </div>
                    </div>
                    
                    <!-- Color -->
                    <div class="flex h-full items-center px-4 gap-x-2">
                        <!-- Color: Highlight -->
                        <div class="isolate flex items-center py-1 h-full relative">
                            <input type="color" class="absolute opacity-0 w-full h-full z-20 cursor-pointer"
                                @input="editor.chain().focus().toggleHighlight({ color: $event.target.value }).run()"
                                :value="editor.getAttributes('highlight').color">
                            <span class="shadow ring-1 ring-gray-400 h-3/4 w-7 px-1 rounded-sm text-gray-300" :style="[`background: ${editor.getAttributes('highlight').color ?? ''}`]">
                                <FontAwesomeIcon icon='far fa-fill-drip' class='text-gray-500' aria-hidden='true' />
                            </span>
                        </div>

                        <!-- Color: Text -->
                        <div class="isolate flex items-center py-1 h-full relative">
                            <input id="input-color-text" type="color"
                                @input="editor.chain().focus().setColor($event.target.value).run()"
                                :value="editor.getAttributes('textStyle').color" class="absolute opacity-0 w-full h-full z-20 cursor-pointer" />
                            <span class="shadow ring-1 ring-gray-400 h-3/4 w-8 px-1 rounded-sm text-gray-700 flex justify-center items-center">
                                <FontAwesomeIcon icon='far fa-font' class="cursor-pointer hover:border z-10 hover:border-gray-300"
                                :style="[`color: ${editor.getAttributes('textStyle').color ?? '#6b7280'}`]"
                                aria-hidden='true' />
                            </span>
                        </div>
                    </div>

                </div>
                
                <!-- Clear text -->
                <div class="w-min rounded-sm grid justify-end items-center place-self-center border border-transparent active:border-gray-700 box-content cursor-pointer px-1 py-0.5"
                    @click="editor.chain().focus().clearContent(true).run()">
                    <span class="text-base text-red-500">Clear</span>
                    <!-- <FontAwesomeIcon aria-hidden="true" icon="far fa-trash-alt" /> -->
                </div>
            </div>

            <!-- The main content -->
            <div :class="['min-w-[200px]', `text-${selectedTextAlign} whitespace-nowrap ring-2 ring-gray-200 rounded hover:bg-gray-200 hover:ring-gray-400 focus-within:ring-gray-400`, props.class]">
                <EditorContent :editor="editor" />
            </div>
            <!-- {{ editor.getAttributes('font-Family').color }} -->
        </div>

        <div v-if="props.showStats" class="grid grid-flow-col text-xs italic text-gray-500 mt-2 space-x-12 justify-start">
            <p class="">
                <!-- {{ pageBody.layout.profile.fields.about.notes }} -->
                Letters: {{ editor.storage.characterCount.characters() }}
            </p>
            <p class="">
                <!-- {{ pageBody.layout.profile.fields.about.notes }} -->
                Words: {{ editor.storage.characterCount.words() }}
            </p>
        </div>
<!-- {{ editor.getHTML() }} -->
<!-- {{ editor.getAttributes('highlight') }} -->
    </div>
</template>

<style lang="scss">
.ProseMirror {
    padding: 7px 15px;
}

.ProseMirror p {
    width: 100%;
    word-wrap: break-word;
    outline-color: #6b7280 !important;
    display: block;
}

.ProseMirror ul,
ol {
    display: block;
    padding-left: 30px;
    list-style-position: outside;
}

.ProseMirror ul {
    list-style-type: decimal;
}

.ProseMirror ol {
    list-style-type: disc;
}

.ProseMirror p.is-editor-empty:first-child::before {
    color: #adb5bd;
    font-style: italic;
    content: attr(data-placeholder);
    pointer-events: none;
}

/* mark {
    background-color: #ffe066;
    padding: 20px;
} */



/* Multiselect styling */
.multiselect {
    width: 150px !important;
}

.multiselect-option.is-selected {
    @apply bg-gray-500 text-white
}

.multiselect-option.is-selected.is-pointed {
    @apply bg-gray-500 text-white
}

.multiselect-option.is-selected.is-disabled {
    @apply bg-gray-300 text-white
}

</style>


<style src="@vueform/multiselect/themes/default.css"></style>
