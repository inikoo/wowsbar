<script setup lang='ts'>
import { ref } from 'vue'
import { DatePicker } from 'v-calendar'
import Button from '@/Components/Elements/Buttons/Button.vue'

const props = defineProps<{
    title?: string
    modelValue: Date
    availableSchedulesOnMonth: {
        [key: string]: {
            [key: string]: string[]
        }[]
    }
    isLoading?: boolean
}>()

const emits = defineEmits<{
    (e: 'update:modelValue', value: Date): void
    (e: 'onFinish'): void
    (e: 'onSelectHour', value: string): void
}>()

// To convert date to yyyy-mm-dd
const getDateOnly = (dateString: Date): string => {
    const date = new Date(dateString)

    // Extract the year, month, and day components
    const year = date.getFullYear()
    const month = date.getMonth() + 1 // Month is zero-based, so add 1
    const day = date.getDate()

    const formattedDate = `${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`
    return formattedDate  // 2023-11-23
}

// Attribute for DatePicker
const attrs = ref([
    {
        key: "today",
        highlight: {
            color: "gray",
            fillMode: "outline",
        },
        dates: new Date(),
    }
])
</script>

<template>
    <div class="bg-white rounded text-left">
        <span v-html="title"></span>
    </div>
    <div class="w-64">
        <DatePicker :value="modelValue" @update:modelValue="(newVal: Date) => emits('update:modelValue', newVal)" expanded :attributes="attrs" />
    </div>

    <!-- Section: Button Hour, Button Submit -->
    <div class="px-2.5 my-4 space-y-4">
        <div v-if="modelValue" class="grid grid-cols-3 justify-center gap-x-2 gap-y-3">
            <template v-if="isLoading">
                <div v-for="_ in 6" class="w-full h-8 rounded skeleton" />
            </template>
            <template v-else>
                <template
                    v-if="availableSchedulesOnMonth[`${modelValue?.getFullYear()}-${modelValue?.getMonth() + 1}` as keyof any]?.[getDateOnly(modelValue)].length > 0">
                    <div v-for="time in availableSchedulesOnMonth[`${modelValue?.getFullYear()}-${modelValue?.getMonth() + 1}`][getDateOnly(modelValue)]"
                        class="w-full">
                        <Button full :key="modelValue + time" @click="emits('onSelectHour', time)" :style="time.split(':')[0] == modelValue.getHours() &&
                            time.split(':')[1] == modelValue.getMinutes()
                                ? 'rainbow'
                                : 'tertiary'
                            ">
                            {{ time }}
                        </Button>
                    </div>
                    <div class="col-span-3">
                        <Button @click="emits('onFinish')" iconRight="fas fa-arrow-alt-right" label="Summary" full />
                    </div>
                </template>
                <div v-else class="col-span-3">No schedules available</div>
            </template>
        </div>
        <!-- If not selected date yet -->
        <div v-else class="text-gray-500 italic">
            ---- Select date to make an appointment ----
        </div>
    </div>
</template>