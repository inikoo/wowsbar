<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 19 Sep 2023 13:37:29 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { capitalize } from "@/Composables/capitalize"
import { trans } from 'laravel-vue-i18n'
import { useLayoutStore } from '@/Stores/layout'
import LastEditedBanners from '@/Components/LastEditedBanners.vue'
import { computed, ref, reactive } from 'vue'
import firstStep from '@/Components/Dashboard/firstStep.vue'
import secondStep from '@/Components/Dashboard/secondStep.vue'
import thirdStep from '@/Components/Dashboard/thirdStep.vue'
import Button from '@/Components/Elements/Buttons/Button.vue'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faArrowRight } from '@/../private/pro-regular-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faArrowRight)

const currentHour = new Date().getHours();

const greetingMessage =
    currentHour >= 4 && currentHour < 12 ? // after 4:00AM and before 12:00PM
        trans('Good morning') :
        currentHour >= 12 && currentHour <= 17 ? // after 12:00PM and before 6:00pm
            trans('Good afternoon') :
            currentHour > 17 || currentHour < 4 ? // after 5:59pm or before 4:00AM (to accommodate night owls)
                trans('Good evening') :
                trans('Welcome') // if for some reason the calculation didn't work

const layout = useLayoutStore()

const props = defineProps<{
    title: string
    latest_banners?: any
    latest_banners_count: number
    portfolio_websites_count: number
    name: string
}>()

// Data for all steps
const data: any = reactive({
    firstStep: {
        websiteValue: ''
    },
    secondStep: {
        slug: "mw",
        customer_name: "Aiku",
        code: null,
        name: "My website 😸",
        url: "hello.com",
        number_banners: 0,
        seo: {
            name: "seo",
            label: "SEO",
            value: 'not_sure'
        },
        "google-ads": {
            name: "google-ads",
            label: "PPC",
            value: "not_interested"
        },
        social: {
            name: "social",
            label: "Social",
            value: 'not_sure'
        },
        prospects: {
            name: "prospects",
            label: "Prospects",
            value: 'not_sure'
        },
        banners: {
            name: "banners",
            label: "Banners",
            value: 'not_sure'
        }
    },
    thirdStep: {
        textareaValue: '',
        inputValue: ''
    }
})

// To indicate the current step
const currentStep = ref({
    id: 1,
    label: 'Step  1',
    component: 'firstStep'
})

// The list of each steps
const stepsList = [
    {
        id: 1,
        label: 'Enter your website name',
        component: 'firstStep'
    },
    {
        id: 2,
        label: 'Select your interest',
        component: 'secondStep'
    },
    {
        id: 3,
        label: 'Make appointment',
        component: 'thirdStep'
    },
]

// Define the component of each Steps
const componentStepsList: any = {
    firstStep: firstStep,
    secondStep: secondStep,
    thirdStep: thirdStep,
}

// Computed dynamic component of Steps
const compComponentSteps = computed(() => {
    return componentStepsList[currentStep.value.component]
})


// const backAction = ref(false) // True/false to define the Transition name
</script>

<template layout="CustomerApp">
    <Head :title="capitalize(title)" />
    
    <div class="max-w-7xl px-4 sm:px-6 lg:px-8 lg:py-6">
        <div class="pt-2 mt-4 lg:mt-0 lg:pt-0 text-2xl font-light">
            {{ trans(greetingMessage) }}, <span class="font-bold capitalize">{{ name }}</span>!
        </div>
        
        <!-- <div class="">
            <hr class="mt-3 mb-8">
            <LastEditedBanners v-if="latest_banners_count > 0" :banners="latest_banners" />
        </div> -->

        <div class="mt-6">
            <hr class="mb-10">

            <!-- Step: Head (progress) -->
            <nav aria-label="Progress" class="mb-10">
                <ol role="list" class="space-y-4 md:flex md:space-x-8 md:space-y-0">
                    <li v-for="(step, index) in stepsList" :key="step.id" class="md:flex-1">
                        <div class="group rounded-md overflow-hidden ring-1 ring-gray-300 shadow flex flex-col border-l-4 py-3 px-4 md:border-l-0 md:border-t-4"
                            :class="[
                                index + 1 < currentStep.id  // Previous step
                                    ? 'border-slate-500'
                                    : currentStep.id == index + 1  // Current step
                                        ? 'bg-gray-200 border-slate-500'
                                        : 'text-gray-400 border-slate-300'
                            ]"
                        >
                            <span class="text-xl font-medium">{{ step.id }}</span>
                            <span class="text-sm font-medium">{{ step.label }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="grid grid-cols-2 justify-between mb-10">
                <div>
                    <!-- <Button v-if="currentStep.id != 1" label="Previous" :style="`tertiary`"
                        @click="currentStep = stepsList[currentStep.id - 2], backAction = true">
                        <FontAwesomeIcon icon='far fa-arrow-left' class='' aria-hidden='true' />
                        <span>Previous</span>
                    </Button> -->
                </div>
                <div class="text-right">
                    <Button v-if="currentStep.id != stepsList.length" label="Next" :style="`secondary`"
                        @click="currentStep = stepsList[currentStep.id]">
                        <span>Next</span>
                        <FontAwesomeIcon icon='far fa-arrow-right' class='' aria-hidden='true' />
                    </Button>
                </div>
            </div>

            <!-- Section: Dynamic Component -->
            <div class="flex flex-col">
                <Transition :name="backAction ? 'slide-to-right' : 'slide-to-left'" mode="out-in">
                    <KeepAlive>
                        <component :is="compComponentSteps" :data="data[currentStep.component]" />
                    </KeepAlive>
                </Transition>

                <hr class="mt-10 mb-5">

            </div>
        </div>
    </div>
</template>

<style>

</style>