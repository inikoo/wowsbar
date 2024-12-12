<script setup lang="ts">
import { onMounted, watch, computed, inject } from 'vue'
import { trans } from 'laravel-vue-i18n'
import PureDatePicker from '@/Components/Pure/PureDatePicker.vue'
import PureInput from '@/Components/Pure/PureInput.vue'
import { get, set } from 'lodash'
import SideEditorInputHTML from './SideEditorInputHTML.vue'

const Countdown = {
    date: new Date(Date.now() + 2 * 24 * 60 * 60 * 1000),
    expired_text: ''
}

const props = defineProps<{
    noToday?: boolean
}>()

const model = defineModel()
const announcementData = inject('announcementData', Countdown)

onMounted(() => {
    if (!get(announcementData, 'fields.countdown', false)) {
        set(announcementData, 'fields.countdown', Countdown)
    }
})
</script>

<template>
    <div class="pb-2">
        <div class="px-3 flex flex-col mb-2">
            <div class="text-xs">{{ trans('Select end date') }}</div>
            
            <!-- Date -->
            <div class="flex items-center gap-x-2 py-1 w-full" >
                <PureDatePicker
                    :modelValue="get(model, 'date', new Date())"
                    @update:modelValue="(e) => set(model, 'date', e)"
                    required
                    :noToday
                    :min-date="new Date()"
                />
            </div>
        </div>

        <div class="px-3 flex flex-col mb-2">
            <div class="text-xs">{{ trans('Enter text (when countdown expired)') }}</div>

            <!-- Text -->
            <div class="flex items-center gap-x-2 py-1" >
                <SideEditorInputHTML
                    :modelValue="get(model, 'expired_text', '')"
                    @update:modelValue="(e) => set(model, 'expired_text', e)"
                />
            </div>
        </div>
    </div>
</template>

<style scoped></style>
