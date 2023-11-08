<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 03 Nov 2023 13:05:39 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import Stats from "@/Components/DataDisplay/Stats.vue"
import { usePage } from '@inertiajs/vue3'
import { routeType } from '@/types/route'
import { Chart as ChartJS, ArcElement, Tooltip, Legend, Colors } from 'chart.js'
import { Doughnut } from 'vue-chartjs'

ChartJS.register(ArcElement, Tooltip, Legend, Colors)


const props = defineProps<{
    data: {
        crmStats: {}
        stats: {
            name: string
            stat: number
            href: routeType
        }
    }
}>()

const dataDoughnut = [
    {
        title: 'Customer',
        labels: ['Registered', 'With Appointment', 'Contacted', 'Active', 'Losing', 'Lost'],
        total: props.data.crmStats.number_customers,
        datasets: [
            {
                data: [
                    props.data.crmStats.number_customers_state_registered,
                    props.data.crmStats.number_customers_state_with_appointment,
                    props.data.crmStats.number_customers_state_contacted,
                    props.data.crmStats.number_customers_state_active,
                    props.data.crmStats.number_customers_state_losing,
                    props.data.crmStats.number_customers_state_lost,
                ]
            }
        ]
    },
    {
        title: 'Orders',
        labels: ['Creating', 'Submitted', 'Handling', 'Packed', 'Finalised', 'Settled'],
        total: props.data.crmStats.number_orders,
        datasets: [
            {
                data: [
                    props.data.crmStats.number_orders_state_creating,
                    props.data.crmStats.number_orders_state_submitted,
                    props.data.crmStats.number_orders_state_handling,
                    props.data.crmStats.number_orders_state_packed,
                    props.data.crmStats.number_orders_state_finalised,
                    props.data.crmStats.number_orders_state_settled,
                ]
            }
        ]
    },
    {
        title: 'Customer Websites',
        labels: ['SEO', 'PPC', 'Social', 'Prospects', 'Banners'],
        total: props.data.crmStats.number_customer_websites,
        datasets: [
            {
                data: [
                    props.data.crmStats.number_customer_websites_seo,
                    props.data.crmStats.number_customer_websites_ppc,
                    props.data.crmStats.number_customer_websites_social,
                    props.data.crmStats.number_customer_websites_prospects,
                    props.data.crmStats.number_customer_websites_banners,
                ]
            }
        ]
    },
    {
        title: 'Prospects',
        labels: ['Not contacted', 'Contacted', 'Not interested', 'Registered', 'Invoiced', 'Bounced'],
        total: props.data.crmStats.number_prospects,
        datasets: [
            {
                data: [
                    props.data.crmStats.number_prospects_state_no_contacted,
                    props.data.crmStats.number_prospects_state_contacted,
                    props.data.crmStats.number_prospects_state_not_interested,
                    props.data.crmStats.number_prospects_state_registered,
                    props.data.crmStats.number_prospects_state_invoiced,
                    props.data.crmStats.number_prospects_state_bounced,
                ]
            }
        ]
    },
    {
        title: 'Prospects Bounce',
        labels: ['Hard', 'Soft', 'Ok'],
        total: props.data.crmStats.number_prospects_bounce_status_hard_bounce + props.data.crmStats.number_prospects_bounce_status_soft_bounce + props.data.crmStats.number_prospects_bounce_status_ok,
        datasets: [
            {
                data: [
                    props.data.crmStats.number_prospects_bounce_status_hard_bounce,
                    props.data.crmStats.number_prospects_bounce_status_soft_bounce,
                    props.data.crmStats.number_prospects_bounce_status_ok
                ]
            }
        ]
    },
    {
        title: 'Prospects Outcome',
        labels: ['Hard fail', 'Soft fail', 'Waiting', 'Soft success', 'Hard success'],
        total: props.data.crmStats.number_prospects_bounce_status_hard_bounce + props.data.crmStats.number_prospects_bounce_status_soft_bounce + props.data.crmStats.number_prospects_bounce_status_ok,
        datasets: [
            {
                data: [
                    props.data.crmStats.number_prospects_outcome_status_hard_fail,
                    props.data.crmStats.number_prospects_outcome_status_soft_fail,
                    props.data.crmStats.number_prospects_outcome_status_waiting,
                    props.data.crmStats.number_prospects_outcome_status_soft_success,
                    props.data.crmStats.number_prospects_outcome_status_hard_success,
                ]
            }
        ]
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
                    <div class="flex gap-x-2 leading-none items-baseline text-2xl font-semibold text-org-500">
                        {{ doughnut.total }}
                        <span class="text-sm font-medium leading-none text-gray-500">in total</span>
                    </div>
                    <div class="w-20">
                        <Doughnut :data="doughnut" :options="options" />
                    </div>
                </dd>
            </div>
        </dl>
    </div>

    <!-- <pre>{{ data.crmStats }}</pre> -->
    <!-- <Stats class="p-4" :stats="data.stats"/> -->
</template>
