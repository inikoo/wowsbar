<script setup lang="ts">
import { ref, onMounted, Ref, watch } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { library } from "@fortawesome/fontawesome-svg-core"
import { faSpinnerThird } from "@fad/"
import { isNull } from "lodash"
import { useFormatTime } from "@/Composables/useFormatTime"
import { DatePicker } from 'v-calendar'
import 'v-calendar/style.css'
import axios from 'axios'
import Button from '@/Components/Elements/Buttons/Button.vue'
import { notify } from '@kyvg/vue3-notification'

library.add(faSpinnerThird)

const props = defineProps<{
    data: {
        info: string
        title: string
    }
}>()

const availableSchedulesOnMonth: Ref<{[key: string]: string[]}> = ref({})  // {2023-6: 2023-11-25 ['09:00, '10:00', ...]}
const selectedDate: any = ref()  // on select date in DatePicker
const isLoading = ref(false)  // Loading on fetch

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

// On click button available hour
const onSelectHour = (time: string) => {
    const timeSplit = time.split(':')
    selectedDate.value = new Date(selectedDate.value)
    selectedDate.value.setHours(timeSplit[0])
    selectedDate.value.setMinutes(timeSplit[1])
}


// To convert date to yyyy-mm-dd
const getDateOnly = (dateString: string | Date): string => {
    const date = new Date(dateString)

    // Extract the year, month, and day components
    const year = date.getFullYear()
    const month = date.getMonth() + 1 // Month is zero-based, so add 1
    const day = date.getDate()

    const formattedDate = `${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`
    return formattedDate  // 2023-11-23
}

// When submit Appointment
const onClickMakeAppointment = async () => {
    console.log('Appointment created')
    try {
        const response = await axios.get(
            route('public.appointment.schedule'),
            {
                params: {
                    year: '2023',
                    month: '11'
                }
            }
        )

        notify({
            title: "Appointment successfuly created.",
            // text: error,
            type: "success"
        })
    }
    catch (error: any) {
        notify({
            // title: "Appointment successfuly created.",
            text: error,
            type: "error"
        })
    }
}

// Fetch available schedule for whole month
const fetchAvailableOnMonth = async (year: number, month: number) => {
    isLoading.value = true
    try {
        const response = await axios.get(
            route('public.appointment.schedule'),
            {
                params: {
                    year: year,
                    month: month
                }
            }
        )

        availableSchedulesOnMonth.value = {
            [`${year}-${month}`]: response.data.availableSchedules,
            ...availableSchedulesOnMonth.value
        }
    }
    catch (error: any) {
        console.log('error', error)
    }
    isLoading.value = false
}

onMounted(() => {
    const today = new Date()
    fetchAvailableOnMonth(today.getFullYear(), today.getMonth() + 1)
})

watch(selectedDate, () => {
    if(!availableSchedulesOnMonth.value[`${selectedDate.value?.getFullYear()}-${selectedDate.value?.getMonth() + 1}`]){
        fetchAvailableOnMonth(selectedDate.value?.getFullYear(), selectedDate.value?.getMonth() + 1)
    }
})

</script>


<template>

    <div style="margin: 20px">
        <div class="container border-2" style="max-width: 800px">
            <div class="row divide-x divide-gray-300">
                <!-- Section: Left (Blog) -->
                <div class="col-md-6 p-0">
                    <div class="text-center rounded-0 border-0">
                        <div class="card-body">
                            <div v-html="data.info"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Section: Right (calendar) -->
                <div class="col-md-6 p-0 text-center">
                    <div class="card-body">
                        <div class="bg-white rounded text-left">
                            <span v-html="data.title"></span>
                        </div>
                        <div>
                            <DatePicker
                                v-model="selectedDate"
                                expanded
                                :attributes="attrs"  
                            />
                        </div>

                        <!-- Section: Button Hour, Button Submit -->
                        <div class="px-2.5 my-4 space-y-4">
                            <div v-if="selectedDate" class="grid grid-cols-3 justify-center gap-x-2 gap-y-3">
                                <template v-if="isLoading">
                                    <div v-for="_ in 6" class="w-full h-8 rounded skeleton" />
                                </template>

                                <template v-else>
                                    <template v-if="availableSchedulesOnMonth[`${selectedDate?.getFullYear()}-${selectedDate?.getMonth()+1}`]?.[getDateOnly(selectedDate)]">
                                        <div v-for="time in availableSchedulesOnMonth[`${selectedDate?.getFullYear()}-${selectedDate?.getMonth()+1}`][getDateOnly(selectedDate)]" class="w-full">
                                            <Button full
                                                :key="selectedDate + time"
                                                @click="onSelectHour(time)"
                                                :style="
                                                    time.split(':')[0] == selectedDate.getHours() &&
                                                    time.split(':')[1] == selectedDate.getMinutes()
                                                        ? 'rainbow'
                                                        : 'tertiary'
                                                "
                                            >
                                                {{ time }}
                                            </Button>
                                        </div>
                                        <div class="col-span-3">
                                            <Button @click="onClickMakeAppointment()" label="Make appointment" full />
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
                        <!-- {{ selectedDate }} -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
