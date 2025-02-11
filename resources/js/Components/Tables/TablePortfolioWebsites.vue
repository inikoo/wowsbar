<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 20 Mar 2023 23:18:59 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {ref} from 'vue'
import {Link} from '@inertiajs/vue3'
import Table from '@/Components/Table/Table.vue'
import IconGroupInterested from '@/Components/Table/IconGroupInterested.vue'
import ModalDivision from '@/Components/Utils/ModalDivision.vue'
import {Website} from "@/types/website"
import {faCheckCircle as fasCheckCircle} from '@fas'
import {faDraftingCompass, faExternalLink} from '@fal'
import {library} from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { trans } from 'laravel-vue-i18n'

library.add(fasCheckCircle, faDraftingCompass, faExternalLink)

const props = defineProps<{
    data: {
        data: {}
    }
    tab?: string
}>()

const isModalOpen = ref(false)

function websiteRoute(website: Website) {

    switch (route().current()) {
        default:
            return route(
                'customer.portfolio.websites.show',
                [website.slug])
    }
}

const selectedColumn = ref({
    label: '',
    name: ''
})
const selectedWebsite = ref({
    slug: '',
    id: ''
})

</script>

<template>
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(name)="{ item: website }">
            <Link :href="websiteRoute(website)" :id="website['slug']" class="py-2 px-1 specialUnderlineCustomer">
                {{ website['name'] }}
            </Link>
        </template>

        <!-- Footer -->
        <template #cell(footer)="{ item: website }">
            <Link
                :href="route('customer.portfolio.websites.footer', [website.slug])"
                class="secondaryLinkCustomer py-1.5"
            >
                <FontAwesomeIcon icon='fal fa-drafting-compass' class='' fixed-width aria-hidden='true' />
                Footer's Workshop

            </Link>
        </template>

        <!-- URL -->
        <template #cell(url)="{ item: website }">
            <a
                :href="`https://${website.url}`"
                class="group space-x-1"
                target="_blank"
            >
                <span class="secondaryLinkCustomer py-1.5">{{website.url}}</span>
                <FontAwesomeIcon icon='fal fa-external-link' class='opacity-0 group-hover:opacity-80' fixed-width aria-hidden='true' />

            </a>
        </template>

        <!-- Announcements -->
        <template #cell(announcements)="{ item: website }">
            <Link :href="route('customer.portfolio.websites.announcements.index', [website.slug])" class="secondaryLinkCustomer py-1.5">
                <!-- <FontAwesomeIcon icon='fas fa-check-circle' class='' fixed-width aria-hidden='true' /> -->
                {{ website.announcements }} {{ website.announcements > 1 ? trans('Announcements') : trans('Announcement') }}
            </Link>
        </template>

        <!-- Leads -->
        <template #cell(leads)="{ item: website }">
            <div v-if="website.prospects?.value!='customer'" class="cursor-pointer" @click="() => {isModalOpen = true, selectedWebsite = website, selectedColumn = website.prospects}">
                <IconGroupInterested :columnValue="website.prospects?.value"/>
            </div>
            <IconGroupInterested v-else :columnValue="website.prospects?.value"/>

        </template>

        <!-- SEO -->
        <template #cell(seo)="{ item: website }">
            <div v-if="website.seo?.value!='customer'" class="cursor-pointer" @click="() => {isModalOpen = true, selectedWebsite = website, selectedColumn = website.seo}">
                <IconGroupInterested :columnValue="website.seo?.value"/>
            </div>
            <IconGroupInterested v-else :columnValue="website.seo?.value"/>

        </template>

        <!-- Google Ads -->
        <template #cell(ppc)="{ item: website }">
            <div v-if="website.ppc?.value!='customer'" class="cursor-pointer" @click="() => {isModalOpen = true, selectedWebsite = website, selectedColumn = website.ppc}">
                <IconGroupInterested :columnValue="website.ppc?.value"/>
            </div>
            <IconGroupInterested v-else :columnValue="website.ppc?.value"/>

        </template>

        <!-- Social -->
        <template #cell(social)="{ item: website }">
            <div v-if="website.social?.value!='customer'" class="cursor-pointer" @click="() => {isModalOpen = true, selectedWebsite = website, selectedColumn = website.social}">
                <IconGroupInterested :columnValue="website.social?.value"/>
            </div>
            <IconGroupInterested v-else :columnValue="website.social?.value"/>
        </template>

        <!-- Banners -->
        <template #cell(banners)="{ item: website }">
            <div v-if="website.banners?.value!='customer'" class="cursor-pointer" @click="() => {isModalOpen = true, selectedWebsite = website, selectedColumn = website.banners}">
                <IconGroupInterested :columnValue="website.banners?.value"/>
            </div>
            <IconGroupInterested v-else :columnValue="website.banners?.value"/>

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
