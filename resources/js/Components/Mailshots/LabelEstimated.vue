<script setup lang='ts'>
import { trans } from 'laravel-vue-i18n'
import { useLocaleStore } from '@/Stores/locale'

    const props = defineProps<{
        emailsEstimated: number 
    }>()
</script>

<template>
    <div class="py-2 px-4 w-full">
        {{ trans('Estimated receiver') }}: <span class="font-semibold">{{ useLocaleStore().number(emailsEstimated) }}</span>
    </div>
</template>