<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sat, 12 Aug 2023 14:01:37 Malaysia Time, Pantai Lembeng, Bali
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup>







const props = defineProps([
    'layout',
    'structure'
])



</script>

<template>
    <div class="relative ">
        <section class="relative isolate overflow-hidden bg-gray-100"
            :class="[structure.layout.layout !== 'center' ? 'mx-auto max-w-5xl' : '']"
        >
           
         
            <slot />

        
         
        </section>
    </div>

    <!-- <Cookies /> -->
</template>

