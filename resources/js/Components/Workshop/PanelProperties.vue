<script setup lang="ts">
import { computed, watch } from 'vue'
import BackgroundProperty from '@/Components/Workshop/Properties/BackgroundProperty.vue'
import BorderProperty from '@/Components/Workshop/Properties/BorderProperty.vue'
import PaddingMarginProperty from '@/Components/Workshop/Properties/PaddingMarginProperty.vue'
import TextProperty from '@/Components/Workshop/Properties/TextProperty.vue'
import DimensionProperty from '@/Components/Workshop/Properties/DimensionProperty.vue'
import { trans } from 'laravel-vue-i18n'
import Checkbox from 'primevue/checkbox';
import { ref } from 'vue'

const model = defineModel()

const compModel = computed(() => {
    // To check does the data if changed
    return JSON.stringify(model.value)
})

const emit = defineEmits()
watch(compModel, () => {
    console.log('on change compModel')
    emit('update:modelValue', model.value)
})


</script>

<template>
    <div>
        <!-- {{ model.isCenterHorizontal }} -->
    </div>

    <div class="p-4">
        <!-- Horizontally Center -->
        <div v-if="model.isCenterHorizontal"  class="flex items-center gap-x-3">
            <Checkbox v-model="model.isCenterHorizontal" inputId="centerHorizontal" name="centerHorizontal" binary />
            <label for="centerHorizontal" class="cursor-pointer select-none">{{ trans('Horizontally Center') }} </label>
        </div>

        <!-- Flying -->
        <div v-if="model.position" class="flex items-center gap-x-3">
            <Checkbox :modelValue="model.position.type === 'fixed'" @update:modelValue="(newVal) => newVal ? model.position.type = 'fixed' : model.position.type = 'relative'"  inputId="isComponentFlying" name="isComponentFlying" binary />
            <label for="isComponentFlying" class="cursor-pointer select-none">Flying</label>
        </div>
    </div>
    
    <div v-if="model?.dimension" class="border-t border-gray-300 bg-gray-100 pb-3">
        <div class="w-full text-center py-1 font-semibold select-none">{{ trans('Dimension') }}</div>
        <DimensionProperty v-model="model.dimension" />
    </div>

    <div v-if="model?.background" class="border-t border-gray-300 pb-3">
        <div class="w-full text-center py-1 font-semibold select-none">{{ trans('Background') }}</div>

        <BackgroundProperty v-model="model.background" />
    </div>

    <div v-if="model?.text" class="border-t border-gray-300 pb-3">
        <div class="w-full text-center py-1 font-semibold select-none">{{ trans('Text') }}</div>

        <TextProperty v-model="model.text" />
    </div>

    <div v-if="model?.border" class="border-t border-gray-300">
        <div class="w-full text-center py-1 font-semibold select-none">{{ trans('Border') }}</div>

        <BorderProperty v-model="model.border" />
    </div>

    <div v-if="model?.padding" class="border-t border-gray-300">
        <div class="w-full text-center py-1 font-semibold select-none">{{ trans('Padding') }}</div>

        <PaddingMarginProperty v-model="model.padding" :scope="trans('Padding')" />
    </div>

    <div v-if="model?.margin" class="border-t border-gray-300">
        <div class="w-full text-center py-1 font-semibold select-none">{{ trans('Margin') }}</div>

        <PaddingMarginProperty v-model="model.margin" :scope="trans('Margin')"
            :additionalData="{
                'right': {
                    disabled: model.isCenterHorizontal,
                    tooltip: trans('Disabled due the component is set to centered horizontally.')
                },
                'left': {
                    disabled: model.isCenterHorizontal,
                    tooltip: trans('Disabled due the component is set to centered horizontally.')
                },
            }"
        />
    </div>

</template>
