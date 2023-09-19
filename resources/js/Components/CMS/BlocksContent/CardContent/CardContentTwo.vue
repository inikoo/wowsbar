<script setup lang="ts">
import Text from '@/Components/CMS/Workshops/WorkshopComponents/Text.vue'
const props = defineProps<{
    data: array,
    pageHead : Object
}>()
</script>

<template>
    <div class="bg-white py-24 sm:py-32">
      <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl sm:text-center">
          <Text v-model="pageHead.title" :key="pageHead.title" :placeholder="'Enter your headline value'" :class="``" />
          <Text v-model="pageHead.description" :key="pageHead.description" :placeholder="'Enter your headline value'" :class="``" />
        </div>
        <ul role="list" class="mx-auto mt-20 grid max-w-2xl grid-cols-1 gap-x-6 gap-y-20 sm:grid-cols-2 lg:max-w-4xl lg:gap-x-8 xl:max-w-none">
          <li v-for="person in data" :key="person.name" class="flex flex-col gap-6 xl:flex-row">
            <img class="aspect-[4/5] w-52 flex-none rounded-2xl object-cover" :src="person.imageUrl" alt="" />
            <div class="flex-auto">
              <Text v-model="person.name" :key="person.name" :placeholder="'Enter your headline value'" :class="``" />
              <Text v-model="person.role" :key="person.role" :placeholder="'Enter your headline value'" :class="``" />
              <Text v-model="person.bio" :key="person.bio" :placeholder="'Enter your headline value'" :class="``" />
            </div>
          </li>
        </ul>
      </div>
    </div>
  </template>
  
