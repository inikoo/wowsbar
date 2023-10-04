<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 20 Mar 2023 23:18:59 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import Table from '@/Components/Table/Table.vue'
import ModalConfirmation from '../Utils/ModalConfirmation.vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
import IconGroupInterested from '@/Components/Table/IconGroupInterested.vue'
import { Website } from "@/types/website"
// import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCheckCircle, faTimesCircle } from '@/../private/pro-light-svg-icons'
import { faCheckCircle as fasCheckCircle, faCircle } from '@/../private/pro-solid-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faCheckCircle, faTimesCircle, fasCheckCircle, faCircle)

const props = defineProps<{
    data: object
    tab?: string
}>()

const isModalOpen = ref(false)
const selectedWebsite = ref()
const selectedColumn = ref()

function websiteRoute(website: Website) {
    switch (route().current()) {
        case 'customer.portfolio.websites.index':
            return route(
                'customer.portfolio.websites.show',
                [website.slug]);
    }
}

const dummyData = {
    leads: 'interested',
    seo: 'paid',
    googleAds: 'interested',
    social: 'uninterested',
    banners: 'notsure',
}

// When click on the icon
const handleIconClick = (column: string, website: string) => {
    selectedColumn.value = column
    selectedWebsite.value = website
    isModalOpen.value = true
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
            <div class="" @click="handleIconClick('Leads', website.name)">
                <IconGroupInterested :columnValue="dummyData.leads" />
            </div>
        </template>

        <template #cell(seo)="{ item: website }">
            <div class="" @click="handleIconClick('SEO', website.name)">
                <IconGroupInterested :columnValue="dummyData.seo" />
            </div>
        </template>

        <template #cell(google-ads)="{ item: website }">
            <div class="" @click="handleIconClick('Google Ads', website.name)">
                <IconGroupInterested :columnValue="dummyData.googleAds" />
            </div>
        </template>

        <template #cell(social)="{ item: website }">
            <div class="" @click="handleIconClick('Social', website.name)">
                <IconGroupInterested :columnValue="dummyData.social" />
            </div>
        </template>

        <template #cell(banners)="{ item: website }">
            <div class="" @click="handleIconClick('Banners', website.name)">
                <IconGroupInterested :columnValue="dummyData.banners" />
            </div>
        </template>
    </Table>

    <!-- Popup: for confirmation -->
    <ModalConfirmation :isOpen="isModalOpen" @onClose="isModalOpen = false">
        <div class="space-y-4">
            <p class="text-gray-600 text-center">Do you want to change the <span class="font-bold">{{ selectedColumn }}</span> status of <span class="font-bold">{{ selectedWebsite }}</span>?</p>
            <div class="flex justify-center gap-x-3">
                <Button :style="'tertiary'" label="Not sure" icon="fas fa-circle" class="text-slate-500" />
                <Button :style="'negative'" label="Not Interested" icon="fal fa-times-circle" />
                <Button :style="'tertiary'" label="Interested" icon="fal fa-check-circle" class="border-green-500 text-green-500 focus:ring-green-500 hover:bg-green-50" />
            </div>
        </div>
    </ModalConfirmation>
</template>


