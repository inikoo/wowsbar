<script setup lang="ts">
import { CheckIcon } from '@heroicons/vue/20/solid'
import Text from '@/Components/CMS/Workshops/WorkshopComponents/Text.vue'
const props = defineProps<{
    data: array,
    pageHead : Object
}>()

</script>

<template>
    <div class="bg-white py-24 sm:py-32">
      <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-4xl text-center">
          <Text v-model="pageHead.title" :key="pageHead.title" :placeholder="'Enter your headline title'" :class="``" />
          <Text v-model="pageHead.description" :key="pageHead.description" :placeholder="'Enter your headline title'" :class="``" />
        </div>
        <div class="isolate mx-auto mt-16 grid max-w-md grid-cols-1 gap-y-8 sm:mt-20 lg:mx-0 lg:max-w-none lg:grid-cols-3">
          <div v-for="(tier, tierIdx) in data" :key="tier.id" :class="[tier.mostPopular ? 'lg:z-10 lg:rounded-b-none' : 'lg:mt-8', tierIdx === 0 ? 'lg:rounded-r-none' : '', tierIdx === data.length - 1 ? 'lg:rounded-l-none' : '', 'flex flex-col justify-between rounded-3xl bg-white p-8 ring-1 ring-gray-200 xl:p-10']">
            <div>
              <div class="flex items-center justify-between gap-x-4">
                <Text v-model="tier.name" :key="tier.name" :placeholder="'Enter your headline title'" :class="``" />
                <p v-if="tier.mostPopular" class="rounded-full bg-indigo-600/10 px-2.5 py-1 text-xs font-semibold leading-5 text-indigo-600">Most popular</p>
              </div>
              <Text v-model="tier.description" :key="tier.description" :placeholder="'Enter your headline title'" :class="``" />
              <p class="mt-6 flex items-baseline gap-x-1">
                <Text v-model="tier.priceMonthly" :key="tier.priceMonthly" :placeholder="'Enter your headline title'" :class="``" />
                <span class="text-sm font-semibold leading-6 text-gray-600">/month</span>
              </p>
              <ul role="list" class="mt-8 space-y-3 text-sm leading-6 text-gray-600">
                <li v-for="(feature,index) in tier.features" :key="feature" class="flex gap-x-3">
                  <CheckIcon class="h-6 w-5 flex-none text-indigo-600" aria-hidden="true" />
                  <Text v-model="tier.features[index]" :key="feature" :placeholder="'Enter your headline title'" :class="``" />
                </li>
              </ul>
            </div>
            <a :href="tier.href" :aria-describedby="tier.id" :class="[tier.mostPopular ? 'bg-indigo-600 text-white shadow-sm hover:bg-indigo-500' : 'text-indigo-600 ring-1 ring-inset ring-indigo-200 hover:ring-indigo-300', 'mt-8 block rounded-md py-2 px-3 text-center text-sm font-semibold leading-6 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600']">Buy plan</a>
          </div>
        </div>
      </div>
    </div>
  </template>
  
