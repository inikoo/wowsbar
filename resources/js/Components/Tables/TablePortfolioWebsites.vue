<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 20 Mar 2023 23:18:59 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Link} from '@inertiajs/vue3'
import Table from '@/Components/Table/Table.vue'
import { Website } from "@/types/website"
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCheckCircle, faTimesCircle } from '@/../private/pro-light-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faCheckCircle, faTimesCircle)

const props = defineProps<{
    data: object
    tab?: string
}>()

function websiteRoute(website: Website) {
    switch (route().current()) {
        case 'customer.portfolio.websites.index':
            return route(
                'customer.portfolio.websites.show',
                [website.slug]);
    }
}

const dummyData = {
    leads: true,
    seo: false,
    googleAds: true,
    social: true,
    banners: false,
}


</script>

<template>

    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(name)="{ item: website }">
            <Link :href="websiteRoute(website)" :id="website['slug']" class="py-2 px-1">
                {{ website['name'] }}
            </Link>
        </template>

        <template #cell(leads)="{ item: website }">
            <div class="text-center">
                <FontAwesomeIcon v-if="dummyData.leads" icon="fal fa-check-circle" class="text-green-500"></FontAwesomeIcon>
                <FontAwesomeIcon v-else icon="fal fa-times-circle" class="text-red-500"></FontAwesomeIcon>
            </div>
        </template>

        <template #cell(seo)="{ item: website }">
            <div class="text-center">
                <FontAwesomeIcon v-if="dummyData.seo" icon="fal fa-check-circle" class="text-green-500"></FontAwesomeIcon>
                <FontAwesomeIcon v-else icon="fal fa-times-circle" class="text-red-500"></FontAwesomeIcon>
            </div>
        </template>

        <template #cell(google-ads)="{ item: website }">
            <div class="text-center">
                <FontAwesomeIcon v-if="dummyData.googleAds" icon="fal fa-check-circle" class="text-green-500"></FontAwesomeIcon>
                <FontAwesomeIcon v-else icon="fal fa-times-circle" class="text-red-500"></FontAwesomeIcon>
            </div>
        </template>

        <template #cell(social)="{ item: website }">
            <div class="text-center">
                <FontAwesomeIcon v-if="dummyData.social" icon="fal fa-check-circle" class="text-green-500"></FontAwesomeIcon>
                <FontAwesomeIcon v-else icon="fal fa-times-circle" class="text-red-500"></FontAwesomeIcon>
            </div>
        </template>

        <template #cell(banners)="{ item: website }">
            <div class="text-center">
                <FontAwesomeIcon v-if="dummyData.banners" icon="fal fa-check-circle" class="text-green-500"></FontAwesomeIcon>
                <FontAwesomeIcon v-else icon="fal fa-times-circle" class="text-red-500"></FontAwesomeIcon>
            </div>
        </template>
    </Table>


</template>


