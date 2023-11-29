<script setup lang='ts'>
import { trans } from 'laravel-vue-i18n'
import { useLocaleStore } from '@/Stores/locale'

    const props = defineProps<{
        emailsEstimated: number
    }>()
</script>

<template>
    <div class="px-5 py-1 w-full border-b mb-1 flex justify-between">
        <div class="text-gray-500">
            {{ trans('Estimated recipients') }}:
            <span class="font-semibold text-gray-700">{{ useLocaleStore().number(emailsEstimated) }}</span>
        </div>
        <slot />
    </div>
</template>
