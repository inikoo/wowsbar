<script setup lang="ts">
import Editor2 from '@/Components/Forms/Fields/BubleTextEditor/EditorV2.vue'
import { EditorContent } from '@tiptap/vue-3'
import { trans } from 'laravel-vue-i18n'
import { get, set } from 'lodash'
import Popover from 'primevue/popover';
import { ref } from 'vue'
import Button from '@/Components/Elements/Buttons/Button.vue';

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faPlus, faArrowUp, faArrowDown, faArrowLeft, faArrowRight } from '@fal'
import { icon, library } from '@fortawesome/fontawesome-svg-core'
import { useHeadlineText } from '@/Composables/useStockList'
import { ulid } from 'ulid'
import PureRange from '@/Components/Pure/PureRange.vue'
import Select from 'primevue/select';

library.add(faPlus, faArrowUp, faArrowDown, faArrowLeft, faArrowRight)

interface MultiEditor {
    multi_text: string[]
    duration: number
}

const props = defineProps<{
    containerClass?: string
}>()
const model = defineModel<MultiEditor>()
const _popover = ref(null);
const idxText = ref(null)

defineOptions({
    inheritAttrs: false
})
const onClickNewText = () => {
    const randIndex = Math.floor(Math.random() * useHeadlineText().length)
    // console.log('dsadsa', model.value?.multi_text?.length)
    model.value?.multi_text.push(`<p>${useHeadlineText()[randIndex]}</p>`)
}

const onDeleteText = () => {
    model.value?.multi_text.splice(idxText.value, 1)
    _popover.value?.hide()
}

const togglePopover = (event: Event, idText : any) => {
    _popover.value?.toggle(event);
    idxText.value = idText
};

const transitionList = [
    {
        label: trans('Blurry'),
        value: 'animate__blurry',
        // icon: 'fal fa-eye-slash',
        keyframes: `@keyframes key-multitext-enter { 0% { filter: blur(0px); scale: 1; opacity: 1; } 100% { filter: blur(10px); scale: 1.1; opacity: 0; } } @keyframes key-multitext-leave { 0% { filter: blur(10px); scale: 1.1; opacity: 0; } 100% { filter: blur(0px); scale: 1; opacity: 1; } }`
    },
    {
        label: trans('Slide down'),
        icon: 'fal fa-arrow-down',
        value: 'animate__slide_down',
        keyframes: `@keyframes key-multitext-enter { 0% { transform: translateY(0); opacity: 1; } 100% { transform: translateY(100%); opacity: 0; } } @keyframes key-multitext-leave { 0% { transform: translateY(-100%); opacity: 0; } 100% { transform: translateY(0); opacity: 1; } }`
    },
    {
        label: trans('Slide up'),
        icon: 'fal fa-arrow-up',
        value: 'animate__slide_up',
        keyframes: `@keyframes key-multitext-enter { 0% { transform: translateY(0); opacity: 1; } 100% { transform: translateY(-100%); opacity: 0; } } @keyframes key-multitext-leave { 0% { transform: translateY(100%); opacity: 0; } 100% { transform: translateY(0); opacity: 1; } }`
    },
    {
        label: trans('Slide left'),
        icon: 'fal fa-arrow-left',
        value: 'animate__slide_left',
        keyframes: `@keyframes key-multitext-enter { 0% { transform: translateX(0); opacity: 1; } 100% { transform: translateX(-100%); opacity: 0; } } @keyframes key-multitext-leave { 0% { transform: translateX(100%); opacity: 0; } 100% { transform: translateX(0); opacity: 1; } }`
    },
    {
        label: trans('Slide right'),
        value: 'animate__slide_right',
        icon: 'fal fa-arrow-right',
        keyframes: `@keyframes key-multitext-enter { 0% { transform: translateX(0); opacity: 1; } 100% { transform: translateX(100%); opacity: 0; } } @keyframes key-multitext-leave { 0% { transform: translateX(-100%); opacity: 0; } 100% { transform: translateX(0); opacity: 1; } }`
    },
]
</script>

<template>
    <div :class="containerClass">
        <div class="mb-1 flex justify-between text-sm text-gray-500">
            <span>{{ trans('Animation') }}</span>
        </div>
        
        <Select
            :modelValue="model?.transition"
            @update:modelValue="(e) => set(model, 'transition', e)"
            :options="transitionList"
            :placeholder="trans('Select an transition')"
            optionLabel="label"  
        >
            <template #value="{ value }">
                <div class="flex items-center gap-x-0.5">
                    {{ value.label }}
                    <FontAwesomeIcon v-if="value.icon" :icon='value.icon' class='text-gray-400 text-sm' fixed-width aria-hidden='true' />
                </div>
            </template>

            <template #option="{ option }">
                <div class="flex items-center gap-x-0.5">
                    {{ option.label }}
                    <FontAwesomeIcon v-if="option.icon" :icon='option.icon' class='text-gray-400 text-sm' fixed-width aria-hidden='true' />
                </div>
            </template>
        </Select>

        <div class="mt-6 flex justify-between text-sm text-gray-500">
            <span>{{ trans('Duration') }}</span>
            <span>{{ (model?.duration || 0)/1000 }}s</span>
        </div>

        <div class="w-[90%] mx-auto my-1">
            <PureRange
                :modelValue="get(model, 'duration', 0)"
                @update:modelValue="(e) => set(model, 'duration', e)"
                :min="2500"
                :max="15000"
                :step="500"

            />
            <!-- <Slider :modelValue="get(model, 'duration', 2500)" @update:modelValue="(e) => (console.log('zzz'), set(model, 'duration', e))" :step="500" :min="2500" :max="15000" class="w-full" /> -->
            <div class="flex justify-between">
                <div class="-translate-x-1/2">
                    2.5s
                </div>
                <div class="translate-x-1/2">
                    15s
                </div>
            </div>
        </div>

        <div class="mt-6 text-sm text-gray-500 mb-2">{{ trans('Text list') }}</div>
        <div class="flex flex-col gap-y-4 ">
            <div v-for="(text, idxText) in model?.multi_text" :key="'texteditor_' + idxText + model?.multi_text?.length" class="flex items-center gap-x-1 w-full">
                <Editor2 :modelValue="text" @update:modelValue="(e) => set(model, ['multi_text', idxText], e)" v-bind="$attrs" class="w-full">
                    <template #editor-content="{ editor }">
                        <div
                            class="w-full bg-gray-200 editor-wrapper border-2 border-gray-300 rounded px-1.5 py-2 shadow-sm focus-within:border-blue-400">
                            <EditorContent :editor="editor" class="max-h-32 overflow-y-auto focus:outline-none" />
                        </div>
                    </template>
                </Editor2>

                <div @click="(event)=>togglePopover(event,idxText)" class="px-1 py-1 text-red-400 hover:text-white cursor-pointer border border-transparent hover:bg-red-500 rounded">
                    <FontAwesomeIcon icon='fal fa-trash-alt' class='' fixed-width aria-hidden='true' />
                </div>
            </div>

            <Popover ref="_popover">
                    <div>
                        <div class="text-sm font-medium mb-3">Are you sure to delete this ?</div>
                        <div class="flex justify-end gap-2">
                            <Button :style="'white'" label="No" size="xs" @click="()=>_popover.hide()" />
                            <Button label="Yes" size="xs" @click="onDeleteText" />
                        </div>
                    </div>
            </Popover>
                

            <div @click="() => onClickNewText()" class="hover:bg-gray-100 cursor-pointer border-2 border-gray-300 border-dashed text-center rounded px-3 py-2 shadow-sm focus-within:border-blue-400">
                <FontAwesomeIcon icon='fal fa-plus' class='' fixed-width aria-hidden='true' /> {{ trans("Add new text") }}
            </div>
        </div>
    </div>
</template>

<style scoped>
.editor-wrapper {
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

</style>
