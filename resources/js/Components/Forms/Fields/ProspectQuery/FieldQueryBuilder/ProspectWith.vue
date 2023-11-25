<script setup lang="ts">
import descriptor from '../descriptor'
import { trans } from "laravel-vue-i18n";

const props = withDefaults(defineProps<{
    value: any
    fieldName : any
}>(), {})
</script>

<template>
    <div class="flex flex-wrap items-center">
        <div v-for="(query, index) in descriptor.QueryLists" :key="query.value"
            class="flex items-center mr-4 mb-2 py-[4px] px-2.5 border border-solid border-gray-300 rounded-lg">
            <input type="checkbox" v-model="value[fieldName].by" :id="query.value" :key="query.value"
                :value="query.value" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4">
            <label :for="query.value" class="ml-2">{{ trans(query.label) }}</label>
        </div>
    </div>
</template>

