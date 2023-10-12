
<script setup lang="ts">

import { onMounted } from 'vue';

const props = defineProps<{
  data: String
}>()
</script>

<template>
  <div v-html="data" ></div>
</template>

