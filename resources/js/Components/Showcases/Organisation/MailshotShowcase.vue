<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 30 Oct 2023 12:50:04 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { computed, ref, Ref, reactive, onMounted, onUnmounted } from 'vue';
import { useLocaleStore } from '@/Stores/locale.js';
import Timeline from '@/Components/Utils/Timeline.vue'
import CountUp from 'vue-countup-v3';
import {trans} from "laravel-vue-i18n";
// import Pusher from 'pusher-js'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faPaperPlane, faDungeon, faSkull } from '@fal'
import { library } from '@fortawesome/fontawesome-svg-core'
import Stats from '@/Components/DataDisplay/Stats.vue'
import { useTimeCountdown, useFormatTime } from '@/Composables/useFormatTime';
library.add(faPaperPlane, faDungeon, faSkull)

interface DateScheduled {
    years: number
    months: number
    days: number
    hours: number
    minutes: number
    seconds: number
}

const props = defineProps<{
    data: {
        id: number
        slug: string
        subject: string
        state: string
        state_icon: {
            icon: string
            class: string
            tooltip: string
        }
        stats: {
            number_estimated_dispatched_emails: number
            number_dispatched_emails: number
            number_error_emails: number
            number_rejected_emails: number
            number_sent_emails: number
            number_delivered_emails: number
            number_hard_bounced_emails: number
            number_soft_bounced_emails: number
            number_opened_emails: number
            number_clicked_emails: number
            number_spam_emails: number
            number_unsubscribed_emails: number

        }
        recipient_stored_at: string
        schedule_at: string
        ready_at: string
        sent_at: string
        cancelled_at: string
        stopped_at: string
        date: string
        created_at: string
        updated_at: string
        timeline: {
            [key: string]: {
                label: string
                icon: string | string[]
            }
        }
    }
    tab?: string
}>()

onMounted(() => {
    window.Echo.private('org.general')
    .listen(`.mailshot.${props.data.id}`, (e: any) => {
        console.log(e)
        // ============ Change here ==============
        // dataStatistic[0].component.update('number_dispatched_emails')
        // dataStatistic[0].component.update('number_rejected_emails')
        // dataStatistic[0].component.update('number_hard_bounced_emails')
        // dataStatistic[0].component.update('number_soft_bounced_emails')
        // dataStatistic[0].component.update('number_delivered_emails')
        // dataStatistic[0].component.update('number_opened_emails')
        // dataStatistic[0].component.update('number_clicked_emails')
        // dataStatistic[0].component.update('number_spam_emails')
        // dataStatistic[0].component.update('number_unsubscribed_emails')
    })
})

onUnmounted(() => {
    Echo.private(`org.general`)
    .stopListening(`.mailshot.${props.data.id}`)
})

// List data of statistic
const dataStatistic = reactive([
    {
        name: 'recipient',
        value: <any>props.data.stats.number_dispatched_emails,
        label: trans('Recipients'),
        component: <any>null
    },
    {
        name: 'error',
        label: trans('Errors'),
        class: 'text-red-500',
        value: props.data.stats.number_rejected_emails,
        component: null
    },
    {
        name: 'bounced',
        label: trans('bounced'),
        type: 'multi',
        list: [
            {
                value: props.data.stats.number_hard_bounced_emails,
                icon: 'fal fa-skull',
                tooltip: trans('Hard bounce')
            },
            {
                value: props.data.stats.number_soft_bounced_emails,
                icon: 'fal fa-dungeon',
                tooltip: trans('Soft bounce')
            }
        ],
        value: 0,
        component: null
    },
    {
        name: 'delivered',
        label: trans('delivered'),
        value: props.data.stats.number_delivered_emails,
        component: null
    },

    {
        name: 'opened',
        label: trans('opened'),
        value: props.data.stats.number_opened_emails,
        component: null
    },
    {
        name: 'clicked',
        label: trans('clicked'),
        value: props.data.stats.number_clicked_emails,
        component: null
    },
    {
        name: 'spam',
        label: trans('spam'),
        value: props.data.stats.number_spam_emails,
        component: null
    },
    {
        name: 'unsubscribed',
        label: trans('unsubscribed'),
        value: props.data.stats.number_unsubscribed_emails,
        component: null
    },
])

// Pusher: subscribe
// const pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
//     cluster: 'ap1'
// })
// const channel = pusher.subscribe('hydrate.sent.emails')
// channel.bind(`mailshot.${props.data.slug}`, (data: any) => {
//     // reactiveProps.value = {...data.mailshot}
//     dataStatistic[0].component.update(data.mailshot.stats.number_dispatched_emails)
//     dataStatistic[0].component.update(data.mailshot.stats.number_rejected_emails)
//     dataStatistic[0].component.update(data.mailshot.stats.number_hard_bounced_emails)
//     dataStatistic[0].component.update(data.mailshot.stats.number_soft_bounced_emails)
//     dataStatistic[0].component.update(data.mailshot.stats.number_delivered_emails)
//     dataStatistic[0].component.update(data.mailshot.stats.number_opened_emails)
//     dataStatistic[0].component.update(data.mailshot.stats.number_clicked_emails)
//     dataStatistic[0].component.update(data.mailshot.stats.number_spam_emails)
//     dataStatistic[0].component.update(data.mailshot.stats.number_unsubscribed_emails)
// })


const countdown: Ref<DateScheduled> = ref(useTimeCountdown(props.data.schedule_at, { zero: true }))

onMounted(() => {
    setInterval(() => {
        countdown.value = useTimeCountdown(props.data.schedule_at, { zero: true })
    }, 1000)
})
</script>


<template>
    <div class="relative">

        <div class="py-3 mx-auto px-5 w-full">
            <!-- <Timeline v-if="data.state === 'sent' || data.state === 'sending' || data.state === 'stopped'" :options="data.timeline" /> -->

            <!-- Component: Countdown Scheduled -->
            <div v-if="data.state == 'scheduled'" v-tooltip="useFormatTime(data.schedule_at, {formatTime: 'hms'})" class="mx-auto bg-white overflow-hidden rounded-md border border-gray-200 w-fit divide-y divide-gray-200">
                <div class="bg-org-500 text-white text-xs text-center py-2 tracking-wider">
                    Mailshot will be send in:
                </div>
                <div class="text-org-700 grid grid-cols-4 divide-x divide-gray-200 tabular-nums">
                    <div class="flex flex-col justify-center items-center gap-y-1.5 text-xl pt-3 pb-2 px-4">
                        <div class="font-semibold leading-4">{{ countdown.days + (countdown.months*30) + (countdown.years*365) }}</div>
                        <div class="text-gray-400 text-xs leading-none">{{ trans('Days') }}</div>
                    </div>
                    <div class="flex flex-col justify-center items-center gap-y-1.5 text-xl pt-3 pb-2 px-4">
                        <div class="font-semibold leading-4">{{ (countdown.hours).toString().padStart(2, '0') }}</div>
                        <div class="text-gray-400 text-xs leading-none">{{ trans('Hours') }}</div>
                    </div>
                    <div class="flex flex-col justify-center items-center gap-y-1.5 text-xl pt-3 pb-2 px-4">
                        <div class="font-semibold leading-4">{{ (countdown.minutes).toString().padStart(2, '0') }}</div>
                        <div class="text-gray-400 text-xs leading-none">{{ trans('Minutes') }}</div>
                    </div>
                    <div class="flex flex-col justify-center items-center gap-y-1.5 text-xl pt-3 pb-2 px-4">
                        <div class="font-semibold leading-4">{{ (countdown.seconds).toString().padStart(2, '0') }}</div>
                        <div class="text-gray-400 text-xs leading-none">{{ trans('Seconds') }}</div>
                    </div>
                </div>
            </div>

            <Stats
                v-if="data.state == 'in-process' || data.state == 'ready'"
                :stats="[{ name: 'recipient', stat: dataStatistic[0].value }]"
            />

            <dl v-else class="mt-5 grid grid-flow-col grid-rows-2 md:grid-rows-1 gap-[1px] overflow-hidden rounded-lg bg-gray-200 shadow">
                <template v-for="(statistic, index) in dataStatistic">
                    <div v-if="!(statistic.name == 'error' && statistic.value == 0)" :key="index" class="bg-white px-4 py-5 sm:px-4 sm:pt-3 sm:pb-2">
                        <!-- Title -->
                        <dt class="text-gray-400 capitalize text-sm" :class="statistic.class">{{ statistic.label }}</dt>
                        <!-- Value -->
                        <dd class="mt-0.5 flex items-baseline justify-between md:block lg:flex">
                            <div class="flex items-baseline text-2xl font-semibold text-org-600 tabular-nums">
                                <div v-if="statistic.type == 'multi'" class="flex gap-x-6 flex-wrap">
                                    <div v-for="subValue in statistic.list" v-tooltip="subValue.tooltip" class="flex flex-nowrap items-center gap-x-1.5">
                                        <FontAwesomeIcon :icon='subValue.icon' class='text-base text-org-200' aria-hidden='true' />
                                        <span>{{ subValue.value }}</span>
                                    </div>
                                </div>
                                <CountUp v-else @init="(el) => statistic.component = el" :endVal="statistic.value" :scrollSpyOnce="true" :duration="1.2" />
                            </div>
                        </dd>
                    </div>
                </template>
            </dl>
        </div>
    </div>

</template>

