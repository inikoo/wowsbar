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
          <img :src="data.src" :alt="data.alt" class="h-full w-full object-cover object-center sm:rounded-lg" />
  </template>
    
    
    