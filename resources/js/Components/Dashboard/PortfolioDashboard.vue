<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Thu, 31 Aug 2023 10:39:28 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup>
import FlatTreeMap from '@/Components/Navigation/FlatTreeMap.vue'
defineProps(['data', 'tab'])
</script>
<template>
    <FlatTreeMap class="mx-4 mt-4" v-for="(treeMap, idx) in data.flatTreeMaps" :key="idx" :nodes="treeMap" />
</template>

