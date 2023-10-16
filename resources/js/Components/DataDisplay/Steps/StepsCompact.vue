<script setup lang="ts">
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCheck } from '@fal/'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faCheck)

const props = defineProps<{
    stepsList: {
        id: number
        label: string
        description: string
    }[]
    currentStep: number
}>()
</script>

<template>
    <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" aria-label="Progress">
        <ol role="list" class="overflow-hidden rounded-md lg:flex lg:border lg:border-gray-200">
            <li v-for="(step, stepName, stepIdx) in stepsList" :key="step.id" class="relative overflow-hidden lg:flex-1">
                <div class="overflow-hidden border border-gray-200 lg:border-0"
                    :class="[stepIdx === 0  // For first Step
                        ? 'rounded-t-md border-b-0'
                        : stepIdx === stepsList.length - 1  // For last Step
                            ? 'rounded-b-md border-t-0'
                            : ''
                ]">
                    <div class="group">
                        <!-- The bottom border -->
                        <span aria-hidden="true"
                            class="absolute left-0 top-0 h-full w-1 lg:bottom-0 lg:top-auto lg:h-1 lg:w-full"
                            :class="[
                                stepIdx + 1 < currentStep  // Previous step
                                    ? 'bg-gray-300'
                                    : currentStep == stepIdx + 1  // Current step
                                        ? ' bg-gray-500'
                                        : 'bg-transparent'
                            ]"
                        />
                        <span :class="[stepIdx !== 0 ? 'lg:pl-9' : '', 'flex items-start px-6 py-5 text-sm font-medium']">
                            <!-- The circle: Number -->
                            <span class="flex-shrink-0">
                                <span class="flex h-10 w-10 items-center justify-center rounded-full"
                                    :class="[
                                        stepIdx + 1 < currentStep  // Previous step
                                            ? 'bg-gray-600 text-white'
                                            : currentStep == stepIdx + 1  // Current step
                                                ? ' border border-gray-600 text-gray-600'
                                                : 'text-gray-400 border border-slate-300 '
                                    ]"
                                >
                                    <FontAwesomeIcon v-if="stepIdx + 1 < currentStep" icon='fal fa-check' class='h-6 w-6' aria-hidden='true' />
                                    <span v-else class="">{{ step.id }}</span>
                                </span>
                            </span>

                            <!-- The title and description -->
                            <span class="text-sm ml-4 mt-0.5 flex min-w-0 flex-col"
                                :class="[
                                    stepIdx + 1 <= currentStep  // Previous & current step
                                        ? 'text-gray-600'
                                        : 'text-gray-400'
                                ]"
                            >
                                <span class="font-semibold">{{ step.label }}</span>
                                <span class="font-light">{{ step.description }}</span>
                            </span>
                        </span>
                    </div>

                    <template v-if="stepIdx !== 0">
                        <!-- Separator -->
                        <div class="absolute inset-0 left-0 top-0 hidden w-3 lg:block" aria-hidden="true">
                            <svg class="h-full w-full text-gray-300" viewBox="0 0 12 82" fill="none"
                                preserveAspectRatio="none">
                                <path d="M0.5 0V31L10.5 41L0.5 51V82" stroke="currentcolor"
                                    vector-effect="non-scaling-stroke" />
                            </svg>
                        </div>
                    </template>
                </div>
            </li>
        </ol>
    </nav>
</template>
