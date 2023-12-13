<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 03 Nov 2023 13:05:39 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">

import CountUp from 'vue-countup-v3'
import {Chart as ChartJS, ArcElement, Tooltip, Legend, Colors} from 'chart.js'
import {Pie} from 'vue-chartjs'
import {trans} from "laravel-vue-i18n";
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome'
import {faSeedling, faChair, faThumbsDown, faLaugh, faUnlink, faExclamationTriangle, faExclamationCircle, faSignIn, faDungeon, faEye, faEyeSlash, faMousePointer, faSnooze} from '@fal'
import {library} from '@fortawesome/fontawesome-svg-core'
import {useLocaleStore} from "@/Stores/locale";
import {capitalize} from '@/Composables/capitalize'

library.add(faSeedling, faChair, faThumbsDown, faLaugh, faUnlink, faExclamationTriangle, faExclamationCircle, faSignIn,
    faDungeon, faEye, faEyeSlash, faMousePointer, faSnooze)

ChartJS.register(ArcElement, Tooltip, Legend, Colors)

const locale = useLocaleStore()
const props = defineProps<{
    data: {
        prospectStats: {
            [key: string]: {
                label: string
                count: number
                cases: {
                    value: string
                    count: number
                    label: string
                    icon: {
                        icon: string | string[]
                        tooltip: string
                        class: string
                    }
                }[]
            }
        }

    }
}>()

const options = {
    responsive: true,
    plugins: {
        legend: {
            display: false
        },
    }
}


window.Echo.private('org.general').listen('.prospects-dashboard', (e) => {
    console.log(e)
})


</script>


<template>
    <div class="px-6">
        <dl class="mt-5 grid grid-cols-1 md:grid-cols-3 gap-x-2 gap-y-3">

            <div v-for="prospectState in data.prospectStats" class="px-4 py-5 sm:p-6 rounded-lg bg-white shadow tabular-nums">
                <dt class="text-base font-medium text-gray-400 capitalize">{{ prospectState.label }}</dt>
                <dd class="mt-2 flex justify-between gap-x-2">
                    <div class="flex flex-col gap-x-2 gap-y-3 leading-none items-baseline text-2xl font-semibold text-org-500">
                        <!-- In Total -->
                        <div class="flex gap-x-2 items-end">
                            <CountUp :start-val="prospectState.count/2" :end-val="prospectState.count ?? 0" :duration="1.5" :options="{
                                formattingFn: (number) => locale.number(number)
                            }"/>
                            <span class="text-sm font-medium leading-4 text-gray-500 ">{{ trans('in total') }}</span>
                        </div>

                        <!-- Statistic -->
                        <div class="text-sm text-gray-500 flex gap-x-5 gap-y-1 items-center flex-wrap">
                            <div v-for="dCase in prospectState.cases" class="flex gap-x-0.5 items-center font-normal" v-tooltip="capitalize(dCase.icon.tooltip)">
                                <FontAwesomeIcon :icon='dCase.icon.icon' :class='dCase.icon.class' fixed-width :title="dCase.icon.tooltip" aria-hidden='true'/>
                                <span class="font-semibold">
                                    <CountUp :end-val="dCase.count" :duration="1" :options="{
                                        formattingFn: (number) => locale.number(number)
                                    }"/>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Donut -->
                    <div class="w-20">
                        <Pie :data="{
                            labels: Object.entries(prospectState.cases).map(([, value]) => value.label),
                            datasets: [{
                                data: Object.entries(prospectState.cases).map(([, value]) => value.count)
                            }]
                        }" :options="options"/>
                    </div>

                </dd>
            </div>
        </dl>
    </div>

</template>
