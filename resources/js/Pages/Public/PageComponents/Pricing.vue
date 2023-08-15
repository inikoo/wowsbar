<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 31 Jul 2023 16:01:32 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->
<script setup lang="ts">

const props = defineProps<{
    data: Object
}>()
</script>
    
<template>
    <div>
        <div class="mt-16 flex justify-center">
            <RadioGroup
                class="grid grid-cols-2 gap-x-1 rounded-full p-1 text-center text-xs font-semibold leading-5 ring-1 ring-inset ring-gray-200">
                <RadioGroupLabel class="sr-only">Payment frequency</RadioGroupLabel>
                <RadioGroupOption as="template" v-for="option in data.frequencies" :key="option.value" :value="option">
                    <div :class="['text-gray-500', 'cursor-pointer rounded-full px-2.5 py-1']">
                        <span>{{ option.label }}</span>
                    </div>
                </RadioGroupOption>
            </RadioGroup>
        </div>
        <div
            class="isolate mx-auto mt-10 grid max-w-md grid-cols-1 gap-8 md:max-w-2xl md:grid-cols-2 lg:max-w-4xl xl:mx-0 xl:max-w-none xl:grid-cols-4 p-11">
            <div v-for="tier in data.tiers" :key="tier.id"
                :class="[tier.mostPopular ? 'ring-2 ring-indigo-600' : 'ring-1 ring-gray-200', 'rounded-3xl p-8']">
                <h2 :id="tier.id"
                    :class="[tier.mostPopular ? 'text-indigo-600' : 'text-gray-900', 'text-lg font-semibold leading-8']">{{
                        tier.name }}</h2>
                <p class="mt-4 text-sm leading-6 text-gray-600">{{ tier.description }}</p>
                <p class="mt-6 flex items-baseline gap-x-1">
                    <span class="text-4xl font-bold tracking-tight text-gray-900">{{ tier.price.monthly }}</span>
                    <span class="text-sm font-semibold leading-6 text-gray-600">{{ tier.price.monthly }}</span>
                </p>
                <a :href="tier.href" :aria-describedby="tier.id"
                    :class="[tier.mostPopular ? 'bg-indigo-600 text-white shadow-sm hover:bg-indigo-500' : 'text-indigo-600 ring-1 ring-inset ring-indigo-200 hover:ring-indigo-300', 'mt-6 block rounded-md py-2 px-3 text-center text-sm font-semibold leading-6 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600']">Buy
                    plan</a>
                <ul role="list" class="mt-8 space-y-3 text-sm leading-6 text-gray-600">
                    <li v-for="feature in tier.features" :key="feature" class="flex gap-x-3">
                        <CheckIcon class="h-6 w-5 flex-none text-indigo-600" aria-hidden="true" />
                        {{ feature }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
    
    
    