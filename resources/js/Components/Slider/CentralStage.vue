<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 24 Jul 2023 12:40:53 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { get } from 'lodash'
const props = defineProps<{
    data: {
        title?: string
        subtitle?: string
        text?: string
        style : Object
    }
  
}>()
console.log('as',props)
</script>

<template>
    <div class="absolute">
        <div v-if="data.title" :style="{...data.style, fontSize : get(data,['style','fontSize','fontTitle'],'27px') }" class="text-gray-100 drop-shadow-md  font-bold">{{ data.title }}</div>
        <div v-if="data.subtitle" :style="{...data.style, fontSize : get(data,['style','fontSize','fontSubtitle'],'14px') }" class="text-gray-300 drop-shadow  tracking-widest">{{ data.subtitle }}</div>
    </div>
</template>

<style lang="scss">
@import url('https://fonts.googleapis.com/css2?family=Comfortaa&family=Laila&family=Lobster&family=Playfair&family=Port+Lligat+Slab&family=Quicksand&family=Yatra+One&display=swap');

</style>