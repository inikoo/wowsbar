<template>
    <div class="lg:border-b lg:border-t lg:border-gray-200">
        <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" aria-label="Progress">
            <ol role="list"
                class="overflow-hidden rounded-md lg:flex lg:rounded-none lg:border-l lg:border-r lg:border-gray-200">
                <li v-for="(step, stepIdx) in steps" :key="step.id" class="relative overflow-hidden lg:flex-1">
                    <div
                        :class="[stepIdx === 0 ? 'rounded-t-md border-b-0' : '', stepIdx === steps.length - 1 ? 'rounded-b-md border-t-0' : '', 'overflow-hidden border border-gray-200 lg:border-0']">
                        <a v-if="step.status === 'complete'" :href="step.href" class="group">
                            <span
                                class="absolute left-0 top-0 h-full w-1 bg-transparent group-hover:bg-gray-200 lg:bottom-0 lg:top-auto lg:h-1 lg:w-full"
                                aria-hidden="true" />
                            <span
                                :class="[stepIdx !== 0 ? 'lg:pl-9' : '', 'flex items-start px-6 py-5 text-sm font-medium']">
                                <span class="flex-shrink-0">
                                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-600">
                                        <CheckIcon class="h-6 w-6 text-white" aria-hidden="true" />
                                    </span>
                                </span>
                                <span class="ml-4 mt-0.5 flex min-w-0 flex-col">
                                    <span class="text-sm font-medium">{{ step.name }}</span>
                                    <span class="text-sm font-medium text-gray-500">{{ step.description }}</span>
                                </span>
                            </span>
                        </a>
                        <a v-else-if="step.status === 'current'" :href="step.href" aria-current="step">
                            <span
                                class="absolute left-0 top-0 h-full w-1 bg-indigo-600 lg:bottom-0 lg:top-auto lg:h-1 lg:w-full"
                                aria-hidden="true" />
                            <span
                                :class="[stepIdx !== 0 ? 'lg:pl-9' : '', 'flex items-start px-6 py-5 text-sm font-medium']">
                                <span class="flex-shrink-0">
                                    <span
                                        class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-indigo-600">
                                        <span class="text-indigo-600">{{ step.id }}</span>
                                    </span>
                                </span>
                                <span class="ml-4 mt-0.5 flex min-w-0 flex-col">
                                    <span class="text-sm font-medium text-indigo-600">{{ step.name }}</span>
                                    <span class="text-sm font-medium text-gray-500">{{ step.description }}</span>
                                </span>
                            </span>
                        </a>
                        <a v-else :href="step.href" class="group">
                            <span
                                class="absolute left-0 top-0 h-full w-1 bg-transparent group-hover:bg-gray-200 lg:bottom-0 lg:top-auto lg:h-1 lg:w-full"
                                aria-hidden="true" />
                            <span
                                :class="[stepIdx !== 0 ? 'lg:pl-9' : '', 'flex items-start px-6 py-5 text-sm font-medium']">
                                <span class="flex-shrink-0">
                                    <span
                                        class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-gray-300">
                                        <span class="text-gray-500">{{ step.id }}</span>
                                    </span>
                                </span>
                                <span class="ml-4 mt-0.5 flex min-w-0 flex-col">
                                    <span class="text-sm font-medium text-gray-500">{{ step.name }}</span>
                                    <span class="text-sm font-medium text-gray-500">{{ step.description }}</span>
                                </span>
                            </span>
                        </a>
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
</div></template>
  
<script setup>
import { CheckIcon } from '@heroicons/vue/24/solid'

const steps = [
    { id: '01', name: 'Job Details', description: 'Vitae sed mi luctus laoreet.', href: '#', status: 'complete' },
    { id: '02', name: 'Application form', description: 'Cursus semper viverra.', href: '#', status: 'current' },
    { id: '03', name: 'Preview', description: 'Penatibus eu quis ante.', href: '#', status: 'upcoming' },
]
</script>