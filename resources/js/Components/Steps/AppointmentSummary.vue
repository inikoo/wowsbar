<script setup lang='ts'>
import { useFormatTime } from '@/Composables/useFormatTime'
import { usePage } from '@inertiajs/vue3'
import { notify } from '@kyvg/vue3-notification'
import Button from '@/Components/Elements/Buttons/Button.vue'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faClock, faCalendarAlt, faMapMarkerAlt } from '@fal/'
import { faArrowAltRight, faPaperPlane } from '@fas/'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faClock, faCalendarAlt, faMapMarkerAlt, faArrowAltRight, faPaperPlane)

const props = defineProps<{
    selectedDate: Date
}>()

const emits = defineEmits<{
    (e: 'onFinish'): void
}>()

// When submit Appointment
const onClickMakeAppointment = async () => {
    if (!!usePage().props.auth.user) {
        console.log('Appointment created')

        notify({
            title: "Appointment successfuly created.",
            // text: error,
            type: "success"
        })

    } else {
    }
    emits('onFinish')
}

</script>

<template>
    <div class="font-medium text-gray-700 text-sm md:text-lg leading-none mb-2 md:mb-3">Summary of your appointment</div>

    <!-- Section: Appointment Card -->
    <div class="bg-white border border-gray-300 rounded-lg overflow-hidden p-4 grid md:grid-cols-2 mb-4 gap-y-4 md:gap-y-0">
        <div class="order-2 col-span-2">
            <div class="tracking-wide text-sm text-gray-700">
                {{ usePage().props.auth.user?.username }}
            </div>
            <div class="text-3xl font-semibold text-gray-700">Financial Plan</div>
        </div>

        <div class="col-span-2 flex justify-start md:justify-end">
            <img src="https://cdn.iconscout.com/icon/free/png-512/free-google-meet-2923654-2416657.png?f=webp&w=100"
                class="h-10 md:h-14 p-1 bg-gray-100 border border-gray-600 rounded" />
        </div>

        <div class="md:mt-4 flex flex-col gap-y-2 text-gray-500 order-3 col-span-2">
            <div class="inline-flex items-center gap-x-1">
                <FontAwesomeIcon fixed-width icon='fal fa-map-marker-alt' class='h-4 md:h-5 aspect-square text-gray-400 '
                    aria-hidden='true' />
                <div class="text-sm md:text-base leading-none">{{ useFormatTime(selectedDate) }}</div>
            </div>
            <div class="inline-flex items-center gap-x-1">
                <FontAwesomeIcon fixed-width icon='fal fa-calendar-alt' class='h-4 md:h-5 aspect-square text-gray-400 '
                    aria-hidden='true' />
                <div class="text-sm md:text-base leading-none">{{ useFormatTime(selectedDate) }}</div>
            </div>
            <div class="inline-flex items-center gap-x-1">
                <FontAwesomeIcon fixed-width icon='fal fa-clock' class='h-4 md:h-5 aspect-square text-gray-400 '
                    aria-hidden='true' />
                <div class="flex items-center space-x-1">
                    <span class="tabular-nums text-sm md:text-lg leading-none">
                        {{ (selectedDate.getHours()).toString().padStart('2', '0') + ':' +
                            (selectedDate.getMinutes()).toString().padStart('2', '0') }}
                    </span>
                    <span class="text-xs md:text-sm text-gray-500 ">
                        {{ selectedDate.getHours() > 11 ? 'PM' : 'AM' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    <Button @click="onClickMakeAppointment()" label="Make appointment" iconRight="fas fa-paper-plane" full />
</template>