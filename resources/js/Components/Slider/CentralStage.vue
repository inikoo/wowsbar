<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 24 Jul 2023 12:40:53 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">

const props = defineProps<{
    data: {
        title?: string
        subtitle?: string
        text?: string
    }
}>()

</script>

<template>
    <div class="absolute">
        <div v-if="data.title" class="text-gray-100 drop-shadow-md text-5xl font-bold">{{ data.title }}</div>
        <div v-if="data.subtitle" class="text-gray-300 drop-shadow text-base italic tracking-widest">{{ data.subtitle }}</div>
    </div>
</template>
