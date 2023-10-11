<script setup lang="ts">
// import PureInputWithAddOn from '@/Components/Pure/PureInputWithAddOn.vue'
import { ref, toRef } from 'vue'
import axios from 'axios'
import { notify } from "@kyvg/vue3-notification"

import Button from '@/Components/Elements/Buttons/Button.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faArrowRight } from '@/../private/pro-regular-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faArrowRight)

const props = defineProps<{
    data: {
        id: 1,
        label: string
        description: string
        component: 1,
        websiteValue: string
        storePortfolioWebsiteRoute: {
            name: string
            parameters?: string | string[]
        }
    }
    currentStep: number
}>()

const emits = defineEmits<{
    (e: 'updateCurrentStep'): void
}>()

// On click the 'Next' button
const handleButtonNext = async () => {
    try {
        await axios.post(
            route(
                props.data.storePortfolioWebsiteRoute.name,
                props.data.storePortfolioWebsiteRoute.parameters
            ),
            { url: props.data.websiteValue },
            {
                headers: { "Content-Type": "multipart/form-data" },
            }
        )

        notify({
            title: "Success!",
            text: 'Successfuly save the website name.',
            type: "success"
        })
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
    <div class="mx-auto">
        <label for="inputWebsite" class="text-xl text-gray-600 font-medium">Enter your website</label>
        <div class="flex w-full ">
            <div class="w-[80%] relative flex rounded-l-md px-4 shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-gray-500">
                <div class="text-3xl flex items-center gap-x-1.5">
                    <div class="flex select-none items-center text-gray-400">
                        <span class="leading-none mb-0.5">https://</span>
                    </div>
                </div>
                <input v-model="data.websiteValue"
                    name="inputWebsite'" id="inputWebsite'"
                    class="text-3xl block flex-1 border-0 bg-transparent w-auto py-4 px-1 mb-0.5 placeholder:text-gray-400 read-only:text-gray-600 focus:ring-0"
                    placeholder="enteryourwebsite.com" />
            </div>

            <Button :style="data.websiteValue.length ? `primary` : 'disabled'"
                label="Next"
                :key="data.websiteValue.length"
                @click="handleButtonNext"
            >
                <span class="text-3xl">Next</span>
                <FontAwesomeIcon icon='far fa-arrow-right' class='text-3xl' aria-hidden='true' />
            </Button>
        </div>
    </div>
</template>