<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 30 Oct 2023 12:50:04 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { computed } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faPaperPlane } from '@fal/'
import { library } from '@fortawesome/fontawesome-svg-core'
import { useLocaleStore } from '@/Stores/locale.js';
import Timeline from '@/Components/Utils/Timeline.vue'

library.add(faPaperPlane)

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
            number_dispatched_emails_state_clicked: number
            number_dispatched_emails: number
            number_dispatched_emails_state_error: number
            number_dispatched_emails_state_hard_bounce: number
            number_dispatched_emails_state_soft_bounce: number
            number_dispatched_emails_state_clicked: number
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
        value: props.data.stats.number_estimated_dispatched_emails,
        label: 'est. dispatched emails'
    },
    {
        value: props.data.stats.number_dispatched_emails,
        label: 'actual dispatched emails'
    },
    {
        value: props.data.stats.number_dispatched_emails_state_error,
        label: 'Emails error'
    },
    {
        value: props.data.stats.number_dispatched_emails_state_hard_bounce + props.data.stats.number_dispatched_emails_state_soft_bounce,
        label: 'Emails bounce'
    },
    {
        value: props.data.stats.number_dispatched_emails_state_clicked,
        label: 'Emails clicked'
    },
    {
        value: props.data.stats.number_dispatched_emails_state_unsubscribed,
        label: 'Emails unsubscribed'
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
    <!-- <pre>{{ compSortSteps }}</pre>------------------ -->
    <!-- <pre>{{ stepsOptions }}</pre>--------------- -->
    <!-- <div v-for="xx, zz in compSortSteps">
        {{ zz }}
    </div> -->
    <div class="py-3 mx-auto px-5 w-full">
        <Timeline :options="compSortSteps" />
        <!-- <div class="grid grid-cols-4 h-64 w-full gap-x-2 gap-y-3">
            <div
                class="bg-[radial-gradient(ellipse_at_bottom,_var(--tw-gradient-stops))] from-org-700 to-gray-900 text-gray-50 flex flex-col justify-end px-7 py-6 rounded-lg shadow col-span-2 row-span-2 border-2 border-org-100">
                <span class="text-gray-300 italic">{{ data.slug }}</span>
                <div class="text-3xl font-semibold leading-none">
                    {{ data.subject }}
                    <FontAwesomeIcon :icon='data.state_icon.icon' class="text-lg" :class='data.state_icon.class'
                        :title="data.state_icon.tooltip" aria-hidden='true' />
                </div>
            </div>
        </div> -->

        <div>
            <!-- <h3 class="font-semibold leading-6 text-gray-900">Last 30 days</h3> -->
            <dl
                class="mt-5 grid grid-cols-1 divide-y divide-gray-200 overflow-hidden rounded-lg bg-white shadow md:grid-cols-3 md:divide-x md:divide-y-1">
                <div v-for="(statistic, index) in dataStatistic" :key="index" class="px-4 py-5 sm:p-6">
                    <dt class="text-gray-400 capitalize">{{ statistic.label }}</dt>
                    <dd class="mt-1 flex items-baseline justify-between md:block lg:flex">
                        <div class="flex items-baseline text-2xl font-semibold text-org-600">
                            {{ locale.number(statistic.value) }}
                            <!-- <span class="ml-2 text-sm font-medium text-gray-500">from {{ statistic.value }}</span> -->
                        </div>

                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- <pre>{{ data }}</pre> -->
</template>

