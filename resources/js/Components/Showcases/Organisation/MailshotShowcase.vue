<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 30 Oct 2023 12:50:04 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { computed } from 'vue'
import { useLocaleStore } from '@/Stores/locale.js';
import Timeline from '@/Components/Utils/Timeline.vue'
import CountUp from 'vue-countup-v3';
import {trans} from "laravel-vue-i18n";

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faPaperPlane, faDungeon, faSkull } from '@fal/'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faPaperPlane, faDungeon, faSkull)

const locale = useLocaleStore()
const props = defineProps<{
    data: {
        slug: string
        subject: string
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
    }
    tab?: string
}>()



const dataStatistic = [
    {
        value: props.data.stats.number_dispatched_emails,
        label: trans('Recipients')
    },
    {
        name: 'error',
        label: trans('Errors'),
        class: 'text-red-500',
        value: props.data.stats.number_rejected_emails
    },
    {
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
        ]
    },
    {
        label: trans('delivered'),
        value: props.data.stats.number_delivered_emails
    },

    {
        label: trans('opened'),
        value: props.data.stats.number_opened_emails
    },
    {
        label: trans('clicked'),
        value: props.data.stats.number_clicked_emails
    },
    {
        label: trans('spam'),
        value: props.data.stats.number_spam_emails
    },
    {
        label: trans('unsubscribed'),
        value: props.data.stats.number_unsubscribed_emails
    },
]

const stepsOptions = {
    // recipient_stored_at: props.data.recipient_stored_at,
    schedule_at: props.data.schedule_at,
    ready_at: props.data.ready_at,
    sent_at: props.data.sent_at,
    cancelled_at: props.data.cancelled_at,
    stopped_at: props.data.stopped_at,
    // date: props.data.date,
    created_at: props.data.created_at,
    updated_at: props.data.updated_at,
}

const compSortSteps = computed(() => {
    const outputData: any = {};

    for (const key in stepsOptions) {
        const value = stepsOptions[key];
        if (value) {
            if (!(value in outputData)) {
                outputData[value] = [];
            }
            outputData[value].push(
                key.replace(/_/g, ' ')
            )
        }
    }

    const entries = Object.entries(outputData).map(([key, value]) => ({ key: new Date(key), value }));

    // Sort entries based on Date objects
    entries.sort((a, b) => a.key - b.key);

    const sortedData = entries.reduce((acc, { key, value }) => {
        const isoString = key.toISOString();

        if (acc.hasOwnProperty(isoString)) {
            // If key already exists, append values to the existing array
            acc[isoString] = acc[isoString].concat(value);
        } else {
            // If key does not exist, create a new array with the values
            acc[isoString] = value;
        }

        return acc;
    }, {});

    return sortedData
})


</script>


<template>
    <div class="py-3 mx-auto px-5 w-full">
        <Timeline :options="compSortSteps" />

        <dl class="mt-5 grid grid-flow-col grid-rows-2 md:grid-rows-1 md:divide-x md:divide-y-0 divide-y divide-gray-200 overflow-hidden rounded-lg bg-white shadow">
            <template v-for="(statistic, index) in dataStatistic">
                <div v-if="!(statistic.name == 'error' && statistic.value == 0)" :key="index" class="px-4 py-5 sm:px-4 sm:pt-3 sm:pb-2">
                    <!-- Title -->
                    <dt class="text-gray-400 capitalize text-sm" :class="statistic.class">{{ statistic.label }}</dt>
                    
                    <!-- Value -->
                    <dd class="mt-0.5 flex items-baseline justify-between md:block lg:flex">
                        <div class="flex items-baseline text-2xl font-semibold text-org-600 tabular-nums">
                            <!-- {{ locale.number(statistic.value) }} -->
                            <div v-if="statistic.type == 'multi'" class="flex gap-x-6 flex-wrap">
                                <div v-for="subValue in statistic.list" v-tooltip="subValue.tooltip" class="flex flex-nowrap items-center gap-x-1.5">
                                    <FontAwesomeIcon :icon='subValue.icon' class='text-base text-org-200' aria-hidden='true' />
                                    <span>{{ subValue.value }}</span>
                                </div>
                            </div>
                            <CountUp v-else :endVal="statistic.value" />
                        </div>
                    </dd>
                </div>
            </template>
        </dl>
    </div>

    <!-- <pre>{{ data }}</pre> -->
</template>

