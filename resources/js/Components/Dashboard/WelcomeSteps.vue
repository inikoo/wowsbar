<script setup lang='ts'>
import StepsClean from '@/Components/DataDisplay/Steps/StepsClean.vue'
import StepsSimple from '@/Components/DataDisplay/Steps/StepsSimple.vue'
import StepsCompact from '@/Components/DataDisplay/Steps/StepsCompact.vue'
import { computed, ref, reactive } from 'vue'
import firstStep from '@/Components/Dashboard/firstStep.vue'
import secondStep from '@/Components/Dashboard/secondStep.vue'
import thirdStep from '@/Components/Dashboard/thirdStep.vue'
import Button from '@/Components/Elements/Buttons/Button.vue'


const props = defineProps<{
    data: {
        currentStep: number
        steps_data: any
    }
}>()

// To indicate the current step
const currentStep = ref(props.data.currentStep)
const compCurrentStepData = computed(() => {
    return props.data.steps_data[`step_${currentStep.value}`]
})

// Dynamic component: Steps
const compComponentSteps = computed(() => {
    const componentStepsList: any = {
        1: firstStep,
        2: secondStep,
        3: thirdStep,
    }
    return componentStepsList[currentStep.value]
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
    <!-- <pre>{{ currentStep }}</pre>
    <pre>{{ compComponentSteps }}</pre> -->
    <div class="mt-6 pt-10 border-t border-gray-300">
        <!-- Section: Dynamic Steps Component (StepsClean.vue, StepsSimple.vue, StepsCompact.vue) -->
        <div class="mb-10">
            <component :is="compContentStep" :stepsList="data.steps_data" :currentStep="currentStep" />
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

        <!-- Section: Dynamic Content (firstStep.vue, secondStep.vue, thirdStep.vue) -->
        <div class="flex gap-x-4 pb-10 mb-5 border-b border-gray-300 w-full">
            <Transition name="slide-to-left" mode="out-in">
                <KeepAlive>
                    <component :is="compComponentSteps"
                        :key="currentStep"
                        :data="compCurrentStepData"
                        :currentStep="currentStep"
                        @updateCurrentStep="() => currentStep++"
                    />
                </KeepAlive>
            </Transition>
        </div>
    </div>
</template>