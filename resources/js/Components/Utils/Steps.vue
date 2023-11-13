<script setup lang='ts'>
const props = defineProps<{
    currentStep: number
}>()

const emits = defineEmits<{
    (e: 'nextStep'): void
    (e: 'previousStep'): void
}>()

const steps = [
    {
        id: 0,
        label: 'Step 1: Login',
        // icon: 'fal fa-check'
    },
    {
        id: 1,
        label: 'Step 2: Select date',
        // icon: 'fal fa-check'
    },
    {
        id: 2,
        label: 'Final: Summary',
        // icon: 'fal fa-check'
    },
]

</script>

<template>
    <div class="w-full pt-4 pb-16">
        <div class="w-11/12 lg:w-2/6 mx-auto">
            <div class="bg-gray-200 h-1 flex items-center justify-between">

                <!-- Step -->
                <div v-for="(step, stepIndex) in steps"
                    @click="stepIndex < currentStep ? emits('previousStep') : ''"
                    class="relative h-1 flex items-center justify-start"
                    :class="[
                        stepIndex == (steps.length - 1) ? '' : 'w-full',
                        stepIndex < currentStep || currentStep == (steps.length - 1)? 'bg-lime-400' : 'bg-gray-100'
                    ]"
                >
                    <div class="h-6 w-6 rounded-full flex items-center justify-center"
                        :class="[
                            //step.id == (steps.length - 1) ? 'translate-x-1/2' : '',
                            { 'translate-x-1/2': stepIndex == (steps.length - 1) },
                            { '-translate-x-1/2': stepIndex == 0 },
                            { 'bg-gray-200': stepIndex > currentStep },
                            { 'bg-gray-500 ring-2 ring-offset-2 ring-gray-500': stepIndex == currentStep },
                            { 'bg-lime-500': stepIndex < currentStep },
                        ]"
                        :title="step.label"
                    >
                        <!-- Icon: Check -->
                        <svg v-if="stepIndex < currentStep" xmlns="http://www.w3.org/2000/svg"
                            class="icon icon-tabler icon-tabler-check" width="18" height="18" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="#FFFFFF" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" />
                            <path d="M5 12l5 5l10 -10" />
                        </svg>
                    </div>

                    <!-- Description -->
                    <div class="absolute top-6"
                        :class="[
                            stepIndex == 0
                                ? ' -left-4'
                                : stepIndex + 1 == steps.length
                                    ? '-right-3'
                                    : 'left-3 -translate-x-1/2',
                            stepIndex == currentStep
                                ? 'font-bold text-gray-600'
                                : stepIndex < currentStep
                                    ? 'text-lime-700'
                                    : 'text-gray-400'
                        ]"
                    >
                        <p tabindex="0" class="whitespace-nowrap focus:outline-none text-xs">
                            {{ step.label ?? 'Untitled'}}
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>