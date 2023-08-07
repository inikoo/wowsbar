<script setup>
import { trans } from 'laravel-vue-i18n'
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label class="block font-medium text-sm text-gray-600">
        <span v-if="value">{{ value ? trans(value) : '' }}</span>
        <span v-else><slot /></span>
    </label>
</template>
