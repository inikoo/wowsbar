<script setup lang="ts">
const props = defineProps<{
    data: array,
}>()
</script>

<template>
    <div class="bg-white py-24 sm:py-32">
      <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl sm:text-center">
          <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Meet our leadership</h2>
          <p class="mt-6 text-lg leading-8 text-gray-600">We’re a dynamic group of individuals who are passionate about what we do and dedicated to delivering the best results for our clients.</p>
        </div>
        <ul role="list" class="mx-auto mt-20 grid max-w-2xl grid-cols-1 gap-x-6 gap-y-20 sm:grid-cols-2 lg:max-w-4xl lg:gap-x-8 xl:max-w-none">
          <li v-for="person in data" :key="person.name" class="flex flex-col gap-6 xl:flex-row">
            <img class="aspect-[4/5] w-52 flex-none rounded-2xl object-cover" :src="person.imageUrl" alt="" />
            <div class="flex-auto">
              <h3 class="text-lg font-semibold leading-8 tracking-tight text-gray-900">{{ person.name }}</h3>
              <p class="text-base leading-7 text-gray-600">{{ person.role }}</p>
              <p class="mt-6 text-base leading-7 text-gray-600">{{ person.bio }}</p>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </template>
  
