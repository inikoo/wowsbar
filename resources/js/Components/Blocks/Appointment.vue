<script setup lang="ts">
import { ref, onMounted, Ref, watch, reactive } from 'vue'
import 'v-calendar/style.css'
import axios from 'axios'
import Button from '@/Components/Elements/Buttons/Button.vue'
import LoginSmall from '@/Components/Public/Auth/LoginSmall.vue'
import Modal from '@/Components/Utils/Modal.vue'
import Steps from '@/Components/Utils/Steps.vue'
import AppointmentSummary from '@/Components/Steps/AppointmentSummary.vue'
import SelectAppointmentDate from '@/Components/Steps/SelectAppointmentDate.vue'

import { faArrowAltRight } from '@fas'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faArrowAltRight)

const props = defineProps<{
    data: {
        info: string
        title: string
    }
}>()

const availableSchedulesOnMonth = ref<{ [key: string]: {[key: string]: string[]}[] }>({})  // {2023-6: {2023-11-25 ['09:00, '10:00', ...]} }
const selectedDate: any = ref(new Date())  // on select date in DatePicker
const isLoading = ref(false)  // Loading on fetch
const isModalSteps = ref(false)
const currentStep = ref(0)

const emailField: any = reactive({
    value: '',
    status: false,
    description: ''
})

const passwordField = reactive({
    value: '',
    valueRepeat: '',
    description: ''
})

const meetEvent = reactive({
    value: null,
    options: [
        {
            name: 'callback',
            label: 'Callback'
        },
        {
            name: 'in-person',
            label: 'In person'
        }
    ]
})

// On click button available hour
const onSelectHour = (time: string) => {
    const timeSplit = time.split(':')
    selectedDate.value = new Date(selectedDate.value)
    selectedDate.value.setHours(timeSplit[0])
    selectedDate.value.setMinutes(timeSplit[1])
}


// Fetch available schedule for whole month
const fetchAvailableOnMonth = async (year: number, month: number) => {
    if (!year || !month) return
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
        console.log(response.data)
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
    if (!availableSchedulesOnMonth.value[`${selectedDate.value?.getFullYear()}-${selectedDate.value?.getMonth() + 1}`]) {
        fetchAvailableOnMonth(selectedDate.value?.getFullYear(), selectedDate.value?.getMonth() + 1)
    }
})

const stepsOptions = [
    {
        label: 'Step 1: Login',
        // icon: 'fal fa-check'
    },
    {
        label: 'Step 2: Select date',
        // icon: 'fal fa-check'
    },
    {
        label: 'Final: Summary',
        // icon: 'fal fa-check'
    },
]

</script>


<template>
    <div class="py-2">
        <div class="max-w-sm md:max-w-3xl mx-auto border-2 grid md:grid-cols-2 divide-y md:divide-x divide-gray-300 rounded-md">
            <!-- Section: Blog -->
            <div class="p-0">
                <div class="text-center rounded-0 border-0">
                    <div class="card-body">
                        <div v-html="data.info"></div>
                    </div>
                </div>
            </div>

            <!-- Section: Calendar -->
            <div class="py-4 text-center h-full flex justify-center items-center">
                <Button @click="isModalSteps = true" :style="'rainbow'" label="Click here" />
            </div>
        </div>
    </div>

    <Modal :isOpen="isModalSteps" @onClose="isModalSteps = false">
        <div class="h-96 overflow-y-auto">

            <Steps
                :options="stepsOptions"
                :currentStep="currentStep"
                @previousStep="currentStep--"    
                @nextStep="currentStep++"    
            />

            <transition name="slide-to-left" mode="out-in">
                <!-- First Step: Login -->
                <div v-if="currentStep === 0" class="flex gap-x-2 pb-2 justify-center">
                    <LoginSmall
                        v-model:email="emailField.value"
                        v-model:password="passwordField.value"
                        v-model:passwordRepeat="passwordField.valueRepeat"
                        :emailField="emailField"
                        :passwordField="passwordField"
                        @loginSuccess="currentStep++"
                        loginRoute="public.appointment.login"
                        checkEmailRoute="public.appointment.check.email"
                        registerRoute="public.appointment.register"
                    />
                </div>

                <!-- Second Step: Select date -->
                <div v-else-if="currentStep === 1" class="">
                    <SelectAppointmentDate
                        v-model="selectedDate"
                        :title="data.title"
                        :isLoading="isLoading"
                        :availableSchedulesOnMonth="availableSchedulesOnMonth"
                        :meetEvent="meetEvent"
                        @onFinish="currentStep++"
                        @onSelectHour="(newValue) => onSelectHour(newValue)"
                    />
                </div>

                <!-- Third Step: Summary review -->
                <div v-else class="max-w-2xl mx-auto py-4">
                    <AppointmentSummary :selectedDate="selectedDate" @onFinish="isModalSteps = false" :meetEvent="meetEvent.value"/>
                </div>
            </transition>
        </div>
    </Modal>
</template>
