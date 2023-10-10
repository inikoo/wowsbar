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
        label: 'Step  1',
        component: 'firstStep'
    },
    {
        id: 2,
        label: 'Step 2',
        component: 'secondStep'
    },
    {
        id: 3,
        label: 'Step 3',
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


const backAction = ref(false) // True/false to define the Transition name
</script>

<template layout="CustomerApp">
    <Head :title="capitalize(title)" />
    
    <div class="max-w-7xl px-4 sm:px-6 lg:px-8 lg:py-6">
        <div class="pt-2 mt-4 lg:mt-0 lg:pt-0 text-2xl font-light">
            {{ trans(greetingMessage) }}, <span class="font-bold capitalize">{{ name }}</span>!
        </div>
        
        <div class="">
            <hr class="mt-3 mb-8">
            <LastEditedBanners v-if="latest_banners_count > 0" :banners="latest_banners" />
        </div>

        <div class="mt-6">
            <hr class="mb-10">

            <!-- Section: Steps button -->
            <div class="max-w-5xl mx-auto mb-10">
                <nav aria-label="Progress">
                    <ol role="list"
                        class="divide-y md:divide-y-0 md:divide-x divide-gray-200 overflow-hidden rounded-md border border-gray-300 md:flex">
                        <!-- The step -->
                        <li v-for="(step, index) in stepsList" class="relative md:flex md:flex-1" :class="[
                            index + 1 < currentStep.id  // Previous step
                                ? 'bg-slate-600'
                                : currentStep.id == index + 1  // Current step
                                    ? 'bg-gray-200'
                                    : ''
                        ]">
                            <div class="group flex w-full items-center">
                                <span class="flex items-center px-6 py-4 text-sm font-medium">
                                    <!-- Circle: Number -->
                                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full"
                                        :class="[
                                            index + 1 < currentStep.id
                                                ? 'bg-white text-slate-600'  // Previous step
                                                : currentStep.id == index + 1
                                                    ? 'bg-gray-100 ring-1 ring-gray-300'  // Current step
                                                    : 'ring-1 ring-gray-400'
                                        ]">
                                        <span class="">{{ index + 1 }}</span>
                                    </div>
                                    <span class="ml-4 text-sm font-medium" 
                                        :class="[
                                            index + 1 < currentStep.id  // Previous step
                                                ? 'text-white'
                                                : 'text-slate-600'
                                        ]"
                                    >
                                        {{ step.label }}
                                    </span>
                                </span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- Section: Dynamic Component -->
            <div class="flex flex-col">
                <Transition :name="backAction ? 'slide-to-right' : 'slide-to-left'" mode="out-in">
                    <KeepAlive>
                        <component :is="compComponentSteps" :data="data[currentStep.component]" />
                    </KeepAlive>
                </Transition>

                <hr class="mt-10 mb-5">

                <div class="grid grid-cols-2 justify-between">
                    <div>
                        <Button v-if="currentStep.id != 1" label="Previous" :style="`tertiary`"
                            @click="currentStep = stepsList[currentStep.id - 2], backAction = true">
                            <FontAwesomeIcon icon='far fa-arrow-left' class='' aria-hidden='true' />
                            <span>Previous</span>
                        </Button>
                    </div>
                    <div class="text-right">
                        <Button v-if="currentStep.id != stepsList.length" label="Next" :style="`secondary`"
                            @click="currentStep = stepsList[currentStep.id], backAction = false">
                            <span>Next</span>
                            <FontAwesomeIcon icon='far fa-arrow-right' class='' aria-hidden='true' />
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>

</style>