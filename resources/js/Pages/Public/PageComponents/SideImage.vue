<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 31 Jul 2023 16:01:32 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->
<script setup lang="ts">
import { ref } from 'vue'
import {
  Disclosure,
  DisclosureButton,
  DisclosurePanel,
} from '@headlessui/vue'
import { MinusIcon, PlusIcon } from '@heroicons/vue/24/outline'
const props = defineProps<{

    data: Object
 
}>()
console.log('sideImage', props)
</script>
  
<template>
    <div :class="`lg:grid lg:grid-cols-${data.length} lg:items-start lg:gap-x-8`">
        <div v-for="(item, index) of data" :key="index">
            <div v-if="item.type === 'image'" class="min-h-300 max-h-[32rem] overflow-hidden">
                <img :src="item.src" :alt="item.alt" class="h-full w-full object-cover object-center sm:rounded-lg" />
            </div>

            <div v-if="item.type === 'text'" class="mt-10 px-4 sm:mt-16 lg:mt-0 p-5">
                <section aria-labelledby="details-heading" class="mt-10">
                    <div class="sm:max-w-lg">
                        <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">{{ item.title }}</h1>
                        <span class="text-3xl text-gray-600 font-light">{{ item.subtitle }}</span>
                        <p class="mt-4 text-xl text-gray-500">
                           {{ item.description }}
                        </p>
                    </div>
                </section>
            </div>

            <div v-if="item.type === 'accordion'" class="divide-y divide-gray-200 border-t p-2.5">
                <Disclosure as="div" v-for="detail in item.details" :key="detail.name" v-slot="{ open }">
                  <h3>
                    <DisclosureButton class="group relative flex w-full items-center justify-between py-6 text-left">
                      <span :class="[open ? 'text-indigo-600' : 'text-gray-900', 'text-sm font-medium']">{{ detail.name}}</span>
                      <span class="ml-6 flex items-center">
                        <PlusIcon v-if="!open" class="block h-6 w-6 text-gray-400 group-hover:text-gray-500"
                          aria-hidden="true" />
                        <MinusIcon v-else class="block h-6 w-6 text-indigo-400 group-hover:text-indigo-500"
                          aria-hidden="true" />
                      </span>
                    </DisclosureButton>
                  </h3>
                  <DisclosurePanel as="div" class="prose prose-sm pb-6">
                    <ul role="list">
                      <li v-for="item in detail.items" :key="item">{{ item }}</li>
                    </ul>
                  </DisclosurePanel>
                </Disclosure>
              </div>
        </div>
    </div>
</template>
  
  
  