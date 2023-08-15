<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 31 Jul 2023 16:01:32 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->
  <script setup lang="ts">
  const props = defineProps<{
    data: Object
  }>()
  
  console.log('sideImage', props)
  </script>
    
  <template>
        <div  class="mt-10 px-4 sm:mt-16 lg:mt-0 p-5">
          <section aria-labelledby="details-heading" class="mt-10">
            <div class="sm:max-w-lg">
              <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">{{ data.title }}</h1>
              <span class="text-3xl text-gray-600 font-light">{{ data.subtitle }}</span>
              <p class="mt-4 text-xl text-gray-500">
                {{ data.description }}
              </p>
            </div>
          </section>
        </div>
  </template>
    
    
    