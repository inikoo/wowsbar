<script setup lang="ts">
import { ref } from 'vue'
import { RadioGroup, RadioGroupLabel, RadioGroupOption } from '@headlessui/vue'
import { CheckIcon, XMarkIcon } from '@heroicons/vue/20/solid'

const frequencies = [
  { value: 'monthly', label: 'Monthly' },
  { value: 'annually', label: 'Annually' },
]
const tiers = [
  {
    name: 'Starter',
    id: 'tier-starter',
    href: '#',
    featured: false,
    description: 'All your essential business finances, taken care of.',
    price: { monthly: '$15', annually: '$144' },
    mainFeatures: ['Basic invoicing', 'Easy to use accounting', 'Mutli-accounts'],
  },
  {
    name: 'Scale',
    id: 'tier-scale',
    href: '#',
    featured: true,
    description: 'The best financial services for your thriving business.',
    price: { monthly: '$60', annually: '$576' },
    mainFeatures: [
      'Advanced invoicing',
      'Easy to use accounting',
      'Mutli-accounts',
      'Tax planning toolkit',
      'VAT & VATMOSS filing',
      'Free bank transfers',
    ],
  },
  {
    name: 'Growth',
    id: 'tier-growth',
    href: '#',
    featured: false,
    description: 'Convenient features to take your business to the next level.',
    price: { monthly: '$30', annually: '$288' },
    mainFeatures: ['Basic invoicing', 'Easy to use accounting', 'Mutli-accounts', 'Tax planning toolkit'],
  },
]
const sections = [
  {
    name: 'Catered for business',
    features: [
      { name: 'Tax Savings', tiers: { Starter: true, Scale: true, Growth: true } },
      { name: 'Easy to use accounting', tiers: { Starter: true, Scale: true, Growth: true } },
      { name: 'Multi-accounts', tiers: { Starter: '3 accounts', Scale: 'Unlimited accounts', Growth: '7 accounts' } },
      { name: 'Invoicing', tiers: { Starter: '3 invoices', Scale: 'Unlimited invoices', Growth: '10 invoices' } },
      { name: 'Exclusive offers', tiers: { Starter: false, Scale: true, Growth: true } },
      { name: '6 months free advisor', tiers: { Starter: false, Scale: true, Growth: true } },
      { name: 'Mobile and web access', tiers: { Starter: false, Scale: true, Growth: false } },
    ],
  },
  {
    name: 'Other perks',
    features: [
      { name: '24/7 customer support', tiers: { Starter: true, Scale: true, Growth: true } },
      { name: 'Instant notifications', tiers: { Starter: true, Scale: true, Growth: true } },
      { name: 'Budgeting tools', tiers: { Starter: true, Scale: true, Growth: true } },
      { name: 'Digital receipts', tiers: { Starter: true, Scale: true, Growth: true } },
      { name: 'Pots to separate money', tiers: { Starter: false, Scale: true, Growth: true } },
      { name: 'Free bank transfers', tiers: { Starter: false, Scale: true, Growth: false } },
      { name: 'Business debit card', tiers: { Starter: false, Scale: true, Growth: false } },
    ],
  },
]

const frequency = ref(frequencies[0])
</script>

<template>
    <div class="isolate overflow-hidden">
      <div class="flow-root bg-gray-900 pb-16 pt-24 sm:pt-32 lg:pb-0">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
          <div class="relative z-10">
            <h2 class="mx-auto max-w-4xl text-center text-5xl font-bold tracking-tight text-white">Simple pricing, no commitment</h2>
            <p class="mx-auto mt-4 max-w-2xl text-center text-lg leading-8 text-white/60">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Velit numquam eligendi quos odit doloribus molestiae voluptatum quos odit doloribus.</p>
            <div class="mt-16 flex justify-center">
              <RadioGroup v-model="frequency" class="grid grid-cols-2 gap-x-1 rounded-full bg-white/5 p-1 text-center text-xs font-semibold leading-5 text-white">
                <RadioGroupLabel class="sr-only">Payment frequency</RadioGroupLabel>
                <RadioGroupOption as="template" v-for="option in frequencies" :key="option.value" :value="option" v-slot="{ checked }">
                  <div :class="[checked ? 'bg-indigo-500' : '', 'cursor-pointer rounded-full px-2.5 py-1']">
                    <span>{{ option.label }}</span>
                  </div>
                </RadioGroupOption>
              </RadioGroup>
            </div>
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
            <div v-for="tier in tiers" :key="tier.id" :class="[tier.featured ? 'z-10 bg-white shadow-xl ring-1 ring-gray-900/10' : 'bg-gray-800/80 ring-1 ring-white/10 lg:bg-transparent lg:pb-14 lg:ring-0', 'relative rounded-2xl']">
              <div class="p-8 lg:pt-12 xl:p-10 xl:pt-14">
                <h3 :id="tier.id" :class="[tier.featured ? 'text-gray-900' : 'text-white', 'text-sm font-semibold leading-6']">{{ tier.name }}</h3>
                <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between lg:flex-col lg:items-stretch">
                  <div class="mt-2 flex items-center gap-x-4">
                    <p :class="[tier.featured ? 'text-gray-900' : 'text-white', 'text-4xl font-bold tracking-tight']">{{ tier.price[frequency.value] }}</p>
                    <div class="text-sm leading-5">
                      <p :class="tier.featured ? 'text-gray-900' : 'text-white'">USD</p>
                      <p :class="tier.featured ? 'text-gray-500' : 'text-gray-400'">{{ `Billed ${frequency.value}` }}</p>
                    </div>
                  </div>
                  <a :href="tier.href" :aria-describedby="tier.id" :class="[tier.featured ? 'bg-indigo-600 shadow-sm hover:bg-indigo-500 focus-visible:outline-indigo-600' : 'bg-white/10 hover:bg-white/20 focus-visible:outline-white', 'rounded-md py-2 px-3 text-center text-sm font-semibold leading-6 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2']">Buy this plan</a>
                </div>
                <div class="mt-8 flow-root sm:mt-10">
                  <ul role="list" :class="[tier.featured ? 'divide-gray-900/5 border-gray-900/5 text-gray-600' : 'divide-white/5 border-white/5 text-white', '-my-2 divide-y border-t text-sm leading-6 lg:border-t-0']">
                    <li v-for="mainFeature in tier.mainFeatures" :key="mainFeature" class="flex gap-x-3 py-2">
                      <CheckIcon :class="[tier.featured ? 'text-indigo-600' : 'text-gray-500', 'h-6 w-5 flex-none']" aria-hidden="true" />
                      {{ mainFeature }}
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="relative bg-gray-50 lg:pt-14">
        <div class="mx-auto max-w-7xl px-6 py-24 sm:py-32 lg:px-8">
          <!-- Feature comparison (up to lg) -->
          <section aria-labelledby="mobile-comparison-heading" class="lg:hidden">
            <h2 id="mobile-comparison-heading" class="sr-only">Feature comparison</h2>
  
            <div class="mx-auto max-w-2xl space-y-16">
              <div v-for="tier in tiers" :key="tier.id" class="border-t border-gray-900/10">
                <div :class="[tier.featured ? 'border-indigo-600' : 'border-transparent', '-mt-px w-72 border-t-2 pt-10 md:w-80']">
                  <h3 :class="[tier.featured ? 'text-indigo-600' : 'text-gray-900', 'text-sm font-semibold leading-6']">{{ tier.name }}</h3>
                  <p class="mt-1 text-sm leading-6 text-gray-600">{{ tier.description }}</p>
                </div>
  
                <div class="mt-10 space-y-10">
                  <div v-for="section in sections" :key="section.name">
                    <h4 class="text-sm font-semibold leading-6 text-gray-900">{{ section.name }}</h4>
                    <div class="relative mt-6">
                      <!-- Fake card background -->
                      <div aria-hidden="true" class="absolute inset-y-0 right-0 hidden w-1/2 rounded-lg bg-white shadow-sm sm:block" />
  
                      <div :class="[tier.featured ? 'ring-2 ring-indigo-600' : 'ring-1 ring-gray-900/10', 'relative rounded-lg bg-white shadow-sm sm:rounded-none sm:bg-transparent sm:shadow-none sm:ring-0']">
                        <dl class="divide-y divide-gray-200 text-sm leading-6">
                          <div v-for="feature in section.features" :key="feature.name" class="flex items-center justify-between px-4 py-3 sm:grid sm:grid-cols-2 sm:px-0">
                            <dt class="pr-4 text-gray-600">{{ feature.name }}</dt>
                            <dd class="flex items-center justify-end sm:justify-center sm:px-4">
                              <span v-if="typeof feature.tiers[tier.name] === 'string'" :class="tier.featured ? 'font-semibold text-indigo-600' : 'text-gray-900'">{{ feature.tiers[tier.name] }}</span>
                              <template v-else>
                                <CheckIcon v-if="feature.tiers[tier.name] === true" class="mx-auto h-5 w-5 text-indigo-600" aria-hidden="true" />
                                <XMarkIcon v-else class="mx-auto h-5 w-5 text-gray-400" aria-hidden="true" />
                                <span class="sr-only">{{ feature.tiers[tier.name] === true ? 'Yes' : 'No' }}</span>
                              </template>
                            </dd>
                          </div>
                        </dl>
                      </div>
  
                      <!-- Fake card border -->
                      <div aria-hidden="true" :class="[tier.featured ? 'ring-2 ring-indigo-600' : 'ring-1 ring-gray-900/10', 'pointer-events-none absolute inset-y-0 right-0 hidden w-1/2 rounded-lg sm:block']" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
  
          <!-- Feature comparison (lg+) -->
          <section aria-labelledby="comparison-heading" class="hidden lg:block">
            <h2 id="comparison-heading" class="sr-only">Feature comparison</h2>
  
            <div class="grid grid-cols-4 gap-x-8 border-t border-gray-900/10 before:block">
              <div v-for="tier in tiers" :key="tier.id" aria-hidden="true" class="-mt-px">
                <div :class="[tier.featured ? 'border-indigo-600' : 'border-transparent', 'border-t-2 pt-10']">
                  <p :class="[tier.featured ? 'text-indigo-600' : 'text-gray-900', 'text-sm font-semibold leading-6']">{{ tier.name }}</p>
                  <p class="mt-1 text-sm leading-6 text-gray-600">{{ tier.description }}</p>
                </div>
              </div>
            </div>
  
            <div class="-mt-6 space-y-16">
              <div v-for="section in sections" :key="section.name">
                <h3 class="text-sm font-semibold leading-6 text-gray-900">{{ section.name }}</h3>
                <div class="relative -mx-8 mt-10">
                  <!-- Fake card backgrounds -->
                  <div class="absolute inset-x-8 inset-y-0 grid grid-cols-4 gap-x-8 before:block" aria-hidden="true">
                    <div class="h-full w-full rounded-lg bg-white shadow-sm" />
                    <div class="h-full w-full rounded-lg bg-white shadow-sm" />
                    <div class="h-full w-full rounded-lg bg-white shadow-sm" />
                  </div>
  
                  <table class="relative w-full border-separate border-spacing-x-8">
                    <thead>
                      <tr class="text-left">
                        <th scope="col">
                          <span class="sr-only">Feature</span>
                        </th>
                        <th v-for="tier in tiers" :key="tier.id" scope="col">
                          <span class="sr-only">{{ tier.name }} tier</span>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(feature, featureIdx) in section.features" :key="feature.name">
                        <th scope="row" class="w-1/4 py-3 pr-4 text-left text-sm font-normal leading-6 text-gray-900">
                          {{ feature.name }}
                          <div v-if="featureIdx !== section.features.length - 1" class="absolute inset-x-8 mt-3 h-px bg-gray-200" />
                        </th>
                        <td v-for="tier in tiers" :key="tier.id" class="relative w-1/4 px-4 py-0 text-center">
                          <span class="relative h-full w-full py-3">
                            <span v-if="typeof feature.tiers[tier.name] === 'string'" :class="[tier.featured ? 'font-semibold text-indigo-600' : 'text-gray-900', 'text-sm leading-6']">{{ feature.tiers[tier.name] }}</span>
                            <template v-else>
                              <CheckIcon v-if="feature.tiers[tier.name] === true" class="mx-auto h-5 w-5 text-indigo-600" aria-hidden="true" />
                              <XMarkIcon v-else class="mx-auto h-5 w-5 text-gray-400" aria-hidden="true" />
                              <span class="sr-only">{{ feature.tiers[tier.name] === true ? 'Yes' : 'No' }}</span>
                            </template>
                          </span>
                        </td>
                      </tr>
                    </tbody>
                  </table>
  
                  <!-- Fake card borders -->
                  <div class="pointer-events-none absolute inset-x-8 inset-y-0 grid grid-cols-4 gap-x-8 before:block" aria-hidden="true">
                    <div v-for="tier in tiers" :key="tier.id" :class="[tier.featured ? 'ring-2 ring-indigo-600' : 'ring-1 ring-gray-900/10', 'rounded-lg']" />
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </template>
  
