<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 31 Jul 2023 16:01:32 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->
  <script setup lang="ts">
  const props = defineProps<{
    data: Object
  }>()
  </script>
    
  <template>
        <div class="mx-auto max-w-xl px-4 pt-24 sm:px-6 sm:pt-32 lg:max-w-7xl lg:px-8 py-24">
          <h2 id="collection-heading" class="text-2xl font-bold tracking-tight text-gray-900">{{ data.title }}</h2>
          <p class="mt-4 text-base text-gray-500">{{ data.subtitle }}</p>
  
          <div class="mt-10 space-y-12 lg:grid lg:grid-cols-3 lg:gap-x-8 lg:space-y-0">
            <a v-for="collection in data.collections" :key="collection.name" :href="collection.href" class="group block">
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
       
  </template>
    
    
    