<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 03 Nov 2023 13:05:39 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import Stats from "@/Components/DataDisplay/Stats.vue";


const props = defineProps<{
  data:object
}>()
</script>


<template>
    <stats class="p-4" :stats="data.stats"/>
</template>
