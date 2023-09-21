<script setup lang="ts">
import { ref } from 'vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
import HyperLink from "../Fields/Hyperlink.vue"

const props = defineProps<{
  data: Object
}>()

</script>

<template>
  <header class="bg-white">
    <nav class="mx-auto flex items-center justify-between p-6 lg:px-8" aria-label="Global">
      <a href="#" class="-m-1.5 p-1.5">
        <span class="sr-only">Your Company</span>
        <img class="h-8 w-auto" :src="data.img" alt="" />
      </a>
      <div class="flex gap-3">
        <div class="hidden lg:flex lg:flex-1 lg:justify-end">
          <a href="#" class="flex text-sm font-semibold leading-6 text-gray-900">
            <Button>
              <HyperLink :formList="{
                label: 'label',
                link: 'link',
              }" label="label" :data="data.appointment" :useDelete="false" />
            </Button></a>
        </div>
        <div class="text-gray-300">|</div>
        <div class="hidden lg:flex lg:flex-1 lg:justify-end">
          <a class="text-sm font-semibold leading-6 text-gray-900"><Button>
              <HyperLink :formList="{
                label: 'label',
                link: 'link',
              }" label="label" :data="data.logIn" :useDelete="false" />
            </Button></a>
        </div>
      </div>

    </nav>
  </header>
</template>
  
 