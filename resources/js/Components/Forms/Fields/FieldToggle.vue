<script setup lang="ts">
import Toggle from '@/Components/Pure/Toggle.vue'

const props = defineProps<{
    form: any
    fieldName: string
}>()

</script>


<template>
    <div class="flex items-center gap-x-2 transition-all duration-1000 ease-in-out">
        <Toggle v-model="form[fieldName]" />
    </div>
</template>