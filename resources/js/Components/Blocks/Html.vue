<script setup lang="ts">


const props = defineProps<{
    data: String
}>()

</script>

<template>
  <div v-html="data"></div>
</template>
