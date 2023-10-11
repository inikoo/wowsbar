<script setup lang='ts'>
import StepsClean from '@/Components/DataDisplay/Steps/StepsClean.vue'
import StepsSimple from '@/Components/DataDisplay/Steps/StepsSimple.vue'
import StepsCompact from '@/Components/DataDisplay/Steps/StepsCompact.vue'
import { computed, ref, reactive } from 'vue'
import firstStep from '@/Components/Dashboard/firstStep.vue'
import secondStep from '@/Components/Dashboard/secondStep.vue'
import thirdStep from '@/Components/Dashboard/thirdStep.vue'
import Button from '@/Components/Elements/Buttons/Button.vue'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faArrowRight } from '@/../private/pro-regular-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faArrowRight)

const props = defineProps<{
    step: number
}>()

// Data content for all steps
const data: any = reactive({
    1: {
        websiteValue: ''
    },
    2: {
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
    3: {
        textareaValue: '',
        inputValue: ''
    }
})

// To indicate the current step
// const currentStep = ref(props.step)
const compCurrentStep = computed(() => {
    return stepsList[props.step-1]
})

// The list of each steps
const stepsList = [
    {
        id: 1,
        label: 'Enter your website name',
        description: 'Lorem ipsum dolor sit amet.',
        component: 1
    },
    {
        id: 2,
        label: 'Select your interest',
        description: 'Lorem ipsum dolor sit amet.',
        component: 2
    },
    {
        id: 3,
        label: 'Make appointment',
        description: 'Lorem ipsum dolor sit amet.',
        component: 3
    },
]

// Dynamic component: Steps
const compComponentSteps = computed(() => {
    const componentStepsList: any = {
        1: firstStep,
        2: secondStep,
        3: thirdStep,
    }

    return componentStepsList[props.step]
})

// Dynamic component: Content of Steps
const compContentStep = computed(() => {
    const compContentStep: any = {
        clean: StepsClean,
        simple: StepsSimple,
        compact: StepsCompact,
    }

    return compContentStep['compact']
})
</script>

<template>
    <div>
        <!-- Steps: Head (progress) -->
        <div class="mb-10">
            <component :is="compContentStep" :stepsList="stepsList" :currentStep="step" />
        </div>

        <!-- Button: Next -->
        <!-- <div class="grid grid-cols-2 justify-between mb-10">
                <div />
                <div class="text-right">
                    <Button v-if="currentStep.id != stepsList.length" label="Next" :style="`secondary`"
                        @click="currentStep = stepsList[currentStep.id]">
                        <span>Next</span>
                        <FontAwesomeIcon icon='far fa-arrow-right' class='' aria-hidden='true' />
                    </Button>
                </div>
            </div> -->

        <!-- Section: Dynamic Component -->
        <div class="flex gap-x-4 pb-10 mb-5 border-b border-gray-300">
            <div class="w-full">
                <Transition name="slide-to-left" mode="out-in">
                    <KeepAlive>
                        <component :is="compComponentSteps" :data="data[compCurrentStep.component]" />
                    </KeepAlive>
                </Transition>
            </div>
            <div class="flex items-center">
                <Button v-if="compCurrentStep.id != stepsList.length" label="Next" :style="`primary`"
                    @click="step = step + 1">
                    <span>Next</span>
                    <FontAwesomeIcon icon='far fa-arrow-right' class='' aria-hidden='true' />
                </Button>
            </div>
        </div>
    </div>
</template>