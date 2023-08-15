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
  <div :class="`lg:grid lg:grid-cols-${data.dataRow.length} lg:items-start lg:gap-x-8`">
    <div v-for="(item, index) of data.dataRow" :key="index">
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
              <span :class="[open ? 'text-indigo-600' : 'text-gray-900', 'text-sm font-medium']">{{ detail.name }}</span>
              <span class="ml-6 flex items-center">
                <PlusIcon v-if="!open" class="block h-6 w-6 text-gray-400 group-hover:text-gray-500" aria-hidden="true" />
                <MinusIcon v-else class="block h-6 w-6 text-indigo-400 group-hover:text-indigo-500" aria-hidden="true" />
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

      <div v-if="item.type == 'banner'" class="relative bg-gray-900">
        <div aria-hidden="true" class="absolute inset-0 overflow-hidden">
          <img :src="item.img.src" :alt="item.img.alt"
            class="h-full w-full object-cover object-center" />
        </div>
        <div aria-hidden="true" class="absolute inset-0 bg-gray-900 opacity-50" />

        <div class="relative mx-auto flex max-w-3xl flex-col items-center px-6 py-32 text-center sm:py-64 lg:px-0">
          <h1 class="text-4xl font-bold tracking-tight text-white lg:text-6xl">{{ item.title }}</h1>
          <p class="mt-4 text-xl text-white">{{ item.subtitle }}</p>
          <a :href="item.button.link"
            class="mt-8 inline-block rounded-md border border-transparent bg-white px-8 py-3 text-base font-medium text-gray-900 hover:bg-gray-100">Shop
            {{ item.button.text }}</a>
        </div>
      </div>

      <div v-if="item.type == 'colllection'"
        class="mx-auto max-w-xl px-4 pt-24 sm:px-6 sm:pt-32 lg:max-w-7xl lg:px-8 py-24">
        <h2 id="collection-heading" class="text-2xl font-bold tracking-tight text-gray-900">{{ item.title }}</h2>
        <p class="mt-4 text-base text-gray-500">{{ item.subtitle }}</p>

        <div class="mt-10 space-y-12 lg:grid lg:grid-cols-3 lg:gap-x-8 lg:space-y-0">
          <a v-for="collection in item.collections" :key="collection.name" :href="collection.href" class="group block">
            <div aria-hidden="true"
              class="aspect-h-2 aspect-w-3 overflow-hidden rounded-lg lg:aspect-h-6 lg:aspect-w-5 group-hover:opacity-75">
              <img :src="collection.imageSrc" :alt="collection.imageAlt"
                class="h-full w-full object-cover object-center" />
            </div>
            <h3 class="mt-4 text-base font-semibold text-gray-900">{{ collection.name }}</h3>
            <p class="mt-2 text-sm text-gray-500">{{ collection.description }}</p>
          </a>
        </div>
      </div>
    </div>
  </div>
</template>
  
  
  