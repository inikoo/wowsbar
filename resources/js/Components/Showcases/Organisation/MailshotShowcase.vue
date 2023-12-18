<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 30 Oct 2023 12:50:04 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {ref, Ref, reactive, onMounted, onUnmounted} from 'vue'
import {useLocaleStore} from '@/Stores/locale.js'
import TimelineWithPlaceholder from '@/Components/Utils/TimelineWithPlaceholder.vue'
import CountUp from 'vue-countup-v3'
import {trans} from "laravel-vue-i18n"
// import Pusher from 'pusher-js'

import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'
import {faPaperPlane, faDungeon, faSkull} from '@fal'
import {faSpinnerThird} from '@fad'
import {library} from '@fortawesome/fontawesome-svg-core'
import Stats from '@/Components/DataDisplay/Stats.vue'
import {useTimeCountdown, useFormatTime} from '@/Composables/useFormatTime'

library.add(faPaperPlane, faDungeon, faSkull, faSpinnerThird)

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
        sent_at: null|string
        cancelled_at: string
        stopped_at: string
        date: string
        created_at: string
        updated_at: string
        timeline: {
            label: string
            icon: string | string[]
            timestamp: string
        }[]
    }
    tab?: string
}>()

onMounted(() => {
    window.Echo.private('org.general')
        .listen(`.mailshot.${props.data.id}`, (e: any) => {
            props.data.sent_at = e.sent_at
            props.data.stats = e.stats  // Update data in box
        })
})

onUnmounted(() => {
    window.Echo.private(`org.general`)
    .stopListening(`.mailshot.${props.data.id}`)
})

const countdown: Ref<DateScheduled> = ref(useTimeCountdown(props.data.schedule_at, {zero: true}))

onMounted(() => {
    setInterval(() => {
        countdown.value = useTimeCountdown(props.data.schedule_at, {zero: true})
    }, 1000)
})

const locale = useLocaleStore();


// console.log(props.data)

</script>


<template>
    <div class="relative">
        <div class="py-3 mx-auto px-5 w-full">
            <TimelineWithPlaceholder :options="data.timeline"/>

            <!-- Component: Countdown Scheduled -->
            <div v-if="data.state == 'scheduled' && countdown" v-tooltip="useFormatTime(data.schedule_at, {formatTime: 'hms'})"
                class="mx-auto bg-white overflow-hidden rounded-md border border-gray-200 w-fit divide-y divide-gray-200">
                <div class="bg-org-500 text-white text-xs text-center py-2 tracking-wider">
                    Mailshot will be send in:
                </div>
                <div class="text-org-700 grid grid-cols-4 divide-x divide-gray-200 tabular-nums">
                    <div class="flex flex-col justify-center items-center gap-y-1.5 text-xl pt-3 pb-2 px-4">
                        <div class="font-semibold leading-4">{{ countdown.days + (countdown.months * 30) + (countdown.years * 365) }}</div>
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


            <!-- Box -->
            <dl class="mt-5 grid grid-flow-col grid-rows-2 md:grid-rows-1 gap-[1px] overflow-hidden rounded-lg bg-gray-200 shadow">
                <!-- Recipient -->
                <div class="bg-white px-4 py-5 sm:px-4 sm:pt-3 sm:pb-2">
                    <dt class="text-gray-400 capitalize text-sm">Recipient</dt>
                    <dd class="mt-0.5 flex items-baseline justify-between md:block lg:flex">
                        <div class="flex items-baseline text-2xl font-semibold text-org-600 tabular-nums">
                            <span>
                                {{ locale.number(data.sent_at ? data.stats.number_dispatched_emails : data.stats.number_estimated_dispatched_emails ?? 0) }}
                            </span>
                            <FontAwesomeIcon v-if="!data.sent_at" icon='fad fa-spinner-third' class='h-4 animate-spin' aria-hidden='true' />
                        </div>
                    </dd>
                </div>

                <!-- Bounce -->
                <div class="bg-white px-4 py-5 sm:px-4 sm:pt-3 sm:pb-2">
                    <dt class="text-gray-400 capitalize text-sm">Bounce</dt>
                    <dd class="mt-0.5 flex items-baseline justify-between md:block lg:flex">
                        <div class="flex items-baseline text-2xl font-semibold text-org-600 tabular-nums">
                            <div class="flex gap-x-6 flex-wrap">
                                <div v-tooltip="'Hard bounce'" class="flex flex-nowrap items-center gap-x-1.5">
                                    <FontAwesomeIcon icon="fal fa-skull" class='text-base text-org-200' aria-hidden='true'/>
                                    <span>{{ locale.number(data.stats.number_hard_bounced_emails) }}</span>
                                </div>
                                <div v-tooltip="'Soft bounce'" class="flex flex-nowrap items-center gap-x-1.5">
                                    <FontAwesomeIcon icon="fal fa-dungeon" class='text-base text-org-200' aria-hidden='true'/>
                                    <span>{{ locale.number(data.stats.number_soft_bounced_emails) }}</span>
                                </div>
                            </div>
                            <!-- <span v-if="index=='recipients' && data.sent_at===null  " class="ml-2">spiner </span> -->
                        </div>
                    </dd>
                </div>

                <!-- Error -->
                <div v-if="data.stats.number_rejected_emails" class="bg-red-50 border border-red-300 px-4 py-5 sm:px-4 sm:pt-3 sm:pb-2">
                    <dt class="text-red-400 capitalize text-sm">Error</dt>
                    <dd class="mt-0.5 flex items-baseline justify-between md:block lg:flex">
                        <div class="flex items-baseline text-2xl font-semibold text-org-600 tabular-nums">
                            <span>
                                {{ locale.number(data.stats.number_rejected_emails) }}
                            </span>
                            <!-- <span v-if="index=='recipients' && data.sent_at===null  " class="ml-2">spiner </span> -->
                        </div>
                    </dd>
                </div>

                <!-- Delivered -->
                <div class="bg-white px-4 py-5 sm:px-4 sm:pt-3 sm:pb-2">
                    <dt class="text-gray-400 capitalize text-sm">Delivered</dt>
                    <dd class="mt-0.5 flex items-baseline justify-between md:block lg:flex">
                        <div class="flex items-baseline text-2xl font-semibold text-org-600 tabular-nums">
                            <span>
                                {{ locale.number(data.stats.number_delivered_emails) }}
                            </span>
                            <!-- <span v-if="index=='recipients' && data.sent_at===null  " class="ml-2">spiner </span> -->
                        </div>
                    </dd>
                </div>

                <!-- Opened -->
                <div class="bg-white px-4 py-5 sm:px-4 sm:pt-3 sm:pb-2">
                    <dt class="text-gray-400 capitalize text-sm">Opened</dt>
                    <dd class="mt-0.5 flex items-baseline justify-between md:block lg:flex">
                        <div class="flex items-baseline text-2xl font-semibold text-org-600 tabular-nums">
                            <span>
                                {{ locale.number(data.stats.number_opened_emails) }}
                            </span>
                            <!-- <span v-if="index=='recipients' && data.sent_at===null  " class="ml-2">spiner </span> -->
                        </div>
                    </dd>
                </div>

                <!-- Clicked -->
                <div class="bg-white px-4 py-5 sm:px-4 sm:pt-3 sm:pb-2">
                    <dt class="text-gray-400 capitalize text-sm">Clicked</dt>
                    <dd class="mt-0.5 flex items-baseline justify-between md:block lg:flex">
                        <div class="flex items-baseline text-2xl font-semibold text-org-600 tabular-nums">
                            <span>
                                {{ locale.number(data.stats.number_clicked_emails) }}
                            </span>
                            <!-- <span v-if="index=='recipients' && data.sent_at===null  " class="ml-2">spiner </span> -->
                        </div>
                    </dd>
                </div>

                <!-- Spam -->
                <div class="bg-white px-4 py-5 sm:px-4 sm:pt-3 sm:pb-2">
                    <dt class="text-gray-400 capitalize text-sm">Spam</dt>
                    <dd class="mt-0.5 flex items-baseline justify-between md:block lg:flex">
                        <div class="flex items-baseline text-2xl font-semibold text-org-600 tabular-nums">
                            <span>
                                {{ locale.number(data.stats.number_spam_emails) }}
                            </span>
                            <!-- <span v-if="index=='recipients' && data.sent_at===null  " class="ml-2">spiner </span> -->
                        </div>
                    </dd>
                </div>

                <!-- Unsubscribed -->
                <div class="bg-white px-4 py-5 sm:px-4 sm:pt-3 sm:pb-2">
                    <dt class="text-gray-400 capitalize text-sm">Unsubscribed</dt>
                    <dd class="mt-0.5 flex items-baseline justify-between md:block lg:flex">
                        <div class="flex items-baseline text-2xl font-semibold text-org-600 tabular-nums">
                            <span>
                                {{ locale.number(data.stats.number_unsubscribed_emails) }}
                            </span>
                            <!-- <span v-if="index=='recipients' && data.sent_at===null  " class="ml-2">spiner </span> -->
                        </div>
                    </dd>
                </div>

            </dl>
        </div>
    </div>

</template>

