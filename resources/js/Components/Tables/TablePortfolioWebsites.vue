<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 20 Mar 2023 23:18:59 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import Table from '@/Components/Table/Table.vue'
import IconGroupInterested from '@/Components/Table/IconGroupInterested.vue'
import ModalDivision from '@/Components/Utils/ModalDivision.vue'
import { Website } from "@/types/website"
// import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCheckCircle as fasCheckCircle } from '@/../private/pro-solid-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(fasCheckCircle)

const props = defineProps<{
    data: {
        data: {}
    }
    tab?: string
}>()

const isModalOpen = ref(false)

function websiteRoute(website: Website) {
    switch (route().current()) {
        case 'org.crm.shop.customers.show':
            return route(
                'org.crm.shop.customers.show.customer-websites.show',
                [route().params.shop, route().params.customer, website.slug])
        default:
            return route(
                'customer.portfolio.websites.show',
                [website.slug])
    }

    /*
    switch (route().current()) {
        case 'customer.caas.websites.index':
            return route(
                'customer.caas.websites.show',
                [website.slug]);
        case 'customer.portfolio.websites.index':
            return route(
                'customer.portfolio.websites.show',
                [website.slug]);
    }

     */
}

const selectedColumn = ref({
    label: '',
    name: ''
})
const selectedWebsite = ref({
    slug: ''
})

</script>

<template>
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(slug)="{ item: website }">
            <Link :href="websiteRoute(website)" :id="website['slug']" class="py-2 px-1">
                {{ website['slug'] }}
            </Link>
        </template>

        <!-- Leads -->
        <template #cell(leads)="{ item: website }">d
            <div class="cursor-pointer" @click="() => {isModalOpen = true, selectedWebsite = website, selectedColumn = website.prospects}">
                <IconGroupInterested :columnValue="website.prospects?.value" />
            </div>
        </template>

        <!-- SEO -->
        <template #cell(seo)="{ item: website }">
            <div class="cursor-pointer" @click="() => {isModalOpen = true, selectedWebsite = website, selectedColumn = website.seo}">
                <IconGroupInterested :columnValue="website.seo?.value" />
            </div>
        </template>

        <!-- Google Ads -->
        <template #cell(ppc)="{ item: website }">
            <div class="cursor-pointer" @click="() => {isModalOpen = true, selectedWebsite = website, selectedColumn = website.ppc}">
                <IconGroupInterested :columnValue="website.ppc?.value" />
            </div>
        </template>

        <!-- Social -->
        <template #cell(social)="{ item: website }">
            <div class="cursor-pointer" @click="() => {isModalOpen = true, selectedWebsite = website, selectedColumn = website.social}">
                <IconGroupInterested :columnValue="website.social?.value" />
            </div>
        </template>

        <!-- Banners -->
        <template #cell(banners)="{ item: website }">
            <div class="cursor-pointer" @click="() => {isModalOpen = true, selectedWebsite = website, selectedColumn = website.banners}">
                <IconGroupInterested :columnValue="website.banners?.value" />
            </div>
        </template>
    </Table>

    <ModalDivision
        :isModalOpen="isModalOpen"
        @onClose="() => isModalOpen = false"
        :selectedWebsite="selectedWebsite"
        :selectedColumn="selectedColumn"
        :routeToSave="{
            name: 'customer.models.portfolio-website.interest.store',
            parameter: selectedWebsite.id
        }"
    />
</template>
