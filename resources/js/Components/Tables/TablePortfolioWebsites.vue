<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 20 Mar 2023 23:18:59 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import Table from '@/Components/Table/Table.vue'
import ModalConfirmation from '@/Components/Utils/ModalConfirmation.vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
import IconGroupInterested from '@/Components/Table/IconGroupInterested.vue'
import { Website } from "@/types/website"
import { notify } from '@kyvg/vue3-notification'
// import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCheckCircle, faTimesCircle } from '@/../private/pro-light-svg-icons'
import { faCircle } from '@/../private/pro-regular-svg-icons'
import { faCheckCircle as fasCheckCircle } from '@/../private/pro-solid-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
import axios from 'axios'
library.add(faCheckCircle, faTimesCircle, fasCheckCircle, faCircle)

const props = defineProps<{
    data: object
    tab?: string
}>()

const isModalOpen = ref(false)
const selectedWebsite = ref()
const selectedColumn = ref()
const selectedWebsiteSlug = ref()

function websiteRoute(website: Website) {
    switch (route().current()) {
        case 'customer.portfolio.websites.index':
            return route(
                'customer.portfolio.websites.show',
                [website.slug]);
    }
}

// When click on the icon
const handleIconClick = (columnData: {name: string, label: string, value: string}, website: string, websiteSlug: string) => {
    selectedColumn.value = columnData
    selectedWebsite.value = website
    selectedWebsiteSlug.value = websiteSlug
    isModalOpen.value = true
}

const submitState = async (websiteSlug: string, selectedColumnName: string, stateName: string) => {
    try {
        const response = await axios.post(
            route('customer.models.portfolio-website.interest.store', websiteSlug),
            {
                division: selectedColumnName,
                interest: stateName
            },
            {
                headers: { "Content-Type": "multipart/form-data" },
            }
        )
        props.data.data[0][selectedColumn.value.name].value = stateName  // For manipulation data without refresh the page

        // To add Toast on success
        notify({
            title: 'Success!',
            text: 'Successfully changed the state',
            type: "success"
        })

        // To close the modal
        setTimeout(() => {
            isModalOpen.value = false
        }, 500)

    } catch (error: any) {
        notify({
            title: error.response.statusText,
            text: error.message,
            type: "error"
        })
    }
}
</script>

<template>
<!-- <pre>{{ data.data[0] }}</pre> -->
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(name)="{ item: website }">
            <Link :href="websiteRoute(website)" :id="website['slug']" class="py-2 px-1">
                {{ website['name'] }}
            </Link>
        </template>

        <template #cell(leads)="{ item: website }">
            <div class="cursor-pointer" @click="handleIconClick(website.prospects, website.name, website.slug)">
                <IconGroupInterested :columnValue="website.prospects?.value" />
            </div>
        </template>

        <template #cell(seo)="{ item: website }">
            <div class="cursor-pointer" @click="handleIconClick(website.seo, website.name, website.slug)">
                <IconGroupInterested :columnValue="website.seo?.value" />
            </div>
        </template>

        <template #cell(google-ads)="{ item: website }">
        <!-- <pre>{{ website }}</pre> -->
            <div class="cursor-pointer" @click="handleIconClick(website['google-ads'], website.name, website.slug)">
                <IconGroupInterested :columnValue="website['google-ads'].value" />
            </div>
        </template>

        <template #cell(social)="{ item: website }">
            <div class="cursor-pointer" @click="handleIconClick(website.social, website.name, website.slug)">
                <IconGroupInterested :columnValue="website.social?.value" />
            </div>
        </template>

        <template #cell(banners)="{ item: website }">
            <div class="cursor-pointer" @click="handleIconClick(website.banners, website.name, website.slug)">
                <IconGroupInterested :columnValue="website.banners?.value" />
            </div>
        </template>
    </Table>

    <!-- Popup: for confirmation -->
    <ModalConfirmation :isOpen="isModalOpen" @onClose="isModalOpen = false">
        <Button class="sr-only" /> <!-- Helper: to focused on popup Modal -->
        <div class="space-y-4">
            <p class="text-gray-600 text-center">Do you want to change the <span class="font-bold">{{ selectedColumn.label }}</span> status of <span class="font-bold">{{ selectedWebsite }}</span>?</p>
            <div class="flex justify-center gap-x-3">
                <Button v-if="data.data[0][selectedColumn.name].value != 'not_sure' && data.data[0][selectedColumn.name].value != null" @click="submitState(selectedWebsiteSlug, selectedColumn.name, 'not_sure')" :style="'tertiary'" label="Not sure" icon="far fa-circle" class="text-slate-500" />
                <Button v-if="data.data[0][selectedColumn.name].value != 'not_interested'" @click="submitState(selectedWebsiteSlug, selectedColumn.name, 'not_interested')" :style="'negative'" label="Not Interested" icon="fal fa-times-circle" />
                <Button v-if="data.data[0][selectedColumn.name].value != 'interested'" @click="submitState(selectedWebsiteSlug, selectedColumn.name, 'interested')" :style="'tertiary'" label="Interested" icon="fal fa-check-circle" class="border-green-500 text-green-500 focus:ring-green-500 hover:bg-green-50" />
            </div>
        </div>
    </ModalConfirmation>
</template>


