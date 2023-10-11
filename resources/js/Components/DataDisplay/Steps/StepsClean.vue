<script setup lang='ts'>
const props = defineProps<{
    stepsList: {
        id: number
        label: string
        component: string
    }[]
    currentStep: {
        id: number
        label: string
        component: string
    }
}>()
</script>

<template>
    <nav aria-label="Progress">
        <ol role="list" class="space-y-4 md:flex md:space-x-8 md:space-y-0">
            <li v-for="(step, index) in stepsList" :key="step.id" class="md:flex-1">
                <div class="group flex flex-col border-l-4 py-3 px-4 md:border-l-0 md:border-t-4"
                    :class="[
                        index + 1 < currentStep.id  // Previous step
                            ? 'border-slate-500'
                            : currentStep.id == index + 1  // Current step
                                ? ' border-slate-500'
                                : 'text-gray-400 border-slate-300'
                    ]">
                    <span class="text-xl font-medium">{{ step.id }}</span>
                    <span class="text-sm font-medium">{{ step.label }}</span>
                </div>
            </li>
        </ol>
    </nav>
</template>