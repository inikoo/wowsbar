<script setup lang="ts">
import Text from '@/Components/CMS/Workshops/WorkshopComponents/Text.vue'

const props = defineProps<{
    data: array,
    pageHead: Object
}>()

</script>


<template>
    <div class="bg-white py-24 sm:py-32">
      <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
          <Text v-model="pageHead.title" :key="pageHead.title" :placeholder="'Enter your headline value'" :class="``" />
          <Text v-model="pageHead.description" :key="pageHead.description" :placeholder="'Enter your headline value'" :class="``" />
        </div>

        <div class="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-3">
          <article v-for="post in data" :key="post.id" class="flex flex-col items-start justify-between">
            <div class="relative w-full">
              <img :src="post.imageUrl" alt="" class="aspect-[16/9] w-full rounded-2xl bg-gray-100 object-cover sm:aspect-[2/1] lg:aspect-[3/2]" />
              <div class="absolute inset-0 rounded-2xl ring-1 ring-inset ring-gray-900/10" />
            </div>
            <div class="max-w-xl">
              <div class="mt-8 flex items-center gap-x-4 text-xs">
                <time :datetime="post.datetime" class="text-gray-500">{{ post.date }}</time>
                <a :href="post.category.href" class="relative z-10 rounded-full bg-gray-50 px-3 py-1.5 font-medium text-gray-600 hover:bg-gray-100">{{ post.category.title }}</a>
              </div>
              <div>
                <Text v-model="post.title" :key="post.title" :placeholder="'Enter your headline value'" :class="``" />
                <Text v-model="post.description" :key="post.description" :placeholder="'Enter your description'" :class="``" />
              </div>
              <div class="relative mt-8 flex items-center gap-x-4">
                <img :src="post.author.imageUrl" alt="" class="h-10 w-10 rounded-full bg-gray-100" />
                <div class="leading-6">
                  <Text v-model="post.author.name" :key="post.author.name" :placeholder="'Enter your name'" :class="``" />
                  <Text v-model="post.author.role" :key="post.author.role" :placeholder="'Enter your role'" :class="``" />
                </div>
              </div>
            </div>
          </article>
        </div>
      </div>
    </div>
  </template>
  
