<script setup lang='ts'>
import { onMounted, onUnmounted } from 'vue'
import { trans } from 'laravel-vue-i18n'
import { useLocaleStore } from '@/Stores/locale'

const props = defineProps<{
    emailsEstimated: number
    idMailshot: number
}>()

onMounted(() => {
    window.Echo.private('org.general')
    .listen(`.mailshot.${props.idMailshot}`, (e: any) => {
        console.log('wwwwwwwwwww')
        // if (e.data.counts.prospects !==  undefined) {
        //     props.stats.prospects.stat = e.data.counts.prospects
        // }
    })
})

onUnmounted(() => {
    Echo.private(`org.general`)
    .stopListening(`.mailshot.${props.idMailshot}`)
})

</script>

<template>
    <div class="px-5 py-1 w-full border-b flex justify-between">
        <slot name="content">
            <div class="text-gray-500">
                {{ trans('Estimated recipients') }}:
                <span class="font-semibold text-gray-700">{{ useLocaleStore().number(emailsEstimated) }}</span>
            </div>
        </slot>
    </div>
</template>
