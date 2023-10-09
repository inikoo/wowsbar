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
import { computed, ref } from 'vue'
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

const currentStep = ref('firstSteps')
const stepsList = [
    {
        id: 1,
        label: 'Step  1',
        component: 'firstSteps'
    },
    {
        id: 2,
        label: 'Step 2',
        component: 'secondSteps'
    },
    {
        id: 3,
        label: 'Step 3',
        component: 'thirdSteps'
    },
]

const componentStepsList: any = {
    firstSteps: firstStep,
    secondSteps: secondStep,
    thirdSteps: thirdStep,
}

const compComponentSteps = computed(() => {
    return componentStepsList[currentStep.value]
})

</script>

<template layout="CustomerApp">
    <Head :title="capitalize(title)" />

    <div class="max-w-7xl px-4 sm:px-6 lg:px-8 lg:py-6">
        <div class="pt-2 mt-4 lg:mt-0 lg:pt-0 text-2xl font-light">
            {{ trans(greetingMessage) }}, <span class="font-bold capitalize">{{ name }}</span>!
        </div>
        <hr class="mt-3 mb-6">
        <LastEditedBanners v-if="latest_banners_count > 0" :banners="latest_banners" />

        <div class="">

            <!-- Section: Steps button -->
            <div class="max-w-5xl mx-auto mb-10">
                <nav aria-label="Progress">
                    <ol role="list"
                        class="divide-x divide-gray-300 rounded-md border border-gray-300 md:flex md:divide-y-0">
                        <li v-for="(step, index) in stepsList" class="relative md:flex md:flex-1"
                            :class="[step.component == currentStep ? 'bg-gray-300' : 'hover:bg-gray-100']">
                            <!-- Completed Step -->
                            <div @click="currentStep = step.component"
                                class="group flex w-full items-center cursor-pointer ">
                                <span class="flex items-center px-6 py-4 text-sm font-medium">
                                    <div
                                        class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-indigo-600">
                                        <span class="text-white">{{ index + 1 }}</span>
                                    </div>
                                    <span class="ml-4 text-sm font-medium text-gray-700">{{ step.label }}</span>
                                </span>
                            </div>

                            <!-- Arrow separator for lg screens and up -->
                            <!-- <div v-if="index < stepsList.length - 1" class="absolute right-0 top-0 hidden h-full w-5 md:block" aria-hidden="true">
                                <svg class="h-full w-full text-gray-300" viewBox="0 0 22 80" fill="none"
                                    preserveAspectRatio="none">
                                    <path d="M0 -2L20 40L0 82" vector-effect="non-scaling-stroke" stroke="currentcolor"
                                        stroke-linejoin="round" />
                                </svg>
                            </div> -->
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- Section: Dynamic Component -->
            <div class="flex flex-col gap-y-10">
                <KeepAlive>
                    <component :is="compComponentSteps" />
                </KeepAlive>
                <div class="flex justify-end">
                    <Button label="Next">
                        <span>Next</span>
                        <FontAwesomeIcon icon='far fa-arrow-right' class='' aria-hidden='true' />
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>
