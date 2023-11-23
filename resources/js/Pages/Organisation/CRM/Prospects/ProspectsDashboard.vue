<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 03 Nov 2023 13:05:39 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">

import CountUp from 'vue-countup-v3'
import { routeType } from '@/types/route'
import { Chart as ChartJS, ArcElement, Tooltip, Legend, Colors } from 'chart.js'
import { Doughnut } from 'vue-chartjs'
import {trans} from "laravel-vue-i18n";
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCommentDots, faSadTear, faLaugh, faCommentExclamation, faCommentSlash, faExclamationCircle, faSignIn } from '@fal/'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faCommentDots, faSadTear, faLaugh, faCommentExclamation, faCommentSlash, faExclamationCircle, faSignIn)

ChartJS.register(ArcElement, Tooltip, Legend, Colors)

const props = defineProps<{
    data: {
        prospectStats: object,
        crmStats: {}
        stats: {
            name: string
            stat: number
            href: routeType
        }
    }
}>()

console.log(props.data.prospectStats)
const dataDoughnut = [
    {
        title: trans('Prospects'),
        labels: [trans('Not contacted'), trans('Contacted'), trans('Fail'),trans('Success')],
        total: props.data.prospectStats.prospects.count,
        elements: [

        ],
        datasets: [
            {
                data: [
                    props.data.crmStats.number_prospects_state_no_contacted,
                    props.data.crmStats.number_prospects_state_contacted,
                    props.data.crmStats.number_prospects_state_fail,
                    props.data.crmStats.number_prospects_state_success,
                ]
            }
        ],
        cases: props.data.prospectStats.prospects.cases
    },
    {
        title: trans('Failed'),
        labels: [trans('Not interested'), trans('Unsubscribed'), trans('Invalid')],
        total: props.data.prospectStats.fail.count,
        datasets: [
            {
                data: [
                    props.data.crmStats.number_prospects_fail_status_not_interested,
                    props.data.crmStats.number_prospects_fail_status_unsubscribed,
                    props.data.crmStats.number_prospects_fail_status_invalid
                ]
            }
        ],
        cases: props.data.prospectStats.fail.cases
    },
    {
        title: trans('Success'),
        labels: [trans('Registered'), trans('Invoiced')],
        total: props.data.prospectStats.success.count,
        datasets: [
            {
                data: [
                    props.data.crmStats.number_prospects_success_status_registered,
                    props.data.crmStats.number_prospects_success_status_invoiced,

                ]
            }
        ],
        cases: props.data.prospectStats.success.cases
    },
    {
        title: trans('Contacted'),
        labels: [trans('Registered'), trans('Invoiced')],
        total: props.data.prospectStats.contacted.count,
        datasets: [
            {
                data: [
                    props.data.crmStats.number_prospects_success_status_registered,
                    props.data.crmStats.number_prospects_success_status_invoiced,

                ]
            }
        ],
        cases: props.data.prospectStats.contacted.cases
    },
]

const options = {
    responsive: true,
    plugins: {
        legend: {
            display: false
        },
    }
}
</script>


<template>
    <div class="px-6">
        <dl class="mt-5 grid grid-cols-1 md:grid-cols-3 gap-x-2 gap-y-3">
            <!-- Box: Customers -->
            <div v-for="doughnut in dataDoughnut" class="px-4 py-5 sm:p-6 rounded-lg bg-white hover:bg-org-30 shadow ">
                <dt class="text-base font-medium text-gray-400">{{doughnut.title}}</dt>
                <dd class="flex items-baseline justify-between">
                    <div class="flex flex-col gap-x-2 gap-y-3 leading-none items-baseline text-2xl font-semibold text-org-500">
                        <div class="flex gap-x-2 items-end">
                            <CountUp :start-val="doughnut.total/2" :end-val="doughnut.total" :duration="1"></CountUp>
                            <span class="text-sm font-medium leading-none text-gray-500">{{trans('in total')}}</span>
                        </div>
                        <div class="text-sm text-gray-500">
                            <div v-for="dCase in doughnut.cases" class="flex gap-x-2 items-center font-normal">
                                <FontAwesomeIcon :icon='dCase.icon.icon' :class='dCase.icon.class' :title="dCase.icon.tooltip" aria-hidden='true' />
                                <div class="">{{ dCase.value }}: <span class="font-semibold">{{ dCase.count ?? 0 }}</span></div>
                            </div>
                        </div>
                        
                    </div>

                    <div class="w-20">
                        <Doughnut :data="doughnut" :options="options" />
                    </div>
                </dd>
            </div>
        </dl>
    </div>
    <!-- <pre>{{ props.data.prospectStats.prospects }}</pre> -->

    <!-- <pre>{{ data.crmStats }}</pre> -->
    <!-- <Stats class="p-4" :stats="data.stats"/> -->
</template>
