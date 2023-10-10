<script setup lang="ts">
import Edit from '@/Components/Edit.vue';

const props = defineProps<{
    data: any
    imagesUploadRoute: any
    updateRoutes: any
}>()
</script>

<template>
    <!-- <div>
        <Edit :formData="data.formData" />
    </div> -->
    <div class="bg-white">
  <div class="relative">
    <div class="mx-auto max-w-7xl">
      <div class="relative z-10 pt-14 lg:w-full lg:max-w-2xl">
        <svg class="absolute inset-y-0 right-8 hidden h-full w-80 translate-x-1/2 transform fill-white lg:block" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
          <polygon points="0,0 90,0 50,100 0,100" />
        </svg>

        <div class="relative px-6 py-32 sm:py-40 lg:px-8 lg:py-56 lg:pr-0">
          <div class="mx-auto max-w-2xl lg:mx-0 lg:max-w-xl">
            <div class="hidden sm:mb-10 sm:flex">
              <div class="relative rounded-full px-3 py-1 text-sm leading-6 text-gray-500 ring-1 ring-gray-900/10 hover:ring-gray-900/20">
                Anim aute id magna aliqua ad ad non deserunt sunt. <a href="#" class="whitespace-nowrap font-semibold text-indigo-600"><span class="absolute inset-0" aria-hidden="true"></span>Read more <span aria-hidden="true">&rarr;</span></a>
              </div>
            </div>
            <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">Data to enrich your online business</h1>
            <p class="mt-6 text-lg leading-8 text-gray-600">Anim aute id magna aliqua ad ad non deserunt sunt. Qui irure qui lorem cupidatat commodo. Elit sunt amet fugiat veniam occaecat fugiat aliqua.</p>
            <div class="mt-10 flex items-center gap-x-6">
              <a href="#" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Get started</a>
              <a href="#" class="text-sm font-semibold leading-6 text-gray-900">Learn more <span aria-hidden="true">→</span></a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="bg-gray-50 lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
      <img class="aspect-[3/2] object-cover lg:aspect-auto lg:h-full lg:w-full" src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1587&q=80" alt="">
    </div>
  </div>
</div>

</template>

<style scoped>

</style>