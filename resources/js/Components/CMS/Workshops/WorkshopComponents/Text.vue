<script setup lang="ts">
import { ref, watch, onMounted, onBeforeUnmount, Ref } from "vue"
import SelectFont from '@/Components/Workshop/Fields/SelectFont.vue'
import Multiselect from "@vueform/multiselect"
import { set, lowerCase, snakeCase } from 'lodash'

import StarterKit from "@tiptap/starter-kit"
import Placeholder from "@tiptap/extension-placeholder"
import Underline from "@tiptap/extension-underline"
import CharacterCount from "@tiptap/extension-character-count"
import { Editor, EditorContent } from "@tiptap/vue-3"
import { Color } from '@tiptap/extension-color'
import TextStyle from '@tiptap/extension-text-style'
import Highlight from '@tiptap/extension-highlight'
import FontFamily from '@tiptap/extension-font-family'

import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { library } from "@fortawesome/fontawesome-svg-core"
import { faBold, faItalic, faUnderline, faTrashAlt, faListUl, faListOl, faUndo, faFont, faRedo, faFillDrip } from "@/../private/pro-regular-svg-icons"
library.add(faBold, faItalic, faUnderline, faTrashAlt, faListUl, faListOl, faUndo, faFont, faRedo, faFillDrip)

const props = defineProps(["modelValue", "showStats", "placeholder", "class"])
const emit = defineEmits(["update:modelValue"])
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

// Handle v-model
watch(props.modelValue,
    (newValue) => {
        const isSame = editor.value.getHTML() === newValue

        if (isSame) {
            return
        }

        editor.commands.setContent(newValue, false)
    }
)

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
        Color.configure({
            types: ['textStyle'],
        }),
        FontFamily.configure({
            types: ['textStyle'],
        }),
        Highlight.configure({ multicolor: true }),
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

// Declare font faily
const fontFamily = ref('Comfortaa')
watch(fontFamily, () => {
    editor.value.chain().focus().setFontFamily(fontFamily.value).run()
})

const fontSizeOptions = ['12px', '16px', '20px', '24px']
const selectedFontSize = ref('16px')

</script>

<template>
    <div class="group ">
        <div class="relative rounded focus-within:ring-2 focus-within:ring-gray-300">
            <!-- Group: editor tools -->
            <div class="hidden group-focus-within:flex bg-gray-100 absolute bottom-full w-fit justify-between text-slate-800 select-none space-x-1 border border-gray-100" tabindex="0">
                <div class="flex justify-start items-center divide-x-2 divide-gray-200">
                    <!-- Font Family -->
                    <div class="isolate relative rounded-sm w-44 flex pr-4">
                        <Multiselect v-model="fontFamily" :options="fontOptions" :placeholder="'Select your option'"
                            :canClear="false" :closeOnSelect="true" :canDeselect="false" :hideSelected="false"
                            :searchable="true">
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
                    </div>

                    <!-- Bold, Italic, Underline -->
                    <div class="flex px-2 h-full items-center text-gray-500 gap-x-2">
                        <!-- Bold -->
                        <div :class="{ 'bg-orange-400 text-white': editor.isActive('bold') }"
                            class="rounded-sm grid justify-center items-center border border-transparent active:border-orange-700 box-content cursor-pointer px-1 py-0.5"
                            @click="editor.chain().focus().toggleBold().run()">
                            <FontAwesomeIcon aria-hidden="true" icon="far fa-bold" />
                        </div>

                        <!-- Italic -->
                        <div :class="{ 'bg-orange-400 text-white': editor.isActive('italic') }"
                            class="rounded-sm grid justify-center items-center border border-transparent active:border-orange-700 box-content cursor-pointer px-1 py-0.5"
                            @click="editor.chain().focus().toggleItalic().run()">
                            <FontAwesomeIcon aria-hidden="true" icon="far fa-italic" />
                        </div>

                        <!-- Underline -->
                        <div :class="{ 'bg-orange-400 text-white': editor.isActive('underline') }"
                            class="rounded-sm grid justify-center items-end border border-transparent active:border-orange-700 box-content cursor-pointer px-1 py-0.5"
                            @click="editor.chain().focus().toggleUnderline().run()">
                            <FontAwesomeIcon aria-hidden="true" icon="far fa-underline" />
                        </div>
                    </div>
                    
                    <!-- Color -->
                    <div class="flex h-full items-center px-4 gap-x-2">
                        <!-- Color: Highlight -->
                        <div class="isolate flex items-center py-1 h-full relative">
                            <input type="color" class="absolute opacity-0 w-full h-full z-20 cursor-pointer"
                                @input="editor.chain().focus().toggleHighlight({ color: $event.target.value }).run()"
                                :value="editor.getAttributes('highlight').color">
                            <span class="h-3/4 w-7 px-1 rounded-sm text-gray-300" :style="[`background: ${editor.getAttributes('highlight').color ?? '#4b5563'}`]">
                                <FontAwesomeIcon icon='far fa-fill-drip' class='shadow' aria-hidden='true' />
                            </span>
                        </div>

                        <!-- Color: Text -->
                        <div class="isolate flex items-center py-1 h-full relative">
                            <input id="input-color-text" type="color"
                                @input="editor.chain().focus().setColor($event.target.value).run()"
                                :value="editor.getAttributes('textStyle').color" class="absolute opacity-0 w-full h-full z-20 cursor-pointer" />
                            <span class="h-3/4 w-8 px-1 rounded-sm bg-gray-300 text-gray-700 flex justify-center items-center">
                                <FontAwesomeIcon icon='far fa-font' class="cursor-pointer hover:border z-10 hover:border-gray-300"
                                :style="[`color: ${editor.getAttributes('textStyle').color ?? '#1f2937'}`]"
                                aria-hidden='true' />
                            </span>
                        </div>
                    </div>

                    <!-- Font Size -->
                    <div>
                        <select v-model="selectedFontSize" @change="editor.commands.setMark('textStyle', { class: selectedFontSize })">
                            <option v-for="fontSizeOption in fontSizeOptions"
                                :key="fontSizeOption" :value="fontSizeOption">{{ fontSizeOption }}</option>
                        </select>
                    </div>

                </div>
                <div class="w-min rounded-sm grid justify-end items-center place-self-end border border-transparent active:border-orange-700 box-content cursor-pointer px-1 py-0.5"
                    @click="editor.chain().focus().clearContent(true).run()">
                    <span class="text-base">Clear</span>
                    <!-- <FontAwesomeIcon aria-hidden="true" icon="far fa-trash-alt" /> -->
                </div>
            </div>

            <!-- The main content -->
            <div :class="['min-w-[200px]', props.class]">
                <EditorContent :editor="editor" />
            </div>
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
    </div>
</template>

<style scoped>
.ProseMirror {
    padding: 7px 15px;
}

.ProseMirror p {
    width: 90%;
    /* background: #e1e1e1; */
    /* max-width: 100%; */
    word-wrap: break-word;
    outline-color: #6b7280 !important;
    display: inline-block;
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

mark {
    background-color: #ffe066;
    padding: 15px 00.125em;
    border-radius: 0.25em;
    box-decoration-break: clone;
}

.multiselect-search {
    display: flex;
    justify-content: start;
    background-color: #ffe066;
    z-index: 50;
}
</style>


<style src="@vueform/multiselect/themes/default.css"></style>