<script setup lang="ts">
import Button from '@/Components/Elements/Buttons/Button.vue'
import PureMultiselect from '@//Components/Pure/PureMultiselect.vue'
import { RadioGroup, RadioGroupLabel, RadioGroupOption, RadioGroupDescription } from '@headlessui/vue'
import { reactive, ref } from 'vue'
// import PureInput from '@/Components/Pure/PureInput.vue'
import { trans } from "laravel-vue-i18n"
import { router } from '@inertiajs/vue3'
import axios from 'axios'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faGlobe } from '@/../private/pro-light-svg-icons'
import { faCheckCircle } from '@/../private/pro-solid-svg-icons'
import { faSpinnerThird } from '@/../private/pro-duotone-svg-icons'
// import { useLayoutStore } from '@/Stores/layout'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faCheckCircle, faSpinnerThird, faGlobe)

const props = defineProps<{
    data: {
        text: string
        createRoute: {
            name: string
            parameters?: any[]
        }
        websiteOptions: any
    }
}>()

const dataToSubmit = reactive({
    orientation: 'landscape',
    selectedWebsiteId: 1
})

const isLoading = ref(false)


const radioOptions = [
    {
        label: trans('landscape'),
        value: 'landscape',
    },
    {
        label: trans('square'),
        value: 'square',
    },
]

const handleCreateButton = async () => {
    isLoading.value = true
    try {
        const response = await axios.post(
            route(props.data.createRoute.name, props.data.createRoute.parameters),
            { 
                type: dataToSubmit.orientation,
                portfolio_website_id: dataToSubmit.selectedWebsiteId
            }
        )
        // console.log(response)
        router.visit(response.data)
    } catch (error) {
        console.log(error)
    }
}
</script>

<template>
    <div class="grid grid-cols-2 max-w-4xl mx-auto pt-5">
    <!-- {{ data }} -->
        <div v-html="data.text"
            class="px-4 py-7 text-4xl font-bold bg-gradient-to-br from-green-300 via-blue-500 to-purple-600 text-transparent bg-clip-text">
        </div>
        <div class="bg-white border border-gray-300 rounded-md shadow flex flex-col gap-y-8 py-6 px-8 justify-center">
            <div v-if="data.websiteOptions" class="flex w-full">
                <div class="flex px-4 py-3 border border-gray-300 rounded-l -mr-1 border-r-0 text-gray-500">
                    <FontAwesomeIcon icon='fal fa-globe' class='-ml-0.5' aria-hidden='true' />
                </div>
                <PureMultiselect v-model="dataToSubmit.selectedWebsiteId" :required="true" :options="data.websiteOptions" />
            </div>
            <div class="text-center">
                <!-- <label class="text-lg">Select banner orientation</label> -->
                <RadioGroup v-model="dataToSubmit.orientation" class="">
                    <RadioGroupLabel class="sr-only">Choose the radio</RadioGroupLabel>
                    <div class="flex gap-x-2 gap-y-1 flex-wrap justify-center">
                        <RadioGroupOption as="template" v-for="(option, index) in radioOptions" :key="option.value"
                            :value="option.value" v-slot="{ active, checked }">
                            <div :class="[
                                'cursor-pointer focus:outline-none flex items-center gap-x-2 justify-center rounded-md py-2 px-10 text-sm font-medium capitalize',
                                active ? 'ring-2 ring-blue-600 ring-offset-2' : '',
                                checked ? 'bg-gradient-to-br from-blue-500 to-purple-600 text-white hover:bg-gray-500 ' : 'border border-dashed border-gray-300 bg-white text-gray-700 hover:bg-gray-50',
                            ]">

                                <RadioGroupLabel as="span" class="relative">
                                    <FontAwesomeIcon v-if="checked" icon='fas fa-check-circle' aria-hidden='true'
                                        class="absolute left-0 -translate-x-5 top-1/2 -translate-y-1/2" />
                                    <span>{{ option.value }}</span>
                                </RadioGroupLabel>
                            </div>
                        </RadioGroupOption>
                    </div>
                </RadioGroup>
            </div>
            <!-- <div>
                <label for="inputfield">Enter your </label>
                <PureInput v-model="dataToSubmit.bannerName" inputName="inputfield" />
            </div> -->
            <div class="flex justify-center flex-col">
                <Button @click="handleCreateButton" label="Create banner" class="w-fit mx-auto" full>
                    <FontAwesomeIcon v-if="isLoading" icon='fad fa-spinner-third' class='animate-spin' aria-hidden='true' />
                    <div>
                        {{ trans('Create Banner') }}
                    </div>
                </Button>
            </div>
        </div>
    </div>
</template>