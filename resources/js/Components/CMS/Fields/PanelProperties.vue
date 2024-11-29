resources/js/Components/Workshop/SideEditor/SideEditorInputHTML.vue resources/js/Components/Workshop/SideEditor/SideEditorText.vue<script setup lang="ts">
import { computed, watch } from 'vue'
import PaddingMarginProperty from '@/Components/Workshop/Properties/PaddingMarginProperty.vue'
import BackgroundProperty from '@/Components/Workshop/Properties/BackgroundProperty.vue'
import BorderProperty from '@/Components/Workshop/Properties/BorderProperty.vue'
import TextProperty from '@/Components/Workshop/Properties/TextProperty.vue'
import DimensionProperty from '@/Components/Workshop/Properties/DimensionProperty.vue'
import ButtonsProperty from '@/Components/CMS/Fields/ButtonProperties.vue'
import { trans } from 'laravel-vue-i18n'
import { set, get } from 'lodash';

const model = defineModel()

// const compModel = computed(() => {
//     // To check does the data if changed
//     return JSON.stringify(model.value)
// })

// const emit = defineEmits();
// watch(compModel, () => {
//     console.log('on change compModel')
//     emit('update:modelValue', model.value)
// })



</script>

<template>
    <div>
        <!-- <pre>{{ model.dimension }}</pre> -->
    </div>
    <!-- <div v-if="model?.buttons" class="border-t border-gray-300 bg-gray-100 pb-3">
        <div class="w-full text-center py-1 font-semibold select-none">{{ trans('Buttons') }}</div>
        <ButtonsProperty :modelValue="get(model, 'buttons', undefined)" @update:modelValue="(e) => set(model, 'buttons', e)" />
    </div>-->

    <div class="border-t border-gray-300 bg-gray-100 pb-3">
        <div class="w-full text-center py-1 font-semibold select-none">{{ trans('Dimension') }}</div>
        <DimensionProperty :modelValue="get(model, 'dimension', undefined)" />
    </div>

    <div class="border-t border-gray-300 bg-gray-100 pb-3">
        <div class="w-full text-center py-1 font-semibold select-none">{{ trans('Background') }}</div>
        <BackgroundProperty :modelValue="get(model, 'background', undefined)" />
    </div>

    <div class="border-t border-gray-300 bg-gray-100 pb-3">
        <div class="w-full text-center py-1 font-semibold select-none">{{ trans('Text') }}</div>

        <TextProperty :modelValue="get(model, 'text', undefined)" />
    </div>

    <div class="border-t border-gray-300 bg-gray-100">
        <div class="w-full text-center py-1 font-semibold select-none">{{ trans('Border') }}</div>

        <BorderProperty :modelValue="get(model, 'border', undefined)" />
    </div>

    <div class="border-t border-gray-300 bg-gray-100">
        <div class="w-full text-center py-1 font-semibold select-none">{{ trans('Padding') }}</div>

        <PaddingMarginProperty :modelValue="get(model, 'padding', undefined)" />
    </div>

    <div class="border-t border-gray-300 bg-gray-100">
        <div class="w-full text-center py-1 font-semibold select-none">{{ trans('Margin') }}</div>

        <PaddingMarginProperty :modelValue="get(model, 'margin', undefined)" />
    </div>

</template>

<style scoped></style>
