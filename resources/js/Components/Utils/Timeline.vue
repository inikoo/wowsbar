<script setup lang='ts'>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCalendarAlt } from '@fal/'
import { library } from '@fortawesome/fontawesome-svg-core'
import { useFormatTime } from '@/Composables/useFormatTime';
library.add(faCalendarAlt)

const props = defineProps<{
    options: {
        [key: string]: string[]
    }
}>()

const isTodayHours = (date: string | Date) => {
    const currentDate = new Date()
    const dateValue = new Date(date)

    // Check if the dates are equal
    return currentDate.getFullYear() === dateValue.getFullYear() &&
        currentDate.getMonth() === dateValue.getMonth() &&
        currentDate.getDate() === dateValue.getDate() &&
        currentDate.getHours() === dateValue.getHours()
}

</script>

<template>
    <div class="w-full py-6 flex">
        <!-- Steps -->
        <div v-for="(stepValue, stepTime, stepIndex) in options" class="w-full">
            <div class="relative mb-2">
                <!-- Step: Tail -->
                <div v-if="stepIndex != 0"
                    class="w-full px-7 absolute flex align-center items-center align-middle content-center -translate-x-1/2 top-1/2 -translate-y-1/2">
                    <div class="w-full rounded items-center align-middle align-center flex-1">
                        <div class="w-full py-1 rounded" :class="[
                            isTodayHours(stepTime) ? 'bg-gray-300' : 'bg-gray-600'
                        ]" />
                    </div>
                </div>

                <!-- Step: Head -->
                <div class="h-10 aspect-square mx-auto rounded-full text-lg flex items-center" :class="[
                    isTodayHours(stepTime)
                        ? 'ring-2 ring-lime-500 text-lime-500'  // If current
                        : 'bg-gray-700 text-gray-100'  // before or after current
                ]">
                    <span class="text-center w-full">
                        <FontAwesomeIcon icon='fal fa-calendar-alt' class='' aria-hidden='true' />
                    </span>
                </div>
            </div>

            <!-- Step: Description -->
            <div class="">
                <div class="text-xs text-center md:text-base font-semibold text-gray-600">
                    {{ useFormatTime(stepTime, { formatTime: 'hms' }) }}
                </div>
                <div class="w-fit mx-auto capitalize text-sm font-thin text-gray-500">
                    {{ stepValue.join(', ') }}
                </div>
            </div>
        </div>

        <!-- isTodayHours(Object.keys(options).slice(-1)) -->
        <div v-if="false" class="w-full">
            <!-- If last option is in range 1 hour ago, not print this section  -->
            <div class="relative mb-2">
                <!-- Step: Tail -->
                <div
                    class="w-full px-7 absolute flex align-center items-center align-middle content-center -translate-x-1/2 top-1/2 -translate-y-1/2">
                    <div class="w-full rounded items-center align-middle align-center flex-1">
                        <div class="w-full py-1 rounded bg-gray-200 shimmer" />
                    </div>
                </div>
                <!-- Step: Head -->
                <div
                    class="h-10 aspect-square mx-auto rounded-full text-lg flex items-center ring-1 ring-gray-300 text-gray-300">
                    <span class="text-center w-full">
                        <FontAwesomeIcon icon='fal fa-calendar-alt' class='' aria-hidden='true' />
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>