<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { ref, Ref } from 'vue'
import ModalDivision from '@/Components/Utils/ModalDivision.vue'
import IconGroupInterested from '@/Components/Table/IconGroupInterested.vue'

interface columnInterest {
    name: string,
    label: string,
    value: string
}

const props = defineProps<{
    data: {
        slug: string
        customer_name: string
        code?: string
        name: string
        url: string
        number_banners: number
        seo: columnInterest
        "google-ads": columnInterest
        social: columnInterest
        prospects: columnInterest
        banners: columnInterest
    }
}>()

const isModalOpen = ref(false)
const selectedColumn: Ref<columnInterest> = ref({
    name: '',
    label: '',
    value: ''
})
const selectedWebsite = ref({
    slug: ''
})

</script>

<template>
    <div class="flow-root overflow-x-auto shadow-sm rounded-md overflow-hidden ring-1 ring-gray-200 ">
        <div class="inline-block min-w-full align-middle">
            <table class="min-w-full divide-y divide-gray-300 text-gray-500">
                <thead>
                    <tr class="divide-x divide-gray-200 text-sm font-semibold">
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
                        <td class="whitespace-nowrap p-4">
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