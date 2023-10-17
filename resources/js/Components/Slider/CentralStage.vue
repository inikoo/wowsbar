<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 24 Jul 2023 12:40:53 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
const props = defineProps<{
    data?: {
        title?: string
        subtitle?: string
        text?: string
        style?: {
            fontSize?: {
                fontTitle: string
                fontSubtitle: string
            }
        }
        textAlign: string
    }

}>()

</script>

<template>
    <div class="absolute px-4 lg:px-6" :class="[{ 'left-0 text-left': data?.textAlign == 'left', 'right-0 text-right': data?.textAlign == 'right' }]">
        <!-- Fallback for FontSize is normal size -->
        <div v-if="data?.title" :style="{...data?.style }" :class="[data?.style?.fontSize?.fontTitle ?? 'text-[25px] lg:text-[44px]']" class="text-gray-100 drop-shadow-md leading-none font-bold">{{ data?.title }}</div>
        <div v-if="data?.subtitle" :style="{...data?.style}" :class="[data?.style?.fontSize?.fontSubtitle ?? 'text-[12px] lg:text-[20px]']" class="text-gray-300 drop-shadow leading-none tracking-widest">{{ data?.subtitle }}</div>
    </div>
</template>

<style lang="scss">

</style>