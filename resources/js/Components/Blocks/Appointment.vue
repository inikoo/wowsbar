<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { library } from "@fortawesome/fontawesome-svg-core";
import { faRocketLaunch, faClock, faVideo } from "@far/";
import { isNull } from "lodash";
import { useFormatTime } from "@/Composables/useFormatTime";
import { DatePicker } from 'v-calendar';
import 'v-calendar/style.css';
import axios from 'axios'
import Button from '@/Components/Elements/Buttons/Button.vue';
import { notify } from '@kyvg/vue3-notification';

library.add(faRocketLaunch, faClock, faVideo);

const props = defineProps<{
    data: {
        info: string
        title: string
    }
}>()

const unavailableDates: any = ref([])
const availableSchedules = ref({})
const selectedDated: any = ref()

// console.log("ini", props.data);
// console.log("porpsd", props);


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

const hours = [
    {
        value: 7,
        label: '7 am'
    },
    {
        value: 8,
        label: '8 am'
    },
    {
        value: 9,
        label: '9 am'
    },
    {
        value: 10,
        label: '10 am'
    },
    {
        value: 11,
        label: '11 am'
    },
    {
        value: 12,
        label: '12 pm'
    },
    {
        value: 13,
        label: '1 pm'
    },
    {
        value: 14,
        label: '2 pm'
    },
    {
        value: 15,
        label: '3 pm'
    },
    {
        value: 16,
        label: '4 pm'
    },
    {
        value: 17,
        label: '5 pm'
    }
]


const onSelectHour = (hour: number) => {
    selectedDated.value = new Date(selectedDated.value)
    selectedDated.value.setHours(hour)
}

onMounted(async () => {
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
        console.log('xxx', response.data)
        // unavailableDates.value = Object.keys(response.data.bookedSchedules).map((item) => {
        //     return new Date(item)
        // })

        unavailableDates.value = response.data.bookedSchedules
        availableSchedules.value = response.data.availableSchedules
        // console.log('response', unavailableDates.value)
    }
    catch (error: any) {
        console.log('error', error)
    }

})

const isHourAvailable = (hour: number) => {
    if (selectedDated.value.length == false) return true  // If not selected date yet


    const formatUnavailable = unavailableDates.value[getDateOnly(selectedDated.value)] ? unavailableDates.value[getDateOnly(selectedDated.value)].map(item => new Date(item)) : []

    return !formatUnavailable.map(item => item.getHours() == hour)[0]

}

const getDateOnly = (dateString: string) => {
    const date = new Date(dateString);

    // Extract the year, month, and day components
    const year = date.getFullYear();
    const month = date.getMonth() + 1; // Month is zero-based, so add 1
    const day = date.getDate();

    // Create the desired date string 'YYYY-MM-DD'
    const formattedDate = `${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
    return formattedDate
}

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
        });
    }
    catch (error: any) {
        notify({
            // title: "Appointment successfuly created.",
            text: error,
            type: "error"
        });
    }
}

</script>


<template>
    <!-- <pre>{{ unavailableDates[getDateOnly(selectedDated)] }}</pre> -->
    <!-- {{ selectedDated }} -->
    <div style="margin: 20px;">
        <div class="container border-2" style="max-width: 800px;">
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
                            <DatePicker v-model="selectedDated" expanded :attributes="attrs" />
                        </div>

                        <div class="px-2.5 my-4 space-y-4">
                            <div v-if="selectedDated" class="grid grid-cols-3 justify-center gap-3">
                                <div v-if="availableSchedules[getDateOnly(selectedDated)]" v-for="hour in availableSchedules[getDateOnly(selectedDated)]" class="w-full">
                                    <Button
                                        :key="selectedDated + hour"
                                        @click="onSelectHour(hour.split(':')[0])" full
                                        :style="isHourAvailable(hour.split(':')[0])
                                            ? new Date(selectedDated).getHours() == hour.split(':')[0]
                                                ? 'rainbow'  // If hour selected
                                                : 'secondary'  // If available but not selected
                                            : 'disabled'  // If unavailable
                                            "
                                    >
                                        {{ hour }}
                                    </Button>
                                </div>
                                <div v-else class="col-span-3">No schedules available</div>
                                <div v-if="hours.some(hour => hour.value == new Date(selectedDated).getHours())" class="col-span-3">
                                    <Button @click="onClickMakeAppointment()" label="Make appointment" full />
                                </div>
                            </div>

                            <!-- If not selected date yet -->
                            <div v-else class="text-gray-500 italic">
                                ---- Select date to make an appointment ----
                            </div>
                            <!-- {{ selectedDated }} -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
