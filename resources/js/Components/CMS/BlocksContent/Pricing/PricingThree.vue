
<script setup lang="ts">
import { CheckIcon } from '@heroicons/vue/20/solid'
import Text from '@/Components/CMS/Workshops/WorkshopComponents/Text.vue'

const props = defineProps<{
    data: array,
    pageHead : Object
}>()
</script>

<template>
    <div class="isolate overflow-hidden bg-gray-900">
      <div class="mx-auto max-w-7xl px-6 pb-96 pt-24 text-center sm:pt-32 lg:px-8">
        <div class="mx-auto max-w-4xl">
          <Text v-model="pageHead.subtitle" :key="pageHead.subtitle" :placeholder="'Enter your headline title'" :class="``" />
          <Text v-model="pageHead.title" :key="pageHead.title" :placeholder="'Enter your headline title'" :class="``" />
        </div>
        <div class="relative mt-6">
          <Text v-model="pageHead.description" :key="pageHead.description" :placeholder="'Enter your headline title'" :class="``" />
          <svg viewBox="0 0 1208 1024" class="absolute -top-10 left-1/2 -z-10 h-[64rem] -translate-x-1/2 [mask-image:radial-gradient(closest-side,white,transparent)] sm:-top-12 md:-top-20 lg:-top-12 xl:top-0">
            <ellipse cx="604" cy="512" fill="url(#6d1bd035-0dd1-437e-93fa-59d316231eb0)" rx="604" ry="512" />
            <defs>
              <radialGradient id="6d1bd035-0dd1-437e-93fa-59d316231eb0">
                <stop stop-color="#7775D6" />
                <stop offset="1" stop-color="#E935C1" />
              </radialGradient>
            </defs>
          </svg>
        </div>
      </div>
      <div class="flow-root bg-white pb-24 sm:pb-32">
        <div class="-mt-80">
          <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto grid max-w-md grid-cols-1 gap-8 lg:max-w-4xl lg:grid-cols-2">
              <div v-for="tier in data" :key="tier.id" class="flex flex-col justify-between rounded-3xl bg-white p-8 shadow-xl ring-1 ring-gray-900/10 sm:p-10">
                <div>
                  <Text v-model="tier.name" :key="tier.name" :placeholder="'Enter your headline title'" :class="``" />
                  <div class="mt-4 flex items-baseline gap-x-2">
                    <Text v-model="tier.priceMonthly" :key="tier.priceMonthly" :placeholder="'Enter your headline title'" :class="``" />
                    <span class="text-base font-semibold leading-7 text-gray-600">/month</span>
                  </div>
                  <Text v-model="tier.description" :key="tier.description" :placeholder="'Enter your headline title'" :class="``" />
                  <ul role="list" class="mt-10 space-y-4 text-sm leading-6 text-gray-600">
                    <li v-for="(feature,index) in tier.features" :key="feature" class="flex gap-x-3">
                      <CheckIcon class="h-6 w-5 flex-none text-indigo-600" aria-hidden="true" />
                      <Text v-model="tier.features[index]" :key="feature" :placeholder="'Enter your headline title'" :class="``" />
                    </li>
                  </ul>
                </div>
                <a :href="tier.href" :aria-describedby="tier.id" class="mt-8 block rounded-md bg-indigo-600 px-3.5 py-2 text-center text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Get started today</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
