<script setup lang='ts'>
const props = defineProps<{
    currentStep: number | string
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
    <!-- component -->
    <div class="w-full pt-4 pb-16">
        <div class="w-11/12 lg:w-2/6 mx-auto">
            <div class="bg-gray-200 h-1 flex items-center justify-between">

                <!-- Step -->
                <div v-for="step in steps" class="relative bg-indigo-700 h-1 flex items-center justify-start"
                    :class="step.id == (steps.length - 1) ? '' : 'w-full'"
                >
                    <div class="h-6 w-6 rounded-full flex items-center justify-center"
                        :class="[
                            //step.id == (steps.length - 1) ? 'translate-x-1/2' : '',
                            { 'translate-x-1/2': step.id == (steps.length - 1) },
                            { '-translate-x-1/2': step.id == 0 },
                            { 'bg-gray-200': step.id > currentStep },
                            { 'bg-gray-500 ring-2 ring-offset-2 ring-gray-500': step.id == currentStep },
                            { 'bg-lime-400': step.id < currentStep },
                        ]"
                    >
                        <svg v-if="step.id < currentStep" xmlns="http://www.w3.org/2000/svg"
                            class="icon icon-tabler icon-tabler-check" width="18" height="18" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="#FFFFFF" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" />
                            <path d="M5 12l5 5l10 -10" />
                        </svg>
                    </div>

                    <!-- Popup -->
                    <div v-if="step.id == currentStep" class="absolute left-0"
                        :class="[
                            step.id == 0
                                ? ''
                                : step.id == (steps.length - 1)
                                    ? '-translate-x-1/2'
                                    : '-translate-x-1/2'
                        ]"
                    >
                        <div class="relative bg-gray-100 ring-1 ring-gray-300 px-2 py-1 rounded mt-16">
                            <svg class="absolute top-0 -mt-1 w-full right-0 left-0" width="16px" height="8px"
                                viewBox="0 0 16 8" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Progress-Bars" transform="translate(-322.000000, -198.000000)" fill="#FFFFFF">
                                        <g id="Group-4" transform="translate(310.000000, 198.000000)">
                                            <polygon id="Triangle" points="20 0 28 8 12 8"></polygon>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                            <p tabindex="0" class="focus:outline-none text-indigo-700 text-xs font-bold">
                                {{ step.label }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Current
                    <div class="flex justify-between bg-indigo-700 h-1 items-center relative w-full">
                        <div class="bg-white h-6 w-6 rounded-full shadow flex items-center justify-center -translate-x-1/2 relative">
                            <div class="h-3 w-3 bg-indigo-700 rounded-full"></div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <div class="bg-gray-100 h-6 w-6 rounded-full"></div>
                    </div> -->
            </div>
        </div>
    </div>
</template>