<script setup lang="ts">
import Text from '@/Components/CMS/Workshops/WorkshopComponents/Text.vue'
const props = defineProps<{
    data: array,
}>()
</script>

<template>
    <div class="bg-white py-24 sm:py-32">
      <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <dl class="grid grid-cols-1 gap-x-8 gap-y-16 text-center lg:grid-cols-3">
          <div v-for="stat in data" :key="stat.id" class="mx-auto flex max-w-xs flex-col gap-y-4">
            <Text v-model="stat.value" :key="'value'" :placeholder="'Enter your headline value'" :class="``" />
            <Text v-model="stat.name" :key="'title'" :placeholder="'Enter your headline title'" :class="``" />
          </div>
        </dl>
      </div>
    </div>
  </template>
  
