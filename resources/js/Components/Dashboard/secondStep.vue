<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { ref, reactive } from 'vue'
import ModalDivision from '@/Components/Utils/ModalDivision.vue'
import IconGroupInterested from '@/Components/Table/IconGroupInterested.vue'

const props = defineProps<{
    data: any
}>()

const isModalOpen = ref(false)
const selectedColumn = ref({
    label: '',
    name: ''
})
const selectedWebsite = ref({
    slug: ''
})

const dummyData = reactive({
    "slug": "mw",
    "customer_name": "Aiku",
    "code": null,
    "name": "My website 😸",
    "url": "hello.com",
    "number_banners": 0,
    "seo": {
        "name": "seo",
        "label": "SEO",
        "value": 'not_sure'
    },
    "google-ads": {
        "name": "google-ads",
        "label": "PPC",
        "value": "not_interested"
    },
    "social": {
        "name": "social",
        "label": "Social",
        "value": 'not_sure'
    },
    "prospects": {
        "name": "prospects",
        "label": "Prospects",
        "value": 'not_sure'
    },
    "banners": {
        "name": "banners",
        "label": "Banners",
        "value": 'not_sure'
    }
})
</script>

<template>
    <div class="flow-root overflow-x-auto">
        <div class="inline-block min-w-full align-middle sm:px-6 lg:px-8">
            <table class="min-w-full divide-y divide-gray-300 shadow rounded-md overflow-hidden text-gray-500">
                <thead>
                    <tr class="divide-x divide-gray-200 text-sm font-semibold">
                        <th scope="col" class="py-3.5 pl-4 pr-4 text-left">Name</th>
                        <th scope="col" class="px-4 py-3.5 text-left">Url</th>
                        <th scope="col" class="px-4 py-3.5 text-left">Leads</th>
                        <th scope="col" class="py-3.5 pl-4 pr-4 text-left">SEO</th>
                        <th scope="col" class="py-3.5 pl-4 pr-4 text-left">Google Ads</th>
                        <th scope="col" class="py-3.5 pl-4 pr-4 text-left">Social</th>
                        <th scope="col" class="py-3.5 pl-4 pr-4 text-left">Banners</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    <tr class="divide-x divide-gray-200">
                        <td class="whitespace-nowrap py-4 pl-4 pr-4">
                            Lindsay Walton
                        </td>
                        <td class="whitespace-nowrap py-4 pl-4 pr-4">
                            {{ 'www.hello.com'}}
                        </td>
                        <td class="whitespace-nowrap p-4 text-sm text-gray-500">
                            <div class="cursor-pointer"
                                @click="() => { isModalOpen = true, selectedWebsite = data, selectedColumn = data.prospects }">
                                <IconGroupInterested :columnValue="data.prospects?.value" />
                            </div>
                        </td>
                        <td class="whitespace-nowrap p-4 text-sm text-gray-500">
                            <div class="cursor-pointer"
                                @click="() => { isModalOpen = true, selectedWebsite = data, selectedColumn = data.seo }">
                                <IconGroupInterested :columnValue="data.seo?.value" />
                            </div>
                        </td>
                        <td class="whitespace-nowrap p-4 text-sm text-gray-500">
                            <div class="cursor-pointer"
                                @click="() => { isModalOpen = true, selectedWebsite = data, selectedColumn = data['google-ads'] }">
                                <IconGroupInterested :columnValue="data['google-ads']?.value" />
                            </div>
                        </td>
                        <td class="whitespace-nowrap p-4 text-sm text-gray-500">
                            <div class="cursor-pointer"
                                @click="() => { isModalOpen = true, selectedWebsite = data, selectedColumn = data.social }">
                                <IconGroupInterested :columnValue="data.social?.value" />
                            </div>
                        </td>
                        <td class="whitespace-nowrap p-4 text-sm text-gray-500">
                            <div class="cursor-pointer"
                                @click="() => { isModalOpen = true, selectedWebsite = data, selectedColumn = data.banners }">
                                <IconGroupInterested :columnValue="data.banners?.value" />
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <ModalDivision
            :isModalOpen="isModalOpen"
            @onClose="() => isModalOpen = false"
            :selectedWebsite="selectedWebsite"
            :selectedColumn="selectedColumn"
        />
    </div>

</template>