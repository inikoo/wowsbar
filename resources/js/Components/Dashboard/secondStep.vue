<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import axios from 'axios'
import { ref, Ref } from 'vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
import ModalDivision from '@/Components/Utils/ModalDivision.vue'
import IconGroupInterested from '@/Components/Table/IconGroupInterested.vue'
import { notify } from "@kyvg/vue3-notification"

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faArrowRight } from '@far'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faArrowRight)

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

const emits = defineEmits<{
    (e: 'updateCurrentStep'): void
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

// On click the 'Next' button
const handleButtonNext = async () => {
    try {
        // await axios.post(
        //     route(
        //         props.data.storePortfolioWebsiteRoute.name,
        //         props.data.storePortfolioWebsiteRoute.parameters
        //     ),
        //     { url: props.data },
        //     {
        //         headers: { "Content-Type": "multipart/form-data" },
        //     }
        // )

        // notis
        emits('updateCurrentStep') // To move to next Step
    } catch (error: any) {
        console.log(error)
        notify({
            title: "Error!",
            text: error.message ?? 'Something went wrong.',
            type: "error"
        })
    }
}
</script>

<template>
    <div class="w-full align-middle flex gap-x-4">
        <table class="w-full rounded-md ring-1 ring-gray-200 shadow-sm overflow-hidden divide-y divide-gray-300 text-gray-500">
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

        <!-- Button: Next -->
        <Button :style="`primary`"
            label="Next"
            @click="handleButtonNext"
        >
            <span class="text-3xl">Next</span>
            <FontAwesomeIcon icon='far fa-arrow-right' class='text-3xl' aria-hidden='true' />
        </Button>

        <!-- Modal: on click interest -->
        <ModalDivision
            :isModalOpen="isModalOpen"
            @onClose="() => isModalOpen = false"
            :selectedWebsite="selectedWebsite"
            :selectedColumn="selectedColumn"
        />
    </div>
</template>
