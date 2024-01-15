<script setup lang='ts'>
// import { onMounted, onUnmounted } from 'vue'
import { trans } from 'laravel-vue-i18n'
import { useLocaleStore } from '@/Stores/locale'
import ProgressLine from '@/Components/Utils/ProgressLine.vue';
import { Stats } from '@/types/Mailshot'

const props = defineProps<{
    emailsEstimated: number
    idMailshot: number
    state?: string
    stats: Stats
}>()

</script>

<template>
    <div class="px-5 py-1 w-full border-b flex justify-between">
        <slot name="content">
            <div v-if="state == 'sending' || state == 'stopped'">
                <ProgressLine :total="stats.number_dispatched_emails" :success="stats.number_sent_emails" />
            </div>
            <div v-else class="text-gray-500 place-self-center">
                {{ trans('Estimated recipients') }}:
                <span class="font-semibold text-gray-700">{{ useLocaleStore().number(emailsEstimated) }}</span>
            </div>

            <!-- Create progress bar here (if state === 'sending') -->
            <slot name="rightSide" />
        </slot>
    </div>
</template>
