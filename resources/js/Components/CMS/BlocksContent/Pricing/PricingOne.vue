<script setup lang="ts">
import { ref } from 'vue'
import { RadioGroup, RadioGroupLabel, RadioGroupOption } from '@headlessui/vue'
import { CheckIcon, XMarkIcon } from '@heroicons/vue/20/solid'

const props = defineProps<{
    data: array,
}>()



</script>

<template>
    <div class="isolate overflow-hidden">
      <div class="flow-root bg-gray-900 pb-16 pt-24 sm:pt-32 lg:pb-0">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
          <div class="relative z-10">
            <h2 class="mx-auto max-w-4xl text-center text-5xl font-bold tracking-tight text-white">Simple pricing, no commitment</h2>
            <p class="mx-auto mt-4 max-w-2xl text-center text-lg leading-8 text-white/60">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Velit numquam eligendi quos odit doloribus molestiae voluptatum quos odit doloribus.</p>
          </div>
          <div class="relative mx-auto mt-10 grid max-w-md grid-cols-1 gap-y-8 lg:mx-0 lg:-mb-14 lg:max-w-none lg:grid-cols-3">
            <svg viewBox="0 0 1208 1024" aria-hidden="true" class="absolute -bottom-48 left-1/2 h-[64rem] -translate-x-1/2 translate-y-1/2 [mask-image:radial-gradient(closest-side,white,transparent)] lg:-top-48 lg:bottom-auto lg:translate-y-0">
              <ellipse cx="604" cy="512" fill="url(#d25c25d4-6d43-4bf9-b9ac-1842a30a4867)" rx="604" ry="512" />
              <defs>
                <radialGradient id="d25c25d4-6d43-4bf9-b9ac-1842a30a4867">
                  <stop stop-color="#7775D6" />
                  <stop offset="1" stop-color="#E935C1" />
                </radialGradient>
              </defs>
            </svg>
            <div class="hidden lg:absolute lg:inset-x-px lg:bottom-0 lg:top-4 lg:block lg:rounded-t-2xl lg:bg-gray-800/80 lg:ring-1 lg:ring-white/10" aria-hidden="true" />
            <div v-for="tier in data" :key="tier.id" :class="[tier.mostPopular ? 'z-10 bg-white shadow-xl ring-1 ring-gray-900/10' : 'bg-gray-800/80 ring-1 ring-white/10 lg:bg-transparent lg:pb-14 lg:ring-0', 'relative rounded-2xl']">
              <div class="p-8 lg:pt-12 xl:p-10 xl:pt-14">
                <h3 :id="tier.id" :class="[tier.mostPopular ? 'text-gray-900' : 'text-white', 'text-sm font-semibold leading-6']">{{ tier.name }}</h3>
                <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between lg:flex-col lg:items-stretch">
                  <div class="mt-2 flex items-center gap-x-4">
                    <p :class="[tier.mostPopular ? 'text-gray-900' : 'text-white', 'text-4xl font-bold tracking-tight']">{{ tier.priceMonthly }}</p>
                    <div class="text-sm leading-5">
                      <p :class="tier.mostPopular ? 'text-gray-900' : 'text-white'">USD</p>
                    </div>
                  </div>
                  <a :href="tier.href" :aria-describedby="tier.id" :class="[tier.mostPopular ? 'bg-indigo-600 shadow-sm hover:bg-indigo-500 focus-visible:outline-indigo-600' : 'bg-white/10 hover:bg-white/20 focus-visible:outline-white', 'rounded-md py-2 px-3 text-center text-sm font-semibold leading-6 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2']">Buy this plan</a>
                </div>
                <div class="mt-8 flow-root sm:mt-10">
                  <ul role="list" :class="[tier.mostPopular ? 'divide-gray-900/5 border-gray-900/5 text-gray-600' : 'divide-white/5 border-white/5 text-white', '-my-2 divide-y border-t text-sm leading-6 lg:border-t-0']">
                    <li v-for="mainFeature in tier.features" :key="mainFeature" class="flex gap-x-3 py-2">
                      <CheckIcon :class="[tier.mostPopular ? 'text-indigo-600' : 'text-gray-500', 'h-6 w-5 flex-none']" aria-hidden="true" />
                      {{ mainFeature }}
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
