<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 24 Jul 2023 12:14:58 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">

const props = defineProps<{
    data: {
        text?: string

    }
}>()

</script>

<template>
    <div v-if="data.text" class="text-gray-400 text-xs">
        {{ data.text }}
    </div>
</template>
