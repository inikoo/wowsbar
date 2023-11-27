<script setup lang='ts'>
import { trans } from 'laravel-vue-i18n'
import { useLocaleStore } from '@/Stores/locale'

    const props = defineProps<{
        emailsEstimated: number
    }>()
</script>

<template>
    <div class="px-5 py-1  w-full border-b mb-1">
        {{ trans('Estimated recipients') }}: <span class="font-semibold">{{ useLocaleStore().number(emailsEstimated) }}</span>
    </div>
</template>
