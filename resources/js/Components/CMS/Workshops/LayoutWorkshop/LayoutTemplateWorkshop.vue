<script setup lang="ts">
import Edit from '@/Components/Edit.vue';

const props = defineProps<{
    data: any
    imagesUploadRoute: any
    updateRoutes: any
}>()
</script>

<template>
    <div>
        <Edit :formData="data.formData" />
    </div>

</template>

<style scoped>
</style>