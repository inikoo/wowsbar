<script setup lang="ts">
import Text from '@/Components/CMS/Workshops/WorkshopComponents/Text.vue'
 const props = defineProps<{
    data: array,
}>()
  </script>

<template>
    <div class="bg-white py-24 sm:py-32">
      <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl lg:max-w-none">
          <div class="text-center">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Trusted by creators worldwide</h2>
            <p class="mt-4 text-lg leading-8 text-gray-600">Lorem ipsum dolor sit amet consect adipisicing possimus.</p>
          </div>
          <dl class="mt-16 grid grid-cols-1 gap-0.5 overflow-hidden rounded-2xl text-center sm:grid-cols-2 lg:grid-cols-4">
            <div v-for="stat in data" :key="stat.id" class="flex flex-col bg-gray-400/5 p-8">
              <Text v-model="stat.value" :key="'value'" :placeholder="'Enter your headline value'" :class="``" />
            <Text v-model="stat.name" :key="'title'" :placeholder="'Enter your headline title'" :class="``" />
            </div>
          </dl>
        </div>
      </div>
    </div>
  </template>
  
  