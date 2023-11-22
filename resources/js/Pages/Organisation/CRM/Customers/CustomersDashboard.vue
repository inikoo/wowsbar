<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Wed, 22 Nov 2023 23:49:23 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import Stats from "@/Components/DataDisplay/Stats.vue"
import { usePage } from '@inertiajs/vue3'
import CountUp from 'vue-countup-v3'
import { routeType } from '@/types/route'
import { Chart as ChartJS, ArcElement, Tooltip, Legend, Colors } from 'chart.js'
import { Doughnut } from 'vue-chartjs'
import { useLocaleStore } from '@/Stores/locale.js'
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
                        <CountUp :start-val="doughnut.total/2" :end-val="doughnut.total" :duration="1"></CountUp>
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
