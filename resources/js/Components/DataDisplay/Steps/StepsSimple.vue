<script setup lang="ts">
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCheck } from '@/../private/pro-light-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faCheck)

const props = defineProps<{
    stepsList: {
        id: number
        label: string
    }[]
    currentStep: {
        id: number
        label: string
    }
}>()
</script>

<template>
    <nav aria-label="Progress">
        <ol role="list" class="divide-y divide-gray-300 rounded-md border border-gray-300 md:flex md:divide-y-0">
            <li v-for="(step, stepIdx) in stepsList" :key="step.id" class="relative md:flex md:flex-1">
                <div class="flex w-full items-center">
                    <span class="flex items-center px-6 py-4 text-sm font-medium">
                        <!-- Circle: Number or Check Mark -->
                        <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full "
                            :class="[
                                stepIdx + 1 < currentStep.id  // Previous step
                                    ? 'bg-gray-600 text-white'
                                    : currentStep.id == stepIdx + 1  // Current step
                                        ? ' border border-gray-600 text-gray-600'
                                        : 'text-gray-400 border border-slate-300 '
                            ]"
                        >
                            <FontAwesomeIcon v-if="stepIdx + 1 < currentStep.id" icon='fal fa-check' class='h-6 w-6' aria-hidden='true' />
                            <span v-else class="text-xl">{{ stepIdx + 1 }}</span>
                        </span>

                        <!-- Title -->
                        <span class="ml-4 text-sm" :class="[
                                stepIdx + 1 <= currentStep.id  // Previous step and current step
                                    ? 'text-gray-700 font-semibold'
                                    : 'text-gray-400' 
                        ]">
                            {{ step.label }}
                        </span>
                    </span>
                </div>
                
                <!-- Arrow separator -->
                <template v-if="stepIdx !== stepsList.length - 1">
                    <div class="absolute right-0 top-0 hidden h-full w-5 md:block" aria-hidden="true">
                        <svg class="h-full w-full text-gray-300" viewBox="0 0 22 80" fill="none" preserveAspectRatio="none">
                            <path d="M0 -2L20 40L0 82" vector-effect="non-scaling-stroke" stroke="currentcolor"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                </template>
            </li>
        </ol>
    </nav>
</template>